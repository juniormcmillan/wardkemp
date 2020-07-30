<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 19-Feb-16
 * Time: 9:14 AM
 *
 * 	 this is not just a job, it has tasks within it
 *
 * 	we can have job templates that specify order of tasks
 * 	we can re-start a job from any step along the way.
 * 	we need to also tag the records created with a particular job, so we can remove them if needed with a consignment / package number

 *
 */

# running or not for each job
define('MAIL_QUEUE_PAUSED',							1);
define('MAIL_QUEUE_RESUMED',						0);


# set if the mail has been processed (eg. tried to get sent)
define('MAIL_QUEUE_PROCESSED',						1);

# what happened when we tried to send the emial
define('MAIL_QUEUE_SEND_OK',						1);
define('MAIL_QUEUE_TEMPLATE_ERROR',					2);
define('MAIL_QUEUE_PARTNER_ERROR',					3);
define('MAIL_QUEUE_CLAIM_DATA_ERROR',				4);
define('MAIL_QUEUE_UNSUBSCRIBED',					5);


# how many emails per hour to send
define('MAIL_QUEUE_MAIL_INTERVAL_MINS',				60);

# how many emails per hour to send
define('MAIL_QUEUE_MAX_MAIL_PER_INTERVAL',			10);


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");



class Mailshot_Class
{

	public function __construct()
	{

	}

	# loops through all possible claims to see if they meet criteria to be added to the queue to be sent. this runs always
	public function populateMailQueues()
	{
AddComment("populateMailQueues() START");
		global	$gMysql;

		$added	=	0;
		# grab all the jobs
		if	(($job_data_array	=	$gMysql->selectToArray("select * from fp_job",__FILE__,__LINE__)) != NULL)
		{
			foreach ($job_data_array as $job_data)
			{
				$job_id			=	$job_data['id'];
				$partner_id		=	$job_data['partner_id'];

				# grab partner
				if	(($partner_data	=	$gMysql->queryRow("select * from fp_partner where id='$partner_id'",__FILE__,__LINE__)) != NULL)
				{
					# grab all the templates
					if	(($template_data_array	=	$gMysql->selectToArray("select * from fp_template where partner_id='$partner_id'",__FILE__,__LINE__)) != NULL)
					{
						foreach ($template_data_array as $template_data)
						{
							$template_id		=	$template_data['id'];
							$send_clause		=	$template_data['clause_id'];
							$send_delay_days	=	$template_data['send_delay_days'];
							# grab all claims for this job (making sure the claim has the data supplied)
							# make sure the claim has not been submitted and has a valid email and name
#							if	(($claim_data_array	=	$gMysql->selectToArray("select * from fp_claim where partner_id='$partner_id' and job_id='$job_id' and email !='' and first_name !=''",__FILE__,__LINE__)) != NULL)
							$strSQL	=	"select * from fp_claim where partner_id='$partner_id' and job_id='$job_id' and email !='' and first_name !='' and id not in (select claim_id from fp_mail_queue where template_id='$template_id')";
							if	(($claim_data_array	=	$gMysql->selectToArray("$strSQL",__FILE__,__LINE__)) != NULL)
							{
								foreach ($claim_data_array as $claim_data)
								{
									$claim_id		=	$claim_data['id'];
									$email			=	$claim_data['email'];

									# check agfainst rule for this template
									if	($this->checkRulePassed($claim_data,$send_clause,$send_delay_days) == true)
									{
										# adds this job to the queue to be processed
										$this->addToQueue($job_id,$partner_id,$template_id,$claim_id,$email);
										$added++;
AddComment("ADDED TO QUEUE job_id:$job_id,partner_id:$partner_id,template_id:$template_id,claim_id:$claim_id");
									}
								}
							}
						}
					}
				}
			}
		}
AddComment("FINISHED populateMailQueues() and added $added to the queue");
	}




	# check if an email has been placed in the queue for this claim and template
	private function checkIfAdded($claim_id,$template_id)
	{
		global	$gMysql;

		if	(($val	=	$gMysql->queryItem("select count(*) from fp_mail_queue where claim_id='$claim_id' and template_id='$template_id' ",__FILE__,__LINE__)) != 0)
		{
			return	true;
		}
	}



	# check we passed the rules imposed on the template being sent
	private function checkRulePassed($claim_data,$send_clause,$send_delay_days)
	{
		# get out early if set
		if	($send_clause == SEND_CLAUSE_OFF)
		{
			return false;
		}

		# days since
		$days_since			=	round(time_since($claim_data['added'])	/	 (24 * 60 * 60));

		# if we only send after a certian amount of days, check this
		if ($send_delay_days > 0)
		{
			if	($days_since < $send_delay_days)
			{
				return false;
			}
		}


		# if no response after a set amount of days
		if	($send_clause == SEND_CLAUSE_IF_NO_RESPONSE)
		{
			if	($days_since >=	$send_delay_days)
			{
				return true;
			}
		}
		# immediate
		if	($send_clause == SEND_CLAUSE_IMMEDIATELY)
		{
			return true;
		}
		# immediately and if we have a 'possible connecting' flight value set somewhere
		/*
		if	($send_clause == SEND_CLAUSE_IMMEDIATELY_IF_CONNECTING)
		{
			return true;
		}

		# we can send a special email if we know there are multiple people on the same booking reference
		# we can't clash with another immediate so need to make an adjustment to 'number of passengers' or something to templates
		if	($send_clause == SEND_CLAUSE_IMMEDIATELY_DUPLICATES)
		{
			return true;
		}

		*/


	}





	# add the email details to the queue
	private function addToQueue($job_id,$partner_id,$template_id,$claim_id,$email)
	{
		global	$gMysql;

		$gMysql->insert("replace into fp_mail_queue(id,job_id,partner_id,template_id,claim_id,email) values(0,'$job_id', '$partner_id','$template_id', '$claim_id','$email') ",__FILE__,__LINE__);

	}












	# loops through queue in reverse order, but in time slices, so this function will be called repeatedly
	# limit of mail per hour (check this)
	public function processMailQueuesInParallel($items = 10)
	{
		global	$gMysql;

		$bActive	=	true;

		$processed_status		=	MAIL_QUEUE_PROCESSED;

		# grab all the mail that has not been sent
AddComment("processMailQueuesInParallel() START with $items mails sent per job at a time");


		# if we want to, we can split by job, to make it more equal in distribution, but
		if	(($job_data_array	=	$gMysql->selectToArray("select distinct(job_id) from fp_mail_queue where processed !='$processed_status'",__FILE__,__LINE__)) != NULL)
		{
			# split by the job
			foreach ($job_data_array as $job_data)
			{
				$job_id		=	$job_data['job_id'];

				# grab mail from this particular job
				if	(($mail_data_array	=	$gMysql->selectToArray("select * from fp_mail_queue where job_id='$job_id' and processed !='$processed_status' limit $items",__FILE__,__LINE__)) != NULL)
				{
					foreach ($mail_data_array as $data)
					{
						# make sure we are below the threshold for mails per hour
						if	(($mails_per_hour = $this->mailsPerHour()) < MAIL_QUEUE_MAX_MAIL_PER_INTERVAL)
						{
							$id				=	$data['id'];
							$claim_id		=	$data['claim_id'];
							$partner_id		=	$data['partner_id'];
							$template_id	=	$data['template_id'];

AddComment("Sending email... id:$id as we are within hourly limits at $mails_per_hour mails per hour");

							# we want to process this and send it
							$status_code	=	$this->sendMail($id,$claim_id,$partner_id,$template_id);

							# set status of this email in queue
							$gMysql->update("update fp_mail_queue set processed='$processed_status', sent_status='$status_code', sent_date=Now() where claim_id='$claim_id' and template_id='$template_id'",__FILE__,__LINE__);
						}
					}
				}
			}
		}

AddComment("processMailQueuesInParallel finished");

		# false means continue looping this
		return	$bActive;
	}



	# compute how many emails per houry and send
	private function mailsPerHour()
	{
		global $gMysql;

		$interval				=	MAIL_QUEUE_MAIL_INTERVAL_MINS;
		$processed_status		=	MAIL_QUEUE_PROCESSED;

		$mails_per_hour 	=	$gMysql->queryItem("SELECT count(*) FROM fp_mail_queue WHERE processed='$processed_status' and sent_date >  NOW() - INTERVAL $interval MINUTE",__FILE__,__LINE__);

		return	$mails_per_hour;
	}



	# send mail template to this claimant
	private function sendMail($id,$claim_id,$partner_id,$template_id)
	{
		global	$gMysql;

		# grab the claim data
		if (($claim_data	=	$gMysql->queryRow("select * from fp_claim where id='$claim_id'",__FILE__,__LINE__)) == NULL)
		{
			return	MAIL_QUEUE_CLAIM_DATA_ERROR;
		}
		# grab the partner info
		if (($partner_data	=	$gMysql->queryRow("select * from fp_partner where id='$partner_id'",__FILE__,__LINE__)) == NULL)
		{
			return	MAIL_QUEUE_PARTNER_ERROR;
		}
		# grab the template
		if (($template_data	=	$gMysql->queryRow("select * from fp_template where id='$template_id'",__FILE__,__LINE__)) == NULL)
		{
			return	MAIL_QUEUE_TEMPLATE_ERROR;
		}

		$subject				=	$template_data['subject'];
		$from_name				=	$template_data['from_name'];
		$from_email				=	$template_data['from_email'];
		$html					=	html_entity_decode($template_data['html'],ENT_QUOTES);

		$company_name			=	$partner_data['company'];

		$hash					=	$claim_data['hash'];
		$forename				=	ucfirst($claim_data['first_name']);
		$surname				=	ucfirst($claim_data['last_name']);
		$email					=	$claim_data['email'];
		$airline_code			=	$claim_data['airline_code'];
		$flight_number			=	$claim_data['flight_number'];
		$sched_dep_date			=	$claim_data['sched_dep_date'];
		$dep_airport			=	$claim_data['departure_airport'];
		$arr_airport			=	$claim_data['arrival_airport'];
		$estimated_comp			=	$claim_data['estimated_compensation'];


		# claim link with click tracking id and class to use for styleing
		$claim_link			=	"<a class='fp_claim_email_link' href='http://ta.fairplane.co.uk/claim/?formID=$hash&click=$id'>Click Here To Make Your Claim</a>";
		$claim_link_only	=	"http://ta.fairplane.co.uk/claim/?formID=$hash&click=$id";
		$tracking_link		=	"http://ta.fairplane.co.uk/mail/opened.php/?id=$id";

		# full flight number
		$flight_number_full		=	$airline_code	.	"" .	$flight_number;
		# flight date human readable
		$flight_date		=	date( 'd/m/Y', strtotime($sched_dep_date) );


		$gMysql->setDB("newfairp_flightdata");
		# we need some info about airline
		$airline_name		=	$gMysql->queryItem("SELECT name from fp_airlines where code='$airline_code'",__FILE__,__LINE__);
		# we need some info about airports
		$departure_airport	=	$gMysql->queryItem("SELECT name from fp_airports where code='$dep_airport'",__FILE__,__LINE__);
		$arrival_airport	=	$gMysql->queryItem("SELECT name from fp_airports where code='$arr_airport'",__FILE__,__LINE__);
		$gMysql->setDB(MYSQL_DBASE);



		# now we need to substitute the tags in the template annd build the email
		# we need to substitute these tags
		# we can push all these pre-filled tags onto the page
		$tags	=	array(

			"{{FORENAME}}"				=>	$forename,
			"{{SURNAME}}"				=>	$surname,
			"{{COMPANY}}"				=>	$company_name,

			"{{FLIGHT_DATE}}"			=>	$flight_date,
			"{{FLIGHT_NUMBER}}"			=>	$flight_number,
			"{{FLIGHT_NUMBER_FULL}}"	=>	$flight_number_full,
			"{{AIRLINE_CODE}}"			=>	$airline_code,
			"{{AIRLINE_NAME}}"			=>	$airline_name,
			"{{DEPARTURE_AIRPORT}}"		=>	$departure_airport,
			"{{ARRIVAL_AIRPORT}}"		=>	$arrival_airport,
			"{{CLAIM_LINK}}"			=>	$claim_link,
			"{{CLAIM_LINK_BUTTON}}"		=>	$claim_link,
			"{{CLAIM_LINK_ONLY}}"		=>	$claim_link_only,
			"{{ESTIMATED_COMP}}"		=>	$estimated_comp,
			"{{TRACKING_LINK}}"			=>	$tracking_link,



		);

		# substitutes the tags
		foreach ($tags as $key => $value)
		{
			$html = str_replace ($key, $value, $html);
		}

		$html	= '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>'.$subject.'</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="background-color:#f7f7f7;">

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="20" cellspacing="20" width="600" id="emailContainer">
                <tr >
                    <td width="20"></td>
                    <td width="560" style="background-color:#ffffff;">
					'.$html.'
                    </td>
                    <td width="20"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- open tracking -->
<img border=0 src="http://ta.fairplane.co.uk/mail/opened.php/?id=$id" width=1 height=1 alt="image for email" >

</body>
</html>
';


		# where to send the email
		$to_email_array	=	array($email);

		# sends the messages
		foreach ($to_email_array as $to_email)
		{
			if	($this->isUnsubscribed($to_email) == false)
			{
				sendEmailNew($from_name, $from_email, $to_email, $subject, $html, "");
			}
			else
			{
				return	MAIL_QUEUE_UNSUBSCRIBED;
			}
		}

		return	MAIL_QUEUE_SEND_OK;
	}








	# check we are not unsubscribed
	private function isUnsubscribed($email)
	{
		global	$gMysql;

		if (($num =	$gMysql->queryItem("select count(*) from fp_mail_unsubscribed where email='$email' ",__FILE__,__LINE__)) == 0)
		{
			return false;
		}
		return true;
	}


	# unsubscribe. get details from mail_sent table
	public function unsubscribe($mail_id)
	{
		global	$gMysql;
		# grab the data
		if (($data	=	$gMysql->queryRow("select * from fp_mail_queue where id='$mail_id' ",__FILE__,__LINE__)) != NULL)
		{
			$email			=	$data['email'];
			$claim_id		=	$data['claim_id'];
			$job_id			=	$data['job_id'];
			$template_id	=	$data['template_id'];

			# insert into the table
			$gMysql->insert("insert into fp_mail_queue (id,mail_id,email,added,job_id,template_id) values(0,'$mail_id','$email',NOW(),'$job_id','$template_id')",__FILE__,__LINE__);


			AddComment("id:$mail_id, claim_id:$claim_id, email:$email UNSUBSCRIBED");
		}
		else
		{
			AddComment("id:$mail_id, ERROR failed to find");
		}


	}

	# get the details from the mail_sent table (we can pool results when updating mail_shot tables and fp_job tables)
	public function opened($mail_id)
	{
		global	$gMysql;
		# grab the data
		if (($data	=	$gMysql->queryRow("select * from fp_mail_queue where id='$mail_id' ",__FILE__,__LINE__)) != NULL)
		{
			$id				=	$data['id'];
			$email			=	$data['email'];
			$claim_id		=	$data['claim_id'];

			# set to opened
			$gMysql->update("update fp_mail_queue set opened_date=NOW(), opened=1 where id='$mail_id'",__FILE__,__LINE__);

			AddComment("id:$mail_id, claim_id:$claim_id, email:$email opened");
		}
		else
		{
			AddComment("id:$mail_id, ERROR failed to find");
		}

	}

	# get the details from the mail_sent table
	public function clicked($mail_id)
	{
		global	$gMysql;
		# grab the data
		if (($data	=	$gMysql->queryRow("select * from fp_mail_queue where id='$mail_id' ",__FILE__,__LINE__)) != NULL)
		{
			$id				=	$data['id'];
			$email			=	$data['email'];
			$claim_id		=	$data['claim_id'];

			# set to opened
			$gMysql->update("update fp_mail_queue set clicked_date=NOW(), clicked=1 where id='$mail_id'",__FILE__,__LINE__);

			AddComment("id:$mail_id, claim_id:$claim_id, email:$email opened");
		}
		else
		{
			AddComment("id:$mail_id, ERROR failed to find");
		}

	}


















	public function resumeMail($job_id)
	{
		global	$gMysql;

		$status	=	MAIL_QUEUE_RESUMED;

		$gMysql->update("update fp_job set mail_status='$status',last_updated=NOW() where id='$job_id'",__FILE__,__LINE__);

	}

	public function pauseMail($job_id)
	{
		global	$gMysql;

		$status	=	MAIL_QUEUE_PAUSED;

		$gMysql->update("update fp_job set mail_status='$status',last_updated=NOW() where id='$job_id'",__FILE__,__LINE__);

	}



	# loops through queue in revers order
	public function processJobsInQueue()
	{
		# iterate through all jobs that are active
		while (($job_data =	$this->getOldestJobInQueue()) != NULL)
		{
			$this->processJob($job_data);
		}
	}



	# loops through queue in reverse order, but in time slices, so this function will be called repeatedly until
	public function processJobsInParallel($lines)
	{
		global	$gMysql;

		$bActive	=	true;

		$status	=	JOB_STATUS_ACTIVE;

		# grab all the jobs
		if	(($job_data	=	$gMysql->selectToArray("select * from fp_job where status='$status' order by last_updated desc",__FILE__,__LINE__)) != NULL)
		{
			# if the job is finished, set to true
			$bActive	=	false;

			foreach ($job_data as $data)
			{
				# we want to process each job, until N lines read from the file
				$this->processJob($data,$lines);
			}
		}
		# false means continue looping this
		return	$bActive;
	}




	# do what needs doing, then set status to done
	public function processJob($job_data,$max_lines)
	{
		global $gMysql;

		$pClaim	=	new Claim_Class();

		# extracts variables (weird way- why?)
		$this->extractData($job_data);

		$login		=	$this->login;

		# get all the partner data
		if (($pPartner	=	new	Partner_Class($this->partner_id)) != NULL)
		{
			# loop through all tasks in the job
			while (($pJobTask	=	$this->getJobTask($this->job_template_id,$this->task_index)) != NULL)
			{
				# import from austria job (this is probably irrelevant as we are forcing CSV only)
				if	($pJobTask['task_id'] == JOB_TASK_CONVERT_TO_CSV)
				{
					# get the directory for uploads
					$directory		=	$this->getUploadDirectory();
					# get the filename of the original file
					$filename		=	$this->filename;
					$extension		=	$this->extension;

					require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/PHPExcel/PHPExcel.php");
					require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/PHPExcel/PHPExcel/IOFactory.php");

					# relative path and filename
					$filename_full	=	$_SERVER["DOCUMENT_ROOT"]	.	"/" . $directory . $filename . "." . $extension;



					if	(($objPHPExcel = $this->loadExcelFile($filename_full)) != NULL)
					{
						$objWriter		=	PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
						$objWriter->save(str_replace($extension, 'csv',$filename_full));
						$partner_code	=	$pPartner->getPartnerCode();
						$gMysql->addLog(LOGTYPE_PARTNER,$this->partner_id,"Converted $this->filename to CSV ");
					}
					else
					{
						# we have a problem converting
						$text	=	"Job ID:		$this->job_id 				<br>
						We have an issue converting this file ($filename), uploaded by:$login, so we are stopping the job";
						AddComment($text);
						sendEmailNew('FairPlane Travel Agent JOB PROBLEM','cron@fairplane.co.uk','cedric@fairplane.co.uk','FairPlane JOB PROBLEM',$text);

						$this->pauseJob($this->job_id);
						return;
					}
				}
				# this will upload the CSV to the database
				else if	($pJobTask['task_id'] == JOB_TASK_UPLOAD_TO_DATABASE)
				{
					# these are the fields we need to have in the header in the file from austria
					global	$gCSV_Initial;
					global	$gCSV_Initial_FULL;
					global	$gCSV_final;

					$directory		=	$this->getUploadDirectory();
					# get the filename of the original file
					$filename		=	$this->filename;
					$filename_full	=	$_SERVER["DOCUMENT_ROOT"]	.	"/" . $directory . $filename . "." . "csv";
					$pCsv			=	new Csv_Class();

					# the current counters for this job. set
					if	($this->current_line == 0)
					{
						$total_lines		=	0;
						$entitled_claims		=	0;
						$duplicate_claims		=	0;
						$submitted_claims		=	0;
						$error_claims			=	0;
						$updated_claims			=	0;
						$current_line			=	0;

						# we need to update the internal systems
						$this->updateStartProcess($this->job_id);

					}
					else
					{
						$total_lines			=	$this->total_lines;
						$entitled_claims		=	$this->entitled_claims;
						$duplicate_claims		=	$this->duplicate_claims;
						$submitted_claims		=	$this->submitted_claims;
						$error_claims			=	$this->error_claims;
						$updated_claims			=	$this->updated_claims;
						$current_line			=	$this->current_line;
					}

					$bUploaded				=	false;

					# get a file, and place it into a csv class
#					if (($pCsv->import($filename_full)) != NULL)
					if ($pCsv->importFromPosition($filename_full,$this->current_line,$max_lines,$total_lines) == true)
					{
						# lines in the CSV file
						$total_lines		=	$pCsv->csv_lines;
						# job id
						$job_id				=	$this->job_id;


						# if we have the file back from the partner, then we should be updating
						if ($pCsv->headerContains($gCSV_final) == true)
						{
							$bUploaded	=	true;
							# make sure we are within bounds
							if	($pCsv->endOfFile($current_line) == false)
							{
								# grab a row
								while (($data	=	$pCsv->getNextRow()) != NULL)
								{

									AddComment("$this->job_id PROCESSING line $current_line / $total_lines  ". $data['reference']);

									# updates do not affect the stats
									$return_code	=	$pClaim->updateClaim($this->partner_id,$data);

									if ($return_code  == CLAIM_OK)
									{
										$updated_claims++;
									}
									else if (($return_code  == CLAIM_ERROR_ALREADY_SUBMITTED) || ($return_code  == CLAIM_ERROR_ALREADY_PROCESSED))
									{
										$duplicate_claims++;
									}
									# these are for items that are not already in the system
									else if ($return_code  == CLAIM_ERROR)
									{
										$error_claims++;
									}
									# update line pointer
									$current_line++;
									# grab next
									next($pCsv->csv_data);

									# make sure we only process a certain number of lines before exiting
									AddComment("$this->job_id PAUSING $current_line / $total_lines.   ");
									$this->updateClaimsDetails($current_line,$total_lines,$duplicate_claims,$entitled_claims,$submitted_claims,$updated_claims,$error_claims);

									# we need to update the internal systems
									if ($this->updateProcess($this->job_id,$current_line,$total_lines,$entitled_claims) == JOB_DOES_NOT_EXIST)
									{
										break;
									}


									return false;
								}
							}
						}
						# we are dealing with the initial file, but full data suite
						else if ($pCsv->headerContains($gCSV_Initial) == true)
						{
							$bUploaded	=	true;
							$bMoreData	=	($pCsv->headerContains($gCSV_Initial_FULL) == true)	?	true	:	false;
							$data_blank	=	array("isChild" => 0,"passenger" => 0,"parent_reference" => 0);

							# make sure we are within bounds
							if	($pCsv->endOfFile($current_line) == false)
							{
								# grab a row
								while (($data	=	$pCsv->getNextRow()) != NULL)
								{

									AddComment("$this->job_id PROCESSING line $current_line / $total_lines  ". $data['reference']);


									# this is if the agent uploads all data in one go (eg. customer data also)
									if	($bMoreData)
									{
										$data			=	array_merge($data,$data_blank);
										$claim_state	=	CLAIM_STATE_PARTNER_UPDATED;
									}
									else
									{
										$claim_state	=	CLAIM_STATE_UNEDITED;
									}

									$return_code	=	$pClaim->addClaim($job_id,$this->partner_id,$data,$bMoreData,$claim_state,$this->check_only);

									if ($return_code  == CLAIM_OK)
									{
										$entitled_claims++;
									}
									else if (($return_code  == CLAIM_ERROR_ALREADY_SUBMITTED) || ($return_code  == CLAIM_ERROR_ALREADY_PROCESSED))
									{
										$duplicate_claims++;
									}
									else if ($return_code  == CLAIM_ERROR)
									{
										$error_claims++;
									}

									# update line pointer
									$current_line++;
									# grab next
									next($pCsv->csv_data);


								}


								# make sure we only process a certain number of lines before exiting
								AddComment("$this->job_id PAUSING $current_line / $total_lines.   ");
								$this->updateClaimsDetails($current_line,$total_lines,$duplicate_claims,$entitled_claims,$submitted_claims,$updated_claims,$error_claims);

								# we need to update the internal systems
								if ($this->updateProcess($this->job_id,$current_line,$total_lines,$entitled_claims) == JOB_DOES_NOT_EXIST)
								{
									break;
								}


								return false;
							}


						}
						else
						{

							$text	=	"

							Job ID:		$this->job_id 				<br>
							File uploaded: $filename 				<br>
							Uploaded by:$login<br><br>
							There has been a problem reading the header line of this file.<br>
							Please ensure the file you have uploaded has the correct header structure.

							";

							AddComment($text);
							sendEmailNew('FairPlane Travel Agent JOB PROBLEM','cron@fairplane.co.uk','cedric@fairplane.co.uk','FairPlane JOB PROBLEM',$text);


						}
						# update for this job
						$this->updateClaimsDetails($current_line,$total_lines,$duplicate_claims,$entitled_claims,$submitted_claims,$updated_claims,$error_claims);

						if	($bUploaded == true)
						{
							$text	=	"

							Job ID:		$this->job_id 				<br>
							File uploaded: $filename 				<br>
							Uploaded by:$login<br><br>
							Uploaded claims:$total_lines,		<br>
							Duplicate claims:$duplicate_claims,		<br>
							Entitled claims:$entitled_claims,		<br>
							Submitted claims:$submitted_claims,		<br>
							Updated claims:$updated_claims,			<br>
							Errors:$error_claims,					<br>

							";


							# if we need to purge the data, do this
							if	($this->check_only)
							{
								# free up file handles
								unset($pCsv);

								$this->purgeClaimData($this->job_id);
							}


							AddComment($text);
							sendEmailNew('FairPlane Travel Agent JOB COMPLETE','cron@fairplane.co.uk','cedric@fairplane.co.uk','FairPlane JOB COMPLETE',$text);

							# for the first run, we should store the stats
#							$gMysql->update("replace into fp_statistics set uploaded_total='$total_lines', entitled_claims='$entitled_claims',duplicate_claims='$duplicate_claims',error_claims='$error_claims'  where id='$this->job_id'",__FILE__,__LINE__);

						}

					}
				}

				# we need to update the task index pointer if we made it here
				$this->updateTaskIndex($this->job_id,$this->job_template_id);

			}



		}

	}






















}













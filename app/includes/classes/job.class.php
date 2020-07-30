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

# these are codes representing jobs that have several tasks. We build some task templates for these
define('JOB_TEMPLATE_ID_IMPORT_FROM_AUSTRIA',		1);
define('JOB_TEMPLATE_ID_EXPORT_TO_PARTNER',			2);
define('JOB_TEMPLATE_ID_IMPORT_FROM_PARTNER',		3);
define('JOB_TEMPLATE_ID_EXPORT_TO_MAILCHIMP',		4);

# job tasks
define('JOB_TASK_CONVERT_TO_CSV',					1);
define('JOB_TASK_UPLOAD_TO_DATABASE',				2);
define('JOB_TASK_EXPORT_MISSING_FIELDS',			3);

# error codes
define('JOB_ERROR_TEMPLATE',						1);
define('JOB_ERROR_TASK',							2);



# this is the overall status of a job
define('JOB_STATUS_ACTIVE',							0);
define('JOB_STATUS_COMPLETED',						1);
define('JOB_STATUS_PAUSED',							2);

# return this if job is not available.
define('JOB_DOES_NOT_EXIST',						2);



/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 20/05/12
 * Time: 19:10
 * TO DO:  cross reference ID and person in actual database





*/

$gJobTemplate	=
	array(

		array(

			# this job imports data from the austria database and creates a new file with missing fields
			"template_id"	=>	1,
			"template_name"	=>	"import initial 4-col",
			"tasks" =>

				array(

/*					array(
						"task_index"	=>	0	,
						"task_id"		=>	JOB_TASK_CONVERT_TO_CSV,
					),
*/
					array(
						"task_index"	=>	0	,
						"task_id"		=>	JOB_TASK_UPLOAD_TO_DATABASE,
					),
					array(
						"task_index"	=>	1	,
						"task_id"		=>	JOB_TASK_EXPORT_MISSING_FIELDS,
					),
				),
		),

		array(
			# this job exports the data to a partner so they can fill in missing details
			"template_id"	=>	2,
			"template_name"	=>	"Export Entitled Claims",
			"tasks" =>

				array(

					array(
						"task_index"	=>	0	,
						"task_id"		=>	JOB_TASK_CONVERT_TO_CSV,
					),
				),
		),
	);




require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/partner.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/csv.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/claim.class.php");




class Job_Class
{
	private $job_id				=	"";
	private $filename			=	"";
	private $extension			=	"";
	private $partner_id			=	"";
	private $job_template_id	=	"";
	private $added				=	"";
	private $last_updated		=	"";
	private $status				=	"";
	private $task_index			=	"";
	private $login				=	"";
	private $check_only			=	"";
	private $current_line		=	"";

	private $total_lines	=	"";
	private $duplicate_claims	=	"";
	private $entitled_claims	=	"";
	private $submitted_claims	=	"";
	private $updated_claims		=	"";
	private $error_claims		=	"";



	public function __construct()
	{

	}



	# adds the job. if we have a set consignment number, we can
	public function addJob($partner_id,$filename,$extension,$job_template_id,$check_only)
	{
		global	$gMysql;
		global	$gSession;

		# login associated with the job
		$login	=	$gSession->getSessionDataVar('email');


		# test the template is valid
		if	(($pTemplate = $this->getJobTemplate($job_template_id)) == NULL)
		{
			return JOB_ERROR_TEMPLATE;
		}

		$name 	=	$filename;

		# now we need to find out number of referrers are using this code, and add 1
		$strSQL	=	"insert into fp_job (id, filename, extension, partner_id,job_template_id,status,login,added,last_updated,check_only,name)
		values (
		'" . 0 ."',
		'" . $filename ."',
		'" . $extension ."',
		'" . $partner_id ."',
		'" . $job_template_id."',
		'" . JOB_STATUS_ACTIVE ."',
		'" . $login."',
		Now(),Now(),
		'". $check_only ."',
		'". $name ."'


		)";

		# older version has levels too
		$gMysql->insert($strSQL,__FILE__,__LINE__);

		# get last id (roundabout way)
		$strSQL	=	"select id from fp_job where
		filename='" 	. $filename .	"' and
		partner_id='" 	. $partner_id.	"' order by last_updated desc limit 1";

		$job_id			=	$gMysql->queryItem($strSQL,__FILE__,__LINE__);
		$template_name	=	$pTemplate['template_name'] ;

		$gMysql->addLog(LOGTYPE_PARTNER,$partner_id,"$template_name (job id:$job_id) added to queue");

	}


	# updates the job
	private function updateJob($job_id, $task_index, $status)
	{
		global	$gMysql;

		$gMysql->update("update fp_job set task_index='$task_index', status='$status',last_updated=NOW() where id='$job_id'",__FILE__,__LINE__);

	}


	private function pauseJob($job_id)
	{
		global	$gMysql;

		$status	=	JOB_STATUS_PAUSED;

		$gMysql->update("update fp_job set status='$status',last_updated=NOW() where id='$job_id'",__FILE__,__LINE__);

	}


	# updates the details
	private function updateClaimsDetails($current_line,$total_lines,$duplicate_claims,$entitled_claims,$submitted_claims,$updated_claims,$error_claims)
	{
		global	$gMysql;

		# get the sum of all estimated claims for this job
		$estimated_compensation	=	$gMysql->queryItem("select sum(estimated_compensation) from fp_claim where job_id='$this->job_id'",__FILE__,__LINE__);

		$gMysql->update("update fp_job set estimated_compensation='$estimated_compensation', current_line='$current_line', updated_claims='$updated_claims', total_lines='$total_lines', entitled_claims='$entitled_claims',duplicate_claims='$duplicate_claims',submitted_claims='$submitted_claims',error_claims='$error_claims'  where id='$this->job_id'",__FILE__,__LINE__);
	}





	# updates the task index
	private function updateTaskIndex($job_id,$job_template_id)
	{
		global	$gMysql;

		if (($pJobTemplate = $this->getJobTemplate($job_template_id)) != NULL)
		{
			$max_tasks	=	count($pJobTemplate['tasks']);

			if (++$this->task_index >= $max_tasks)
			{
				$this->status		=	JOB_STATUS_COMPLETED;
			}
			$gMysql->update("update fp_job set status='$this->status', task_index='$this->task_index', last_updated=NOW() where id='$job_id'",__FILE__,__LINE__);
		}
	}





	# extract data
	private function extractData($job_data)
	{
		if	(!empty($job_data))
		{
			$this->job_id			=	$job_data['id'];
			$this->filename			=	$job_data['filename'];
			$this->extension		=	$job_data['extension'];
			$this->partner_id		=	$job_data['partner_id'];
			$this->job_template_id	=	$job_data['job_template_id'];
			$this->task_index		=	$job_data['task_index'];
			$this->added			=	$job_data['added'];
			$this->last_updated		=	$job_data['last_updated'];
			$this->status			=	$job_data['status'];
			$this->login			=	$job_data['login'];
			$this->check_only		=	$job_data['check_only'];
			$this->current_line		=	$job_data['current_line'];

			$this->current_line		=	$job_data['current_line'];
			$this->total_lines	=	$job_data['total_lines'];
			$this->duplicate_claims	=	$job_data['duplicate_claims'];
			$this->entitled_claims	=	$job_data['entitled_claims'];
			$this->submitted_claims	=	$job_data['submitted_claims'];
			$this->updated_claims	=	$job_data['updated_claims'];
			$this->error_claims		=	$job_data['error_claims'];


		}
	}

	# return item
	public function getJob($id)
	{
		global	$gMysql;

		if	(($data	=	$gMysql->queryRow("select * from fp_job where id='$id'",__FILE__,__LINE__)) != NULL)
		{
			return	$data;
		}
	}

	# returns the oldest job
	private function getOldestJobInQueue()
	{
		global	$gMysql;

		$status	=	JOB_STATUS_ACTIVE;

		if	(($data	=	$gMysql->queryRow("select * from fp_job where status='$status' order by last_updated desc limit 1",__FILE__,__LINE__)) != NULL)
		{
			return	$data;
		}
	}


	private function getJobTemplate($job_template_id)
	{
		global $gJobTemplate;

		foreach ($gJobTemplate as $pJobTemplate)
		{
			if	($pJobTemplate['template_id'] == $job_template_id)
			{
				return $pJobTemplate;
			}
		}
	}



	private function getJobTask($job_template_id,$index)
	{
		if ($this->status != JOB_STATUS_COMPLETED)
		{
			if (($pJobTemplate = $this->getJobTemplate($job_template_id)) != NULL)
			{
				if (isset($pJobTemplate['tasks'][$index]))
				{
					return $pJobTemplate['tasks'][$index];
				}
			}
		}
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



	# remove any claim data and physical files from the server
	public function purgeClaimData($job_id)
	{
		global $gMysql;

		$gMysql->delete("delete from fp_claim where job_id='$job_id' ",__FILE__,__LINE__);

		if	(($data	=	$this->getJob($job_id)) != NULL)
		{
			# extracts variables
			$this->extractData($data);

			# get the directory for uploads
			$directory		=	$this->getUploadDirectory();

			$filename		=	$this->filename;
			$extension		=	$this->extension;
			# relative path and filename
			$filename_full	=	$_SERVER["DOCUMENT_ROOT"]	.	"/" . $directory . $filename . "." . $extension;

			# remove
			unlink($filename_full);

AddComment("purgeClaimData job ID:$job_id ");

		}

	}

	# deletes the job only if no claims are updated
	public function deleteJob($job_id)
	{
		global $gMysql;

		$claim_state			=	CLAIM_STATE_UNEDITED;
		$claim_state_submitted	=	CLAIM_STATE_CLAIMANT_SUBMITTED;
		$claim_state_processed	=	CLAIM_STATE_PROCESSED;
		$claim_state_partner	=	CLAIM_STATE_PARTNER_UPDATED;

		$gMysql->delete("delete from fp_claim where job_id='$job_id' and (claim_state='$claim_state' or claim_state='$claim_state_partner')",__FILE__,__LINE__);

		# make sure we don't have any claims that have been edited
		if (($num = $gMysql->queryItem("select count(*) from fp_claim where job_id='$job_id' and (claim_state='$claim_state_submitted' or claim_state='$claim_state_processed')",__FILE__,__LINE__)) == 0)
		{
			$gMysql->delete("delete from fp_job where id='$job_id' ",__FILE__,__LINE__);
		}

	}




	private function updateStartProcess($id)
	{
		global	$gMysql;
		#
		$gMysql->update("update fp_job set processing_started=NOW() where id='$id'",__FILE__,__LINE__);
	}



	private function updateProcess($id,$row,$max_rows,$claims)
	{
		global	$gMysql;

		if	(($value = $gMysql->queryItem("select count(*) from fp_job where id='$id'",__FILE__,__LINE__)) == 0)
		{
AddComment("Deleting job ID:$id during updateProcess()");
			$this->deleteJob($id);
			return	JOB_DOES_NOT_EXIST;
		}
		else
		{
			$gMysql->update("update fp_job set current_row='$row', max_rows='$max_rows', current_claims='$claims' where id='$id'",__FILE__,__LINE__);
		}
	}



	public function getUploadDirectory()
	{
		return	"partners/temp/$this->partner_id/";

	}





	# loads the excel file if possible (sometimes file extension may lie)
	private function loadExcelFile($filename_full)
	{
		if	(file_exists($filename_full) == true)
		{
			/**  Identify the type of $inputFileName  **/
			$filetype = PHPExcel_IOFactory::identify($filename_full);
			/**  Create a new Reader of the type that has been identified  **/
			$objReader = PHPExcel_IOFactory::createReader($filetype);
			if ($objReader->canRead($filename_full))
			{
				/**  Load $inputFileName to a PHPExcel Object  **/
				$objPHPExcel = $objReader->load($filename_full);

				return	$objPHPExcel;
			}
		}
	}


























	# uploads a file to temp location '$check_only' means that we do not store any data
	public function uploadJob($file_data,$partner_id,$check_only=0)
	{
		if	(($filename_full =	$this->uploadFile($file_data,$partner_id)) != NULL)
		{
			global $gMysql;

			$gMysql->addLog(LOGTYPE_PARTNER,$partner_id,"uploaded:$this->filename.$this->extension");

			# create a job now the file is uploaded
			$this->addJob($partner_id,$this->filename,$this->extension,JOB_TEMPLATE_ID_IMPORT_FROM_AUSTRIA,$check_only);


			return true;
		}

	}

	# uploads a file to temp location
	public function uploadFile($file_data,$partner_id)
	{
		# these are the fields we need to have in the header in the file from austria
		global	$gCSV_Initial;
		global	$gCSV_final;

		# now we check if it needs converting
		$pCsv			=	new Csv_Class();
		# get a file, and place it into a csv class
		if (($pCsv->import($file_data["tmp_name"])) != NULL)
		{
			# if we have the file back from the partner, then we should be updating
			if (($pCsv->headerContains($gCSV_Initial) == false) && ($pCsv->headerContains($gCSV_final) == false))
			{
				return	false;
			}
		}



		$filename 				=	$file_data["name"];
		# base directory via $_SERVER["DOCUMENT_ROOT"]
		$this->directory		=	"../../../partners/temp/$partner_id/";



		# check if it exists, and if not, create it, exit if we cant
		if ($this->checkCreateDir($this->directory) == true)
		{
			$this->extension		=	pathinfo($filename,PATHINFO_EXTENSION);
			$this->filename			=	basename($filename,$this->extension)	.	date("F j, y, g-i-s");
			$new_base_filename		=	$this->filename;

			# save file into the path
			$new_filename	=	$this->directory . "" . $new_base_filename	. "." . $this->extension;

			//upload the file
			if (move_uploaded_file($file_data["tmp_name"], $new_filename) == true)
			{
				return $new_filename;
			}

			return false;
		}

		return false;
	}


	# creates a directory for the partner if it doesnt exist
	function checkCreateDir($directory)
	{
		if	(is_dir($directory))
		{
			return true;
		}
		if (!mkdir($directory, 0777, true))
		{
			return false;
		}

		return true;
	}




	# gets a unique consignment id that will tag all records
	private function getUniqueConsignmentID()
	{
		global	$gMysql;

		if	(($value = $gMysql->queryItem("select max(consignment_id) from fp_claim",__FILE__,__LINE__)) != NULL)
		{
			return $value + 1;
		}
		# initial consignment id
		return  1;
	}















	# builds table of all consignments for this partner
	public function buildClaimsTable($partner_id,$job_id)
	{
		require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/job.class.php");

		global	$gMysql;
		global	$gCSV_final;

		# list all claims for this consignment and partner
		$string			=	"<table class='table table-hover table-bordered' style='font-size:smaller;'>";
		$string			.=	"<tr>";
		#		$string			.=	$this->arrayToTable($gCSV_final,array('job_id','partner_id'));
		$string .=	$this->arrayToTable(array('delay','reference','date','airline_code','flight_number','departure','arrival','first name','last name','child','address','postcode','city','country','telephone','email','claim_state','last_updated'));

		$string			.=	"<tr>";


		if	(($claims_list = $gMysql->selectToArray("select * from fp_claim where partner_id='$partner_id' and job_id='$job_id' order by added desc",__FILE__,__LINE__)) != NULL)
		{
			$item = 1;

			foreach ($claims_list as $claim )
			{
				$job_id	=	$claim['job_id'];
				# build a row
				$string	.=	$this->buildClaimRow($claim,$item++);
			}
		}


		$string	.=	"</table>";


		return $string;
	}








	private function buildClaimRow($data,$number)
	{
		global	$gMysql;


		$fairplane_id		=	$data['fairplane_id'];
		$partner_id			=	$data['partner_id'];
		$job_id		=	$data['job_id'];
		$reference			=	$data['reference'];
		$airline_code		=	$data['airline_code'];
		$flight_number		=	$data['flight_number'];
		$delay				=	$data['delay'];

		$sched_dep_date 	=	$data['sched_dep_date'];
		$departure_airport 	=	$data['departure_airport'];
		$arrival_airport 	=	$data['arrival_airport'];

		$title				=	$data['title'];
		$first_name			=	$data['first_name'];
		$last_name			=	$data['last_name'];
		$address			=	$data['address'];
		$postcode			=	$data['postcode'];
		$city				=	$data['city'];
		$country			=	$data['country'];
		$telephone			=	$data['telephone'];
		$email				=	$data['email'];
		$hash				=	$data['hash'];
		$last_updated		=	$data['last_updated'];


		$last_updated				=	date( 'M jS Y G:i:s', strtotime($last_updated));


		$claim_state		=	$data['claim_state'];
		if ($data['claim_state'] == CLAIM_STATE_CLAIMANT_SUBMITTED)
		{
			$claim_state	=	"Submitted by user";
		}
		else if ($data['claim_state'] == CLAIM_STATE_PROCESSED)
		{
			$claim_state	=	"Sent to Proclaim";
		}
		else if ($data['claim_state'] == CLAIM_STATE_PARTNER_UPDATED)
		{
			$claim_state	=	"Awaiting user submission";
		}
		else if ($data['claim_state'] == CLAIM_STATE_UNEDITED)
		{
			$claim_state	=	"Initial upload state";
		}
		else
		{
			$claim_state	=	"Not submitted by user";
		}

		if ($data['isChild'])
		{
			$is_child	=	"Child";
		}
		else
		{
			$is_child	=	"";
		}




		$string	=	"<tr>";
		$string	.=	"<td>$delay</td>";
		$string	.=	"<td>$reference</td>";
		$string	.=	"<td>$sched_dep_date</td>";
		$string	.=	"<td>$airline_code</td>";
		$string	.=	"<td>$flight_number</td>";
		$string	.=	"<td>$departure_airport</td>";
		$string	.=	"<td>$arrival_airport</td>";
		$string	.=	"<td>$first_name</td>";
		$string	.=	"<td>$last_name</td>";
		$string	.=	"<td>$is_child</td>";
		$string	.=	"<td>$address</td>";
		$string	.=	"<td>$postcode</td>";
		$string	.=	"<td>$city</td>";
		$string	.=	"<td>$country</td>";
		$string	.=	"<td>$telephone</td>";
		$string	.=	"<td>$email</td>";
		$string	.=	"<td>$claim_state</td>";
		$string	.=	"<td>$last_updated</td>";

		# view the claim as an end-user (so push to new website eventually)
		$button_view	=	"<button type='button' class='btn-sm btn-success btn' onclick=\"window.location='/claim/?formID=$hash'\">Edit (as customer)</button>";
		$button_del		=	"<button type='button' class='btn-sm btn-danger btn' onclick=\"redirect('Are you sure you want to delete this?','/dashboard/partner/delete-claim?formID=$hash');\">Delete</button>";

		$string		.=	"<td>$button_view</td>";
		$string		.=	"<td>$button_del</td>";
		$string		.=	"</tr>";

		return $string;

	}






























	# change it to Excel normal header
	public function arrayToTable($data,$remove_array=array())
	{
		$string	=	"";
		foreach ($data as $key=> $value)
		{
			$value 		=	strtolower($value);

			if	($this->inArray($remove_array,$value) === false)
			{
				$value 		=	str_replace('_',' ',$value);
				$value 		=	ucwords($value);
				$data[$key]	=	$value;
				$string		.=	"<th>$value</th>";
			}


		}

		return $string;
	}







	# check if this column is in the header
	public function inArray($array,$column)
	{
		if	(count($array)== 0)
		{
			return false;
		}
		if (($key = array_search(strtolower($column), array_map('strtolower', $array))) !== FALSE)
		{
			return true;
		}
	}




	# builds table of all consignments for this partner
	public function buildTable($partner_id)
	{
		global	$gMysql;
		# list all consignments for this partner

		$string			=	"<table class='table table-hover table-bordered' style='font-size:smaller;'>";
		$string			.=	"<tr><td>No.</td><td>JOB ID</td><td>ENTITLED / PROCESSED</td><td>ADDED</td><td colspan=4></td></tr>";

		if	(!empty($partner_id))
		{
			$strSQL			=	"select distinct(job_id) as job_id from fp_claim where partner_id='$partner_id' order by added desc";
		}
		else
		{
			$strSQL			=	"select distinct(job_id) as job_id from fp_claim order by added desc";
		}

		if	(($consignment_list = $gMysql->selectToArray($strSQL,__FILE__,__LINE__)) != NULL)
		{
			$item = 1;

			foreach ($consignment_list as $consignment )
			{
				$job_id		=	$consignment['job_id'];
				$partner_id	=	$gMysql->queryItem("select partner_id from fp_job where id='$job_id'",__FILE__,__LINE__);
				# build a row
				$string	.=	$this->buildRow($job_id,$partner_id,$item++);
			}
		}

		$string	.=	"</table>";

		return $string;
	}



	private function buildRow($job_id,$partner_id,$number)
	{
		global	$gMysql;


		$strSQL			=	"select * from fp_job where partner_id='$partner_id' and id='$job_id'";
		$data			=	$gMysql->queryRow($strSQL,__FILE__,__LINE__,CACHE_CACHE_TIME_LONG);

		$records_updated	=	$data['submitted_claims'];
		$entitled_claims		=	$data['entitled_claims'];



		$claim_state_1		=	CLAIM_STATE_PROCESSED;
		$claim_state_2		=	CLAIM_STATE_CLAIMANT_SUBMITTED;

		$entitled_claims		=	$gMysql->queryItem("select count(*) from fp_claim where partner_id='$partner_id' and job_id='$job_id'",__FILE__,__LINE__);
		$records_processed	=	$gMysql->queryItem("select count(*) from fp_claim where partner_id='$partner_id' and job_id='$job_id' and (claim_state='$claim_state_1')",__FILE__,__LINE__);
		$records_submitted	=	$gMysql->queryItem("select count(*) from fp_claim where partner_id='$partner_id' and job_id='$job_id' and (claim_state='$claim_state_2')",__FILE__,__LINE__);




		$added				=	$gMysql->queryItem("select distinct(added) from fp_claim where partner_id='$partner_id' and job_id='$job_id' limit 1",__FILE__,__LINE__);

		$string	=	"<tr>";
		$string	.=	"<td>$number</td>";
		$string	.=	"<td align=center>$job_id</td>";
		$string	.=	"<td align=center>$entitled_claims / $records_processed</td>";
		$string	.=	"<td>$added</td>";

		# export the file
		$button_export	=	"<button type='button' class='btn-sm btn-warning btn' onclick=\"window.location='/dashboard/partner/export?formID=$partner_id&formConsignmentID=$job_id'\">Export Entitled Claims</button>";


		if ($entitled_claims)
		{
			$button_export	=	"</i><button type='button' class='btn-sm btn-success btn download' onclick=\"window.location='/dashboard/partner/export?formID=$partner_id&formConsignmentID=$job_id'\"><i class=\"fa fa-file-excel-o\">&nbsp;Export Entitled Claims</button>";
			$button_view	=	"<button type='button' class='btn-sm btn-success btn' onclick=\"window.location='/dashboard/partner/claims/?formID=$partner_id&formConsignmentID=$job_id'\">View Claims</button>";
		}



		#$button_mail	=	"<button type='button' class='btn-sm btn-primary btn' onclick=\"window.location='/dashboard/partner/mailchimp?formID=$partner_id&formConsignmentID=$job_id'\">Export To Mailchimp</button>";

		# test for mailchimp
		$claim_state_1	=	CLAIM_STATE_PARTNER_UPDATED;
		$claim_state_2	=	CLAIM_STATE_PARTNER_UPDATED;

#		$mailchimp		=	$gMysql->queryItem("select count(*) from fp_claim where partner_id='$partner_id' and job_id='$job_id' and email  REGEXP '^[a-zA-Z0-9][+a-zA-Z0-9._-]*@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]*\\.[a-zA-Z]{2,4}$' and (claim_state='$claim_state_1' or claim_state ='$claim_state_2')",__FILE__,__LINE__);
#		if	($mailchimp > 0)
#		{
#			$button_mail	=	"<button type='button' class='btn-sm btn-primary btn' title='$mailchimp claim(s) to export' onclick=\"window.location='/dashboard/partner/mailchimp?formID=$partner_id&formConsignmentID=$job_id'\">Export To Mailchimp</button>";
#		}






		$button_del		=	"<button type='button' class='btn-sm btn-danger btn' onclick=\"redirect('Are you sure you want to delete this?','/dashboard/partner/delete-consignment?formID=$partner_id&formConsignmentID=$job_id');\">Delete</button>";

		$string		.=	"<td>$button_export</td>";
		$string		.=	"<td>$button_view</td>";
#		$string		.=	"<td>$button_mail</td>";
		$string		.=	"<td>$button_del</td>";
		$string		.=	"</tr>";

		return $string;

	}




























}













<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();


# grab the basic details

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|");
$company_id				=	str_replace($invalid_characters, "", $_POST['company_id']);
$case_key				=	str_replace($invalid_characters, "", $_POST['case_key']);
$policy_code			=	str_replace($invalid_characters, "", $_POST['policy_code']);
$accept					=	str_replace($invalid_characters, "", $_POST['accept']);





# this is the return array with all the commands
$returnArray		=	array();





# check that this solicitor has been correctly assigned the case or not.
# this should update the user dbase
$gUser	=	new User_Class();

# grab the case
if (($data	=	$gUser->getUserViaCaseKey($case_key)) != NULL)
{

	# now for accept or reject

	if ($accept == "accept")
	{
		# empty means that we can store this solicitor
		if (($data['company_id'] == $company_id) && ($data['accepted'] == ""))
		{
			# update to accepted
			$gUser->updateAccepted($case_key,$company_id,$accept);

			$message	=	"Thank You. We will update you in due course.";
			$returnCode	=	"success";

			$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/acceptance/";
			$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/acceptance/";

			# the data
			$data	=
				$case_key					.	">".
				$company_id			 		.	">".
				"ACCEPT"					;

			$data						=	trim($data). "\n";

			$date 						=	date("dmYHis");
			$random 					=	rand(1,1000);
			$file 						=	"accept_".	$date ."_".	 $random . ".csv";
			$fd 						=	fopen("$server_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
			$fout 						= 	fwrite($fd, $data);
			fclose($fd);

			# backup file
			$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
			$fout 						= 	fwrite($fd, $data);
			fclose($fd);

		}
		else if	($data['company_id'] != $company_id)
		{
			$message	=	"Ref:101 - Unfortunately, this link has now expired";
			$returnCode	=	"error";
		}
		else
		{
			$message	=	"Ref:102 - You have already accepted this case. This link has now expired";
			$returnCode	=	"error";
		}

	}
	else
	{
		# empty means that we can store this solicitor
		if (($data['company_id'] == $company_id) && ($data['accepted'] == ""))
		{
			$message	=	"Thank You. This case has been rejected";
			$returnCode	=	"success";

			$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/acceptance/";
			$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/acceptance/";

			# the data
			$data	=
				$case_key					.	">".
				$company_id			 		.	">".
				"REJECT"					;

			$data						=	trim($data). "\n";

			$date 						=	date("dmYHis");
			$random 					=	rand(1,1000);
			$file 						=	"reject_".	$date ."_".	 $random . ".csv";
			$fd 						=	fopen("$server_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
			$fout 						= 	fwrite($fd, $data);
			fclose($fd);

			# backup file
			$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
			$fout 						= 	fwrite($fd, $data);
			fclose($fd);


		}
		else if	($data['company_id'] != $company_id)
		{
			$message	=	"Ref:201 - This case has been rejected";
			$returnCode	=	"error";
		}
		else
		{
			$message	=	"Ref:202 - You have already accepted this case.";
			$returnCode	=	"error";
		}


	}
}


# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);

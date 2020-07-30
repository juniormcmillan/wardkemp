<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 20/05/17
 * Time: 19:10
 *
 */
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


# session
$gSession		=	new Session_Library(

		array(

				"session_id"		=>	SESSION_ID,
				"table"				=>	SESSION_TABLE,
				"logged_in_url"		=>	SESSION_LOGGED_IN_URL,
				"logged_out_url"	=>	SESSION_LOGGED_OUT_URL,
				"login_url"			=>	SESSION_LOGIN_URL,
				"registered_url"	=>	SESSION_REGISTERED_URL,
			)

		);

# check for referrer and set
$case_key 		= strtoupper(GetVariableString('case_key',$_POST,""));
$email 			= GetVariableString('email',$_POST,"");



if	(empty($case_key))
{
	$returnCode			=	"error";
	$popupID			=	"fileNoRef";


	$output 			=	array	(	"popupID" 		=> 		"fileNoRef" 	);


	# return two variables
	echo json_encode($output);
	exit;



}
else
{


	/*
	If you recall, the files need to be named:

Lease:

TA_caseref

Landlord:

LD_caseref

If multiple files, we could do eg.

Lease:
TB_caseref
TC_caseref
Etc

Landlord:
LE_caseref
LF_caseref
Etc

*/



	# ID proof version
	$prefix		=	"IP";



	$date			=	date("Y-m-d");


	# loop for naming the files and saving at once
	foreach($_FILES['file']['name'] as $index=>$name)
	{
		$filename 		=	$name;
		$fileType 		=	pathinfo($_FILES['file']["name"][$index],PATHINFO_EXTENSION);


		$tempFile 		=	$_FILES['file']['tmp_name'][$index];          //3
		$targetPath 	=	"../../../files/id/";
		$backupPath 	=	"../../../files_backup/id/";

		# get the new name
		$new_name		=	$prefix . $case_key .  "_" . $date	. "." .$fileType;


		$backup_name	=	$backupPath. $new_name;

		$new_name		=	$targetPath. $new_name;

		copy($tempFile,$backup_name);

		move_uploaded_file($tempFile,$new_name); //6

		# second item...
		$prefix	=	"IA";

		sendEmailLocal($filename, "website@wardkemp.uk", "cedric@boxlegal.co.uk", "Ward Kemp Ltd" . $subject, "ID Upload", $attachment = $tempFile);

	}













}



$output 			=	array	(	"popupID" 		=> 		"" 	);


# return two variables
echo json_encode($output);





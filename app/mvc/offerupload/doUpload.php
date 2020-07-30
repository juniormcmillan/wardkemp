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




	#  document name
	$prefix		=	"DO";


	$date			=	date("dmy");


	# loop for naming the files and saving at once
	foreach($_FILES['file']['name'] as $index=>$name)
	{
		$filename 		=	$name;
		$fileType 		=	pathinfo($_FILES['file']["name"][$index],PATHINFO_EXTENSION);


		$tempFile 		=	$_FILES['file']['tmp_name'][$index];          //3
		$targetPath 	=	"../../../files/offer/";
		$backupPath		=	"../../../files_backup/offer/";

		# get the new name
		$new_name		=	$prefix . $case_key .  "_" . $date	. "." .$fileType;
		$new_name		=	$targetPath. $new_name;
		$backup_name	=	$backupPath. $prefix . $case_key .  "_" . $date	. "." .$fileType;

		$targetFile 	=  	$targetPath. $_FILES['file']['name'];  //5
		$backupFile 	=  	$targetPath. $new_name;  //5

		copy($tempFile,$backup_name);

		move_uploaded_file($tempFile,$new_name); //6


		$prefix++;

#		sendEmailLocal($name, $email, "cedric@boxlegal.co.uk", "COPY-" . $subject, "ID Upload", $attachment = $tmpFilename);

	}









}



$output 			=	array	(	"popupID" 		=> 		"" 	);


# return two variables
echo json_encode($output);





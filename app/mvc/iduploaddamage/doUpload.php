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


$invalid_characters 	=	array("$", "%", "#", "<", ">", "|","'",'"',"'");

# check for referrer and set
$case_key 		=	strtoupper(GetVariableString('case_key',$_POST,""));
$email 			= 	GetVariableString('email',$_POST,"");
$description	=	str_replace($invalid_characters, "", $_POST['description']);
$room			= 	GetVariable('room',$_POST,"");
$code			= 	GetVariableString('code',$_POST,"");


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






	*/


	# code
	$prefix		=	$code."1";
	$date			=	date("Y-m-d");

	# first, store any description comments
	if (!empty($description))
	{


		$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/damagecomment/";
		$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/damagecomment/";

		# the data
		$data	=
			$case_key					.	">".
			$prefix						.	">".
			$description;

		$data						=	trim($data). "\n";

		$date 						=	date("dmYHis");
		$random 					=	rand(1,1000);
		$file 						=	$prefix."_".	$date ."_".	 $random . ".csv";
		$fd 						=	fopen("$server_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
		$fout 						= 	fwrite($fd, $data);
		fclose($fd);

		# backup file
		$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
		$fout 						= 	fwrite($fd, $data);
		fclose($fd);




	}



	# loop for naming the files and saving at once
	foreach($_FILES['file']['name'] as $index=>$name)
	{
		$filename 		=	$name;
		$fileType 		=	pathinfo($_FILES['file']["name"][$index],PATHINFO_EXTENSION);


		$tempFile 		=	$_FILES['file']['tmp_name'][$index];          //3
		$targetPath 	=	"../../../files/damage/";
		$backupPath 	=	"../../../files_backup/damage/";

		# get the new name
		$new_name		=	$prefix . $case_key .  "_" . $date	. "." .$fileType;


		$backup_name	=	$backupPath. $new_name;

		$new_name		=	$targetPath. $new_name;

		copy($tempFile,$backup_name);

		move_uploaded_file($tempFile,$new_name); //6


		$prefix++;

		sendEmailLocal($filename, "website@wardkemp.uk", "cedric@boxlegal.co.uk", "Ward Kemp Ltd" . $subject, "ID Upload", $attachment = $tempFile);

	}













}



$output 			=	array	(	"popupID" 		=> 		"" 	);


# return two variables
echo json_encode($output);





<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();


# grab the basic details
$formData	=	array();
parse_str($_POST['formData'],$formData);

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|");
$formEmail				=	str_replace($invalid_characters, "", $formData['formEmail']);
$formPhone				=	str_replace($invalid_characters, "", $formData['formTelephone']);
$formTitle				=	str_replace($invalid_characters, "", $formData['formTitle']);
$formForeName			=	str_replace($invalid_characters, "", $formData['formForeName']);
$formSurName			=	str_replace($invalid_characters, "", $formData['formSurName']);




$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();


# error checks
if (empty($formForeName))
{
	$message		=	"Please enter your <b>forename</b> in the box provided";
}
else if (empty($formTitle))
{
	$message		=	"Please select your <b>Title</b> (Mr, Mrs etc) in the box provided";
}
# error checks
else if (empty($formSurName))
{
	$message		=	"Please enter your <b>surname</b> in the box provided";
}
else if (empty($formPhone))
{
	$message		=	"Please enter your <b>phone number</b> in the box provided";
}
else if (empty($formEmail))
{
	$message		=	"Please enter your <b>email address</b> in the box provided";
}


if (!empty($message))
{
	$returnCode		=	"error";
	$returnArray	=

		array	(
			"returncode" 		=> 		$returnCode,
			"message"			=>		$message,
		);
	# return two variables
	echo json_encode($returnArray);
	exit;
}







$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/claim/";
$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/claim/";

# the data
$data	=
	"PLEVIN" 	.	">".
	$formTitle					.	">".
	$formForeName				.	">".
	$formSurName				.	">".
	$formEmail					.	">".
	$formPhone;

$data						=	trim($data). "\n";

$date 						=	date("dmYHis");
$random 					=	rand(1,1000);
$file 						=	"claim_".	$date ."_".	 $random . ".csv";
$fd 						=	fopen("$server_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
$fout 						= 	fwrite($fd, $data);
fclose($fd);

# backup file
$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
$fout 						= 	fwrite($fd, $data);
fclose($fd);


# this should update the user dbase
$gUser	=	new User_Class();
# now add the order_id
$gUser->createUser($formEmail,$formTitle,$formForeName,$formSurName,$formPhone);

$returnCode	=	"success";
$message	=	"OK";
AddComment($data);










$htmlMessage = "
		Name: " . $formFullName ."\n
		Email: " . $formEmail ."\n
		Telephone: " . $formPhone ."\n

		";

$htmlMessage	=	str_replace('\r\n', "\r\n",$htmlMessage);


$subject 	=	"Enquiry from PPI Solicitors Website";
$email		=	"website@ppisolicitors.co.uk";

sendEmailLocal("PPI Solicitors Website", $email, "cedric@ppisolicitors.co.uk", $subject, nl2br($htmlMessage));




# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);

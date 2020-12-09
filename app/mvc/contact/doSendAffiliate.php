<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();


# grab the basic details
$formData	=	array();
parse_str($_POST['formData'],$formData);

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|", "'");
$formEmail				=	str_replace($invalid_characters, "", $formData['formEmail']);
$formPhone				=	str_replace($invalid_characters, "", $formData['formTelephone']);
$formName				=	str_replace($invalid_characters, "", $formData['formName']);
$formAddress			=	str_replace($invalid_characters, "", $formData['formAddress']);



SetCommentFile("affiliates.txt");


$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();


# error checks
# error checks
if (empty($formName))
{
	$message		=	"Please enter your <b>name</b> in the box provided";
}
else if (empty($formPhone))
{
	$message		=	"Please enter your <b>phone number</b> in the box provided";
}
else if (empty($formEmail))
{
	$message		=	"Please enter your <b>email address</b> in the box provided";
}
else if (empty($formAddress))
{
	$message		=	"Please enter your <b>address</b> in the box provided";
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




# if first numbers are 44, then delete first two numbers
$first_two 				=	substr($formPhone, 0, 2);
if($first_two == "44")
{
	$formPhone	=		substr($formPhone, 2);
}

$first_one 				=	substr($formPhone, 0, 1);

if($first_one != "0")
{
	$formPhone	=		"0".$formPhone;
}








$htmlMessage = "
		Name: " . $formName ."\n
		Email: " . $formEmail ."\n
		Telephone: " . $formPhone ."\n
		Address: " . $formAddress."\n

		";

$htmlMessage	=	str_replace('\r\n', "\r\n",$htmlMessage);


$subject 	=	"Registration of Ward Kemp Affiliate";
$email		=	"website@wardkemp.uk";

sendEmailLocal("Registration from Ward Website", $email, "cedric@boxlegal.co.uk", $subject, nl2br($htmlMessage));
sendEmailLocal("Registration from Ward Website", $email, "partner@wardkemp.uk", $subject, nl2br($htmlMessage));



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);

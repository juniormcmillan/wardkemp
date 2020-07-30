<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();


$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();


$case_key			= GetVariableString('case_key',$_POST,"");


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUserViaCaseKey($case_key)) == NULL)
{
	$message		=	"Invalid CASE KEY";
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



$returnCode	=	"success";
$message	=	"OK";


$formFullName	=	$data['title'] .	" " . $data['forename'] .	" " . $data['surname'];
$formEmail		=	$data['email'];
$formPhone		=	$data['mobile'];




$htmlMessage = "
		Case Key: " . $case_key."\n\n
		Name: " . $formFullName ."\n
		Email: " . $formEmail ."\n
		Telephone: " . $formPhone ."\n\n\n
		This person has indicated that they do not have the offer letter to hand

		";

$htmlMessage	=	str_replace('\r\n', "\r\n",$htmlMessage);


$subject 	=	"No Offer Letter - PPI Solicitors Website";
$email		=	"website@ppisolicitors.co.uk";

sendEmailLocal("PPI Solicitors Offer Letter", $email, "cedric@ppisolicitors.co.uk", $subject, nl2br($htmlMessage));
sendEmailLocal("PPI Solicitors Offer Letter", $email, "team@ppisolicitors.co.uk", $subject, nl2br($htmlMessage));




# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);

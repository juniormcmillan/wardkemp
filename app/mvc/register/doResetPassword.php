<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");



# mysql
$gMysql			=	new Mysql_Library();
# session
$gRegistration	=	new	Registration_Class( );


$email	= 	GetVariableString('formEmail',$_GET,0);


$returnCode		=	"error";
$error_string	=	"";
$message		=	"";


if	(empty($email))
{
	$message	.= "Please enter your <b>Email Address</b>";
}
# returns what happens when registration is attempted
else
{
	# send a code to the email address
	$gRegistration->request_new_password($email);
	$returnCode		=	"success";
}






# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,


	);



# return two variables
echo json_encode($returnArray);



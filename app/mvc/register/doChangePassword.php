<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");



# mysql
$gMysql			=	new Mysql_Library();
# session
$gRegistration	=	new	Registration_Class( );



$hash			= 	GetVariableString('formHash',$_GET,0);
$password	 	= 	GetVariableString('formPassword',$_GET,0);
$password2	 	= 	GetVariableString('formPassword2',$_GET,0);



$returnCode		=	"error";
$error_string	=	"";
$message		=	"";



if	(empty($password))
{
	$message	.= "<li>Please enter your <b>Password</b></li>";
}
else
{
	if	(strcmp($password,$password2) != 0)
	{
		$message	.= "<li>Passwords do not match</li>";
	}
}

if	(empty($message))
{
	# send a code to the email address
	$retcode		=	$gRegistration->set_new_password($hash,$password);

	if ($retcode == LOST_LINK_OK)
	{
		$returnCode		=	"success";
	}
	else
	{
		$message	=	"Your link has expired or is invalid.<br>Please request another.";


	}
}








# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,


	);




# return two variables
echo json_encode($returnArray);



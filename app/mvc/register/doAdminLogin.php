<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();
# session
$gRegistration	=	new	Registration_Class( array(

		"session_id"			=>	SESSION_ADMIN_ID,
		"logged_in_url"			=>	SESSION_ADMIN_LOGGED_IN_URL,
		"logged_out_url"		=>	SESSION_ADMIN_LOGGED_OUT_URL,
		"login_url"				=>	SESSION_ADMIN_LOGIN_URL,
	)
);




$username		= 	GetVariableString('formUsername',$_GET,0);
$password		= 	GetVariableString('formPassword',$_GET,0);
$remember_me	 = 	GetVariableString('formAdminRememberMe',$_GET,0);


$returnCode		=	"error";
$error_string	=	"";
$message		=	"";



# validate form data
if	( (empty($username)) || (empty($password)))
{
	$message	= "Please enter your login details";
}
# returns what happens when registration is attempted
if	(empty($message))
{
	$retcode		=	$gRegistration->login_admin($username,$password);

	if	($retcode == LOGIN_OK)
	{

		$returnCode	=	"success";
	}
	else
	{
		$message	.= "Login details are incorrect.";
	}


}












# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,
		"returnurl"			=>		$gRegistration->logged_in_url,


	);



# return two variables
echo json_encode($returnArray);


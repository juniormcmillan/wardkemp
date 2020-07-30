<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();
# session
$gRegistration	=	new	Registration_Class( array(

		"session_id"			=>	SESSION_ID,
		"table"					=>	SESSION_TABLE,
		"logged_in_url"			=>	SESSION_LOGGED_IN_URL,
		"logged_out_url"		=>	SESSION_LOGGED_OUT_URL,
		"login_url"				=>	SESSION_LOGIN_URL,
		"registered_url"		=>	SESSION_REGISTERED_URL
	)
);



$email			= 	GetVariableString('loginEmail',$_POST,0);
$password	 	= 	GetVariableString('loginPassword',$_POST,0);
$remember_me	 = 	GetVariableString('loginRememberMe',$_POST,0);




$returnCode		=	"error";
$error_string	=	"";
$message		=	"";



# validate form data
if	( (empty($email)) || (empty($password)))
{
	$message	= "Please enter your login details";
}
# returns what happens when registration is attempted
if	(empty($message))
{

	# this sets the remember me cookie for one year, if set
	if($remember_me)
	{
		setcookie ("loginRememberMe",$email,time()+ (10 * 365 * 24 * 60 * 60), "/");
	}

	$returnCode	=	"success";
}






# this sets the cookie for the time being -  it will be destroyed if the login is not successful
setcookie ("boxlegal[loginEmail]",$email,time()+ (10 * 365 * 24 * 60 * 60), "/");
setcookie ("boxlegal[loginPassword]",$password,time()+ (10 * 365 * 24 * 60 * 60), "/");




# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,
		"returnurl"			=>		'../login' #$gRegistration->logged_in_url,


	);



# return two variables
echo json_encode($returnArray);


<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();
# session
$gRegistration	=	new	Registration_Class();



$email			= 	GetVariableString('formEmail',$_GET,0);
$password	 	= 	GetVariableString('formPassword',$_GET,0);
$password2	 	= 	GetVariableString('formPassword2',$_GET,0);




$returnCode		=	"error";
$error_string	=	"";
$message		=	"";



# validate form data
if	(empty($email))
{
	$error_string	.= "Please enter your <b>Email Address</b>";
}
else if	(empty($password))
{
	$error_string	.= "Please enter your <b>Password</b>";
}
else
{
	if	(strcmp($password,$password2) != 0)
	{
		$error_string	.= "Passwords do not match";
	}
}

# returns what happens when registration is attempted
if	(empty($error_string))
{
	$retcode		=	$gRegistration->validate_customer_details($email);

	if	($retcode == REGISTER_ACCOUNT_EXISTS)
	{
		$error_string	.= "This account already exists, please login using your details supplied or request a new password";
	}
	else if	($retcode == REGISTER_USER_NOT_IN_PROCLAIM_DATABSE)
	{
		$error_string	.= "Details not in the database yet. Please try again in 24hrs before contacting support";
	}
	else if	($retcode == REGISTER_EXISTS_IN_PROCLAIM_DATABSE)
	{
		$gRegistration->register_fairplane_customer($email,$password);

		$returnCode	=	"success";
	}

}




# if we have errors
if	(!empty($error_string))
{
	$message	=	$error_string;


}











# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,


	);



# return two variables
echo json_encode($returnArray);


<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();
# session
$gRegistration	=	new	Registration_Class();



$email	= 	GetVariableString('formEmail',$_GET,0);


$returnCode		=	"error";
$error_string	=	"";
$message		=	"";



if	(empty($email))
{
	$error_string	.= "<li>Please enter your <b>Email Address</b></li>";
}
# returns what happens when registration is attempted
else
{
	# we will need to do some dbase checks as well
	if	(($data	=	$gMysql->queryRow("SELECT * from fp_client where email='$email'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
	{
		if	($data['activated'] == 1)
		{
			$error_string	= "Account is already activated.";
		}
	}
	else
	{
		$error_string	= "An account with that email address is not registered in our system.";
	}
}

# empty means ok
if	(empty($error_string))
{
	# send a code to the email address
	$gRegistration->create_activation_code($email);

	$returnCode		=	"success";
}




	$message	= $error_string;











# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,


	);



# return two variables
echo json_encode($returnArray);


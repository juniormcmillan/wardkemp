<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$email			= 	GetVariableString('formEmail',$_GET,0);
$remember_me	 = 	GetVariableString('formRememberMe',$_GET,0);
$remember		 = 	GetVariableString('value',$_GET,0);




$returnCode		=	"success";
$error_string	=	"";
$message		=	"";


# this sets the remember me cookie for one year, if set
if($remember == "checked")
{
	setcookie ("remember_me_email",$email,time()+ (10 * 365 * 24 * 60 * 60), "/");
}
else
{
	$email	=	"";
	if(isset($_COOKIE["remember_me_email"]))
	{
		setcookie ("remember_me_email",$email,-60, "/");
	}
}









# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,
		"email"			=>		$email,


	);



# return two variables
echo json_encode($returnArray);


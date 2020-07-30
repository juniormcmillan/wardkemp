<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$username		= 	GetVariableString('formUsername',$_GET,0);
$password		= 	GetVariableString('formPassword',$_GET,0);
$remember_me	 = 	GetVariableString('formAdminRememberMe',$_GET,0);
$remember		 = 	GetVariableString('value',$_GET,0);




$returnCode		=	"success";
$error_string	=	"";
$message		=	"";


# this sets the remember me cookie for one year, if set
if($remember == "checked")
{
	setcookie ("remember_me_username",$username,time()+ (10 * 365 * 24 * 60 * 60), "/");
	setcookie ("remember_me_password",$password,time()+ (10 * 365 * 24 * 60 * 60), "/");
}
else
{
	$username	=	"";
	$password	=	"";
	if(isset($_COOKIE["remember_me_username"]))
	{
		setcookie ("remember_me_username",$username,-60, "/");
		setcookie ("remember_me_password",$username,-60, "/");
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


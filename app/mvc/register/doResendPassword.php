<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");



# mysql
$gMysql			=	new Mysql_Library();
# session
$gRegistration	=	new	Registration_Class( );


$email	= 	GetVariableString('formLostEmailAddress',$_POST,0);


$returnCode		=	"error";
$error_string	=	"";
$message		=	"";

$data		=	$gMysql->queryRow("select * from user_access where email='$email' and active=1",__FILE__,__LINE__);

if (!empty($data))
{

	$input .= "You recently requested your password from Box Legal<br>";
	$input .= "Your password is: " . $data['password'] . "<br><br>";
	$input .= "If you did not request this password please contact us.<br>";
	$input .= "The Box Legal Team";
	$subject = "Password reminder from Box Legal Website";

	sendEmailLocal("Box Legal Website", "info@boxlegal.co.uk", $email, $subject, $input);

	$returnCode		=	"success";


	$message = "Thank you. Your password has been sent.<br><br>";
}
else
{
	$message = "Your email address was not recognised. Please try again.<br><br>";
}






# returns the last N messages from an owner
$returnArray		=

	array	(

		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,


	);



# return two variables
echo json_encode($returnArray);



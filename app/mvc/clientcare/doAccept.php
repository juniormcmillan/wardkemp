<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql					=	new Mysql_Library();



$returnCode				=	"success";
# this is the return array with all the commands
$returnArray			=	array();


# grab the basic details
$uri					=	str_replace($invalid_characters, "", $_POST['uri']);
$email					=	str_replace($invalid_characters, "", $_POST['email']);
$case_key				=	str_replace($invalid_characters, "", $_POST['case_key']);


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUser($case_key,$email)) == NULL)
{
	$message		=	"Details not found";
}
else
{
	# show that we have accepted the details
	$gUser->acceptUserPage($case_key,$email,$uri);
}





if (!empty($message))
{
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


# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);

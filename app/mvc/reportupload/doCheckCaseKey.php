<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");



$case_key 		= strtoupper(GetVariableString('formCaseKey',$_POST,""));
$company_id		= GetVariableString('formCompanyID',$_POST,"");

# mysql
$gMysql			=	new Mysql_Library();


$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUserViaCaseKey($case_key)) == NULL)
{
	$message		=	"Invalid CASE KEY";
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
# now check that this company is registered
else
{
	if	(strcasecmp($data['company_id'],$company_id) !== 0)
	{
		$message		=	"You do not have this case";
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

}



$returnCode	=	"success";
$message	=	"Please upload your report";


# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);

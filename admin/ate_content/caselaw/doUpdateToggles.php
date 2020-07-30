<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 20/05/12
 * Time: 19:10
 * TO DO:  cross reference ID and person in actual database
 *
 */
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$message			=	"";
$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();


$id		 						= GetVariableString('id',$_POST,"");
$status				 			= GetVariableString('status',$_POST,"");


if ($status == "true")
{
	$status 			= "A";
}
else
{
	$status 			= "D";
}


$gMysql					=	new Mysql_Library();


# we are updating our gpdr records

$gMysql->update("update ate_caselaw set 

status='$status'

where id='$id'", __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);







# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);


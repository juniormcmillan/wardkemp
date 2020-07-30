<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 20-Jul-16
 * Time: 11:05 AM
 */

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/ssp.class.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");




$gMysql					=	new Mysql_Library();

$idOrderList 			= 	$_POST['idOrderList'];
$idOrderNameList 		= 	$_POST['idOrderNameList'];

$category 				= 	GetVariableString('category',$_POST,$_GET);

# the order of all items for this section will be reset and stored

$num	=	count($idOrderList);
for ($i=0;$i<$num;$i++)
{
	$id	=	$idOrderList[$i];

	$gMysql->update("update ate_caselaw set sort_order='$i' where id='$id'", __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);
#	$data	=	$gMysql->queryRow("select * from ate_caselaw where category='$category' and id='$id'", __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);

}

$message	=	"completed!";

$returnArray	=	array();

# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);


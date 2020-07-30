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


$category			= GetVariableString('category',$_POST,"");
$new_category		= GetVariableString('new_category',$_POST,"");
$new_order			= GetVariableString('new_order',$_POST,"");


$gMysql					=	new Mysql_Library();

$gMysql->update("update ate_caselaw set category='$new_category',category_order='$new_order' where category='$category'", __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);


$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);


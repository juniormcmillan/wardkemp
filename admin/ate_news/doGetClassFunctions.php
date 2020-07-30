<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 12-Jan-16
 * Time: 12:10 PM
 */
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();

# we need the Carrier and the the Flight Number to effect this query.
# results would be something like BA 2156
$controller 		= 	strtoupper(GetVariableString('controller',$_POST,0));

$returnCode			=	"success";
$returnArray		=	array();

# create the class name
$class_name			=	ucfirst(strtolower($controller))	.	"_Controller";

# make sure it exists
if (class_exists($class_name,true) == true)
{
	$action_data		=	get_class_methods($class_name);
	$remove_array		=	array('__construct','__destruct','appendParams','prependTags','appendTags','checkAppendPopup','getTemplate','getHeader','getFooter');
	$action_data		=	array_values(array_diff($action_data, $remove_array));

	# this will get the names of all the templates
	$directory 			=	DOCUMENT_ROOT	.	'mvc/'. $controller . '/';
	$template_data		=	file_list($directory,".html");
}
else
{
	$returnCode			=	"error";
	$message			=	"class $class_name does not exist";
}



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,
		"action"			=>		$action_data,
		"template"			=>		$template_data,

	)

);



# return two variables
echo json_encode($returnArray);




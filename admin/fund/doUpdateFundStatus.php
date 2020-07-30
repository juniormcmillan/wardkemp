<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 20/05/12
 * Time: 19:10
 * To change this template use File | Settings | File Templates.
 */
session_start();

require($_SERVER["DOCUMENT_ROOT"]."/lib/lib_mysql.php");
require($_SERVER["DOCUMENT_ROOT"]."/lib/common.php");


# create new class
$gMysql	=	new	MySQL_class;
# create connection to dbase
$gMysql->Create(null);


# get value
$value	=	GetVariableString('value',$_POST,"");



# set it
$gMysql->Update("update system set fund='$value'",__FILE__,__LINE__);




# this is the return array with all the commands
$returnArray	=	array();

# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

								array	(
								            "error_html"		=>		$policyErrors,
											"returncode" 		=> 		$returnCode
										)

									);




# return two variables
echo json_encode($returnArray);

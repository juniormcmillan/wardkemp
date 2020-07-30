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


$formBank			= GetVariableString('formBank',$_POST,"");
$formAccount 		= GetVariableString('formAccount',$_POST,"");
$formAccountName 	= GetVariableString('formAccountName',$_POST,"");
$formSortCode1 		= GetVariableString('formSortCode1',$_POST,"");
$formSortCode2 		= GetVariableString('formSortCode2',$_POST,"");
$formSortCode3 		= GetVariableString('formSortCode3',$_POST,"");


$case_key 		= GetVariableString('case_key',$_POST,"");
$surname 		= GetVariableString('surname',$_POST,"");


if	(empty($case_key))
{
	$returnCode			=	"error";
	$message			=	"There is no case key or ref code";
}
else
{

	$number		=	date("U");

	$data		=	$case_key	. ">" . $formAccountName	. ">" . $formAccount . ">" . $formSortCode1 .$formSortCode2 .$formSortCode3;
	$data		=	trim($data). "\n";

	$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/details/";

	$file 				=	"BD$number.csv";
	$fd 				=	fopen("$server_location$file", "w+") or die ("<br>Error creating file<br>");
	$fout 				= 	fwrite($fd, $data);
	fclose($fd);

	$returnCode			=	"success";

}







# this is the return array with all the commands
$returnArray	=	array();



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

								array
										(	"returncode" 		=> 		$returnCode,
								            "popupID"			=>		$popupID,
										)

									);




# return two variables
echo json_encode($returnArray);





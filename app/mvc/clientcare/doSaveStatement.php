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


$invalid_characters 	=	array("$", "%", "#", "<", ">", "|", '"', "'");


$formData	=	array();
parse_str($_POST['formData'],$formData);


# check for variables
$case_key		= $formData['case_key'];


$q1				= str_replace($invalid_characters, "", $formData['q1']);
$q2				= str_replace($invalid_characters, "", $formData['q2']);
$q2a			= str_replace($invalid_characters, "", $formData['q2a']);
$q2b			= str_replace($invalid_characters, "", $formData['q2b']);
$q3				= str_replace($invalid_characters, "", $formData['q3']);
$q3a			= str_replace($invalid_characters, "", $formData['q3a']);
$q3b			= str_replace($invalid_characters, "", $formData['q3b']);
$q4				= str_replace($invalid_characters, "", $formData['q4']);
$q4a			= str_replace($invalid_characters, "", $formData['q4a']);
$q5				= str_replace($invalid_characters, "", $formData['q5']);
$q6				= str_replace($invalid_characters, "", $formData['q6']);
$q6a			= str_replace($invalid_characters, "", $formData['q6a']);
$q6b			= str_replace($invalid_characters, "", $formData['q6b']);
$q6c			= str_replace($invalid_characters, "", $formData['q6c']);
$q7				= str_replace($invalid_characters, "", $formData['q7']);
$q8				= str_replace($invalid_characters, "", $formData['q8']);
$q9				= str_replace($invalid_characters, "", $formData['q9']);
$q10			= str_replace($invalid_characters, "", $formData['q10']);
$q10a			= str_replace($invalid_characters, "", $formData['q10a']);
$q11			= str_replace($invalid_characters, "", $formData['q11']);
$q11a			= str_replace($invalid_characters, "", $formData['q11a']);
$q12			= str_replace($invalid_characters, "", $formData['q12']);
$q12a			= str_replace($invalid_characters, "", $formData['q12a']);
$q13			= str_replace($invalid_characters, "", $formData['q13']);
$q14			= str_replace($invalid_characters, "", $formData['q14']);
$q15			= str_replace($invalid_characters, "", $formData['q15']);

if	(empty($case_key))
{
	$returnCode			=	"error";
	$message			=	"There is no case key or ref code";
}
else
{

	$data		=	$case_key	. ">" . $q1	. ">" . $q2 .  ">" . $q2a .  ">" . $q2b .  ">" . $q3 .  ">" . $q3a .  ">" . $q3b .  ">" . $q4 .  ">" . $q4a .  ">" . $q5
					.  ">" . $q6	.	">" . $q6a	.	">" . $q6b	.	">" . $q6c	.	">" . $q7.	">" . $q8	.  ">" . $q9 .  ">" . $q10 .  ">" . $q10a . ">" . $q11
					. ">" . $q11a . ">" . $q12 . ">" . $q12a . ">" .  $q13 . ">" .  $q14 . ">" .  $q15;

	$data		=	trim($data). "\n";

	$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/statement/";
	$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/statement/";

	$file 				=	"statement_$case_key.csv";
	$fd 				=	fopen("$server_location$file", "w+") or die ("<br>Error creating file<br>");
	$fout 				= 	fwrite($fd, $data);
	fclose($fd);


	# backup file
	$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
	$fout 						= 	fwrite($fd, $data);
	fclose($fd);

	$returnCode			=	"success";

}







# this is the return array with all the commands
$returnArray	=	array();



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

								array
										(	"returncode" 		=> 		$returnCode,
								            "message"			=>		$message,
										)

									);




# return two variables
echo json_encode($returnArray);





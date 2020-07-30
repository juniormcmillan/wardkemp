
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 20/05/12
 * Time: 19:10
 * To change this template use File | Settings | File Templates.
 */
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");



$gMysql					=	new Mysql_Library();


$folder			=	"../../../app/images/blog/";

$image			=	 $_FILES['file']["name"];
$newFilename	=	$folder . $image;

$imageFileType 	=	strtolower(pathinfo($_FILES['file']["name"],PATHINFO_EXTENSION));

//upload the file
if (move_uploaded_file($_FILES['file']["tmp_name"], $newFilename) == false)
{
	$returnCode		=	"error";
	$message 		=	"<larger>Error</larger><br>There is a problem uploading this file.";

}
else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"  )
{
	$returnCode			=	"error";
	$message 		=	"<larger>Error</larger><br>Sorry, only JPG, JPEG, PNG, GIF files are allowed.";
}

else
{
	# variable of the page
	if ($id =	GetVariable("id",$_POST,$_GET,""))
	{
		$returnCode			=	"success";
		$message 		=	"<larger>Thank You</larger><br>Your image has been uploaded";
		$image			=	 $_FILES['file']["name"];

		$strSQL = "UPDATE sm_post SET story_image = '$image' WHERE id = $id";
		$gMysql->update($strSQL,__FILE__,__LINE__);
	}
	else
	{
		$returnCode			=	"error";
		$message 		=	"<larger>Error</larger><br>Sorry, Cannot find details of the page. Are you logged in?";
	}

}





# this is the return array with all the commands
$returnArray	=	array();



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array
	(	"returncode" 		=> 		$returnCode,
		"message"			=>		$message,
		"image"				=>		$image,
	)

);




# return two variables
echo json_encode($returnArray);








# logs info to logfile
function AddComment($comment = "",$fn="logfile.txt")
{


	if	(!file_exists("$fn"))
	{
		if (!$handle = fopen($fn, 'w+')) {  }
		// Write $logline to our logfile.
		if (fwrite($handle, "") === FALSE) {  } fclose($handle);
		chmod("$fn", 0777);
	}



	# Getting the information
	if	(array_key_exists('REMOTE_ADDR',$_SERVER))
	{
		$ipaddress		=	$_SERVER['REMOTE_ADDR'];
		$page			=	"http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
		# gives log errors a lot
		#		$useragent		=	$_SERVER['HTTP_USER_AGENT'];
		$useragent		=	"";
		$remotehost		=	@getHostByAddr($ipaddress);
	}
	else
	{
		$ipaddress		=	"";
		$page			=	"";
		$useragent		=	"";
		$remotehost		=	"";
	}

	$time			=	date("Y-m-d G:i:s");

	if (!$handle = fopen($fn, 'a+')) { echo("Failed to open addcomment log file $fn"); return;}

	// Create log line
	$logline	=	$time . '|'	. $comment . "\n";


	// Write $logline to our logfile.
	if (fwrite($handle, $logline) === FALSE) { echo("Failed to write to main log file"); } fclose($handle);



}



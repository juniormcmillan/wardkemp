<?
/*
Basic login example with php user class
http://phpUserClass.com
*/

if (isset($checkLogin) == false)
{
	$checkLogin = true;
}
require_once($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/access.class.php");
$user = new flexibleAccess();

if	(isset($_GET['logout']))
{
	if ( $_GET['logout'] == 1 )
		$user->logout('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
}

if ( !$user->is_loaded() && $checkLogin == true )
{
  AddCommentLogin("User failed attempt ");
  //User is loaded
  header("Location: /admin/login.php");

  // added by CM -13/04/15 extra security
  exit;
}



# logs info to logfile
function AddCommentLogin($comment = "")
{

	$fn	=	$_SERVER["DOCUMENT_ROOT"]. "/logfile.txt";

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
		$page			=	$_SERVER['REQUEST_URI']; #"http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
# gives log errors a lot
		$useragent		=	$_SERVER['HTTP_USER_AGENT'];
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
	$logline	=	$time . '|'	.	$ipaddress . '|' . 	$useragent . '|'	. 	$page . '|'	. $comment . "\n";


	// Write $logline to our logfile.
	if (fwrite($handle, $logline) === FALSE)
	{
		echo("Failed to write to main log file");
	}

	fclose($handle);



}

?>
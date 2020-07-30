<?php
//date_default_timezone_set('Europe/London');
// Associative array to store website settings
$websiteSettings = Array();

session_start();
while($vars=each($_REQUEST))
	{
	$$vars["key"]=$vars["value"];
	}
$pagetitle = "FairplaneNetwork";
$sitemail = "admin@surefiremedia.co.uk";

$thisCategory = $_GET["cid"];

if(!isset($_COOKIE["remember"]))
	{
	mt_srand((double)microtime() * 1000000);
	if(!$_SESSION["sessionid"])
		{
		$charset = "23456789ABCDEFGHJKLMNPQRSTWXYZ";
		for ($i=0;$i<10;$i++)
			{
  			$sessionid = random_string($charset,8); 
  			}
  		$_SESSION["sessionid"]=$sessionid;
		setcookie("basket", $sessionid);
		}
	}
else
	{
	$_SESSION["sessionid"]=$_COOKIE["basket"];
	}
	
$sessionid=$_SESSION["sessionid"];

if($_SESSION["login"]=="")
	{
	$_SESSION["login"] = "false";
	}

if($_REQUEST["page"]=="summary")
	{
	if ($_COOKIE['admin']!='1'){
		if(!is_valid_email($_POST["email"]))
			{
			header("location:index.php?page=checkout");
			}
		}
	}

//**********************************************
// START OF POST VARIABLES
//**********************************************

// POST VARIABLES
foreach($_POST as $key=>$value){ 
  
  $value = str_replace("\\", "", $value);
  
  $$key = $value;
  $i[] = $value;
  $counter++;

} 

// GET VARIABLES
foreach($_GET as $key=>$value){ 
  
  $value = str_replace("\\", "", $value);
  
  $$key = $value;
  $i[] = $value;
  $counter++;

} 

//**********************************************
// END
//**********************************************
?>

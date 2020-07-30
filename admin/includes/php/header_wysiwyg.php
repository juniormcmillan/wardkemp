<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header('Content-Type: text/html;  charset=utf8');

if(!isset($pageTitle))
{
	$pageTitle = "Admin";
}
if(!isset($pageSection))
{
	$pageSection = "1";
}
if(!isset($subSection))
{
	$subSection = "0";
}

include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/check_login.php");
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/scripts.php");
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/globals.php");
ob_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $websiteSettings["websiteName"]; if(isset($pageTitle)) echo " - ".$pageTitle; ?></title>
<style type="text/css" media="screen">
/* <![CDATA[ */
@import url(/admin/includes/css/admin.css);
/* ]]> */
</style>
<!--[if IE]>
    <link rel="stylesheet" type="text/css" href="ie-hacks.css" media="screen" />
<![endif]-->
<!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="ie-6-hacks.css" media="screen" />
<![endif]-->
<script language="javascript" src="/admin/includes/js/admin.js"></script>
<?php
if($datePicker)
{
	echo '<script language="javascript" src="/admin/includes/js/calendarDateInput.js"></script>';
}
?>


<!--
<link rel="stylesheet" type="text/css" href="/app/includes/js/jquery-confirm.min.css" />
<script type="text/javascript" src="/app/includes/js/jquery-confirm.min.js"></script>
-->

<script src="/admin/includes/ckeditor/ckeditor.js" charset="utf-8"></script>


<script>CKEDITOR.dtd.$removeEmpty['span'] = false;</script>

</head>

<body class="section<?php echo $pageSection; ?>">
<div id="pageHolder">
	<div id="headerContainer">
		<div id="headerLogo">
			<img src="/app/images/bl-web-logo.png" />
		</div>

<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/navigation.php");
?>
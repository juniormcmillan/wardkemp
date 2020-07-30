<?php
#error_reporting(E_ERROR | E_WARNING | E_PARSE);
#header('Content-Type: text/html; charset=utf8');
header('Content-Type:text/html; charset=UTF-8');
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
//ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $websiteSettings["websiteName"]; if(isset($pageTitle)) echo " - ".$pageTitle; ?></title>




		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
		<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<!--<![endif]-->


<!--
style issue conflict cm 19/09/2019
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

		<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

		<link href='/admin/includes/css/multi-select.css' media='screen' rel='stylesheet' type='text/css'>

<!--
		<link rel="stylesheet" href="/app/includes/css/style2.css" />
-->



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


	<script src="/admin/includes/js/jquery.multi-select.js"></script>



		<script src="/admin/includes/ckeditor/ckeditor.js" charset="utf-8"></script>


		<script>CKEDITOR.dtd.$removeEmpty['span'] = false;</script>




	</head>

<body class="section<?php echo $pageSection; ?>">
<div id="pageHolder">
	<div id="">
		<div id="headerLogo">
			<img src="/app/images/bl-web-logo.png" />
		</div>

<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/navigation.php");
?>
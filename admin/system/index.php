<?php
if(!isset($pageTitle))
{
	$pageTitle = "System Menu";
}
if(!isset($pageSection))
{
	$pageSection = "4";
}
//phpinfo();
if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
{
	$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF']) ) );
}
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
?>
<div class="mainAdminPage">
	<h1><?php echo $pageTitle; ?></h1>
	<div class="mainAdminFull"> 
	<ul>
       <li><a href="./admin_users/" rel="nofollow">Users</a></li>
        <li><a href="/admin/" target="_self" rel="nofollow">Admin Home</a></li>
    </ul>
	</div>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
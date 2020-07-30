<?php
if(!isset($pageTitle))
{
	$pageTitle = "Admin Menu";
}
if(!isset($pageSection))
{
	$pageSection = "1";
}
//phpinfo();
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
?>
<div class="mainAdminPage">
	<h1><?php echo $pageTitle; ?></h1>
	<div class="mainAdminLeft"> 
	<ul>
		<li><a href="/admin/content/" target="_self">Content</a></li>
		<li><a href="/admin/ate_news/" target="_self">BLOG</a></li>
		<li><a href="/admin/fund/" target="_self">Change Fund Message in case of erroneous reporting</a></li>
		<li><a href="/admin/ate_policy_type/edit_cert/" target="_self">Policy Type Editor</a></li>
		<li><a href="/admin/ate_master_code/" target="_self">Master Code Editor</a></li>

		<li><a href="/admin/login.php?logout=1" onclick="return confirm('Are you sure?');">Logout</a></li>
    </ul>
	</div>
	<div class="mainPortfolioRight"> 
     <p>&nbsp;</p>
    </div>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
<?php
if(!isset($pageTitle))
{
	$pageTitle = "ATE Panel Access Menu";
}
if(!isset($pageSection))
{
	$pageSection = "5";
}
//phpinfo();
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
?>
<div class="mainAdminPage">
	<h1><?php echo $pageTitle; ?></h1>
	<div class="mainAdminLeft"> 
	<ul>
        <li><a href="/admin/ate_panel_access/users/">ATE Panel Users</a></li>
        <li><a href="/admin/ate_panel_access/solicitors/">Solicitors</a></li>
        <li><a href="/admin/ate_panel_access/leeward/">Leeward Panel Users</a></li>
        <li><a href="/admin/">Main Menu</a></li>
    </ul>
	</div>
	<div class="mainPortfolioRight"> 
     <p>&nbsp;</p>
    </div>
	</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
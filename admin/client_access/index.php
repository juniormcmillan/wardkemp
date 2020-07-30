<?
if(!isset($pageTitle))
{
	$pageTitle = "Client Access Menu";
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
        <li><a href="/admin/client_access/users/">Customers</a></li>
        <li><a href="/admin/client_access/referrers/">Referrers</a></li>
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
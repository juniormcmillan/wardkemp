<?
if(!isset($pageTitle))
{
	$pageTitle = "ATE Content Menu";
}
if(!isset($pageSection))
{
	$pageSection = "4";
}
//phpinfo();
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
?>
<div class="mainAdminPage">
	<h1><?php echo $pageTitle; ?></h1>
	<div class="mainAdminLeft"> 
	<ul>
        <li><a href="/admin/ate_content/documents/">Useful Downloads</a></li>
        <li><a href="/admin/ate_content/subjects/">ATE Training Subjects</a></li>
        <li><a href="/admin/ate_content/caselaw/">ATE Training Caselaw</a></li>
        <li><a href="/admin/">Main Menu</a></li>
    </ul>
	</div>
	<div class="mainPortfolioRight"> 
     <p>&nbsp;</p>
    </div>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
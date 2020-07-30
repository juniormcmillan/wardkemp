<?
if(!isset($pageTitle))
{
	$pageTitle = "Content Menu";
}
if(!isset($pageSection))
{
	$pageSection = "2";
}
//phpinfo();
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
?>
<div class="mainAdminPage">
	<h1><?php echo $pageTitle; ?></h1>
	<div class="mainAdminLeft"> 
	<ul>
		<li><a href="/admin/content/pages/">Pages</a></li>
		<li><a href="/admin/content/news/">News Stories</a></li>
		<li><a href="/admin/content/announcement">Announcement</a></li>
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
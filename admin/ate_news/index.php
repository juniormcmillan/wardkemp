<?
$pageTitle = "News Stories";
$pageSection = "2";
$subSection = "1";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

$gMysql				=	new Mysql_Library();


?>
<div class="mainAdminPage">
	<h1><? echo $pageTitle ?></h1>

	<div class="mainAdminLeft">
		<ul>
			<li><a href="/admin/ate_news/add_post.php">New BLOG Post</a></li>
			<li><a href="/admin/">Main Menu</a></li>
		</ul>
	</div>
	<div class="mainPortfolioRight">
		<p>&nbsp;</p>
	</div>


	<div class="container-fluid">
	<!--- main content -->
		<table id="pages" class="display table table-striped table-bordered " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th width="180">Date Last Updated</td>
				<th width=180>Title</td>
				<th width="180">Page link</td>
				<th width="180">Publish Date</td>
				<th>Author</td>
				<th align=center>Active?</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th width="180">Date Last Updated</td>
				<th width=180>Title</td>
				<th width="180">Page link</td>
				<th width="180">Publish Date</td>
				<th>Author</td>
				<th align=center>Active?</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
			</tr>
			</tfoot>
		</table>
	</div>

	<hr/>


	<div class="col-xs-12 text-center">
		<button type='button' class='btn-sm btn-default btn' onclick="window.location='../'">Back to Content Menu</button>
		<button type='button' class='btn-sm btn-success btn' onclick="window.location='add_post.php'">Add News Post</button>
	</div>


	</div>
	<div class="mainPortfolioRight"> 
     <p>&nbsp;</p>
    </div>
	</div>


<script>


	// this is the list of all the jobs
	var pages_table = $('#pages').DataTable(
		{
			"pageLength": 10,
			"processing": false,
			"serverSide": false,
			"autoWidth": false,
			"ajax": "doGetAllPosts.php",
			// sorts them by this default column
			"order": [0,"desc"],
			// removes sorting from these classes
			"columnDefs": [ {
				orderable: false,
				targets: 0,
				searchable: true,
				//  column is not visible
				visible: false,"targets": 8,

			}],

		} );
</script>

<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");


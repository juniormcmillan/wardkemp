<?
$pageTitle = "Voucher List";
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
	<div class="container-fluid">
	<!--- main content -->
		<table id="pages" class="display table table-striped table-bordered " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th width="180">Date Added</td>
				<th width=180>Code</td>
				<th >Value</td>
				<th>Type</td>
				<th align=center>Commission</td>
				<th align=center>Times Submitted</td>
				<th  width="180" align=center>Last Used</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th width="180">Date Added</td>
				<th width=180>Code</td>
				<th>Value</td>
				<th>Type</td>
				<th align=center>Commission</td>
				<th align=center>Times Submitted</td>
				<th  width="180" align=center>Last Used</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
			</tr>
			</tfoot>
		</table>
	</div>

	<hr/>


	<div class="col-xs-12 text-center">
		<button type='button' class='btn-sm btn-default btn' onclick="window.location='../'">Back to Content Menu</button>
		<button type='button' class='btn-sm btn-success btn' onclick="window.location='add_voucher.php'">Add new Voucher</button>
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
			"pageLength": 50,
			"processing": false,
			"serverSide": false,
			"autoWidth": false,
			"ajax": "doGetAllVouchers.php",
			// sorts them by this default column
			"order": [0,"desc"],
			// removes sorting from these classes
			"columnDefs": [ {
				orderable: false,
				targets: 0,
				searchable: true,
				//  column 10 is not visible
//				visible: false,"targets": 9,
			}],

		} );
</script>

<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");



?>


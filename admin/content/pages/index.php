<?
$pageTitle = "Page List";
$pageSection = "2";
$subSection = "1";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

$gMysql				=	new Mysql_Library();


$dbRsList = mysql_query("SELECT * FROM sm_page ORDER BY section_id,ref,updated desc");
$count = mysql_num_rows($dbRsList);


$dbRsList1 = mysql_query("SELECT DATABASE();");
$item = mysql_fetch_array($dbRsList1);

?>
<div class="mainAdminPage">
	<h1><? echo $pageTitle ?></h1>
	<div class="container-fluid">
	<p>Pages menu...</p>
	<!--- main content -->
		<table id="pages" class="display table table-striped table-bordered " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th width="180">Date Last Updated</td>
				<th width=180>Title</td>
				<th width="180">Page link</td>
				<th>Method</td>
				<th align=center>TAG</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th width="180">Date Last Updated</td>
				<th width=180>Title</td>
				<th>Page link</td>
				<th>Method</td>
				<th align=center>TAG</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
				<th >&nbsp;</td>
			</tr>
			</tfoot>
		</table>
	</div>

	<hr/>


	<div class="col-xs-12 text-center">
		<button type='button' class='btn-sm btn-default btn' onclick="window.location='../'">Back to Content Menu</button>
		<button type='button' class='btn-sm btn-success btn' onclick="window.location='/admin/content/pages/add_page.php?a=1'"  name="dd">Add new page</button>
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
			"ajax": "doGetAllPages.php",
			// sorts them by this default column
			"order": [0,"desc"],
			// removes sorting from these classes
			"columnDefs": [ {
				orderable: false,
				targets: 0,
				searchable: true,
				//  column 10 is not visible
				visible: false,"targets": 7,
			}],

		} );
</script>

<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");



/*

/*

$item_array	=	$gMysql->selectToArray("select * from sm_page where language='en' ORDER BY section_id,ref,updated desc",__FILE__,__LINE__);

foreach ($item_array as $item)
{
$id = $item["id"];
$datetime = $item["updated"];
$ref 	= $item["ref"];
$sectionId 	= $item["section_id"];
$order	 	= $item["prod_slots"];
$status 	= $item["status"];

# extract language... for flag
$language			= $item["language"];
# and we need the UK version to be a link (if there is an ID)
$data				=	$gMysql->queryRow("select * from sm_page where version_of='$id' ",__FILE__,__LINE__);
if	(!empty($data))
{
$version_of		 	= 	$data["id"];
$language_of		=	$data["language"];
$language_of_image	=	$gLanguage->getLanguageFlag($language_of);
$language_of_link	=	"<a href='detail.php?pageId=$version_of'>$language_of_image</a>";
}
else
{
$language_of_link	=	"";
}

$title = $item["title"];
echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
	<td>'.  date("l jS \of M Y h:i A", strtotime($datetime)).'</td>
	<td>'.urldecode($title).'</td>
	<td>'.urldecode($ref).'</td>
	<td align=center>'.$sectionId.'</td>
	<td align=center>'.$order.'</td>
	<td align=center>'.$status.'</td>

	<td><a href="detail.php?pageId='.$id.'"></a></td>
	<td>' . $language_of_link. '</td>


	<td><a href="detail.php?pageId='.$id.'">EDIT</a></td>
	<td><a href="detail.php?pageId='.$id.'&btnDelete=yes">DELETE</a></td>



</tr>';
}
echo '</table>';
}
else
{
echo '<p>There are no Pages to show</p>';
}
*/

?>


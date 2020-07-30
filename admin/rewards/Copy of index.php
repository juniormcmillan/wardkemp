<?
$pageTitle = "Products";
$pageSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$countMatches = mysql_query("SELECT COUNT(*) FROM product");
$count = mysql_result($countMatches,"");
$dbRsList = mysql_query("SELECT id, title, status, reference FROM product ORDER BY reference");	
?>
<div class="mainAdminFull">
	<h1><? echo $pageTitle ?></h1>
	<?
		if($count > 0)
		{
			echo '<table cellspacing="0" class="listTable">
					<tr>
						<th>Stock No</td>
						<th>Title</td>
						<th width="110">&nbsp;</td>
						<th width="60">&nbsp;</td>
						<th width="60">&nbsp;</td>
					</tr>';
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$title = $item["title"];
				$status = $item["status"];
				$reference = $item["reference"];
				if($status == "A")
				{
					$statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Deactivate\')">DEACTIVATE</a>';
				}
				else
				{
					$statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Activate\')">ACTIVATE</a>';
				}
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td><strong>'.urldecode($reference).'</strong></td>
						<td>'.urldecode($title).'</td>
						<td>'.$statusLink.'</td>
						<td><a href="#" rel="nofollow" onclick="do_action('.$id.')">EDIT</a></td>
						<td><a href="#" rel="nofollow" onclick="do_action('.$id.',\'Delete\')">DELETE</a></td>
					</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no Products to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php" rel="nofollow">New Product</a></li>
	</ul>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
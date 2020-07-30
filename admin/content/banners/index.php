<?
$pageTitle = "Banner List";
$pageSection = "2";
$subSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$strSQL = "SELECT id, description, status FROM sm_banner ORDER BY description";
$dbRsList = mysql_query($strSQL);
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminFull">
	<h1><? echo $pageTitle ?></h1>
	<?
		if($count > 0)
		{
			echo '<table cellspacing="0" class="listTable">
				<tr>
					<th>Description</td>
					<th width="110">&nbsp;</td>
					<th width="60">&nbsp;</td>
					<th width="60">&nbsp;</td>
				</tr>';
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$description = $item["description"];
				$status = $item["status"];

				if($status == "A")
				{
					$statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Deactivate\')">DEACTIVATE</a>';
				}
				else
				{
					$statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Activate\')">ACTIVATE</a>';
				}
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td>'.$description.'</td>
						<td>'.$statusLink.'</td>
						<td><a href="#" rel="nofollow" onclick="do_action('.$id.')">EDIT</a></td>
						<td><a href="#" rel="nofollow" onclick="do_action('.$id.',\'Delete\')">DELETE</a></td>
					</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no Banners to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php" rel="nofollow">New Banner</a></li>
        <li><a href="../" rel="nofollow">Back to Content Menu</a></li>
	</ul>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
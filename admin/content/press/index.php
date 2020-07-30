<?
$pageTitle = "Press Menu";
$pageSection = "2";
$subSection = "4";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$dbRsList = mysql_query("SELECT id, headline, display_date, status FROM press ORDER BY display_date DESC");
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminPage">
<div class="mainAdminFull">
	<h1><? echo $pageTitle ?></h1>
	<?
		if($count > 0)
		{
			echo '<table cellspacing="0" class="listTable">
					<tr>
						<td><strong>Title</strong></td>
						<td width="110"><strong>Date</strong></td>
						<td width="110">&nbsp;</td>
						<td width="60">&nbsp;</td>
						<td width="60">&nbsp;</td>
					</tr>';
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$headline = $item["headline"];
				$display_date = date("d/m/Y",strtotime($item["display_date"]));
				$status = $item["status"];
				if($status == "A")
				{
					$statusLink = '<a href="detail.php?newsId='.$id.'&dbAction=Deactivate">DEACTIVATE</a>';
				}
				else
				{
					$statusLink = '<a href="detail.php?newsId='.$id.'&dbAction=Activate">ACTIVATE</a>';
				}
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td><strong>'.urldecode($headline).'</strong></td>
						<td>'.$display_date.'</td>
						<td>'.$statusLink.'</td>
						<td><a href="detail.php?newsId='.$id.'">EDIT</a></td>
						<td><a href="detail.php?newsId='.$id.'&dbAction=Delete" onclick="return confirm(\'Are you sure?\')">DELETE</a></td>
					</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no Press Releases to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php">New Press Release</a></li>
	</ul>
	</div>
</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
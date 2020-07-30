<?
$pageTitle = "Key People Menu";
$pageSection = "2";
$subSection = "5";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$dbRsList = mysql_query("SELECT id, name, status FROM person ORDER BY sort_order, name");
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
						<td><strong>Name</strong></td>
						<td width="110">&nbsp;</td>
						<td width="60">&nbsp;</td>
						<td width="60">&nbsp;</td>
					</tr>';
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$name = $item["name"];
				$status = $item["status"];
				if($status == "A")
				{
					$statusLink = '<a href="detail.php?iId='.$id.'&dbAction=Deactivate">DEACTIVATE</a>';
				}
				else
				{
					$statusLink = '<a href="detail.php?iId='.$id.'&dbAction=Activate">ACTIVATE</a>';
				}
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td><strong>'.urldecode($name).'</strong></td>
						<td>'.$statusLink.'</td>
						<td><a href="detail.php?iId='.$id.'">EDIT</a></td>
						<td><a href="detail.php?iId='.$id.'&dbAction=Delete" onclick="return confirm(\'Are you sure?\')">DELETE</a></td>
					</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no Key People to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php">New Key Person</a></li>
	</ul>
	</div>
</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
<?php
$pageTitle = "ATE Training Subjects Menu";
$pageSection = "4";
$subSection = "1";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$dbRsList = mysql_query("SELECT id, headline, status FROM ate_subject ORDER BY sort_order, headline");
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminPage">
<div class="mainAdminFull">
	<h1><?php echo $pageTitle ?></h1>
	<?php
		if($count > 0)
		{
			echo '<table cellspacing="0" class="listTable">
					<tr>
						<td><strong>Title</strong></td>
						<td width="110">&nbsp;</td>
						<td width="60">&nbsp;</td>
						<td width="60">&nbsp;</td>
					</tr>';
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$headline = $item["headline"];
				$status = $item["status"];
				if($status == "A")
				{
					$statusLink = '<a href="detail.php?sId='.$id.'&dbAction=Deactivate">DEACTIVATE</a>';
				}
				else
				{
					$statusLink = '<a href="detail.php?sId='.$id.'&dbAction=Activate">ACTIVATE</a>';
				}
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td><strong>'.urldecode($headline).'</strong></td>
						<td width="110">'.$statusLink.'</td>
						<td width="60"><a href="detail.php?sId='.$id.'">EDIT</a></td>
						<td width="60"><a href="detail.php?sId='.$id.'&dbAction=Delete" onclick="return confirm(\'Are you sure?\')">DELETE</a></td>
					</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no Subjects to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php">New Subject</a></li>
	</ul>
	</div>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
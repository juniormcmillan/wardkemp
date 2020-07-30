<?php
$pageTitle = "Useful Downloads Menu";
$pageSection = "4";
$subSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$dbRsList = mysql_query("SELECT id, headline, status, category, ate, referrer, owncosts FROM ate_document ORDER BY category, sort_order, headline ");
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminPage">
<div class="mainAdminFull">
	<h1><?php echo $pageTitle ?></h1>
	<?php
		if($count > 0)
		{
			$cat = "";
			
			while($item = mysql_fetch_array($dbRsList))
			{
				if($item["category"] != $cat)
				{
					if($cat != "")
					{
						echo '</table>';
					}
					echo '<h3>'.$item["category"].'</h3>
					<table cellspacing="0" class="listTable">';
				}
				$id = $item["id"];
				$headline = $item["headline"];
				$status = $item["status"];
				
				if($item["ate"] == "Y" && $item["referrer"] == "Y" && $item["owncosts"] == "Y")
				{
					$tmpArea = "ALL";
				}
				elseif($item["ate"] == "Y" && $item["referrer"] == "N" && $item["owncosts"] == "N")
				{
					$tmpArea = "ATE";
				}
				elseif($item["ate"] == "N" && $item["referrer"] == "Y" && $item["owncosts"] == "N")
				{
					$tmpArea = "Referrer";
				}
				elseif($item["ate"] == "N" && $item["referrer"] == "N" && $item["owncosts"] == "Y")
				{
					$tmpArea = "Owncosts";
				}
				else
				{
					$tmpArea = "None";
				}
				
				if($status == "A")
				{
					$statusLink = '<a href="detail.php?dId='.$id.'&dbAction=Deactivate">DEACTIVATE</a>';
				}
				else
				{
					$statusLink = '<a href="detail.php?dId='.$id.'&dbAction=Activate">ACTIVATE</a>';
				}
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td><strong>'.urldecode($headline).'</strong></td>
						<td width="110">'.$tmpArea.'</td>
						<td width="110">'.$statusLink.'</td>
						<td width="60"><a href="detail.php?dId='.$id.'">EDIT</a></td>
						<td width="60"><a href="detail.php?dId='.$id.'&dbAction=Delete" onclick="return confirm(\'Are you sure?\')">DELETE</a></td>
					</tr>';
				$item["category"] = $cat;
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no Useful Downloads to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php">New Useful Download</a></li>
	</ul>
	</div>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
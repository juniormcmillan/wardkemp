<?
$pageTitle = "Subject Area Features";
$pageSection = "Subjects";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
if(isset($_GET["pId"]))
{
	$tmpId = intval($_GET["pId"]);
}

$countMatches = mysql_query("SELECT COUNT(*) FROM subject_feature WHERE subject_id = ".$tmpId);
$count = mysql_result($countMatches,"");
$dbRsItem = mysql_query("SELECT description FROM subject WHERE id = ".$tmpId);
$dbRsList = mysql_query("SELECT id, description FROM subject_feature WHERE subject_id = ".$tmpId." ORDER BY sort_order");
?>
<div class="mainAdminPage">
	<h1><? echo $pageTitle ?></h1>
	<div class="mainAdminFull"> 
	<?
		if($count > 0)
		{
			echo '<table cellspacing="0">
					<tr>
						<th width="300">Title</td>
						<th width="60">&nbsp;</td>
						<th width="60">&nbsp;</td>
					</tr>';
			$bgColour = "#eeeeee";
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$description = $item["description"];
				echo '<tr bgcolor="'.$bgColour.'">
						<td><strong>'.$description.'</strong></td>
						<td><a href="#" onclick="window.location=\'feature_detail.php?iId='.$id.'&pId='.$tmpId.'\';">EDIT</a></td>
						<td><a href="#" onclick="if(confirm(\'Are you sure?\')){window.location=\'feature_detail.php?iId='.$id.'&pId='.$tmpId.'&dbAction=Delete\';}">DELETE</a></td>
					</tr>';
					if($bgColour == "#ffffff")
				{
					$bgColour = "#eeeeee";
				}
				else
				{
					$bgColour = "#ffffff";
				}
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no features to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="feature_detail.php?pId=<?php echo $tmpId; ?>" rel="nofollow">New Feature</a></li>
		<li><a href="./" rel="nofollow">Back to Subject Area List</a></li>
	</ul>
	</div>
	<div class="mainPortfolioRight"> 
     <p>&nbsp;</p>
    </div>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
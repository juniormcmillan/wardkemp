<?
$pageTitle = "Rewards";
$pageSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
if($_POST["keywords"] != "")
{
	$dbRsList = mysql_query("SELECT id, title, status, reference FROM product WHERE reference LIKE '%".$_POST["keywords"]."%' OR title LIKE '%".$_POST["keywords"]."%' ORDER BY reference");
}
else
{
	$dbRsList = mysql_query("SELECT id, title, status, reference FROM product ORDER BY reference");
}
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminFull">
	<h1><? echo $pageTitle ?></h1>
    <form name="frmSearch" id="frmLogin" method="post" action="./" class="formData" />
    <fieldset>
	 <p><label for="uname">Search For:</label><input type="text" name="keywords" id="keywords" class="formInputData" />&nbsp;<input type="submit" name="Search" id="Search" value="Search" class="formButton" />&nbsp;<input type="button" name="ShowAll" id="ShowAll" value="Show All" class="formButton" onclick="window.location='./';" /></p>
    </fieldset>
	</form>
    <p>&nbsp;</p>
	<?
		if($count > 0)
		{
			echo '<table cellspacing="0" class="listTable">
					<tr>
						<th>Reference</td>
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
			echo '<p>There are no rewards to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php" rel="nofollow">New Reward</a></li>
	</ul>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
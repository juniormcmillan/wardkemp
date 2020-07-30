<?
$pageTitle = "Solicitors";
$pageSection = "5";
$subSection = "2";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$dbRsList = mysql_query("SELECT * FROM sm_solicitor ORDER BY name");
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
						<td><strong>Claim Fund Limit</strong</td>
						<td width="110">&nbsp;</td>
						<td width="60">&nbsp;</td>
						<td width="60">&nbsp;</td>
					</tr>';
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$name = $item["name"];
				$status = $item["status"];
				$tmpCode = $item["code"];

				$strSQL 		=	"SELECT claims_fund,rpt_date  FROM rpt_summary WHERE solicitor_code = '$tmpCode' order by rpt_date desc";
				$dbRsRpt 		=	mysql_query($strSQL);
				$data			=	mysql_fetch_array($dbRsRpt);
				if	(!empty($data))
				{
					$claims_fund	=	$data['claims_fund'];
				}
				else
				{
					$claims_fund	=	0;
				}
				$claims_fund	=	number_format($claims_fund,2);


				if($status == "A")
				{
					$statusLink = '<a href="detail.php?iId='.$id.'&dbAction=Deactivate">DEACTIVATE</a>';
				}
				else
				{
					$statusLink = '<a href="detail.php?iId='.$id.'&dbAction=Activate">ACTIVATE</a>';
				}
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
						<td ><strong><a href="detail.php?iId='.$id.'">'.urldecode($name).'</a></strong></td>
						<td>'.$claims_fund.'</td>
						<td>'.$statusLink.'</td>
						<td><a href="detail.php?iId='.$id.'">EDIT</a></td>
						<td><a href="detail.php?iId='.$id.'&dbAction=Delete" onclick="return confirm(\'Are you sure?\')">DELETE</a></td>
					</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no Solicitors to show</p>';
		}
	?>
	<hr/>
	<ul>
		<li><a href="detail.php">New Solicitor</a></li>
	</ul>
	</div>
</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
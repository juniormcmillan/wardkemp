<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

$pageTitle = "Members";
$pageSection = "4";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

if(!isset($_SESSION["admin_listings"]))
{
	$listings = array(	"type"		=>	"N",
						"criteria"	=>	"");
	$_SESSION["admin_listings"] = $listings;
}
$listings = $_SESSION["admin_listings"];

if(isset($_GET["lType"]))
{
	$listings["type"] = $_GET["lType"];
}
$_SESSION["admin_listings"] = $listings;

//var_dump($listings);
if($listings["type"] == "ALL")
{
	$strSQL = "SELECT * FROM member ORDER BY company_name, last_name, first_name, email";
	$pageTitle = "All Members";
}
elseif($listings["type"] == "N")
{
	$strSQL = "SELECT * FROM member WHERE status = 'N' ORDER BY company_name, last_name, first_name, email";
	$pageTitle = "New Members";
}
elseif($listings["type"] == "P")
{
	$strSQL = "SELECT * FROM member WHERE status = 'P' ORDER BY company_name, last_name, first_name, email";
	$pageTitle = "Incomplete Members";
}
elseif($listings["type"] == "U")
{
	$strSQL = "SELECT * FROM member WHERE status = 'U' ORDER BY company_name, last_name, first_name, email";
	$pageTitle = "Updated Members";
}
elseif($listings["type"] == "V")
{
	$strSQL = "SELECT DISTINCT * FROM member WHERE virtual = 'Y' ORDER BY company_name, last_name, first_name, email";
	$pageTitle = "Virtual Members";
}
else
{
	$strSQL = "SELECT * FROM member WHERE status = '".$listings["type"]."' ORDER BY company_name, last_name, first_name, email";
	if($listings["type"] == "A")
	{
		$pageTitle = "Active Members";
	}
	elseif($listings["type"] == "D")
	{
		$pageTitle = "Deactivated Members";
	}
}
//echo $strSQL;exit();
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
						<th>Company Name</td>
						<th>Member Email</td>
						<th>Virtual</td>
						<th>Status</td>
						<th>Created</td>';
						echo '<th width="110">&nbsp;</td>';
						echo '<th width="60">&nbsp;</td>';
						echo '<th width="60">&nbsp;</td>';
					echo '</tr>';
			$bgColour = "#eeeeee";
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$name = $item["company_name"];
				$first_name = $item["first_name"];
				$last_name = $item["last_name"];
				$email = $item["email"];
				//$created = date_create($item["created"]);
				$created = date("d/m/Y",strtotime($item["created"]));
				$status = $item["status"];
				
				if($status == "N")
				{
					$statusLink = '&nbsp;';
					$editLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.')">VIEW</a>';
					$statusDesc = "New";
				}
				elseif($status == "P")
				{
					$statusLink = '&nbsp;';
					$editLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.')">VIEW</a>';
					$statusDesc = "Incomplete";
				}
				elseif($status == "U")
				{
					$statusLink = '&nbsp;';
					$editLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.')">VIEW</a>';
					$statusDesc = "Updated";
				}
				elseif($status == "A")
				{
					$statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Deactivate\')">DEACTIVATE</a>';
					$editLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.')">VIEW</a>';
					$statusDesc = "Active";
				}
				else
				{
					$statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Activate\')">ACTIVATE</a>';
					$editLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.')">VIEW</a>';
					$statusDesc = "Deactivated";
				}
				$deleteLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Delete\')">DELETE</a>';
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
				<td><strong>'.urldecode($name).'</strong></td>
				<td>'.urldecode($email).'</td>
				<td>'.$item["virtual"].'</td>
				<td>'.$statusDesc.'</td>
				<td>'.$created.'</td>';
					echo '<td>'.$statusLink.'</td>';
					echo '<td>'.$editLink.'</td>';
					echo '<td>'.$deleteLink.'</td>';
					echo '</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no listings to show</p>';
		}
	?>
	<hr/>
	<!--<ul>
		<li><a href="./detail.php" rel="nofollow">New Pricing Option</a></li>
	</ul>-->
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
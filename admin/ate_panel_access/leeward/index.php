<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

error_reporting(E_ALL ^ E_DEPRECATED);

$pageTitle = "ATE Panel Users";
$pageSection = "5";
$subSection = "1";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

if(!isset($_SESSION["admin_listings"]))
{
	$listings = array(	"type"		=>	"N",
						"criteria"	=>	"");
	$_SESSION["admin_listings"] = $listings;
}
$listings = $_SESSION["admin_listings"];

if(isset($_POST["criteria"]))
{
	$listings["type"] = "ALL";
	$listings["criteria"] = strtolower($_POST["criteria"]);
}
if(isset($_GET["lType"]))
{
	$listings["type"] = $_GET["lType"];
	$listings["criteria"] = "";
}
$_SESSION["admin_listings"] = $listings;

//var_dump($listings);
if($listings["type"] == "N")
{
	$strSQL = "SELECT * FROM leeward_access WHERE active = 0 ORDER BY date_created, surname, forename";
	$pageTitle = "New Users";
}
elseif($listings["type"] == "A")
{
	$strSQL = "SELECT * FROM leeward_access WHERE active = 1 ORDER BY date_created, surname, forename";
	$pageTitle = "Active Users";
}
elseif($listings["type"] == "P")
{
	$strSQL = "SELECT * FROM leeward_access WHERE partner = 'Y' ORDER BY surname, forename";
	$pageTitle = "Partners";
}
elseif($listings["criteria"] != "")
{
	$strSQL = "SELECT * FROM leeward_access WHERE (LOWER(forename) = '".$listings["criteria"]."' OR LOWER(surname) = '".$listings["criteria"]."' OR LOWER(company_name) = '".$listings["criteria"]."') ORDER BY surname, forename";

	$criteria	=	$listings["criteria"];

	$strSQL = "SELECT * FROM leeward_access WHERE (forename like '%$criteria%' OR surname like '%$criteria%'  OR company_name like '%$criteria%' ) ORDER BY surname, forename";

	$pageTitle = "Search Results";
}
else
{
	$strSQL = "SELECT * FROM leeward_access ORDER BY surname, forename";
	$pageTitle = "All Users";
}
//echo $strSQL;exit();
$dbRsList = mysql_query($strSQL);
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminFull">
	<h1><?php echo $pageTitle ?></h1>
    <form name="frmSearch" id="frmLogin" method="post" action="./" class="formData" />
    	<fieldset>
	 <p><label for="criteria">Search for:</label><input type="text" name="criteria" id="criteria" class="formInputData" /></p>
	 <p><label>&nbsp;</label><input type="submit" value="Search" class="formButton" /></p>
     </fieldset>
	</form>
    <p>&nbsp;</p>
	<?php
		if($count > 0)
		{
			echo '<table cellspacing="0" class="listTable">
					<tr>
						<th>Name</td>
						<th>Company</td>
						<th>Member Email</td>
						<th>ID</td>
						<th>Created</td>
						<th>Login Count</td>';
						echo '<th width="60">&nbsp;</td>';
						echo '<th width="60">&nbsp;</td>';
					echo '</tr>';
			$bgColour = "#eeeeee";
			while($item = mysql_fetch_array($dbRsList))
			{
				$id = $item["id"];
				$name = $item["forename"].' '.$item["surname"];
				$company = $item["company_name"];
				$email = $item["email"];
				$created = $item["date_created"];
				$loginCount = $item["login_count"];
				//$statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Activate\')">ACTIVATE</a>';
				//$statusDesc = "Deactivated";
				$editLink = '<a href="#" rel="nofollow" onclick="do_action(\''.$id.'\')">VIEW</a>';
				$deleteLink = '<a href="#" rel="nofollow" onclick="do_action(\''.$id.'\',\'Delete\')">DELETE</a>';
				echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
				<td><strong>'.$name.'</strong></td>
				<td>'.$company.'</td>
				<td>'.$email.'</td>
				<td>'.$id.'</td>
				<td>'.date("d/m/Y",strtotime($created)).'</td>
				<td>'.$loginCount.'</td>
				<td>'.$editLink.'</td>
				<td>'.$deleteLink.'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo '<p>There are no listings to show</p>';
		}
	?>
	</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
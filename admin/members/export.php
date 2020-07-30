<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

$pageTitle = "Export Member Data";
$pageSection = "4";
$checkLogin = false;
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

$strSQL = "SELECT * FROM member ORDER BY company_name, last_name, first_name, email";

//echo $strSQL;exit();
$dbRsList = mysql_query($strSQL);
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminFull">
	<h1><? echo $pageTitle ?></h1>
	<?
		
		$filename = 'members_'.date("Ymd").".csv";
		$filePath = $_SERVER["DOCUMENT_ROOT"]."/files/members/".$filename;
		if(file_exists($filePath))
		{
			try
			{
				chmod($filePath,0777);
				unlink($filePath);
			}
			catch(Exception $e)
			{
				$fileWarnings .= $e->getMessage();
			}
		}
		
		$fileContent = "Email,First Name,Last Name,Password,Company Name,Company Phone,Mobile Phone,Position,Gender,Age Group";
		while($item = mysql_fetch_array($dbRsList))
		{
			$fileRow = $item["email"] . "," . urldecode($item["first_name"]) . "," . urldecode($item["last_name"]) . "," . $item["password"] . "," . urldecode($item["company_name"]) . "," . urldecode($item["company_phone"]) . "," . urldecode($item["mobile_phone"]) . "," . $item["position"] . "," . $item["gender"] . "," . $item["age_group"] ."\n";
			$fileContent .= $fileRow;
		}
		//exit($fileContent);
		
		
		$file=fopen($filePath,"a");
		//fputcsv($file, $fields, ",", '"');
		fwrite($file,$fileContent);
		fclose($file);
	?>
    <p>Member data exported.</p>
    <p><a href="/files/members/<?php echo $filename; ?>" target="_blank">Click here to download</a></p>
	<hr/>
	<ul>
		<li><a href="./" rel="nofollow">Back to Members list</a></li>
	</ul>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
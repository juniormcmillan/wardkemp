<?
$pageTitle = "Subject Area Feature Detail";
$pageSection = "Subjects";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpTitle = '';
$tmpSortOrder = 99;

if(isset($_POST["dbAction"]))
{
	$dbAction = $_POST["dbAction"];
}
elseif(isset($_GET["dbAction"]))
{
	$dbAction = $_GET["dbAction"];
}
else
{
	$dbAction = '';
}

if(isset($_POST["iId"]))
{
	$tmpId = intval($_POST["iId"]);
}
elseif(isset($_GET["iId"]))
{
	$tmpId = intval($_GET["iId"]);
}
else
{
	$tmpId = 0;
}

if(isset($_POST["pId"]))
{
	$pId = intval($_POST["pId"]);
}
elseif(isset($_GET["pId"]))
{
	$pId = intval($_GET["pId"]);
}
else
{
	$pId = 0;
}

if($pId == 0)
{
	ob_end_clean();
	header("Location: ./");
	exit();
}

if($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM subject_feature WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./features.php?iId=".$iId);
	exit();
}

if(isset($_POST["txtDescription"]) && isset($_POST["txtSortOrder"]))
{
	$tmpDescription = html_entity_decode($_POST["txtDescription"],ENT_QUOTES);
	$tmpSortOrder = intval($_POST["txtSortOrder"]);

	if($formErrors == "")
	{
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO subject_feature (subject_id,description,sort_order,created,created_by,updated,updated_by,status) VALUES ($pId,'$tmpDescription',$tmpSortOrder,'2008-01-01 12:00:00',-1,'2008-01-01 12:00:00',-1,'A')";
			mysql_query($strSQL);
		}
		else
		{
			mysql_query("UPDATE subject_feature SET description = '$tmpDescription', sort_order = $tmpSortOrder, updated = '2008-01-01 12:00:00', updated_by = -1 WHERE id = $tmpId");
		}
		ob_end_clean();
		header("Location: ./features.php?pId=".$pId);
		exit();
	}
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$dbRsItem = mysql_query("SELECT description, sort_order FROM subject_feature WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpDescription = html_entity_decode($item["description"],ENT_QUOTES);
	$tmpSortOrder = intval($item["sort_order"]);
}

$pageTitle = "Feature Detail";
if($tmpId > 0)
{
	$msgAction = "edit feature";
}
else
{
	$msgAction = "add feature";
}

?>
<div class="mainAdminPage">
	<h1><? echo $pageTitle ?></h1>
	<?
		if($formErrors != "")
		{
			echo "<p><strong>ERROR:</strong></p><ul>$formErrors</ul>";
		}
	?>
	<div class="mainAdminLeft"> 
	<p>Use this form to <? echo $msgAction; ?></p>
	<form name="frmCS" id="frmCS" method="post" enctype="multipart/form-data" class="formData" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpId; ?>" />
		<input type="hidden" name="pId" id="pId" value="<? echo $pId; ?>" />
		<fieldset>
			<legend>Feature Details</legend>
			<p>
				<label for="txtDescription">Description</label>
				<input type="text" name="txtDescription" id="txtDescription" maxlength="100" value="<? echo $tmpDescription; ?>" class="formInputData" />
			</p>
			<p>
				<label for="txtSortOrder">Sort Order</label>
				<input type="text" name="txtSortOrder" id="txtSortOrder" maxlength="2" value="<? echo $tmpSortOrder; ?>" class="formInputDataSmall" />
			</p>
		</fieldset>
		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./features.php?pId=<?php echo $pId; ?>';" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" />
		</p>
	</form>
	</div>
	<div class="mainPortfolioRight"> 
     <p>&nbsp;</p>
    </div>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
<?php
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "4";
$subSection = "1";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpHeadline = '';
$tmpSummary = '';
$tmpContent = '';
$tmpSortOrder = 99;
//$tmpDate = date_create();
//$tmpDisplayDate = date_format($tmpDate,"Y-m-d");

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
	$dbAction = 'Insert';
}

if(isset($_POST["sId"]))
{
	$tmpId = intval($_POST["sId"]);
}
elseif(isset($_GET["sId"]))
{
	$tmpId = intval($_GET["sId"]);
}

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE ate_subject SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE ate_subject SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM ate_subject WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtHeadline"]) && isset($_POST["txtContent"]))
{
	$tmpHeadline = htmlentities($_POST["txtHeadline"],ENT_QUOTES);
	$tmpSummary = htmlentities($_POST["txtSummary"],ENT_QUOTES);
	$tmpContent = htmlentities($_POST["txtContent"],ENT_QUOTES);
	$tmpSortOrder = intval($_POST["txtSortOrder"]);
	
	if($tmpHeadline == "")
	{
		$formErrors .= "<li>Please enter a title</li>";
	}
	if($tmpSummary == "")
	{
		$formErrors .= "<li>Please enter a summary</li>";
	}
	if($tmpContent == "")
	{
		$formErrors .= "<li>Please enter some content</li>";
	}
	/*if(!checkdate($tmpMonth,$tmpDay,$tmpYear))
	{
		$formErrors .= "<li>Please enter a valid date</li>";
	}*/

	if($formErrors == "")
	{
		//$tmpDisplayDate = str_pad($tmpYear,4,'0',STR_PAD_LEFT)."-".str_pad($tmpMonth,2,'0',STR_PAD_LEFT)."-".str_pad($tmpDay,2,'0',STR_PAD_LEFT)." 00:00:00";
		/*echo $tmpDisplayDate;
		exit();*/
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO ate_subject (headline,summary,content,created,created_by,updated,updated_by,status,sort_order) VALUES ('$tmpHeadline', '$tmpSummary', '$tmpContent',Now(),$user->userID,Now(),$user->userID,'A',$tmpSortOrder)";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE ate_subject SET headline = '$tmpHeadline', content = '$tmpContent', summary = '$tmpSummary', updated = Now(), updated_by = $user->userID, sort_order = $tmpSortOrder WHERE id = $tmpId";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		ob_end_clean();
		header("Location: ./");
		exit();
	}
	else
	{
		$tmpDbAction = $dbAction;
	}
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$dbRsItem = mysql_query("SELECT headline, summary, content, sort_order FROM ate_subject WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpHeadline = $item["headline"];
	$tmpSummary = $item["summary"];
	$tmpContent = $item["content"];
	$tmpSortOrder = $item["sort_order"];
}
else
{
	$tmpDbAction = $dbAction;
}

$pageTitle = "ATE Training Subject";
if($tmpId > 0)
{
	$msgAction = "edit a subject";
}
else
{
	$msgAction = "add a subject";
}
?>
<div class="mainAdminPage">
	<h1><?php echo $pageTitle ?></h1>
	<?php
		if($formErrors != "")
		{
			echo "<p><strong>ERROR:</strong></p><ul>$formErrors</ul>";
		}
	?>
	<div class="mainAdminFull"> 
	<p>Use this form to <?php echo $msgAction; ?></p>
	<form name="frmService" id="frmService" method="post" enctype="multipart/form-data" class="formEditor" />
		<input type="hidden" name="dbAction" id="dbAction" value="<?php echo $tmpDbAction; ?>" />
		<input type="hidden" name="sId" id="sId" value="<?php echo $tmpId; ?>" />
		<fieldset>
			<legend>ATE Training Subject Details</legend>
			<p>
				<label for="txtHeadline">Title</label>
				<input type="text" name="txtHeadline" id="txtHeadline" maxlength="255" value="<?php echo html_entity_decode($tmpHeadline,ENT_QUOTES); ?>" class="formInputData" />
			</p>
			<p>
				<label for="txtSummary">Summary</label>
				<textarea name="txtSummary" id="txtSummary" rows="5" class="formInputData"><?php echo html_entity_decode($tmpSummary,ENT_QUOTES); ?></textarea>
			</p>
            <p>
				<label for="txtContent">Content</label></p>
				<p><textarea name="txtContent" id="txtContent" rows="10" class="formInputWysiwyg"><?php echo html_entity_decode($tmpContent,ENT_QUOTES); ?></textarea>
			</p>
            <p>
				<label for="txtSortOrder">Sort Order</label>
				<input type="text" name="txtSortOrder" id="txtSortOrder" maxlength="99" value="<?php echo $tmpSortOrder; ?>" class="formInputDataSmall" />
			</p>
		</fieldset>
		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
		</p>
	</form>
	</div>
	<div class="mainPortfolioRight"> 
     <p>&nbsp;</p>
    </div>
	</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
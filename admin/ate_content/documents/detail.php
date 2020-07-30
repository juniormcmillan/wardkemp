<?php
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "4";
$subSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpHeadline = '';
$tmpCategory = '';
$tmpFilename = '';
$tmpSortOrder = 99;
$tmpATE = "N";
$tmpReferrer = "N";
$tmpOwnCosts = "N";

//$tmpDate = date_create();
//$tmpDisplayDate = date_format($tmpDate,"Y-m-d");
$filePath = $_SERVER["DOCUMENT_ROOT"]."/uploads/ate_documents/";

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

if(isset($_POST["dId"]))
{
	$tmpId = intval($_POST["dId"]);
}
elseif(isset($_GET["dId"]))
{
	$tmpId = intval($_GET["dId"]);
}

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE ate_document SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE ate_document SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM ate_document WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtHeadline"]) && isset($_POST["selCategory"]))
{
	$tmpHeadline = htmlentities($_POST["txtHeadline"],ENT_QUOTES);
	$tmpCategory = $_POST["selCategory"];
	$tmpSortOrder = intval($_POST["txtSortOrder"]);
	$tmpFilename = $_POST["hfOldFilename"];
	
	if(isset($_POST["cbxATE"]))
	{
		$tmpATE = "Y";
	}
	else
	{
		$tmpATE = "N";
	}
	
	if(isset($_POST["cbxReferrer"]))
	{
		$tmpReferrer = "Y";
	}
	else
	{
		$tmpReferrer = "N";
	}

	if(isset($_POST["cbxOwnCosts"]))
	{
		$tmpOwnCosts = "Y";
	}
	else
	{
		$tmpOwnCosts = "N";
	}
	//var_dump($_FILES);
	//exit();
	
	if(isset($_POST["cbxDeleteFile"]))
	{
		if(file_exists($filePath . $tmpFilename))
		{
			chmod($filePath.$tmpFilename,0777);
			unlink($filePath.$tmpFilename);
		}
		$tmpFilename = '';
	}
	
	$fnFile = "flFilename";
	//echo "ERROR:".$_FILES[$fnFile]["error"];
	//exit();
	//var_dump($_FILES);exit();
	if ($_FILES[$fnFile]["name"] != "")
	{




		//var_dump($_FILES[$fnFile]);exit();
		if (($_FILES[$fnFile]["type"] == "application/msword" || $_FILES[$fnFile]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $_FILES[$fnFile]["type"] == "application/vnd.ms-excel" || $_FILES[$fnFile]["type"] == "application/vnd.ms-powerpoint" || $_FILES[$fnFile]["type"] == "application/pdf" || $_FILES[$fnFile]["type"] == "application/octet-stream") && $_FILES[$fnFile]["error"] == 0)
		{
			$tmpFilename = $_FILES[$fnFile]["name"];
			if (file_exists($filePath . $_FILES[$fnFile]["name"]))
			{
				# added by CM 04/04/14 so two files don't overwrite each other
				$formErrors .= "<li>File already exists. Make sure you are not trying to overwrite an OWN COSTS or ATE document by mistake. Otherwise, please delete file first</li>";
#				chmod($filePath . $_FILES[$fnFile]["name"],0777);
			}
			move_uploaded_file($_FILES[$fnFile]["tmp_name"],$filePath.$_FILES[$fnFile]["name"]);
			chmod($filePath . $_FILES[$fnFile]["name"],0777);
		}
		else
		{
			$formErrors .= "<li>There was an error uploading the file</li>";
		}
	}
	
	if($tmpHeadline == "")
	{
		$formErrors .= "<li>Please enter a title</li>";
	}

	if($formErrors == "")
	{
		//$tmpDisplayDate = str_pad($tmpYear,4,'0',STR_PAD_LEFT)."-".str_pad($tmpMonth,2,'0',STR_PAD_LEFT)."-".str_pad($tmpDay,2,'0',STR_PAD_LEFT)." 00:00:00";
		/*echo $tmpDisplayDate;
		exit();*/
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO ate_document (headline,category,filename,created,created_by,updated,updated_by,status,sort_order,ate,referrer,owncosts) VALUES ('$tmpHeadline', '$tmpCategory', '$tmpFilename',Now(),$user->userID,Now(),$user->userID,'A',$tmpSortOrder,'$tmpATE','$tmpReferrer','$tmpOwnCosts')";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE ate_document SET headline = '$tmpHeadline', category = '$tmpCategory', filename = '$tmpFilename', updated = Now(), updated_by = $user->userID, sort_order = $tmpSortOrder, ate = '$tmpATE', referrer = '$tmpReferrer' , owncosts = '$tmpOwnCosts' WHERE id = $tmpId";
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
	$dbRsItem = mysql_query("SELECT * FROM ate_document WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpHeadline = $item["headline"];
	$tmpCategory = $item["category"];
	$tmpFilename = $item["filename"];
	$tmpSortOrder = $item["sort_order"];
	$tmpATE = $item["ate"];
	$tmpReferrer = $item["referrer"];
	$tmpOwnCosts = $item["owncosts"];
}
else
{
	$tmpDbAction = $dbAction;
}

$pageTitle = "Useful Download";
if($tmpId > 0)
{
	$msgAction = "edit a useful download";
}
else
{
	$msgAction = "add a useful download";
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
	<form name="frmDocument" id="frmDocument" method="post" enctype="multipart/form-data" class="formEditor" />
		<input type="hidden" name="dbAction" id="dbAction" value="<?php echo $tmpDbAction; ?>" />
		<input type="hidden" name="dId" id="dId" value="<?php echo $tmpId; ?>" />
        <input type="hidden" name="hfOldFilename" id="hfOldFilename" value="<?php echo $tmpFilename; ?>" />
		<fieldset>
			<legend>Useful Document Details</legend>
			<p>
				<label for="txtHeadline">Title</label>
				<input type="text" name="txtHeadline" id="txtHeadline" maxlength="255" value="<?php echo html_entity_decode($tmpHeadline,ENT_QUOTES); ?>" class="formInputData" />
			</p>
            <p>
				<label>ATE Panel</label>
                <input type="checkbox" name="cbxATE" value="Y"<?php if($tmpATE == "Y") { echo ' checked'; } ?> /> - check if this document should be available in the ATE Panel
			</p>
            <p>
				<label>ATE Panel</label>
                <input type="checkbox" name="cbxReferrer" value="Y"<?php if($tmpReferrer == "Y") { echo ' checked'; } ?> /> - check if this document should be available in the Referrer Panel
			</p>
            <p>
				<label>Own Costs Panel</label>
                <input type="checkbox" name="cbxOwnCosts" value="Y"<?php if($tmpOwnCosts == "Y") { echo ' checked'; } ?> /> - check if this document should be available in the Own Costs Panel
			</p>
            <p>
				<label for="selCategory">Category</label>
                <select name="selCategory" id="selCategory" class="formInputData">
                	<option value="Getting Started Downloads"<?php if($tmpCategory == "Getting Started Downloads") { echo ' selected'; } ?>>Getting Started Downloads</option>
                    <option value="Useful Documents"<?php if($tmpCategory == "Useful Documents") { echo ' selected'; } ?>>Useful Documents</option>
                    <option value="Specific Issue Notes "<?php if($tmpCategory == "Specific Issue Notes ") { echo ' selected'; } ?>>Specific Issue Notes </option>
                </select>
			</p>
            <p>
				<label for="flFilename">File:</label>
				<input type="file" name="flFilename" id="flFilename" value="" class="formInputData" />
			</p>
            <?php
				if($tmpFilename != "")
				{
					echo '<p><label></label><a href="/uploads/ate_documents/'.$tmpFilename.'" target="_blank">View current document</a>&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteFile" id="cbxDeleteFile"></p>';
				}
			?>
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
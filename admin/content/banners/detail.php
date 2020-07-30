<?
$pageTitle = "Banner Detail";
$pageSection = "2";
$subSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpDescription = '';
$tmpLink = '';
$tmpTarget = '_self';
$tmpFilename = '';
$tmpContent = '';

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

if(isset($_POST["iId"]))
{
	$tmpId = intval($_POST["iId"]);
}
elseif(isset($_GET["iId"]))
{
	$tmpId = intval($_GET["iId"]);
}

//var_dump($_POST);
//exit();

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE sm_banner SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE sm_banner SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM sm_banner WHERE id = $tmpId");
	mysql_query("DELETE FROM sm_page_banner WHERE banner_id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtDescription"]))
{
	//var_dump($_POST);
	//exit();
	$tmpDescription = htmlentities($_POST["txtDescription"],ENT_QUOTES);
	$tmpLink = urlencode($_POST["txtLink"]);
	$tmpTarget = $_POST["selTarget"];
	$tmpFilename = $_POST["hfOldFilename"];
	$tmpContent = htmlentities($_POST["txtContent"],ENT_QUOTES);
	
	//var_dump($_FILES);
	//exit();
	
	if(isset($_POST["cbxDeleteFile"]))
	{
		if(file_exists($_SERVER["DOCUMENT_ROOT"]."/app/images/banners/" . $tmpFilename))
		{
			chmod($_SERVER["DOCUMENT_ROOT"]."/app/images//banners/".$tmpFilename,0777);
			unlink($_SERVER["DOCUMENT_ROOT"]."/app/images//banners/".$tmpFilename);
		}
		$tmpFilename = '';
	}
	
	$fnFile = "flFilename";
	//echo "ERROR:".$_FILES[$fnFile]["error"];
	//exit();
	if ($_FILES[$fnFile]["name"] != "")
	{
		//var_dump($_FILES[$fnFile]);exit();
		if (($_FILES[$fnFile]["type"] == "image/gif" || $_FILES[$fnFile]["type"] == "image/jpeg" || $_FILES[$fnFile]["type"] == "image/pjpeg")	&& ($_FILES[$fnFile]["size"] < 200000) && ($_FILES[$fnFile]["error"] == 0))
		{
			$tmpFilename = $_FILES[$fnFile]["name"];
			if (file_exists($_SERVER["DOCUMENT_ROOT"]."/app/images/banners/" . $_FILES[$fnFile]["name"]))
			{
				chmod($_SERVER["DOCUMENT_ROOT"]."/app/images/banners/" . $_FILES[$fnFile]["name"],0777);
			}
			move_uploaded_file($_FILES[$fnFile]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/app/images/banners/".$_FILES[$fnFile]["name"]);
			chmod($_SERVER["DOCUMENT_ROOT"]."/app/images//banners/" . $_FILES[$fnFile]["name"],0777);
		}
		else
		{
			$formErrors .= "<li>There was an error uploading the file</li>";
		}
	}
	//var_dump($tmpFilename);
	//var_dump($formErrors);

	if($formErrors == "")
	{
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO sm_banner (description,filename,link,target,created,created_by,updated,updated_by,status,content) VALUES ('$tmpDescription','$tmpFilename','$tmpLink','$tmpTarget',Now(),$user->userID,Now(),$user->userID,'A','$tmpContent')";
			//echo $strSQL;
			//exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE sm_banner SET description = '$tmpDescription', filename = '$tmpFilename', link = '$tmpLink', target = '$tmpTarget', updated = Now(), updated_by = $user->userID, content = '$tmpContent' WHERE id = $tmpId";
			//echo $strSQL;
			//exit();
			mysql_query($strSQL);
		}
		ob_end_clean();
		header("Location: ./");
		exit();
	}
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$dbRsItem = mysql_query("SELECT * FROM sm_banner WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpDescription = $item["description"];
	$tmpFilename = $item["filename"];
	$tmpLink = $item["link"];
	$tmpTarget = $item["target"];
	$tmpContent = $item["content"];
}
else
{
	$tmpDbAction = $dbAction;
}

if($tmpId > 0)
{
	$msgAction = "edit a banner.";
}
else
{
	$msgAction = "add a banner.";
}

?>
<div class="mainAdminFull">
	<h1><? echo $pageTitle ?></h1>
	<?
		if($formErrors != "")
		{
			echo "<p><strong>ERROR:</strong></p><ul>$formErrors</ul>";
		}
	?>
	<p>Use this form to <? echo $msgAction; ?></p>
    <ul>
    	<li>Large rotating banners for the home page should be 720 x 280 pixels</li>
        <li>Banners for the bottom of the home page should be 300 pixels wide by 112 pixels high</li>
        <li>Banners for the left hand column of pages should be no more than 210 pixels wide (the height can vary)</li>
    </ul>
	<form name="frmData" id="frmData" method="post" enctype="multipart/form-data" class="formData" />
		<input type="hidden" name="dbAction" id="dbAction" value="<?php echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<?php echo $tmpId; ?>" />
        <input type="hidden" name="hfOldFilename" id="hfOldFilename" value="<?php echo $tmpFilename; ?>" />
		<fieldset>
			<legend>Banner Details</legend>
			<p>
				<label for="txtDescription">Description</label>
				<input type="text" name="txtDescription" id="txtDescription" maxlength="255" value="<?php echo html_entity_decode($tmpDescription,ENT_QUOTES); ?>" class="formInputData" />
			</p>
			<p>
				<label for="txtLink">Link</label>
				<input type="text" name="txtLink" id="txtLink" maxlength="255" value="<?php echo urldecode($tmpLink); ?>" class="formInputData" />
			</p>
			<p>
				<label for="selTarget">Target</label>
				<select name="selTarget" id="selTarget" class="formInputData">
                	<option value="_self" <?php if($tmpTarget == "_self"){echo " selected";} ?>>Same Window</option>
                    <option value="_blank" <?php if($tmpTarget == "_blank"){echo " selected";} ?>>New Window</option>
                </select>
			</p>
            <p>
				<label for="flFilename">Image File:</label>
				<input type="file" name="flFilename" id="flFilename" value="" class="formInputData" />
			</p>
            <?php
				if($tmpFilename != "")
				{
					echo '<p><label></label><a href="/app/images/banners/'.$tmpFilename.'" target="_blank">View current Image</a>&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteFile" id="cbxDeleteFile"></p>';
				}
			?>
		</fieldset>
        <fieldset>
        	<legend>Optional Content</legend>
            <p>This is to populate the expanding areas for the small home page banners.</p>
            <p>
				<label for="txtContent">Content</label></p>
				<p><textarea name="txtContent" id="txtContent" rows="10" class="formInputWysiwyg"><? echo html_entity_decode($tmpContent,ENT_QUOTES); ?></textarea>
			</p>
        </fieldset>
		<p>
			<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="window.location='./';" class="formButton" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
		</p>
	</form>
	</div>
	</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
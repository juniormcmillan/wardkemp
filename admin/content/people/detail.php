<?
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "2";
$subSection = "5";
$datePicker = true;
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpName = '';
$tmpPosition = '';
$tmpProfile = '';
$tmpPhone = '';
$tmpEmail = '';
$tmpFacebook = '';
$tmpTwitter = '';
$tmpLinkedin = '';
$tmpSortOrder = 99;
$tmpFilename = '';

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

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE person SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE person SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM person WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtName"]) && isset($_POST["txtProfile"]))
{
	$tmpName = htmlentities($_POST["txtName"],ENT_QUOTES);
	$tmpPosition = htmlentities($_POST["txtPosition"],ENT_QUOTES);
	$tmpProfile = htmlentities($_POST["txtProfile"],ENT_QUOTES);
	$tmpPhone = htmlentities($_POST["txtPhone"],ENT_QUOTES);
	$tmpEmail = urlencode($_POST["txtEmail"]);
	$tmpFacebook = urlencode($_POST["txtFacebook"]);
	$tmpTwitter = urlencode($_POST["txtTwitter"]);
	$tmpLinkedin = urlencode($_POST["txtLinkedin"]);
	$tmpSortOrder = intval($_POST["txtSortOrder"]);
	$tmpFilename = $_POST["hfOldFilename"];
	
	//var_dump($_FILES);
	//exit();
	
	if(isset($_POST["cbxDeleteFile"]))
	{
		if(file_exists($_SERVER["DOCUMENT_ROOT"]."/key_people/photos/" . $tmpFilename))
		{
			chmod($_SERVER["DOCUMENT_ROOT"]."/key_people/photos/".$tmpFilename,0777);
			unlink($_SERVER["DOCUMENT_ROOT"]."/key_people/photos/".$tmpFilename);
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
			if (file_exists($_SERVER["DOCUMENT_ROOT"]."/key_people/photos/" . $_FILES[$fnFile]["name"]))
			{
				chmod($_SERVER["DOCUMENT_ROOT"]."/key_people/photos/" . $_FILES[$fnFile]["name"],0777);
			}
			move_uploaded_file($_FILES[$fnFile]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/key_people/photos/".$_FILES[$fnFile]["name"]);
			chmod($_SERVER["DOCUMENT_ROOT"]."/key_people/photos/" . $_FILES[$fnFile]["name"],0777);
		}
		else
		{
			$formErrors .= "<li>There was an error uploading the file</li>";
		}
	}
	
	if($tmpName == "")
	{
		$formErrors .= "<li>Please enter a name</li>";
	}
	if($tmpPosition == "")
	{
		$formErrors .= "<li>Please enter a position</li>";
	}
	if($tmpProfile == "")
	{
		$formErrors .= "<li>Please enter a profile</li>";
	}
	if($tmpPhone == "")
	{
		$formErrors .= "<li>Please enter a phone number</li>";
	}
	if($tmpEmail == "")
	{
		$formErrors .= "<li>Please enter an email address</li>";
	}

	if($formErrors == "")
	{
		//$tmpDisplayDate = str_pad($tmpYear,4,'0',STR_PAD_LEFT)."-".str_pad($tmpMonth,2,'0',STR_PAD_LEFT)."-".str_pad($tmpDay,2,'0',STR_PAD_LEFT)." 00:00:00";
		/*echo $tmpDisplayDate;
		exit();*/
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO person (name,position,profile,phone,email,facebook,twitter,linkedin,created,created_by,updated,updated_by,status,sort_order,photo) VALUES ('$tmpName', '$tmpPosition', '$tmpProfile', '$tmpPhone', '$tmpEmail', '$tmpFacebook', '$tmpTwitter', '$tmpLinkedin',Now(),$user->userID,Now(),$user->userID,'A',$tmpSortOrder,'$tmpFilename')";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE person SET name = '$tmpName', position = '$tmpPosition', profile = '$tmpProfile', phone = '$tmpPhone', email = '$tmpEmail', facebook = '$tmpFacebook', twitter = '$tmpTwitter', linkedin = '$tmpLinkedin', updated = Now(), updated_by = $user->userID, sort_order = $tmpSortOrder, photo = '$tmpFilename' WHERE id = $tmpId";
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
	$dbRsItem = mysql_query("SELECT * FROM person WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpName = $item["name"];
	$tmpPosition = $item["position"];
	$tmpProfile = $item["profile"];
	$tmpPhone = $item["phone"];
	$tmpEmail = $item["email"];
	$tmpFacebook = $item["facebook"];
	$tmpTwitter = $item["twitter"];
	$tmpLinkedin = $item["linkedin"];
	$tmpSortOrder = $item["sort_order"];
	$tmpFilename = $item["photo"];
}


$pageTitle = "Key Person";
if($tmpId > 0)
{
	$msgAction = "edit a key person";
}
else
{
	$msgAction = "add a key person";
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
	<div class="mainAdminFull"> 
	<p>Use this form to <? echo $msgAction; ?></p>
	<form name="frmService" id="frmService" method="post" enctype="multipart/form-data" class="formEditor" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpId; ?>" />
        <input type="hidden" name="hfOldFilename" id="hfOldFilename" value="<? echo $tmpFilename; ?>" />
		<fieldset>
			<legend>Key Person Details</legend>
			<p>
				<label for="txtName">Name</label>
				<input type="text" name="txtName" id="txtName" maxlength="200" value="<? echo html_entity_decode($tmpName,ENT_QUOTES); ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtPosition">Position</label>
				<input type="text" name="txtPosition" id="txtPosition" maxlength="200" value="<? echo html_entity_decode($tmpPosition,ENT_QUOTES); ?>" class="formInputData" />
			</p>
			<p>
				<label for="txtProfile">Profile</label>
				<textarea name="txtProfile" id="txtProfile" rows="10" class="formInputData"><? echo html_entity_decode($tmpProfile,ENT_QUOTES); ?></textarea>
			</p>
            <p>
				<label for="txtPhone">Phone</label>
				<input type="text" name="txtPhone" id="txtPhone" maxlength="200" value="<? echo html_entity_decode($tmpPhone,ENT_QUOTES); ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtEmail">Email</label>
				<input type="text" name="txtEmail" id="txtEmail" maxlength="200" value="<? echo urldecode($tmpEmail); ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtFacebook">Facebook</label>
				<input type="text" name="txtFacebook" id="txtFacebook" maxlength="200" value="<? echo urldecode($tmpFacebook); ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtTwitter">Twitter</label>
				<input type="text" name="txtTwitter" id="txtTwitter" maxlength="200" value="<? echo urldecode($tmpTwitter); ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtLinkedin">Linked In</label>
				<input type="text" name="txtLinkedin" id="txtLinkedin" maxlength="200" value="<? echo urldecode($tmpLinkedin); ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtSortOrder">Sort Order</label>
				<input type="text" name="txtSortOrder" id="txtSortOrder" maxlength="2" value="<? echo $tmpSortOrder; ?>" class="formInputDataSmall" />
			</p>
            <p>
				<label for="flFilename">Photo File:</label>
				<input type="file" name="flFilename" id="flFilename" value="" class="formInputData" />
			</p>
            <?php
				if($tmpFilename != "")
				{
					echo '<p><label></label><a href="/key_people/photos/'.$tmpFilename.'" target="_blank">View current Image</a>&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteFile" id="cbxDeleteFile"></p>';
				}
			?>
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
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
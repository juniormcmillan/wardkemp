<?
$pageTitle = "Member Detail";
$pageSection = "4";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$formErrors = '';
/*$tmpDbAction = 'Insert';
$tmpDesc = '';
$tmpSummary = "";
$tmpDuration = 52;
$tmpCost = 0;
$tmpType = 'L';
$tmpImages = 0;
$tmpFeatured = "N";*/

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

if(isset($_POST["iId"]))
{
	$strSQL = "SELECT * FROM member WHERE id = ".$tmpId;
	//echo $strSQL;
	$dbRsItem = mysql_query($strSQL);
	$count = mysql_num_rows($dbRsItem);
	if($count == 0)
	{
		ob_end_clean();
		header("Location: ./");
		exit();
	}
	else
	{
		$item = mysql_fetch_array($dbRsItem);
		
		if(($item["status"] == "N" || $item["status"] == "P" || $item["status"] == "U") && $_POST["status"] == "A")
		{
			$input = "Dear: " . urldecode($item['first_name']) . " " . urldecode($item["last_name"]). ",\n\n";
			$input .= "Your Premium Points account is now active. \n\n";
			$input .= "Go to www.premiumpoints.co.uk/your_account/ to log in. \n\n";
			$input .= "The Premium Points Team";
			$subject = "Account Activation from Premium Points Website";
			$headers = "From: Premium Points Website <website@premiumpoints.co.uk>\n";
			$headers .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=ISO-8859-1\nContent-Transfer-Encoding: 8bit;\n\n";
			$input = stripslashes($input);
			//exit("$headers$input");
			
			ini_set("sendmail_from",  "website@premiumpoints.co.uk");
			mail(urldecode($item['email']), "$subject", "$input", "$headers");
			//mail("andrew@surefiremedia.co.uk", "$subject", "$input", "$headers");
			//mail("roger@bankdigital.co.uk", "$subject", "$input", "$headers");
		}
	}
	
	mysql_query("UPDATE member SET virtual = '".$_POST["virtual"]."', status = '".$_POST["status"]."' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE member SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE member SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM member WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

/*if(isset($_POST["txtDesc"]) && isset($_POST["txtCost"]))
{
	$tmpDesc = urlencode($_POST["txtDesc"]);
	$tmpSummary = urlencode($_POST["txtSummary"]);
	$tmpDuration = intval($_POST["txtDuration"]);
	$tmpCost = intval($_POST["txtCost"]);
	$tmpType = $_POST["selType"];
	$tmpImages = intval($_POST["txtImages"]);
	if(isset($_POST["cbxFeatured"]))
	{
		$tmpFeatured = "Y";
	}
	else
	{
		$tmpFeatured = "N";
	}

	if($tmpDesc == "")
	{
		$formErrors .= "<li>Please enter a description</li>";
	}
	if($tmpDuration == 0)
	{
		$formErrors .= "<li>Please enter a duration</li>";
	}
	if($tmpCost == 0)
	{
		$formErrors .= "<li>Please enter a cost</li>";
	}

	if($formErrors == "")
	{
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO pricing (description,summary,duration,cost,type,images,featured,created,created_by,updated,updated_by,status) VALUES ('$tmpDesc','$tmpSummary',$tmpDuration,$tmpCost,'$tmpType',$tmpImages,'$tmpFeatured',Now(),$user->userID,Now(),$user->userID,'A')";
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE pricing SET description = '$tmpDesc', summary = '$tmpSummary', duration = $tmpDuration, cost = $tmpCost, type = '$tmpType', images = $tmpImages, featured = '$tmpFeatured', updated = Now(), updated_by = $user->userID WHERE id = $tmpId";
			mysql_query($strSQL);
		}
		ob_end_clean();
		header("Location: ./");
		exit();
	}
}*/

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Activate";
	$strSQL = "SELECT * FROM member WHERE id = ".$tmpId;
	//echo $strSQL;
	$dbRsItem = mysql_query($strSQL);
	$count = mysql_num_rows($dbRsItem);
	if($count == 0)
	{
		ob_end_clean();
		header("Location: ./");
		exit();
	}
	else
	{
		$item = mysql_fetch_array($dbRsItem);
		$tmpName = $item["company_name"];
		$tmpFirstName = $item["first_name"];
		$tmpLastName = $item["last_name"];
		$tmpEmail = $item["email"];
		$tmpPhone1 = $item["company_phone"];
		$tmpPhone2 = $item["mobile_phone"];
		$tmpPosition = $item["position"];
		$tmpGender = $item["gender"];
		$tmpAgeGroup = $item["age_group"];
		$tmpVirtual = $item["virtual"];
		$tmpStatus = $item["status"];
		$tmpCreated = $item["created"];
		$tmpPassword = $item["password"];
	}
}

if($tmpId > 0)
{
	$msgAction = "edit a member";
}
else
{
	$msgAction = "add a member";
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
	<div class="mainAdminFull"> 
	<form name="frmData" id="frmData" method="post" class="formData" action="detail.php" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpId; ?>" />
		<fieldset>
			<legend>Personal & Contact Details</legend>
            <p style="width:800px; display:inline; float:left;">
				<label>Company Name</label>
				<?php echo urldecode($tmpName); ?>
			</p>
            <p style="width:800px; display:inline; float:left;">
				<label>Name</label>
				<?php echo urldecode($tmpFirstName) . ' ' . urldecode($tmpLastName); ?>
			</p>
			<p style="width:800px; display:inline; float:left;">
				<label>Company Phone</label>
				<?php echo urldecode($tmpPhone1)."&nbsp;"; ?>
			</p>
            <p style="width:800px; display:inline; float:left;">
				<label>Mobile</label>
				<?php echo urldecode($tmpPhone2)."&nbsp;"; ?>
			</p>
			<p style="width:800px; display:inline; float:left;">
				<label>Email</label>
				<?php echo urldecode($tmpEmail); ?>
			</p>
            <p style="width:800px; display:inline; float:left;">
				<label>Password</label>
				<?php echo urldecode($tmpPassword); ?>
			</p>
            <p style="width:800px; display:inline; float:left;">
				<label>Position :</label>
				<?php echo urldecode($tmpPosition); ?>
			</p>
            <p style="width:800px; display:inline; float:left;">
				<label>Created :</label>
				<?php echo urldecode($tmpCreated); ?>
			</p>
            <p style="width:800px; display:inline; float:left;">
				<label>Gender :</label>
				<?php echo urldecode($tmpGender); ?>
			</p>
            <p style="width:800px; display:inline; float:left;">
				<label>Age Group :</label>
				<?php echo urldecode($tmpAgeGroup); ?>
			</p>
            <p>
				<label for="virtual">Virtual :</label>
                <select name="virtual" id="virtual" class="formInputData">
                	<option value="Y"<?php if($tmpVirtual == "Y") { echo ' selected'; } ?>>Yes</option>
                    <option value="N"<?php if($tmpVirtual == "N") { echo ' selected'; } ?>>No</option>
                </select>
			</p>
            <p>
				<label for="status">Status:</label>
                <select name="status" id="status" class="formInputData">
                	<option value="N"<?php if($tmpStatus == "N") { echo ' selected'; } ?>>New</option>
                    <option value="P"<?php if($tmpStatus == "P") { echo ' selected'; } ?>>Incomplete</option>
                    <option value="U"<?php if($tmpStatus == "U") { echo ' selected'; } ?>>Updated</option>
                    <option value="A"<?php if($tmpStatus == "A") { echo ' selected'; } ?>>Active</option>
                    <option value="D"<?php if($tmpStatus == "D") { echo ' selected'; } ?>>Deactivated</option>
                </select>
			</p>
        </fieldset>
        
		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
            <input type="submit" name="btnUpdate" id="btnUpdate" value="Update" onClick="return confirm(\'Are you sure?\');" class="formButton" />
		</p>
	</form>
	</div>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
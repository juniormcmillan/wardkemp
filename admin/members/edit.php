<?
$pageTitle = "Listing Detail";
$pageSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$formErrors = '';
$tmpDbAction = 'Update';
/*$tmpDesc = '';
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


if(isset($_POST["txtCharges"]))
{
	$tmpLat = urlencode($_POST["txtLat"]);
	$tmpLng = urlencode($_POST["txtLng"]);
	$tmpCharges = urlencode($_POST["txtCharges"]);
	$tmpQualifications = urlencode($_POST["txtQualifications"]);
	$tmpMemberships = urlencode($_POST["txtMemberships"]);
	$tmpSkills = urlencode($_POST["txtSkills"]);
	$tmpBackground = urlencode($_POST["txtBackground"]);
	
	$strSQL = "UPDATE listing SET lat = '".$tmpLat."', lng = '".$tmpLng."', charges = '".$tmpCharges."', qualifications = '".$tmpQualifications."', memberships = '".$tmpMemberships."', skills = '".$tmpSkills."', background = '".$tmpBackground."', updated = Now(), updated_by = ".$user->userID." WHERE id = ".$tmpId;
	//echo $strSQL;exit();
	mysql_query($strSQL);
	
	ob_end_clean();
	header("Location: ./detail.php?iId=".$tmpId);
	exit();
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$strSQL = "SELECT u.name AS name, u.email AS email, l.* FROM listing l JOIN user u ON u.id = l.user_id WHERE l.id = ".$tmpId;
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
		$tmpListingId = $item["id"];
		$tmpName = $item["name"];
		$tmpEmail = $item["email"];
		$tmpPhone = $item["phone"];
		$tmpAddr1 = $item["addr1"];
		$tmpAddr2 = $item["addr2"];
		$tmpAddr3 = $item["addr3"];
		$tmpAddr4 = $item["addr4"];
		$tmpPostcode = $item["postcode"];
		$tmpLat = $item["lat"];
		$tmpLng = $item["lng"];
		$tmpWebsite = urldecode($item["website"]);
		if(stristr($tmpWebsite,"http://"))
		{
			$tmpWebsite2 = $tmpWebsite;
		}
		else
		{
			$tmpWebsite2 = "http://".$tmpWebsite;
		}
		$tmpServiceType = $item["service_type"];
		$tmpCharges = $item["charges"];
		$tmpWorkOverPhone = $item["work_over_phone"];
		$tmpQualifications = $item["qualifications"];
		$tmpMemberships = $item["memberships"];
		$tmpSkills = $item["skills"];
		$tmpBackground = $item["background"];
		$tmpCreated = $item["created"];
		$tmpStatus = $item["status"];
		
		$strSQL = "SELECT s.*, p.description AS price_desc FROM subscription s JOIN pricing p ON p.id = s.pricing_id WHERE s.listing_id = ".$tmpId ." ORDER BY start_date";
		//echo $strSQL;
		$dbRsSubList = mysql_query($strSQL);
		
		$strSQL = "SELECT * FROM listing_image WHERE listing_id = ".$tmpId." ORDER BY index_id";
		$dbRsImages = mysql_query($strSQL);
		$imageCount = mysql_num_rows($dbRsImages);
		
		$profilePic = "";
		$extraPics = array();
		
		if($imageCount > 0)
		{
			$imageCounter = 1;
			$imageIndex = 0;
			while($item = mysql_fetch_array($dbRsImages))
			{
				if($imageCounter == 1)
				{
					$profilePic = $item["image"];
				}
				else
				{
					$extraPics[$imageIndex] = $item["image"];
					$imageIndex++;
				}
				$imageCounter++;
			}
		}
	}
}

if($tmpId > 0)
{
	$msgAction = "edit a listing";
}
else
{
	$msgAction = "add a listing";
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
	<div class="mainAdminLeft"> 
	<form name="frmData" id="frmData" method="post" class="formData" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpListingId; ?>" />
		<fieldset>
			<legend>Personal & Contact Details</legend>
            <p>
				<label>Name</label>
				<?php echo urldecode($tmpName); ?>
			</p>
			<p>
				<label>Phone</label>
				<?php echo urldecode($tmpPhone)."&nbsp;"; ?>
			</p>
			<p>
				<label>Email</label>
				<?php echo urldecode($tmpEmail); ?>
			</p>
            <p>
				<label>Address</label>
				<?php
                	echo '<span style="display:inline; float:left; width:500px;">' . urldecode($tmpAddr1);
					if($tmpAddr2 != "")
					{
						echo "<br />".urldecode($tmpAddr2);
					}
					if($tmpAddr3 != "")
					{
						echo "<br />".urldecode($tmpAddr3);
					}
					if($tmpAddr4 != "")
					{
						echo "<br />".urldecode($tmpAddr4);
					}
					echo '</span>';
				?>
			</p>
            <p>
				<label>Postcode :</label>
				<?php echo urldecode($tmpPostcode); ?>
			</p>
            <p>
				<label>Latitude :</label>
				<input type="text" name="txtLat" id="txtLat" value="<?php echo urldecode($tmpLat); ?>" class="formInputData" />
			</p>
            <p>
				<label>Longitude :</label>
				<input type="text" name="txtLng" id="txtLng" value="<?php echo urldecode($tmpLng); ?>" class="formInputData" />
			</p>
            <?php
			if($tmpWebsite != "")
			{
            	echo'<p>
					<label>Website :</label>
					<a href="' . $tmpWebsite2 . '" target="_blank">' . $tmpWebsite . '</a>
					</p>';
			}
			?>
            <p>
				<label>Charges :</label>
				<input type="text" name="txtCharges" id="txtCharges" value="<?php echo urldecode($tmpCharges); ?>" class="formInputData" />
			</p>
        </fieldset>
        <fieldset>
        	<legend>Memberships</legend>
            <p>
                <textarea name="txtMemberships" id="txtMemberships" class="formInputDataWide" rows="5"><? echo urldecode($tmpMemberships); ?></textarea>
            </p>
        </fieldset>
        <fieldset>
        	<legend>Qualifications</legend>
            <p>
            	<textarea name="txtQualifications" id="txtQualifications" class="formInputDataWide" rows="5"><? echo urldecode($tmpQualifications); ?></textarea>
            </p>
        </fieldset>
        <fieldset>
        	<legend>Skills</legend>
            <p>
            	<textarea name="txtSkills" id="txtSkills" class="formInputDataWide" rows="5"><? echo urldecode($tmpSkills); ?></textarea>
            </p>
        </fieldset>
        <fieldset>
            <legend>Background</legend>
            <p>
            	<textarea name="txtBackground" id="txtBackground" class="formInputDataWide" rows="5"><? echo urldecode($tmpBackground); ?></textarea>
            </p>
        </fieldset>

		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./detail.php?iId=<?php echo $tmpId; ?>';" />
            <input type="submit" name="btnSave" id="btnSave" value="Save Changes" onClick="return confirm(\'Are you sure?\');" />
		</p>
	</form>
	</div>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
<?
//error_reporting( E_ALL );
$pageTitle = "Reward Detail";
$pageSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpTitle = '';
$tmpDetail = '';
$tmpPrice = '';
$tmpSortOrder = 99;
$tmpReference = '';
$tmpImages = Array();
$tmpImages[1] = "";
$tmpCatId = 1;
$tmpOptLabel = "";
$tmpOpt1 = "";
$tmpOpt2 = "";
$tmpOpt3 = "";
$tmpOpt4 = "";
$tmpOpt5 = "";

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

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE product SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE product SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM product WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtTitle"]) && isset($_POST["txtDetail"]))
{
	$tmpTitle = htmlentities($_POST["txtTitle"],ENT_QUOTES);
	$tmpRef = htmlentities($_POST["txtRef"],ENT_QUOTES);
	//echo($tmpTitle);exit();
	$tmpDetail = htmlentities($_POST["txtDetail"],ENT_QUOTES);
	$tmpPrice = intval($_POST["txtPrice"]);
	$tmpSortOrder = intval($_POST["txtSortOrder"]);
	$tmpCatId = intval($_POST["selCatId"]);
	$tmpImages[1] = $_POST["hfOldImage1"];
	//var_dump($_FILES);exit();
	$tmpOptLabel = htmlentities($_POST["txtOptLabel"],ENT_QUOTES);
	$tmpOpt1 = htmlentities($_POST["txtOpt1"],ENT_QUOTES);
	$tmpOpt2 = htmlentities($_POST["txtOpt2"],ENT_QUOTES);
	$tmpOpt3 = htmlentities($_POST["txtOpt3"],ENT_QUOTES);
	$tmpOpt4 = htmlentities($_POST["txtOpt4"],ENT_QUOTES);
	$tmpOpt5 = htmlentities($_POST["txtOpt5"],ENT_QUOTES);
	
	$fileWarnings = "";
	$fileErrors = "";
	for($i=1;$i<=3;$i++)
	{
		// and now we have the unique file name for the upload file
		$tmpFileNameL = $tmpImages[$i];
		$tmpFileNameM = str_replace("_l.","_m.",$tmpImages[$i]);
		$tmpFileNameT = str_replace("_l.","_t.",$tmpImages[$i]);
		$cbxDeleteImage = "cbxDeleteImage".$i;
		if(isset($_POST[$cbxDeleteImage]) && $tmpImages[$i] != "")
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/" . $tmpFileNameL))
			{
				try
				{
					chmod($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/".$tmpFileNameL,0777);
					unlink($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/".$tmpFileNameL);
				}
				catch(Exception $e)
				{
					$fileWarnings .= $e->getMessage();
				}
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/" . $tmpFileNameM))
			{
				try
				{
					chmod($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/".$tmpFileNameM,0777);
					unlink($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/".$tmpFileNameM);
				}
				catch(Exception $e)
				{
					$fileWarnings .= $e->getMessage();
				}
			}
			if(file_exists($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/" . $tmpFileNameT))
			{
				try
				{
					chmod($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/".$tmpFileNameT,0777);
					unlink($_SERVER["DOCUMENT_ROOT"]."/catalogue/images/".$tmpFileNameT);
				}
				catch(Exception $e)
				{
					$fileWarnings .= $e->getMessage();
				}
			}
			$tmpImages[$i] = '';
		}
		//echo 'Warning: '.$fileWarnings;exit();
		$fnFile = "flImage".$i;
		if ($_FILES[$fnFile]["name"] != "")
		{
			if (($_FILES[$fnFile]["type"] != "image/jpg" && $_FILES[$fnFile]["type"] != "image/jpeg" && $_FILES[$fnFile]["type"] != "image/pjpeg") || $_FILES[$fnFile]["error"] > 0)
			{
				$fileErrors .= "<li>There was an error uploading the file</li>";
			}
			else
			{
				// get the file extension first
				$ext = substr(strrchr($_FILES[$fnFile]['name'], "."), 1);
				
				// make the random file name
				$randName = md5(rand() * time());
				$destFileNameL = $randName . '_l.' . $ext;
				$destFileNameM = $randName . '_m.' . $ext;
				$destFileNameT = $randName . '_t.' . $ext;
				$tmpImages[$i] = $destFileNameL;
				// This is the temporary file created by PHP
				$uploadedfile = $_FILES[$fnFile]['tmp_name'];
				
				// Create an Image from it so we can do the resize
				$src = imagecreatefromjpeg($uploadedfile);
				
				// Capture the original size of the uploaded image
				list($width,$height)=getimagesize($uploadedfile);
				$ratio = $width / $height;
				
				// For our purposes, I have resized the image to be
				// 600 pixels wide, and maintain the original aspect
				// ratio. This prevents the image from being "stretched"
				// or "squashed". If you prefer some max width other than
				// 600, simply change the $newwidth variable
				$newwidth=600;
				$newheight=round($newwidth / $ratio);
				//echo $newheight;exit();

				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				// this line actually does the image resizing, copying from the original
				// image into the $tmp image
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				// now write the resized image to disk. I have assumed that you want the
				// resized, uploaded image file to reside in the ./images subdirectory.
				$filename = $_SERVER["DOCUMENT_ROOT"]."/catalogue/images/". $destFileNameL;
				imagejpeg($tmp,$filename,100);
				
				// For our purposes, I have resized the image to be
				// 600 pixels wide, and maintain the original aspect
				// ratio. This prevents the image from being "stretched"
				// or "squashed". If you prefer some max width other than
				// 600, simply change the $newwidth variable
				$newwidth=300;
				$newheight=round($newwidth / $ratio);

				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				// this line actually does the image resizing, copying from the original
				// image into the $tmp image
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				// now write the resized image to disk. I have assumed that you want the
				// resized, uploaded image file to reside in the ./images subdirectory.
				$filename = $_SERVER["DOCUMENT_ROOT"]."/catalogue/images/". $destFileNameM;
				imagejpeg($tmp,$filename,100);
				
				$newwidth=150;
				$newheight=round($newwidth / $ratio);

				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				// this line actually does the image resizing, copying from the original
				// image into the $tmp image
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				// now write the resized image to disk. I have assumed that you want the
				// resized, uploaded image file to reside in the ./images subdirectory.
				$filename = $_SERVER["DOCUMENT_ROOT"]."/catalogue/images/". $destFileNameT;
				imagejpeg($tmp,$filename,100);
				
				imagedestroy($src);
				imagedestroy($tmp); // NOTE: PHP will clean up the temp file it created when the request
				// has completed.
				
				//chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$_FILES[$fnFile]["name"],0777);
				
			}
		}
	}
	
	if($tmpTitle == "")
	{
		$formErrors .= "<li>Please enter a title</li>";
	}
	if($tmpRef == "")
	{
		$formErrors .= "<li>Please enter a reference</li>";
	}
	if($tmpDetail == "")
	{
		$formErrors .= "<li>Please enter some detail</li>";
	}
	if($tmpPrice == "")
	{
		$formErrors .= "<li>Please enter the number of points</li>";
	}
	/*if($tmpCatId == 0)
	{
		$formErrors .= "<li>Please select a category</li>";
	}*/
	if($fileErrors != "")
	{
		$formErrors .= "<li>There was a problem uploading one or more of your images. Please ensure that they are all in </li>";
	}
	
	
	if($formErrors == "")
	{
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO product (title,detail,price,reference,image1,sort_order,cat_id,created,created_by,updated,updated_by,status,opt_label,opt1,opt2,opt3,opt4,opt5) VALUES ('$tmpTitle','$tmpDetail','$tmpPrice','$tmpRef','$tmpImages[1]',$tmpSortOrder,$tmpCatId,Now(),$user->userID,Now(),$user->userID,'A','$tmpOptLabel','$tmpOpt1','$tmpOpt2','$tmpOpt3','$tmpOpt4','$tmpOpt5')";
			//echo $strSQL;exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE product SET title = '$tmpTitle', detail = '$tmpDetail', price = '$tmpPrice', reference = '$tmpRef', image1 = '$tmpImages[1]', sort_order = $tmpSortOrder, cat_id = $tmpCatId, updated = Now(), updated_by = $user->userID, opt_label = '$tmpOptLabel', opt1 = '$tmpOpt1', opt2 = '$tmpOpt2', opt3 = '$tmpOpt3', opt4 = '$tmpOpt4', opt5 = '$tmpOpt5'  WHERE id = $tmpId";
			//echo $strSQL;exit();
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
	$strSQL = "SELECT title, detail, price, reference, image1, sort_order, cat_id, opt_label, opt1, opt2, opt3, opt4, opt5 FROM product WHERE id = " . $tmpId;
	$dbRsItem = mysql_query($strSQL);
	//echo $strSQL;
	$item = mysql_fetch_array($dbRsItem);
	$tmpTitle = $item["title"];
	$tmpDetail = $item["detail"];
	$tmpPrice = $item["price"];
	$tmpRef = $item["reference"];
	$tmpCatId = $item["cat_id"];
	$tmpImages[1] = $item["image1"];
	$tmpSortOrder = intval($item["sort_order"]);
	$tmpOptLabel = $item["opt_label"];
	$tmpOpt1 = $item["opt1"];
	$tmpOpt2 = $item["opt2"];
	$tmpOpt3 = $item["opt3"];
	$tmpOpt4 = $item["opt4"];
	$tmpOpt5 = $item["opt5"];
}
else
{
	$tmpDbAction = $dbAction;
}

if($tmpId > 0)
{
	$msgAction = "edit a reward";
}
else
{
	$msgAction = "add a reward";
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
	<form name="frmData" id="frmData" method="post" class="formEditor" enctype="multipart/form-data" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpId; ?>" />
        <input type="hidden" name="hfOldFilename" id="hfOldFilename" value="<? echo $tmpFilename; ?>" />
        <input type="hidden" name="hfOldImage1" id="hfOldImage1" value="<? echo $tmpImages[1]; ?>" />
		<fieldset>
			<legend>Reward Details</legend>
			<p>
				<label for="txtRef">Title</label>
				<input type="text" name="txtRef" id="txtRef" maxlength="24" value="<?php echo $tmpRef; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtTitle">Summary</label>
				<input type="text" name="txtTitle" id="txtTitle" maxlength="60" value="<?php echo $tmpTitle; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtDetail">Detail</label>
				<textarea name="txtDetail" id="txtDetail" rows="10" class="formInputData"><?php echo $tmpDetail; ?></textarea>
			</p>
            <p>
				<label for="txtPrice">Points</label>
				<input type="text" name="txtPrice" id="txtPrice" maxlength="255" value="<?php echo $tmpPrice; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtSortOrder">Sort Order</label>
				<input type="text" name="txtSortOrder" id="txtSortOrder" maxlength="2" value="<?php echo $tmpSortOrder; ?>" class="formInputDataSmall" />
			</p>
            <p>
				<label for="selCatId">Card Colour</label>
                <select name="selCatId" id="selCatId" class="formInputData">
                	<option value="1" style="background-color:#ecf0f9;"<?php if($tmpCatId == 1) { echo ' selected'; } ?>>Colour 1</option>
                    <option value="2" style="background-color:#c6d5da;"<?php if($tmpCatId == 2) { echo ' selected'; } ?>>Colour 2</option>
                    <option value="3" style="background-color:#dee6db;"<?php if($tmpCatId == 3) { echo ' selected'; } ?>>Colour 3</option>
                    <option value="4" style="background-color:#a7bdd2;"<?php if($tmpCatId == 4) { echo ' selected'; } ?>>Colour 4</option>
                    <option value="5" style="background-color:#d2e6f1;"<?php if($tmpCatId == 5) { echo ' selected'; } ?>>Colour 5</option>
                </select>
			</p>
        </fieldset>
            <?php
				for($i=1;$i<=1;$i++)
				{
					echo '<fieldset>
						<legend>Reward Image</legend>
						<span style="width:630px; display:inline; float:left;">';
					echo '<p>
						<label for="flImage'.$i.'">Main Image:</label>
						<input type="file" name="flImage'.$i.'" id="flImage'.$i.'" value="" class="formInputData" />
					</p>';
					if($tmpImages[$i] != "")
					{
						echo '<p><label></label><a href="/catalogue/images/'.str_replace("_l.","_m.",$tmpImages[$i]).'" target="_blank">View current image</a>&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteImage'.$i.'" id="cbxDeleteImage'.$i.'"></p>';
					}
					echo '</span></fieldset>';
				}
			?>
            <fieldset>
            	<legend>Options</legend>
                <p>If the reward has options, enter a label for the options, then add the options in the fields provided. You don't have to enter values in all the fields, the site will only display those that aren't empty. You must enter a label and at least the first option in order to display the options to members.</p>
                <p>
                    <label for="txtOptLabel">Option Label (e.g. 'Colour' or 'Size')</label>
                    <input type="text" name="txtOptLabel" id="txtOptLabel" maxlength="100" value="<?php echo html_entity_decode($tmpOptLabel,ENT_QUOTES); ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtOpt1">Option 1</label>
                    <input type="text" name="txtOpt1" id="txtOpt1" maxlength="100" value="<?php echo html_entity_decode($tmpOpt1,ENT_QUOTES); ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtOpt2">Option 2</label>
                    <input type="text" name="txtOpt2" id="txtOpt2" maxlength="100" value="<?php echo html_entity_decode($tmpOpt2,ENT_QUOTES); ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtOpt3">Option 3</label>
                    <input type="text" name="txtOpt3" id="txtOpt3" maxlength="100" value="<?php echo html_entity_decode($tmpOpt3,ENT_QUOTES); ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtOpt4">Option 4</label>
                    <input type="text" name="txtOpt4" id="txtOpt4" maxlength="100" value="<?php echo html_entity_decode($tmpOpt4,ENT_QUOTES); ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtOpt5">Option 5</label>
                    <input type="text" name="txtOpt5" id="txtOpt5" maxlength="100" value="<?php echo html_entity_decode($tmpOpt5,ENT_QUOTES); ?>" class="formInputData" />
                </p>
            </fieldset>
		<div style="display:block; margin:5px 0 5px 0;">
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
		</div>
	</form>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
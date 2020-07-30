<?
$pageTitle = "Plot Detail";
$pageSection = "3";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpTitle = '';
$tmpDetail = '';
$tmpPrice = '';
$tmpDimensions = '';
$tmpSortOrder = 99;
$tmpFilename = '';
$tmpThumbs = Array();
$tmpImages = Array();
$tmpThumbs[1] = "";
$tmpImages[1] = "";
$tmpThumbs[2] = "";
$tmpImages[2] = "";
$tmpThumbs[3] = "";
$tmpImages[3] = "";

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
	mysql_query("UPDATE plot SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE plot SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM plot_feature WHERE plot_id = $tmpId");
	mysql_query("DELETE FROM plot WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtTitle"]) && isset($_POST["txtDetail"]))
{
	$tmpTitle = urlencode($_POST["txtTitle"]);
	$tmpDetail = urlencode($_POST["txtDetail"]);
	$tmpPrice = urlencode($_POST["txtPrice"]);
	$tmpDimensions = urlencode($_POST["txtDimensions"]);
	$tmpSortOrder = intval($_POST["txtSortOrder"]);
	$tmpFilename = $_POST["hfOldFilename"];
	$tmpThumbs[1] = $_POST["hfOldThumb1"];
	$tmpImages[1] = $_POST["hfOldImage1"];
	$tmpThumbs[2] = $_POST["hfOldThumb2"];
	$tmpImages[2] = $_POST["hfOldImage2"];
	$tmpThumbs[3] = $_POST["hfOldThumb3"];
	$tmpImages[3] = $_POST["hfOldImage3"];
	//var_dump($_FILES);
	
	if(isset($_POST["cbxDeleteFile"]))
	{
		if(file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $tmpFilename))
		{
			chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpFilename,764);
			unlink($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpFilename);
		}
		$tmpFilename = '';
	}
	
	$fnFile = "flFilename";
	//echo "ERROR:".$_FILES[$fnFile]["error"];
	//exit();
	if ($_FILES[$fnFile]["name"] != "")
	{
		if (($_FILES[$fnFile]["type"] != "image/jpg" || $_FILES[$fnFile]["type"] != "image/jpeg" || $_FILES[$fnFile]["type"] != "image/pjpeg" && $_FILES[$fnFile]["type"] != "application/pdf") && $_FILES[$fnFile]["error"] > 0)
		{
			$formErrors .= "<li>There was an error uploading the file</li>";
		}
	  	else
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $tmpFilename))
			{
				chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpFilename,764);
				unlink($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpFilename);
			}
			$tmpFilename = $_FILES[$fnFile]["name"];
			if (file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $_FILES[$fnFile]["name"]))
			{
				chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $_FILES[$fnFile]["name"],764);
			}
			move_uploaded_file($_FILES[$fnFile]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/uploads/".$_FILES[$fnFile]["name"]);
			chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$_FILES[$fnFile]["name"],764);
		}
	}
	
	for($i=1;$i<=3;$i++)
	{
		$cbxDeleteThumb = "cbxDeleteThumb".$i;
		if(isset($_POST[$cbxDeleteThumb]) && $tmpThumbs[$i] != "")
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $tmpThumbs[$i]))
			{
				chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpThumbs[$i],764);
				unlink($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpThumbs[$i]);
			}
			$tmpThumbs[$i] = '';
		}
		
		$cbxDeleteImage = "cbxDeleteImage".$i;
		if(isset($_POST[$cbxDeleteImage]) && $tmpImages[$i] != "")
		{
			if(file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $tmpImages[$i]))
			{
				chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpImages[$i],764);
				unlink($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpImages[$i]);
			}
			$tmpImages[$i] = '';
		}
		
		$fnFile = "flThumb".$i;
		//echo "ERROR:".$_FILES[$fnFile]["error"];
		//exit();
		if ($_FILES[$fnFile]["name"] != "")
		{
			if (($_FILES[$fnFile]["type"] == "image/jpg" || $_FILES[$fnFile]["type"] == "image/jpeg" || $_FILES[$fnFile]["type"] == "image/pjpeg") && $_FILES[$fnFile]["error"] > 0)
			{
				$formErrors .= "<li>There was an error uploading the file</li>";
			}
			else
			{
				if($tmpThumbs[$i] != "" && file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $tmpThumbs[$i]))
				{
					chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpThumbs[$i],764);
					unlink($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpThumbs[$i]);
				}
				$tmpThumbs[$i] = $_FILES[$fnFile]["name"];
				if(file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $_FILES[$fnFile]["name"]))
				{
					chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $_FILES[$fnFile]["name"],764);
				}
				move_uploaded_file($_FILES[$fnFile]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/uploads/".$_FILES[$fnFile]["name"]);
				chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$_FILES[$fnFile]["name"],764);
			}
		}
		
		$fnFile = "flImage".$i;
		if ($_FILES[$fnFile]["name"] != "")
		{
			if (($_FILES[$fnFile]["type"] == "image/jpg" || $_FILES[$fnFile]["type"] == "image/jpeg" || $_FILES[$fnFile]["type"] == "image/pjpeg") && $_FILES[$fnFile]["error"] > 0)
			{
				$formErrors .= "<li>There was an error uploading the file</li>";
			}
			else
			{
				if($tmpImages[$i] != "" && file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $tmpImages[$i]))
				{
					chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpImages[$i],764);
					unlink($_SERVER["DOCUMENT_ROOT"]."/uploads/".$tmpImages[$i]);
				}
				$tmpImages[$i] = $_FILES[$fnFile]["name"];
				if (file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $_FILES[$fnFile]["name"]))
				{
					chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/" . $_FILES[$fnFile]["name"],764);
				}
				move_uploaded_file($_FILES[$fnFile]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/uploads/".$_FILES[$fnFile]["name"]);
				chmod($_SERVER["DOCUMENT_ROOT"]."/uploads/".$_FILES[$fnFile]["name"],764);
			}
		}
	}

	if($formErrors == "")
	{
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO plot (title,detail,price,dimensions,filename,thumb1,image1,thumb2,image2,thumb3,image3,sort_order,created,created_by,updated,updated_by,status) VALUES ('$tmpTitle','$tmpDetail','$tmpPrice','$tmpDimensions','$tmpFilename','$tmpThumbs[1]','$tmpImages[1]','$tmpThumbs[2]','$tmpImages[2]','$tmpThumbs[3]','$tmpImages[3]',$tmpSortOrder,Now(),$user->userID,Now(),$user->userID,'A')";
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE plot SET title = '$tmpTitle', detail = '$tmpDetail', price = '$tmpPrice', dimensions = '$tmpDimensions', filename = '$tmpFilename', thumb1 = '$tmpThumbs[1]', image1 = '$tmpImages[1]', thumb2 = '$tmpThumbs[2]', image2 = '$tmpImages[2]', thumb3 = '$tmpThumbs[3]', image3 = '$tmpImages[3]', sort_order = $tmpSortOrder, updated = Now(), updated_by = $user->userID WHERE id = $tmpId";
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
	$dbRsItem = mysql_query("SELECT title, detail, price, dimensions, filename, thumb1, image1, thumb2, image2, thumb3, image3, sort_order FROM plot WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpTitle = $item["title"];
	$tmpDetail = $item["detail"];
	$tmpPrice = $item["price"];
	$tmpDimensions = $item["dimensions"];
	$tmpFilename = $item["filename"];
	$tmpThumbs[1] = $item["thumb1"];
	$tmpImages[1] = $item["image1"];
	$tmpThumbs[2] = $item["thumb2"];
	$tmpImages[2] = $item["image2"];
	$tmpThumbs[3] = $item["thumb3"];
	$tmpImages[3] = $item["image3"];
	$tmpSortOrder = intval($item["sort_order"]);
}

if($tmpId > 0)
{
	$msgAction = "edit a plot";
}
else
{
	$msgAction = "add a plot";
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
	<p>Use this form to <? echo $msgAction; ?></p>
	<form name="frmData" id="frmData" method="post" class="formEditor" enctype="multipart/form-data" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpId; ?>" />
        <input type="hidden" name="hfOldFilename" id="hfOldFilename" value="<? echo $tmpFilename; ?>" />
        <input type="hidden" name="hfOldThumb1" id="hfOldThumb1" value="<? echo $tmpThumbs[1]; ?>" />
        <input type="hidden" name="hfOldImage1" id="hfOldImage1" value="<? echo $tmpImages[1]; ?>" />
        <input type="hidden" name="hfOldThumb2" id="hfOldThumb2" value="<? echo $tmpThumbs[2]; ?>" />
        <input type="hidden" name="hfOldImage2" id="hfOldImage2" value="<? echo $tmpImages[2]; ?>" />
        <input type="hidden" name="hfOldThumb3" id="hfOldThumb3" value="<? echo $tmpThumbs[3]; ?>" />
        <input type="hidden" name="hfOldImage3" id="hfOldImage3" value="<? echo $tmpImages[3]; ?>" />
		<fieldset>
			<legend>Plot Details</legend>
			<p>
				<label for="txtTitle">Title</label>
				<input type="text" name="txtTitle" id="txtTitle" maxlength="100" value="<? echo urldecode($tmpTitle); ?>" class="formInputData" />
			</p>
			<p>
				<label for="txtDetail">Detail</label>
				<textarea name="txtDetail" id="txtDetail" rows="10" class="formInputWysiwyg"><? echo urldecode($tmpDetail); ?></textarea>
			</p>
            <p>
				<label for="txtPrice">Price</label>
				<input type="text" name="txtPrice" id="txtPrice" maxlength="255" value="<? echo urldecode($tmpPrice); ?>" class="formInputData" />
			</p>
			<p>
				<label for="txtDimensions">Dimensions</label>
				<input type="text" name="txtDimensions" id="txtDimensions" maxlength="255" value="<? echo urldecode($tmpDimensions); ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtSortOrder">Sort Order</label>
				<input type="text" name="txtSortOrder" id="txtSortOrder" maxlength="2" value="<? echo $tmpSortOrder; ?>" class="formInputDataSmall" />
			</p>
        </fieldset>
        <fieldset>
        	<legend>Floor Plan</legend>
            <span style="width:630px; display:inline; float:left;">
            <p>
				<label for="flFilename">Floor Plan:</label>
				<input type="file" name="flFilename" id="flFilename" value="" class="formInputData" />
			</p>
			<?php
				if($tmpFilename != "")
				{
					echo '<p><label></label><a href="/uploads/'.$tmpFilename.'" target="_blank">View current file</a>&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteFile" id="cbxDeleteFile"></p>';
				}
			?>
	        </span>
        </fieldset>
        
            <?php
				for($i=1;$i<=3;$i++)
				{
					echo '<fieldset>
						<legend>Image '.$i.'</legend>
						<span style="width:630px; display:inline; float:left;">
						<p>
						<label for="flThumb'.$i.'">Thumbnail:</label>
						<input type="file" name="flThumb'.$i.'" id="flThumb'.$i.'" value="" class="formInputData" />
					</p>';
					if($tmpThumbs[$i] != "")
					{
						echo '<p><label></label><a href="/uploads/'.$tmpThumbs[$i].'" target="_blank">View current image</a>&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteThumb'.$i.'" id="cbxDeleteThumb'.$i.'"></p>';
					}
					echo '<p>
						<label for="flImage'.$i.'">Main Image:</label>
						<input type="file" name="flImage'.$i.'" id="flImage'.$i.'" value="" class="formInputData" />
					</p>';
					if($tmpImages[$i] != "")
					{
						echo '<p><label></label><a href="/uploads/'.$tmpImages[$i].'" target="_blank">View current image</a>&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteImage'.$i.'" id="cbxDeleteImage'.$i.'"></p>';
					}
					echo '</span></fieldset>';
				}
			?>
		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" />
		</p>
	</form>
	</div>
	<div class="mainAdminRight"> 
     <p>&nbsp;</p>
    </div>
	</div>
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
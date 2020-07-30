<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpTitle = '';
$tmpMetaTitle = '';
$tmpMetaDesc = '';
$tmpContent = '';
$tmpMetaKW = '';
$tmpCMS = 'N';
$tmpSEO = 'N';
$tmpBannerSlots = 0;
$tmpSeoContent = '';
$tmpTitleImage = '';

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

if(isset($_POST["pageId"]))
{
	$tmpId = intval($_POST["pageId"]);
}
elseif(isset($_GET["pageId"]))
{
	$tmpId = intval($_GET["pageId"]);
}
else
{
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtTitle"]))
{
	$formErrors = "";
	$tmpTitle = htmlspecialchars($_POST["txtTitle"]);
	$tmpCMS = $_POST["cms"];
	$tmpSEO = $_POST["seo"];
	$tmpBannerSlots = intval($_POST[bannerSlots]);
	if($tmpCMS == 'Y')
	{
		$tmpContent = htmlspecialchars($_POST["txtContent"]);
		if($tmpContent == '')
		{
			$formErrors .= '<li>Please enter some content</li>';
		}
		$tmpTitleImage = $_POST["oldTitleImage"];
		$fnFile = "flTitleImage";

		if (($_FILES[$fnFile]["type"] == "image/gif") || ($_FILES[$fnFile]["type"] == "image/jpeg") || ($_FILES[$fnFile]["type"] == "image/pjpeg")	&& ($_FILES[$fnFile]["size"] < 20000))
		{
			if ($_FILES[$fnFile]["error"] > 0)
			{
				//echo "Return Code: " . $_FILES[$fnFile]["error"] . "<br />";
				$formErrors .= "<li>There was an error uploading the icon file</li>";
			}
			else
			{
				$tmpTitleImage = $_FILES[$fnFile]["name"];
				/*echo "Upload: " . $_FILES[$fnFile]["name"] . "<br />";
				echo "Type: " . $_FILES[$fnFile]["type"] . "<br />";
				echo "Size: " . ($_FILES[$fnFile]["size"] / 1024) . " Kb<br />";
				echo "Temp file: " . $_FILES[$fnFile]["tmp_name"] . "<br />"; */
				if (file_exists($DOCUMENT_ROOT."/uploads/images/titles/" . $_FILES[$fnFile]["name"]))
				{
					//echo $_FILES[$fnFile]["name"] . " already exists. ";
					//$formErrors .= "<li>The icon file already exists</li>";
					chmod($DOCUMENT_ROOT."/uploads/images/titles/" . $_FILES[$fnFile]["name"],764);
				}
				/*else
				{*/
					move_uploaded_file($_FILES[$fnFile]["tmp_name"],$DOCUMENT_ROOT . "/uploads/images/titles/" . $_FILES[$fnFile]["name"]);
					//echo "Stored in: " . $DOCUMENT_ROOT . "/uploads/images/" . $_FILES[$fnFile]["name"];
				//}
			}
		}
	}
	if($tmpSEO == 'Y')
	{
		$tmpMetaTitle = htmlspecialchars($_POST["txtMetaTitle"]);
		$tmpMetaDesc = htmlspecialchars($_POST["txtMetaDesc"]);
		$tmpMetaKW = htmlspecialchars($_POST["txtMetaKW"]);
		$tmpSeoContent = urlencode($_POST["txtSeoContent"]);
	}
	
	if($formErrors == "")
	{
		/*if($tmpBannerSlots > 0)
		{
			mysql_query("DELETE FROM page_banner WHERE page_id = $tmpId");
			for($i=1;$i<=$tmpBannerSlots;$i++)
			{
				$tmpBannerId = intval($_POST["ddlBanner".$i]);
				if($tmpBannerId > 0)
				{
					mysql_query("INSERT INTO page_banner (page_id,slot_id,banner_id) VALUES ($tmpId,$i,$tmpBannerId)");
				}
			}
		}*/
		mysql_query("UPDATE page SET title = '$tmpTitle', meta_title = '$tmpMetaTitle', meta_desc = '$tmpMetaDesc', content = '$tmpContent', meta_kw = '$tmpMetaKW', seo_content = '$tmpSeoContent', title_image = '$tmpTitleImage', updated = '2008-01-01 12:00:00', updated_by = -1 WHERE id = $tmpId");
		ob_end_clean();
		header("Location: ./");
		exit();
	}
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$dbRsItem = mysql_query("SELECT title,meta_title,meta_desc,content,seo_content,meta_kw,cms,seo,banner_slots,title_image FROM page WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpTitle = $item["title"];
	$tmpMetaTitle = $item["meta_title"];
	$tmpMetaDesc = $item["meta_desc"];
	$tmpContent = $item["content"];
	$tmpSeoContent = urldecode($item["seo_content"]);
	$tmpMetaKW = $item["meta_kw"];
	$tmpCMS = $item["cms"];
	$tmpSEO = $item["seo"];
	$tmpBannerSlots = $item["banner_slots"];
	$tmpTitleImage = $item["title_image"];
}

$pageTitle = "Page Detail";
if($tmpId == 0)
{
	$msgAction = "add a page";
}
else
{
	$msgAction = "update page content";
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
	<form name="frmPage" id="frmPage" method="post" class="formEditor" enctype="multipart/form-data" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="pageId" id="pageId" value="<? echo $tmpId; ?>" />
		<input type="hidden" name="cms" id="cms" value="<? echo $tmpCMS; ?>" />
		<input type="hidden" name="seo" id="seo" value="<? echo $tmpSEO; ?>" />
		<input type="hidden" name="bannerSlots" id="bannerSlots" value="<? echo $tmpBannerSlots; ?>" />
		<input type="hidden" name="oldTitleImage" id="oldTitleImage" value="<? echo $tmpTitleImage; ?>" />
		<fieldset>
			<legend>Page Details</legend>
			<p>
				<label for="txtTitle">Title</label>
				<input type="text" name="txtTitle" id="txtTitle" maxlength="100" value="<? echo $tmpTitle; ?>" class="formInputData" />
			</p>
			<?php
				if($tmpSEO == 'Y')
				{
					echo '<p>
								<label for="txtMetaTitle">META Title</label>
								<input type="text" name="txtMetaTitle" id="txtMetaTitle" maxlength="255" value="'.$tmpMetaTitle.'" class="formInputData" />
							</p>
							<p>
								<label for="txtMetaDesc">META Description</label>
								<textarea name="txtMetaDesc" id="txtMetaDesc" rows="5" class="formInputData">'.$tmpMetaDesc.'</textarea>
							</p>
							<p>
								<label for="txtMetaKW">META Keywords</label>
								<input type="text" name="txtMetaKW" id="txtMetaKW" maxlength="255" value="'.$tmpMetaKW.'" class="formInputData" />
							</p>
							<p>
								<label for="txtSeoContent">SEO Content</label>
								<textarea name="txtSeoContent" id="txtSeoContent" rows="10" class="formInputData">'.urldecode($tmpSeoContent).'</textarea>
							</p>';
				}
				if($tmpCMS == 'Y')
				{
					echo '<p>
							<label for="txtContent">Content</label>
							<textarea name="txtContent" id="txtContent" rows="10" class="formInputWysiwyg">'.$tmpContent.'</textarea>
						</p>';
					echo '<p>
								<label for="flTitleImage">Title Image:</label>
								<input type="file" name="flTitleImage" id="flTitleImage" value="" class="formInputData" />
							</p>';
						if($tmpTitleImage != "")
						{
							echo '<p><label for="cbxDeleteTitleImage">Current Image:</label><img src="/uploads/images/titles/'.$tmpTitleImage.'">
									&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteTitleImage" id="cbxDeleteIcon"></p>';
						}
				}
			?>
		</fieldset>
		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" />
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
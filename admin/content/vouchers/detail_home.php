<?
$pageTitle = "Page Detail";
$pageSection = "2";
$subSection = "1";
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
$tmpProdSlots = 0;
$tmpSeoContent = '';
$tmpTitleImage = '';
$tmpBanners = array();
$tmpLinks = array();
$tmpProds = array();

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
	$tmpTitle = htmlentities($_POST["txtTitle"],ENT_QUOTES);
	$tmpCMS = $_POST["cms"];
	$tmpSEO = $_POST["seo"];
	$tmpBannerSlots = intval($_POST[bannerSlots]);
	$tmpProdSlots = intval($_POST[prodSlots]);
	if($tmpCMS == 'Y')
	{
		$tmpContent = htmlentities($_POST["txtContent"],ENT_QUOTES);
		//echo $tmpContent;exit();
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
		
		$counter1 = 0;
		$counter2 = 0;
		//var_dump($_POST["selBanners"]);
		if(isset($_POST["selBanners"]))
		{
			if(is_array($_POST["selBanners"]))
			{
				foreach($_POST["selBanners"] as $value)
				{
					if($value > 0)
					{
						$slot_id = $counter1 + 1;
						$tmpBanners[$counter2] = array($slot_id,$value);
						$counter2++;
					}
					$counter1++;
				}
			}
		}
		
		$counter = 0;
		if(isset($_POST["cbxLinks"]))
		{
			if(is_array($_POST["cbxLinks"]))
			{
				foreach($_POST["cbxLinks"] as $value)
				{
					$tmpLinks[$counter] = $value;
					$counter++;
				}
			}
		}
	}
	$counter1 = 0;
	$counter2 = 0;
	//var_dump($_POST["selProds"]);
	if(isset($_POST["selProds"]))
	{
		if(is_array($_POST["selProds"]))
		{
			foreach($_POST["selProds"] as $value)
			{
				if($value > 0)
				{
					$slot_id = $counter1 + 1;
					$tmpProds[$counter2] = array($slot_id,$value);
					$counter2++;
				}
				$counter1++;
			}
		}
	}
	if($tmpSEO == 'Y')
	{
		$tmpMetaTitle = htmlentities($_POST["txtMetaTitle"],ENT_QUOTES);
		$tmpMetaDesc = htmlentities($_POST["txtMetaDesc"],ENT_QUOTES);
		$tmpMetaKW = htmlentities($_POST["txtMetaKW"],ENT_QUOTES);
		$tmpSeoContent = htmlentities($_POST["txtSeoContent"],ENT_QUOTES);
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
		$strSQL = "UPDATE sm_page SET title = '$tmpTitle', meta_title = '$tmpMetaTitle', meta_desc = '$tmpMetaDesc', content = '$tmpContent', meta_kw = '$tmpMetaKW', seo_content = '$tmpSeoContent', title_image = '$tmpTitleImage', updated = '2008-01-01 12:00:00', updated_by = -1 WHERE id = $tmpId";
		mysql_query($strSQL);
		
		$strSQL = "DELETE FROM sm_page_banner WHERE page_id = $tmpId";
		mysql_query($strSQL);
		
		//var_dump($tmpBanners);
		if(is_array($tmpBanners))
		{
			if($tmpId == 0)
			{
				$tmpId = mysql_insert_id();
			}
			$counter = 1;
			foreach($tmpBanners as $value)
			{
				$strSQL = "INSERT INTO sm_page_banner (page_id, slot_id, banner_id) VALUES (".$tmpId.",".$value[0].",".$value[1].")";
				mysql_query($strSQL);
				$counter++;
				//echo "<p>".$strSQL."</p>";
			}
			//exit();
		}
		
		$strSQL = "DELETE FROM sm_page_prod WHERE page_id = $tmpId";
		mysql_query($strSQL);
		
		//var_dump($tmpProds);
		if(is_array($tmpProds))
		{
			if($tmpId == 0)
			{
				$tmpId = mysql_insert_id();
			}
			$counter = 1;
			foreach($tmpProds as $value)
			{
				$strSQL = "INSERT INTO sm_page_prod (page_id, slot_id, prod_id) VALUES (".$tmpId.",".$value[0].",".$value[1].")";
				mysql_query($strSQL);
				$counter++;
				//echo "<p>".$strSQL."</p>";
			}
			//exit();
		}
		
		$strSQL = "DELETE FROM sm_page_link WHERE page_id = $tmpId";
		mysql_query($strSQL);
		
		
		if(is_array($tmpLinks))
		{
			if($tmpId == 0)
			{
				$tmpId = mysql_insert_id();
			}
			foreach($tmpLinks as $value)
			{
				$strSQL = "INSERT INTO sm_page_link (page_id, link_id) VALUES (".$tmpId.",".$value.")";
				mysql_query($strSQL);
				//echo "<p>".$strSQL."</p>";
			}
			//exit();
		}
		
		ob_end_clean();
		header("Location: ./");
		exit();
	}
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$dbRsItem = mysql_query("SELECT title,meta_title,meta_desc,content,seo_content,meta_kw,cms,seo,banner_slots,title_image, sm_prod_slots FROM page WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpTitle = $item["title"];
	$tmpMetaTitle = $item["meta_title"];
	$tmpMetaDesc = $item["meta_desc"];
	$tmpContent = $item["content"];
	$tmpSeoContent = $item["seo_content"];
	$tmpMetaKW = $item["meta_kw"];
	$tmpCMS = $item["cms"];
	$tmpSEO = $item["seo"];
	$tmpBannerSlots = $item["banner_slots"];
	$tmpTitleImage = $item["title_image"];
	$tmpProdSlots = $item["prod_slots"];
	
	$strSQL = "SELECT banner_id, slot_id FROM sm_page_banner WHERE page_id = $tmpId ORDER BY slot_id";
	$dbRsPageBanners = mysql_query($strSQL);
	$counter =0;
	while($item = mysql_fetch_array($dbRsPageBanners))
	{
		$tmpBanners[$counter] = array($item["slot_id"],$item["banner_id"]);
		$counter++;
	}
	
	$strSQL = "SELECT prod_id, slot_id FROM sm_page_prod WHERE page_id = $tmpId ORDER BY slot_id";
	$dbRsPageProds = mysql_query($strSQL);
	$counter =0;
	while($item = mysql_fetch_array($dbRsPageProds))
	{
		$tmpProds[$counter] = array($item["slot_id"],$item["prod_id"]);
		$counter++;
	}
	
	$strSQL = "SELECT link_id FROM sm_page_link WHERE page_id = $tmpId ORDER BY link_id";
	$dbRsPageLinks = mysql_query($strSQL);
	$counter =0;
	while($item = mysql_fetch_array($dbRsPageLinks))
	{
		$tmpLinks[$counter] = $item["link_id"];
		$counter++;
	}
}

$strSQL = "SELECT id, description FROM sm_banner WHERE status = 'A' ORDER BY description";
$dbRsBanners = mysql_query($strSQL);

/*$strSQL = "SELECT id, title, reference FROM product WHERE status = 'A' ORDER BY reference";
$dbRsProds = mysql_query($strSQL);

$strSQL = "SELECT id, title FROM page WHERE status = 'A' AND id <> $tmpId ORDER BY title";
$dbRsLinks = mysql_query($strSQL);*/

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
        <input type="hidden" name="txtSeoContent" id="txtSeoContent" value="" />
		<input type="hidden" name="bannerSlots" id="bannerSlots" value="<? echo $tmpBannerSlots; ?>" />
        <input type="hidden" name="prodSlots" id="prodSlots" value="<? echo $tmpProdSlots; ?>" />
		<input type="hidden" name="oldTitleImage" id="oldTitleImage" value="<? echo $tmpTitleImage; ?>" />
		<fieldset>
			<legend>Page Details</legend>
			<p>
				<label for="txtTitle">Title</label>
				<input type="text" name="txtTitle" id="txtTitle" maxlength="100" value="<? echo urldecode($tmpTitle); ?>" class="formInputData" />
			</p>
			<?php
				if($tmpSEO == 'Y')
				{
					echo '<p>
								<label for="txtMetaTitle">META Title</label>
								<input type="text" name="txtMetaTitle" id="txtMetaTitle" maxlength="255" value="'.urldecode(html_entity_decode($tmpMetaTitle,ENT_QUOTES)).'" class="formInputData" />
							</p>
							<p>
								<label for="txtMetaDesc">META Description</label>
								<textarea name="txtMetaDesc" id="txtMetaDesc" rows="5" class="formInputData">'.urldecode(html_entity_decode($tmpMetaDesc,ENT_QUOTES)).'</textarea>
							</p>
							<p>
								<label for="txtMetaKW">META Keywords</label>
								<input type="text" name="txtMetaKW" id="txtMetaKW" maxlength="255" value="'.urldecode(html_entity_decode($tmpMetaKW,ENT_QUOTES)).'" class="formInputData" />
							</p>';
				/*echo '<p>
								<label for="txtSeoContent">SEO Content</label>
								<textarea name="txtSeoContent" id="txtSeoContent" rows="10" class="formInputData">'.urldecode(html_entity_decode($tmpSeoContent,ENT_QUOTES)).'</textarea>
							</p>';*/
				}
				if($tmpCMS == 'Y')
				{
					echo '<p>
							<label for="txtContent">Content</label></p>
							<p><textarea name="txtContent" id="txtContent" rows="10" class="formInputWysiwyg">'.urldecode(html_entity_decode($tmpContent,ENT_QUOTES)).'</textarea>
						</p>';
					/*echo '<p>
								<label for="flTitleImage">Title Image:</label>
								<input type="file" name="flTitleImage" id="flTitleImage" value="" class="formInputData" />
							</p>';*/
						/*if($tmpTitleImage != "")
						{
							echo '<p><label for="cbxDeleteTitleImage">Current Image:</label><img src="/uploads/images/titles/'.$tmpTitleImage.'">
									&nbsp;Delete?&nbsp;<input type="checkbox" name="cbxDeleteTitleImage" id="cbxDeleteIcon"></p>';
						}*/
					
				}
			?>
		</fieldset>
        <?php
			/*if($tmpCMS == 'Y')
			{*/
				if($tmpBannerSlots > 0)
				{
					echo '<fieldset>
							<legend>Banners</legend>';
					for($i = 1; $i <= $tmpBannerSlots; $i++)
					{
						if(mysql_num_rows($dbRsBanners) > 0)
						{
							mysql_data_seek($dbRsBanners,0);
						}
						echo '<p><label for="selBanners">Banner '.$i.'</label>
							<select name="selBanners[]" id="selBanners[]" class="formInputData">
							<option value="0">-- None --</option>';
							while($item1 = mysql_fetch_array($dbRsBanners))
							{
								$selected = "";
								$counter = 0;
								foreach($tmpBanners as $value)
								{
									if(is_array($value))
									{
										if(($value[0] == $i) && ($value[1] == $item1["id"]))
										{
											$selected = "selected";
											break;
										}
									}
									$counter++;
								}
								/*if(mysql_num_rows($dbRsPageBanners) > 0)
								{
									mysql_data_seek($dbRsPageBanners,0);
								}
								while($item2 = mysql_fetch_array($dbRsPageBanners))
								{
									if(($i == $item2["slot_id"]) && ($item1["id"] == $item2["banner_id"]))
									{
										$selected = "selected";
										break;
									}
								}*/
								echo '<option '.$selected.' value="'.$item1["id"].'">'.$item1["description"].'</option>';
							}
						echo '</select>';
					}
					echo '</fieldset>';
				}
				/*if($tmpProdSlots > 0)
				{
					echo '<fieldset>
							<legend>Products</legend>';
					for($i = 1; $i <= $tmpProdSlots; $i++)
					{
						if(mysql_num_rows($dbRsProds) > 0)
						{
							mysql_data_seek($dbRsProds,0);
						}
						echo '<p><label for="selProds">Prod '.$i.'</label>
							<select name="selProds[]" id="selProds[]" class="formInputData">
							<option value="0">-- None --</option>';
							while($item1 = mysql_fetch_array($dbRsProds))
							{
								$selected = "";
								$counter = 0;
								foreach($tmpProds as $value)
								{
									if(is_array($value))
									{
										if(($value[0] == $i) && ($value[1] == $item1["id"]))
										{
											$selected = "selected";
											break;
										}
									}
									$counter++;
								}
								echo '<option '.$selected.' value="'.$item1["id"].'">'.$item1["reference"]." ".$item1["title"].'</option>';
							}
						echo '</select>';
					}
					echo '</fieldset>';
				}*/
			//}
		?>
		<div style="display:block; margin:5px 0 5px 0;">
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
		</div>
	</form>
	</div>
</div>	
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
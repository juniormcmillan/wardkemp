<?
define('hasEditor','true');

$datePicker	=	true;
$tmpDate = $_SERVER["REQUEST_TIME"];
$tmpDisplayDate = date("Y-m-d",$tmpDate);
$tmpDay = date("d");
$tmpMonth = date("m");
$tmpYear = date("Y");

$pageTitle = "News Post  Detail";
$pageSection = "2";
$subSection = "2";
#include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpTitle = '';
$tmpSubTitle = '';
$tmpMetaTitle = '';
$tmpMetaSubTitle = '';
$tmpMetaDesc = '';
$tmpMetaVersionOf = '';
$tmpMetaCanonical = '';
$tmpContent = '';
$tmpBreadcrumb = '';
$tmpCMS = 'N';
$tmpSEO = 'N';
$tmpBannerSlots = 0;
$tmpProdSlots = 0;
$tmpTopBannerSlots = 0;
$tmpSeoContent = '';
$tmpTitleImage = '';
$tmpBanners = array();
$tmpTopBanners = array();
$tmpLinks = array();
$tmpBoxes = array();
$tmpBoxCount = 0;
$tmpRelated = "N";

#require($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/lib_mysql.php");
#require($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/common.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$gMysql				=	new Mysql_Library();


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/admin/includes/phpspellcheck/include.php");


$mySpell = new SpellCheckButton();
$mySpell->InstallationPath = "/admin/includes/phpspellcheck/";
$mySpell->Fields = "EDITORS";
$spellcheck_button	=	$mySpell->SpellImageButton();


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
	//	ob_end_clean();
	//	header("Location: ./");
	//	exit();
}




if	(isset($_POST['btnDelete']) || isset($_GET['btnDelete']))
{

	#	$strSQL = "delete from sm_post_banner  where page_id = $tmpId";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	#	$strSQL = "delete from sm_post_top_banner  where page_id = $tmpId";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	#	$strSQL = "delete from sm_post_link where page_id = $tmpId ";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	#	$strSQL = "delete from sm_post_box where page_id = $tmpId";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	$strSQL = "delete from sm_post where id= $tmpId";
	$gMysql->Delete($strSQL,__FILE__,__LINE__);

	ob_end_clean();
	header("Location: ./");
	exit();

}





# cm 28/08/15



# database query for items
$item					=	$gMysql->queryRow("select * from sm_post where id='$tmpId'",__FILE__,__LINE__);

# arrays for options
$status_array				=	array('A','N');
$status_name_array			=	array('Active','Not Active');
$mvc_status 				=	GetVariableString("mvc_status",$_POST,"",$item['status']);
$dropdown_status			=	buildSelectBox("mvc_status",$mvc_status,$status_array,$status_name_array,"myclass","Status");



$author_array		=	array(0,1,2,3);
$author_name_array	=	array('Rosemary Garstang','Michelle Nicoll','Robin Selley','Daniel Morris','Kirsten Roberts','Simon Pinner','Roger Orwin','Box Legal' );
$author 			=	GetVariableString("author",$_POST,"",$item['author']);
$dropdown_author	=	buildSelectBox("author",$author,$author_name_array,$author_name_array,"myclass","Author");




$dropdown_admin_only	=	buildSelectBox("mvc_admin_only",$mvc_admin_only,$admin_only_id_array,$admin_only_name_array,"myclass","Admin Only?");


#$story_image 			=	"/app/images/blog/" . $item['story_image'];
$tmpStory_image 		=	$item['story_image'];




# posting
if(isset($_POST["txtTitle"]))
{
	$formErrors = "";
	$tmpTitle = htmlentities($_POST["txtTitle"],ENT_QUOTES);
	$tmpTitleSEO = htmlentities($_POST["txtTitleSEO"],ENT_QUOTES);
	$tmpPrettyLink = htmlentities($_POST["txtPrettyLink"],ENT_QUOTES);
	$tmpHeader_Title = htmlentities($_POST["txtHeader_Title"],ENT_QUOTES);
	$tmpHeader_SubTitle = htmlentities($_POST["txtHeader_SubTitle"],ENT_QUOTES);




	# recent ?
	if	(strtotime($item['published']) > strtotime('2017-01-27 00:00:00'))
	{
		$tmpContent = htmlentities($_POST["txtContent"],ENT_QUOTES);
	}
	# wordpress posts with nonstandard formatting
	else
	{
	#	$_POST["txtContent"]	=	strip_tags($_POST["txtContent"], '<br />');
	#	$_POST["txtContent"]	=	strip_tags($_POST["txtContent"], '<br  />');
	#	$_POST["txtContent"]	=	strip_tags($_POST["txtContent"], '</br>');
	#	$_POST["txtContent"]	=	strip_tags($_POST["txtContent"], '<br>');

#		$string = htmlentities($_POST["txtContent"], null, 'utf-8');
		$_POST["txtContent"]	=	mb_str_replace("<br>", "", $_POST["txtContent"]);
		$_POST["txtContent"]	=	mb_str_replace('<br />', "", $_POST["txtContent"]);

	#	$_POST["txtContent"] = preg_replace('/<br \/>/', '', $_POST["txtContent"], 1);

		$_POST["txtContent"]	= preg_replace("/<br \/>/","",$_POST["txtContent"]);

		#$tmpContent = html_entity_decode($_POST["txtContent"],ENT_QUOTES);


		$tmpContent = $_POST["txtContent"];



	}






	$tmpSnippet = htmlentities($_POST["snippet"],ENT_QUOTES);
	$tmpAuthor 	= htmlentities($_POST["author"],ENT_QUOTES);
#	$tmpStory_image 	= htmlentities($_POST["story_image"],ENT_QUOTES);


	$valid_file	=	true;




	$tmpMetaTitle = htmlentities($_POST["txtMetaTitle"],ENT_QUOTES);
	$tmpMetaDesc = htmlentities($_POST["txtMetaDesc"],ENT_QUOTES);
	$tmpBreadcrumb = htmlentities($_POST["txtBreadcrumb"],ENT_QUOTES);

	$tmpDisplayDate = $_POST["displayDate"];


	$strSQL = "UPDATE sm_post SET 
	
	header_title		=	'$tmpHeader_Title',
	header_subtitle		=	'$tmpHeader_SubTitle',
	title 				= 	'$tmpTitle',   
	ref					=	'$tmpPrettyLink',
	author				=	'$tmpAuthor',
	status				=	'$mvc_status',
	meta_title 			= 	'$tmpMetaTitle', 
	meta_desc 			= 	'$tmpMetaDesc', 
	content 			= 	'$tmpContent', 
	content_snippet 	= 	'$tmpSnippet', 
	meta_breadcrumb_text = 	'$tmpBreadcrumb', 
	published			=	'$tmpDisplayDate',
	story_image			=	'$tmpStory_image',
	
	
	updated 			= NOW() 
		
	WHERE id = $tmpId";
#	mysql_query($strSQL);
	$gMysql->update($strSQL,__FILE__,__LINE__);



	ob_end_clean();
	header("Location: ./");
	exit();

}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$strSQL		=	"SELECT * FROM sm_post WHERE id = " . $tmpId;
	$item	=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);


	$tmpTitle = $item["title"];
	$tmpSubTitle = $item["subtitle"];
	$tmpTitleSEO = $item["title_seo"];
	$tmpPrettyLink	=	$item["ref"];

	# test for SEO ate-blog
	if (stripos($tmpPrettyLink, "ate-blog") === false)
	{
		$tmpPrettyLink	=	"ate-blog/"	.	$tmpPrettyLink;
	}


	$tmpMetaTitle = $item["meta_title"];
	$tmpMetaDesc = $item["meta_desc"];



	$tmpHeader_Title  = $item["header_title"];
	$tmpHeader_SubTitle = $item["header_subtitle"];



	# recent ?
	if	(strtotime($item['published']) > strtotime('2017-01-27 00:00:00'))
	{
		$tmpContent = $item["content"];
	}
	# wordpress posts with nonstandard formatting
	else
	{
		$tmpContent = nl2br(htmlspecialchars_decode ($item["content"], ENT_QUOTES) );
		$tmpContent = nl2br(html_entity_decode($item["content"], ENT_QUOTES));
	}



	$tmpSnippet = $item["content_snippet"];
	$tmpBreadcrumb = $item["meta_breadcrumb_text"];
	$tmpRelated = $item["related"];
	$tmpSectionId = $item["section_id"];
	$tmpProdSlots  = $item["prod_slots"];
	$tmpMetaCanonical = $item["meta_canonical"];

	$tmpDate = strtotime($item["published"]);
	$tmpDisplayDate = date("Y-m-d",$tmpDate);

	$tmpStory_image = $item['story_image'];


}

$story_image	=


$pageTitle = "News Post";
if($tmpId == 0)
{
	$msgAction = "add a page";
}
else
{
	$msgAction = "update news post";
}
?>

<div class="mainAdminPage">


<div class="container-fluid">
	<h1><? echo $pageTitle ?></h1>
	<?
	if($formErrors != "")
	{
		echo "<p><strong>ERROR:</strong></p><ul>$formErrors</ul>";
	}
	?>
	<div class="col-xs-12">
		<p>Use this form to <? echo $msgAction; ?></p>
		<form name="frmPage" id="frmPage" method="post" class="formEditor" enctype="multipart/form-data"  onsubmit="doCopyTextDiv()">
			<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
			<input type="hidden" name="pageId" id="pageId" value="<? echo $tmpId; ?>" />
			<input type="hidden" name="cms" id="cms" value="<? echo $tmpCMS; ?>" />
			<input type="hidden" name="seo" id="seo" value="<? echo $tmpSEO; ?>" />
			<input type="hidden" name="txtSeoContent" id="txtSeoContent" value="" />
			<input type="hidden" name="bannerSlots" id="bannerSlots" value="<? echo $tmpBannerSlots; ?>" />
			<input type="hidden" name="topBannerSlots" id="topBannerSlots" value="<? echo $tmpTopBannerSlots; ?>" />
			<input type="hidden" name="boxCount" id="boxCount" value="<? echo $tmpBoxCount; ?>" />
			<input type="hidden" name="section_id" id="section_id" value="<? echo $tmpSectionId; ?>" />
			<input type="hidden" name="prodSlots" id="prodSlots" value="<? echo $tmpProdSlots; ?>" />
			<input type="hidden" name="oldTitleImage" id="oldTitleImage" value="<? echo $tmpTitleImage; ?>" />
			<input type="hidden" name="flTitleImage" id="flTitleImage" value="<? echo $tmpTitleImage; ?>" />
			<input type="hidden" name="txtContentHidden" id="txtContentHidden" value="" />
			<input type="hidden" name="story_image" id="txtContentHidden" value="<? echo $tmpStory_image; ?>" />
			<fieldset>
				<legend>Post Details</legend>
				<p>
					<label for="txtTitle">Title</label>
					<input type="text" name="txtTitle" id="txtTitle" maxlength="100" value="<? echo urldecode($tmpTitle); ?>" class="formInputData" />
				</p>

				<p>
					<label for="txtPrettyLink">SEO link to page<br>( "ate-blog" will be prepended)</label>
					<input type="text" name="txtPrettyLink" id="txtPrettyLink" maxlength="100" value="<? echo urldecode($tmpPrettyLink); ?>" class="formInputData" />
				</p>

				<p>
					<label for="displayDate">Published</label>
					<script>DateInput("displayDate", true, "YYYY-MM-DD",'<?php echo $tmpDisplayDate; ?>')</script>
					<br>
				</p>

				<?php
				{
					echo '

							<p>
								<label for="txtMetaDesc">SEO META Description</label>
								<textarea name="txtMetaDesc" id="txtMetaDesc" rows="2" class="formInputData">'.urldecode(html_entity_decode($tmpMetaDesc,ENT_QUOTES)).'</textarea>
							</p>




							<p>'.	$dropdown_author	.'
							</p>

							
							
	
		
				




							<br>

							<p>'.	$dropdown_status	.'
							</p>


							</fieldset>
							<br>
							<br>
							<br>

							';

					echo '<p>
							<label for="txtSummary">Summary</label></p>
							<p><textarea name="snippet" id="snippet" rows="5" style="width:50%;">'.html_entity_decode($tmpSnippet,ENT_QUOTES).'</textarea>
							<br>
						</p>';
					echo '<p>
							<label for="txtContent">Content</label></p>
							<p><textarea name="txtContent" id="txtContent" rows="5" class="formInputWysiwyg">'.html_entity_decode($tmpContent,ENT_QUOTES).'</textarea>
						</p>';
				}
				?>


			</fieldset>
			<div style="clear:both;">

				<br>
				<span style="font-size:large;font-weight:bold;">
					<? echo "To spellcheck click this button: ". $spellcheck_button; ?>
				</span>

				<br><br>


				<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
				<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
				<input type="submit" name="btnDelete" id="btnDelete" value="Delete" class="formButton" />

				<br><br>

				<br><br>



				</div>
		</form>
	</div>
</div>

<script>


	$("#avatar").change(function() {


		var myData = new FormData();
		myData.append('file', $('#avatar').prop('files')[0]);

		myData.append('id', $('#pageId').val());


		$.ajax({
			url: "doUploadImage.php",
			type: "post",
			dataType: 'json',
			processData: false,
			contentType: false,
			data: myData,


		success:
			function(data)
			{
				if	(data.returncode == 'error')
				{
					alert('error uploading image');

					$.alert({
						title: 'Attention!',
						confirmButton: 'Ok',
						content: data.message,
					});
				}
				else if (data.returncode == 'success')
				{
					$("#banner_image_1").html($("<img/>", { src: "/app/images/blog/" + data.image , width: "400"} ));



				}
			}
	});



	});




</script>

<?



include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");

?>


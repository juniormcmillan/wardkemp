<?
define('hasEditor','true');



$pageTitle = "Page Detail";
$pageSection = "2";
$subSection = "1";
#include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpTitle = '';
$tmpAlias = '';
$tmpSubTitle = '';
$tmpMetaTitle = '';
$tmpMetaSubTitle = '';
$tmpMetaDesc = '';
$tmpMetaVersionOf = '';
$tmpMetaCanonical = '';
$tmpContent = '';
$tmpMetaKW = '';
$tmpBreadcrumb = '';
$tmpTag = '';

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
#$gMysql->query(" SET NAMES utf8",__FILE__,__LINE__);

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

	#	$strSQL = "delete from sm_page_banner  where page_id = $tmpId";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	#	$strSQL = "delete from sm_page_top_banner  where page_id = $tmpId";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	#	$strSQL = "delete from sm_page_link where page_id = $tmpId ";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	#	$strSQL = "delete from sm_page_box where page_id = $tmpId";
	#	$gMysql->Delete($strSQL,__FILE__,__LINE__);
	$strSQL = "delete from sm_page where id= $tmpId";
	$gMysql->Delete($strSQL,__FILE__,__LINE__);

	ob_end_clean();
	header("Location: ./");
	exit();

}





# cm 28/08/15

# arrays for options
$status_array		=	array('A','N');
$status_name_array	=	array('Active','Not Active');
$method_array		=	array('','get','post');

# this can be built on the fly later
$action_array		=	array('render','post','update','delete');

$template_array		=	array('index.html');
$language_array		=	array('en','es');
$data_array		=	$gMysql->selectToArray("select title,id from sm_page where language='en' and status='A'",__FILE__,__LINE__);
$version_name_array	=	array();
$version_id_array	=	array();

foreach($data_array as $data)
{
	$version_name_array[]	=	$data['title'];
	$version_id_array[]		=	$data['id'];
}

# admin only pages
$admin_only_name_array	=	array('No','Yes');
$admin_only_id_array	=	array(0,1);



# database query for items
$item					=	$gMysql->queryRow("select * from sm_page where id='$tmpId'",__FILE__,__LINE__);


# this will get the names of all the images
$directory 			=	DOCUMENT_ROOT	.	'images/backdrop';
$scanned_directory	=	array_diff(scandir($directory), array('..', '.'));

# we should have a table of all images
$remove_array		=	array();				#array('error','base');
$add_array			=	array('Nothing');		#array('error','base');
$banner_array		=	array_values(array_diff($scanned_directory, $remove_array));
# prepend data
$banner_array		=	array_merge($add_array,$banner_array);


$mvc_banner_1 				=	GetVariableString("mvc_banner_1",$_POST,"",$item['mvc_banner_1']);
$mvc_banner_2 				=	GetVariableString("mvc_banner_2",$_POST,"",$item['mvc_banner_2']);
$mvc_banner_3 				=	GetVariableString("mvc_banner_3",$_POST,"",$item['mvc_banner_3']);

$dropdown_banner_1			=	buildSelectBox("mvc_banner_1",$mvc_banner_1,$banner_array,$banner_array,"myclass","Choose Banner 1");
$dropdown_banner_2			=	buildSelectBox("mvc_banner_2",$mvc_banner_2,$banner_array,$banner_array,"myclass","Choose Banner 2");
$dropdown_banner_3			=	buildSelectBox("mvc_banner_3",$mvc_banner_3,$banner_array,$banner_array,"myclass","Choose Banner 3");


if	((strcasecmp($mvc_banner_1,"") != 0) && (strcasecmp($mvc_banner_1,"nothing") != 0))
{
	$image_banner_1			=	"<img src='/app/images/backdrop/" . $mvc_banner_1 . "' width=600>";
}
if	((strcasecmp($mvc_banner_2,"") != 0) && (strcasecmp($mvc_banner_2,"nothing") != 0))
{
	$image_banner_2			=	"<img src='/app/images/backdrop/" . $mvc_banner_2. "' width=600>";
}
if	((strcasecmp($mvc_banner_3,"") != 0) && (strcasecmp($mvc_banner_3,"nothing") != 0))
{
	$image_banner_3			=	"<img src='/app/images/backdrop/" . $mvc_banner_3. "' width=600>";
}



# this will get the names of all the images
$directory 			=	DOCUMENT_ROOT	.	'images/banners';
$scanned_directory	=	array_diff(scandir($directory), array('..', '.'));

# we should have a table of all images
$remove_array		=	array();				#array('error','base');
$add_array			=	array('Nothing');		#array('error','base');
$banner_array		=	array_values(array_diff($scanned_directory, $remove_array));
# prepend data
$banner_array		=	array_merge($add_array,$banner_array);


$feature_title 		=	GetVariableString("feature_title",$_POST,"",$item['feature_title']);
$feature_content 	=	GetVariableString("feature_content",$_POST,"",$item['feature_content']);
$feature_link	 	=	GetVariableString("feature_link",$_POST,"",$item['feature_link']);
$feature_banner 	=	GetVariableString("feature_banner",$_POST,"",$item['feature_banner']);

#$feature_content	=	str_replace(array('\r\n', '\r', '\n'), '</br>', $feature_content);

$dropdown_feature_banner	=	buildSelectBox("feature_banner",$feature_banner,$banner_array,$banner_array,"myclass","Choose Feature Banner");

if	((strcasecmp($feature_banner,"") != 0) && (strcasecmp($feature_banner,"nothing") != 0))
{
	$image_feature_banner		=	"<img src='/app/images/banners/" . $feature_banner. "' width=600>";
}



# what to have (slider or banner)
$slider_array			=	array('Nothing','Banner','Slider');
$mvc_slider 			=	GetVariableString("mvc_slider",$_POST,"",$item['mvc_slider']);
$dropdown_slider		=	buildSelectBox("mvc_slider",$mvc_slider,$slider_array,$slider_array,"myclass","Choose Banner Type");




# this will get the names of all the controllers
$directory 			=	DOCUMENT_ROOT	.	'mvc';
$scanned_directory	=	array_diff(scandir($directory), array('..', '.'));

# we can remove any controllers we do not want
$remove_array		=	array();		#array('error','base');
$controller_array	=	array_values(array_diff($scanned_directory, $remove_array));


# grab the data for the state of the variables
$mvc_header 			=	GetVariableString("mvc_header",$_POST,"",$item['mvc_header']);
$mvc_footer 			=	GetVariableString("mvc_footer",$_POST,"",$item['mvc_footer']);
$mvc_controller 		=	GetVariableString("mvc_controller",$_POST,"",$item['mvc_controller']);
$mvc_method 			=	GetVariableString("mvc_method",$_POST,"",$item['mvc_method']);
$mvc_action 			=	GetVariableString("mvc_action",$_POST,"",$item['mvc_action']);
$mvc_template 			=	GetVariableString("mvc_template",$_POST,"",$item['mvc_template']);
$mvc_language 			=	GetVariableString("mvc_language",$_POST,"",$item['language']);
$mvc_version_of			=	GetVariableString("mvc_version_of",$_POST,"",$item['version_of']);
$mvc_status				=	GetVariableString("mvc_status",$_POST,"",$item['status']);
$mvc_admin_only			=	GetVariableString("mvc_admin_only",$_POST,"",$item['admin_only']);


# this is for methods/actions
$remove_action_array	=	array('__construct','__destruct','appendParams','prependTags','appendTags','checkAppendPopup','getTemplate','getHeader','getFooter');
$action_array			=	buildClassMethods($mvc_controller,$remove_action_array);
$dropdown_action		=	buildSelectBox("mvc_action",$mvc_action,$action_array,$action_array,"myclass","Choose Action ");

#$dropdown_header		=	buildSelectBox("mvc_header",$mvc_header,$header_array,$header_array,"myclass","Choose Header");
#$dropdown_footer		=	buildSelectBox("mvc_footer",$mvc_footer,$footer_array,$footer_array,"myclass","Choose Footer");
$dropdown_controller	=	buildSelectBox("mvc_controller",$mvc_controller,$controller_array,$controller_array,"myclass","Choose Controller","pages","doGetClassFunctions()");
$dropdown_method		=	buildSelectBox("mvc_method",$mvc_method,$method_array,$method_array,"myclass","Choose Method");


# this will get the names of all the templates in the directory
$directory 				=	DOCUMENT_ROOT	.	'templates/';
$template_array			=	file_list($directory,".html");
$dropdown_header		=	buildSelectBox("mvc_header",$mvc_header,$template_array,$template_array,"myclass","Choose Header");

# this will get the names of all the templates in the directory
$directory 				=	DOCUMENT_ROOT	.	'templates/';
$template_array			=	file_list($directory,".html");
$dropdown_footer		=	buildSelectBox("mvc_footer",$mvc_footer,$template_array,$template_array,"myclass","Choose Footer");


#$header_array		=	array('header_page.html','header_home.html');
#$footer_array		=	array('footer_home.html');



# this will get the names of all the templates in the directory
$directory 				=	DOCUMENT_ROOT	.	'mvc/'. $mvc_controller . '/';
$template_array			=	file_list($directory,".html");
$dropdown_template		=	buildSelectBox("mvc_template",$mvc_template,$template_array,$template_array,"myclass","Choose Base Template");


$dropdown_language		=	"";
$dropdown_version_of	=	buildSelectBox("mvc_version_of",$mvc_version_of,$version_id_array,$version_name_array,"myclass","Version Of");

$dropdown_status		=	buildSelectBox("mvc_status",$mvc_status,$status_array,$status_name_array,"myclass","Status");

$dropdown_admin_only	=	buildSelectBox("mvc_admin_only",$mvc_admin_only,$admin_only_id_array,$admin_only_name_array,"myclass","Admin Only?");







# posting
if(isset($_POST["txtTitle"]))
{
	$formErrors = "";
	$tmpTitle = htmlentities($_POST["txtTitle"],ENT_QUOTES);
	$tmpAlias = htmlentities($_POST["txtAlias"],ENT_QUOTES);
	$tmpSubTitle = htmlentities($_POST["txtSubTitle"],ENT_QUOTES);
	$tmpTitleSEO = htmlentities($_POST["txtTitleSEO"],ENT_QUOTES);
	$tmpPrettyLink = htmlentities($_POST["txtPrettyLink"],ENT_QUOTES);


	$tmpBannerSlots = intval($_POST["bannerSlots"]);
	$tmpTopBannerSlots = intval($_POST["topBannerSlots"]);
	$tmpBoxCount = intval($_POST["boxCount"]);
	$tmpContent = htmlentities($_POST["txtContent"],ENT_QUOTES);
//	$tmpContent = htmlentities($_POST["txtContentHidden"],ENT_QUOTES);


	#	if($tmpContent == '')
	#	{
	#		$formErrors .= '<li>Please enter some content</li>';
	#	}
	$tmpMetaTitle = htmlentities($_POST["txtMetaTitle"],ENT_QUOTES);
	$tmpMetaDesc = $_POST["txtMetaDesc"];


	$tmpMetaKW = htmlentities($_POST["txtMetaKW"],ENT_QUOTES);
	$tmpBreadcrumb = htmlentities($_POST["txtBreadcrumb"],ENT_QUOTES);
	$tmpTag = htmlentities($_POST["txtTag"],ENT_QUOTES);


	$tmpMetaCanonical= htmlentities($_POST["txtMetaCanonical"],ENT_QUOTES);
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


	if($tmpBoxCount > 0)
	{
		for($i=1;$i<=$tmpBoxCount;$i++)
		{
			$tmpBox = array(	"id"		=>	$i,
				"title"		=>	htmlentities($_POST["box_title_".$i],ENT_QUOTES),
				"content"	=>	htmlentities($_POST["box_content_".$i],ENT_QUOTES),
				"link"		=>	htmlentities($_POST["box_link_".$i],ENT_QUOTES));
			$tmpBoxes[$i] = $tmpBox;
		}
	}

	if($formErrors == "")
	{


#		$feature_content = htmlentities($_POST["feature_content"],ENT_QUOTES);


		# save to database
		$tmpSectionId	=	$_POST['section_id'];
		$tmpProdSlots	=	$_POST['prodSlots'];

		$strSQL = "UPDATE sm_page SET title = '$tmpTitle',  subtitle = '$tmpSubTitle',  ref='$tmpPrettyLink',
		section_id	='$tmpSectionId',
		prod_slots	='$tmpProdSlots',

		mvc_header 			=	'$mvc_header',
		mvc_footer 			=	'$mvc_footer',
		mvc_controller 		=	'$mvc_controller',
		mvc_method 			=	'$mvc_method',
		mvc_action 			=	'$mvc_action',
		mvc_template 		=	'$mvc_template',

		language 			=	'$mvc_language',
		version_of			=	'$mvc_version_of',
		status				=	'$mvc_status',

		mvc_slider 			=	'$mvc_slider',
		mvc_banner_1 		=	'$mvc_banner_1',
		mvc_banner_2 		=	'$mvc_banner_2',
		mvc_banner_3 		=	'$mvc_banner_3',

		admin_only			=	'$mvc_admin_only',

		alias				=	'$tmpAlias',
		
		
		feature_title		=	'$feature_title',
		feature_content		=	'$feature_content',
		feature_link		=	'$feature_link',
		feature_banner		=	'$feature_banner',


		meta_canonical = '$tmpMetaCanonical', meta_title = '$tmpMetaTitle', meta_desc = '$tmpMetaDesc', content = '$tmpContent', meta_breadcrumb_text = '$tmpBreadcrumb', text_tag = '$tmpTag', updated = NOW() WHERE id = $tmpId";
		$gMysql->update($strSQL,__FILE__,__LINE__);






		ob_end_clean();
		header("Location: ./");
		exit();
	}
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";

	$strSQL		=	"SELECT * FROM sm_page WHERE id = " . $tmpId;
	$item	=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);


	$tmpTitle = $item["title"];
	$tmpAlias = $item["alias"];
	$tmpSubTitle = html_entity_decode($item["subtitle"],ENT_QUOTES);
	$tmpTitleSEO = $item["title_seo"];
#	$tmpTitleSEO = html_entity_decode($item["title_seo"],ENT_QUOTES);
	$tmpPrettyLink	=	trim($item["ref"],'/');

	$tmpMetaTitle = $item["meta_title"];
	$tmpMetaDesc = $item["meta_desc"];
	$tmpContent = $item["content"];

#	$tmpContent = html_entity_decode($item["content"], ENT_NOQUOTES);
#	$tmpContent = (htmlspecialchars_decode ($item["content"], ENT_QUOTES) );

	$tmpBreadcrumb = $item["meta_breadcrumb_text"];
	$tmpTag= $item["text_tag"];
	$tmpRelated = $item["related"];
	$tmpSectionId = $item["section_id"];
	$tmpProdSlots  = $item["prod_slots"];
	$tmpLanguage = $item["language"];
	$tmpMetaCanonical = $item["meta_canonical"];
	$tmpBoxCount = $item["boxes"];




	$feature_title 		=	$item['feature_title'];
	$feature_content 	=	$item['feature_content'];
	$feature_link	 	=	$item['feature_link'];
	$feature_banner 	=	$item['feature_banner'];


}

$strSQL = "SELECT id, title,ref  FROM sm_page WHERE status = 'A' AND id <> $tmpId ORDER BY title";
$dbRsLinks 	=	$gMysql->selectToArray($strSQL,__FILE__,__LINE__);




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

<!--[if lt IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<!--<![endif]-->

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
			<fieldset>
				<legend>Page Details</legend>
				<p>
					<label for="txtTitle">Title</label>
					<input type="text" name="txtTitle" id="txtTitle" maxlength="100" value="<? echo html_entity_decode($tmpTitle,ENT_QUOTES); ?>" class="formInputData" />
				</p>
				<p>
					<label for="txtMetaDesc">SEO META Title</label>
					<input type="text" name="txtMetaTitle" id="txtMetaTitle" maxlength="255" value="<? echo html_entity_decode($tmpMetaTitle,ENT_QUOTES); ?>" class="formInputData" />
				</p>
				<p>
					<label for="txtPrettyLink">SEO link to page<br>No spaces. dashes-between-words</label>
					<input type="text" name="txtPrettyLink" id="txtPrettyLink" maxlength="100" value="<? echo urldecode($tmpPrettyLink); ?>" class="formInputData" />
				</p>





				<?php



				{
					echo '

							<p>
								<label for="txtMetaDesc">SEO META Description</label>
								<textarea name="txtMetaDesc" id="txtMetaDesc" rows="5" class="formInputData">'.html_entity_decode($tmpMetaDesc,ENT_QUOTES).'</textarea>
							</p>
							<p>
								<label for="txtBreadcrumb">SEO Breadcrumb text</label>
								<input type="text" name="txtBreadcrumb" id="txtBreadcrumb" maxlength="255" value="'.urldecode(html_entity_decode($tmpBreadcrumb,ENT_QUOTES)).'" class="formInputData" />
							</p>
							<p>
								<label for="txtTag">TAG (for grouping links in sitemap etc.)</label>
								<input type="text" name="txtTag" id="txtTag" maxlength="255" value="'.urldecode(html_entity_decode($tmpTag,ENT_QUOTES)).'" class="formInputData" />
							</p>



							<p>'.	$dropdown_slider	.'
							</p>

							<p>'.	$dropdown_banner_1	.'
							<div id="banner_image_1">'.$image_banner_1.'</div>
							</p>

							<p>'.	$dropdown_banner_2	.'
							<div id="banner_image_2">'.$image_banner_2.'</div>
							</p>

							<p>'.	$dropdown_banner_3	.'
							<div id="banner_image_3">'.$image_banner_3.'</div>
							</p>

							<p>'.	$dropdown_status	.'
							</p>



							<br>

							</fieldset>
							
							
							
							<br>
							<br>
							<br>
							<br>
							
							
							
							
							
							<fieldset>
							
							
							
							<legend>Feature Box</legend>

							<p>
								<label for="feature_title">Feature Title</label>
								<input type="text" name="feature_title" id="feature_title" maxlength="100" value="'. html_entity_decode($feature_title,ENT_QUOTES) .'" class="formInputData" />
							</p>

							<p>
								<label for="feature_content">Feature Content</label>
								<textarea name="feature_content" id="feature_content" rows="5" class="formInputData">'.html_entity_decode($feature_content,ENT_QUOTES).'</textarea>
							</p>
							<p>
								<label for="feature_link">Feature link to page</label>
								<input type="text" name="feature_link" id="feature_link" maxlength="100" value="'. $feature_link.'" class="formInputData" />
							</p>
							
							
							<p>'.	$dropdown_feature_banner	.'
							<div id="banner_image_feature">'.$image_feature_banner.'</div>
							</p>

	

							</fieldset>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>




							<fieldset>
							
							
							
							<legend>Backend functionality (Expert Mode)</legend>
							<p>'.	$dropdown_header	.'
							</p>
							<p>'.	$dropdown_footer	.'
							</p>
							<p>'.	$dropdown_controller	.'
							</p>
							<p>'.	$dropdown_method	.'
							</p>
							<p>'.	$dropdown_action	.'
							</p>
							<p>'.	$dropdown_template	.'
							</p>
							<p>'.	$dropdown_language	.'
							</p>
							<p>'.	$dropdown_admin_only	.'
							</p>
							</fieldset>
							<br>
							<br>
							<br>


							';

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
				<? echo "To spellcheck, click this button: ". $spellcheck_button; ?>
				</span>

				<br><br>


				<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
				<input style="background-color:#007700;" type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
				<input type="submit" name="btnDelete" id="btnDelete" value="Delete" class="formButton" />

				<br><br>
				<span style="font-size:large;">

				Insert {{summary_benefits}}	for <b>Summary Benefits</b><br>
				Insert {{key_benefits}}	for <b>Key Benefits</b><br>
				Insert {{pricing}}	for <b>Pricing</b><br>
				Insert {{feature_box}} for Feature Box to be used. (no <b>link</b> means it won't be shown)</b><br>

</span>

				<br><br>
			</div>
		</form>
	</div>
</div>

<script>



	function buildActions()
	{
		// get on controller (class)
		var $controller		=	$('#mvc_controller option:selected').html();

//	alert($controller);


	}

















	// get actions from the class methods
	function doGetClassFunctions()
	{
		// get the selected controller
		var controller		=	$('#mvc_controller option:selected').html();

		$.ajax(
			{
				url:		'doGetClassFunctions.php',
				type:		'POST',
				cache:		false,
				async:		true,
				dataType:	'json',
				data:
				{
					controller : controller,
				},
				success:	function(data)
				{
					if	(data.returncode == 'error')
					{

						alert('error');
					}
					else if (data.returncode == 'success')
					{
						// empty and then repopulate dropdown
						var dropdown = $('#mvc_action');
						dropdown.empty();

						$.each(data.action, function(index,value) {
							dropdown.append($("<option></option>")
								.attr("value", value).text(value));
						});


						// empty and then repopulate dropdown
						var dropdown = $('#mvc_template');
						dropdown.empty();

						$.each(data.template, function(index,value) {
							dropdown.append($("<option></option>")
								.attr("value", value).text(value));
						});



					}
				},
				failure:	function()
				{
					alert('failure');
				}
			}

		)
	}











	// get templates from the directory
	function doGetTemplates()
	{
		// get the selected controller
		var controller		=	$('#mvc_controller option:selected').html();

		$.ajax(
			{
				url:		'doGetTemplates.php',
				type:		'POST',
				cache:		false,
				async:		true,
				dataType:	'json',
				data:
				{
					controller : controller,
				},
				success:	function(data)
				{
					if	(data.returncode == 'error')
					{

						alert('error');
					}
					else if (data.returncode == 'success')
					{
						// empty and then repopulate dropdown
						var dropdown = $('#mvc_template');
						dropdown.empty();

						$.each(data.dropdown, function(index,value) {
							dropdown.append($("<option></option>")
								.attr("value", value).text(value));
						});

					}
				},
				failure:	function()
				{
					alert('failure');
				}
			}

		)
	}






	// copy text to hidden input
	function doCopyTextDiv()
	{
	//	var data =	$('div#txtContent').froalaEditor('html.get');
	//	$('#txtContentHidden').val( 	data  );
	}






	// once all page is loaded
	window.onload = function(e)
	{

		$(document).ready(function ()
		{


			$("#mvc_banner_1").change(function() {

				$("#banner_image_1").html($("<img/>", { src: "/app/images/backdrop/" + $(this).val(), width: "600"} ));
			})

			$("#mvc_banner_2").change(function() {

				$("#banner_image_2").html($("<img/>", { src: "/app/images/backdrop/" + $(this).val(), width: "600"} ));
			})

			$("#mvc_banner_3").change(function() {

				$("#banner_image_3").html($("<img/>", { src: "/app/images/backdrop/" + $(this).val(), width: "600"} ));
			})


			$("#feature_banner").change(function() {

				$("#banner_image_feature").html($("<img/>", { src: "/app/images/banners/" + $(this).val(), width: "600"} ));
			})


		}


	);

	};




</script>

<?




# CHOICE, ARRAY[id,value]
function boxImageOptions($choice=0,$array)
{
	if(empty($choice))
	{
		$choice	=	0;
	}

	$select_box	=	"";

	# build the box
	$len	=	count($array);

	for	($i=0;$i<$len;$i++)
	{
		$select_box	.=	"<option value=\"{$array[$i]['id']}\"";


		if ( $choice == $array[$i]['id'])
		{
			$select_box .= " selected";
		}

		$str	=	$array[$i]['description'];

		$select_box	.=	">{$str}</option>\n";
	}

	# end
	$select_box		.=	"</select>";

	return $select_box;
}

include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");



?>


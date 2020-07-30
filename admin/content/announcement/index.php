<?
define('hasEditor','true');



$pageTitle = "Announcement";
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




# posting
if(isset($_POST["btnSubmit"]))
{

	if(strcasecmp($_POST["btnSubmit"],'Save') == 0)
	{
		$tmpTitle 		= 	htmlentities($_POST["txtTitle"],ENT_QUOTES);
		$tmpPrettyLink 	= 	htmlentities($_POST["txtPrettyLink"],ENT_QUOTES);
		$tmpContent 	= 	htmlentities($_POST["txtContent"],ENT_QUOTES);
		$tmpStatus		=	GetVariableString("mvc_status",$_POST,"");


		$strSQL = "replace into sm_announcement SET title = '$tmpTitle',   content='$tmpContent', link='$tmpPrettyLink' , status='$tmpStatus'";

		$gMysql->update($strSQL,__FILE__,__LINE__);


		ob_end_clean();
		header("Location: ./");
		exit();
	}
}


$strSQL				=	"SELECT * FROM sm_announcement";
$item				=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);


$tmpTitle 			=	$item["title"];
$tmpPrettyLink		=	trim($item["link"],'/');
$tmpContent 		=	$item["content"];
$tmpStatus	 		=	$item["status"];

# arrays for options
$status_array		=	array('A','N');
$status_name_array	=	array('Active','Not Active');
$method_array		=	array('','get','post');

$dropdown_status		=	buildSelectBox("mvc_status",$tmpStatus,$status_array,$status_name_array,"myclass","Status");


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
		<p>Use this form to create Announcements <? echo $msgAction; ?></p>
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
					<label for="txtPrettyLink">Link to page</label>
					<input type="text" name="txtPrettyLink" id="txtPrettyLink" maxlength="100" value="<? echo urldecode($tmpPrettyLink); ?>" class="formInputData" />
				</p>
				<p>
					<? echo $dropdown_status; ?>
				</p>










				<p>
					<label for="txtContent">Content</label></p>
					<p><textarea name="txtContent" id="txtContent" rows="5" class="formInputWysiwyg"><? echo html_entity_decode($tmpContent,ENT_QUOTES); ?></textarea>
				</p>

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


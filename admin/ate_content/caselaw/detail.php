<?php
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "4";
$subSection = "2";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpHeadline = '';
$tmpCategory = '';
$tmpSummary = '';
$tmpContent = '';
$tmpSortOrder = 99;
//$tmpDate = date_create();
//$tmpDisplayDate = date_format($tmpDate,"Y-m-d");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$gMysql				=	new Mysql_Library();





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

if(isset($_POST["cId"]))
{
	$tmpId = intval($_POST["cId"]);
}
elseif(isset($_GET["cId"]))
{
	$tmpId = intval($_GET["cId"]);
}

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE ate_caselaw SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE ate_caselaw SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM ate_caselaw WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtHeadline"]) && isset($_POST["txtContent"]))
{
	$tmpHeadline = htmlentities($_POST["txtHeadline"],ENT_QUOTES);
	$tmpCategory = $_POST["selCategory"];
	$tmpSummary = htmlentities($_POST["txtSummary"],ENT_QUOTES);
	$tmpContent = htmlentities($_POST["txtContent"],ENT_QUOTES);
	$tmpSortOrder = intval($_POST["txtSortOrder"]);

	$tmpnewCategory = htmlentities($_POST["newCategory"],ENT_QUOTES);

	
	if($tmpHeadline == "")
	{
		$formErrors .= "<li>Please enter a title</li>";
	}
	if($tmpSummary == "")
	{
		$formErrors .= "<li>Please enter a summary</li>";
	}
	if($tmpContent == "")
	{
		$formErrors .= "<li>Please enter some content</li>";
	}

	// if we do not set new cat name
	if (($tmpnewCategory == "") && ($tmpCategory == "** New Category **"))
	{
		$formErrors .= "<li>Please add name for new category</li>";
	}
	else if ($tmpCategory == "** New Category **")
	{
		$tmpCategory	=	$tmpnewCategory;
	}




	/*if(!checkdate($tmpMonth,$tmpDay,$tmpYear))
	{
		$formErrors .= "<li>Please enter a valid date</li>";
	}*/

	if($formErrors == "")
	{
		//$tmpDisplayDate = str_pad($tmpYear,4,'0',STR_PAD_LEFT)."-".str_pad($tmpMonth,2,'0',STR_PAD_LEFT)."-".str_pad($tmpDay,2,'0',STR_PAD_LEFT)." 00:00:00";
		/*echo $tmpDisplayDate;
		exit();*/

		# this needs storing also, so we can group the caselaws together
		$category_order		=	$gMysql->queryItem("select category_order from ate_caselaw where category='$tmpCategory' order by category_order desc limit 1", __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);


		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO ate_caselaw (category_order,headline,category,summary,content,created,created_by,updated,updated_by,status,sort_order) VALUES ('$category_order','$tmpHeadline', '$tmpCategory', '$tmpSummary', '$tmpContent',Now(),$user->userID,Now(),$user->userID,'A',$tmpSortOrder)";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE ate_caselaw SET category_order='$category_order', headline = '$tmpHeadline', category = '$tmpCategory', content = '$tmpContent', summary = '$tmpSummary', updated = Now(), updated_by = $user->userID, sort_order = $tmpSortOrder WHERE id = $tmpId";
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
	$dbRsItem = mysql_query("SELECT headline, category, summary, content, sort_order FROM ate_caselaw WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpHeadline = $item["headline"];
	$tmpCategory = $item["category"];
	$tmpSummary = $item["summary"];
	$tmpContent = $item["content"];
	$tmpSortOrder = $item["sort_order"];
}
else
{
	$tmpDbAction = $dbAction;
}

$pageTitle = "ATE Training Caselaw Item";
if($tmpId > 0)
{
	$msgAction = "edit a caselaw item";
}
else
{
	$msgAction = "add a caselaw item";
}













# build a menu and insert some onselect code to insert/remove inputbox area


$string		=	"<select name='selCategory' id='selCategory' class='formInputData'>";
$data_array	=	$gMysql->selectToArray("select distinct(category) from ate_caselaw order by category", __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);

foreach ($data_array as $data)
{
	$string	.=	"<option value='".$data['category']."'";

	if ($tmpCategory == $data['category'])
	{
		$string	.=	" selected";
	}

	$string	.=	">".$data['category']."</option>";
}

$string	.=	"<option value='** New Category **'>** New Category **</option></select><br><br>";

$string	.=	"<span id='newCategoryLabel' style='display:none;'><label>Enter New Category</label><input id='newCategory' name='newCategory' class='formInputData'></input></span>";

/*
                	<option value='Conditional Fee Agreements'<?php if($tmpCategory == 'Conditional Fee Agreements') { echo ' selected'; } ?>>Conditional Fee Agreements</option>
	<option value='BTE Insurance'<?php if($tmpCategory == 'BTE Insurance') { echo ' selected'; } ?>>BTE Insurance</option>
	<option value='ATE Insurance'<?php if($tmpCategory == 'ATE Insurance') { echo ' selected'; } ?>>ATE Insurance</option>
	<option value='Practice & Procedure'<?php if($tmpCategory == 'Practice & Procedure') { echo ' selected'; } ?>>Practice & Procedure</option>
	</select>

*/





?>
<div class="mainAdminPage">
	<h1><?php echo $pageTitle ?></h1>
	<?php
		if($formErrors != "")
		{
			echo "<p><strong>ERROR:</strong></p><ul>$formErrors</ul>";
		}
	?>
	<div class="mainAdminFull"> 
	<p>Use this form to <?php echo $msgAction; ?></p>
	<form name="frmService" id="frmService" method="post" enctype="multipart/form-data" class="formEditor" />
		<input type="hidden" name="dbAction" id="dbAction" value="<?php echo $tmpDbAction; ?>" />
		<input type="hidden" name="cId" id="cId" value="<?php echo $tmpId; ?>" />
		<fieldset>
			<legend>ATE Training Subject Details</legend>
			<p>
				<label for="txtHeadline">Title</label>
				<input type="text" name="txtHeadline" id="txtHeadline" maxlength="255" value="<?php echo html_entity_decode($tmpHeadline,ENT_QUOTES); ?>" class="formInputData" />
			</p>
            <p>
				<label for="selCategory">Category</label>



				<?

				echo $string;

				?>

			</p>
			<p>
				<label for="txtSummary">Summary</label>
				<textarea name="txtSummary" id="txtSummary" rows="5" class="formInputData"><?php echo html_entity_decode($tmpSummary,ENT_QUOTES); ?></textarea>
			</p>
            <p>
				<label for="txtContent">Content</label></p>
				<p><textarea name="txtContent" id="txtContent" rows="10" class="formInputWysiwyg"><?php echo html_entity_decode($tmpContent,ENT_QUOTES); ?></textarea>
			</p>
            <p>
				<label for="txtSortOrder">Sort Order</label>
				<input type="text" name="txtSortOrder" id="txtSortOrder" maxlength="99" value="<?php echo $tmpSortOrder; ?>" class="formInputDataSmall" />
			</p>
		</fieldset>
		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
		</p>
	</form>
	</div>
	</div>

<script>


$(document).ready(function(){

	$("#selCategory").on('change', function(){
		if($('#selCategory :selected').text() == "** New Category **"){

			$('#newCategoryLabel').show();

		}
		else{
			$('#newCategoryLabel').hide();
		}
	})
});
</script>


<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
<?
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "2";
$subSection = "4";
$datePicker = true;
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header_wysiwyg.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpHeadline = '';
$tmpSummary = '';
$tmpContent = '';
//$tmpDate = date_create();
//$tmpDisplayDate = date_format($tmpDate,"Y-m-d");
$tmpDate = $_SERVER["REQUEST_TIME"];
$tmpDisplayDate = date("Y-m-d",$tmpDate);
$tmpDay = date("d");
$tmpMonth = date("m");
$tmpYear = date("Y");

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

if(isset($_POST["newsId"]))
{
	$tmpId = intval($_POST["newsId"]);
}
elseif(isset($_GET["newsId"]))
{
	$tmpId = intval($_GET["newsId"]);
}

if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE press SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE press SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM press WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtHeadline"]) && isset($_POST["txtContent"]))
{
	$tmpHeadline = htmlentities($_POST["txtHeadline"],ENT_QUOTES);
	$tmpSummary = htmlentities($_POST["txtSummary"],ENT_QUOTES);
	$tmpContent = htmlentities($_POST["txtContent"],ENT_QUOTES);
	$tmpDay = $_POST["txtDay"];
	$tmpMonth = $_POST["txtMonth"];
	$tmpYear = $_POST["txtYear"];
	$tmpDisplayDate = $_POST["displayDate"];
	
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
	/*if(!checkdate($tmpMonth,$tmpDay,$tmpYear))
	{
		$formErrors .= "<li>Please enter a valid date</li>";
	}*/

	if($formErrors == "")
	{
		//$tmpDisplayDate = str_pad($tmpYear,4,'0',STR_PAD_LEFT)."-".str_pad($tmpMonth,2,'0',STR_PAD_LEFT)."-".str_pad($tmpDay,2,'0',STR_PAD_LEFT)." 00:00:00";
		/*echo $tmpDisplayDate;
		exit();*/
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO press (headline,summary,content,display_date,created,created_by,updated,updated_by,status) VALUES ('$tmpHeadline', '$tmpSummary', '$tmpContent','$tmpDisplayDate',Now(),$user->userID,Now(),$user->userID,'A')";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE press SET headline = '$tmpHeadline', content = '$tmpContent', summary = '$tmpSummary', display_date = '$tmpDisplayDate', updated = Now(), updated_by = $user->userID WHERE id = $tmpId";
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
	$dbRsItem = mysql_query("SELECT headline, summary, content, display_date FROM press WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpHeadline = $item["headline"];
	$tmpSummary = $item["summary"];
	$tmpContent = $item["content"];
	$tmpDate = strtotime($item["display_date"]);
	$tmpDisplayDate = date("Y-m-d",$tmpDate);
}


$pageTitle = "Press Release";
if($tmpId > 0)
{
	$msgAction = "edit a press release";
}
else
{
	$msgAction = "add a press release";
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
	<form name="frmService" id="frmService" method="post" enctype="multipart/form-data" class="formEditor" />
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="newsId" id="newsId" value="<? echo $tmpId; ?>" />
		<fieldset>
			<legend>Press Release Details</legend>
			<p>
				<label for="txtHeadline">Title</label>
				<input type="text" name="txtHeadline" id="txtHeadline" maxlength="255" value="<? echo html_entity_decode(urldecode($tmpHeadline),ENT_QUOTES); ?>" class="formInputData" />
			</p>
			<p>
				<label for="txtSummary">Summary</label>
				<textarea name="txtSummary" id="txtSummary" rows="5" class="formInputData"><? echo html_entity_decode(urldecode($tmpSummary),ENT_QUOTES); ?></textarea>
			</p>
            <p>
				<label for="txtContent">Content</label></p>
				<p><textarea name="txtContent" id="txtContent" rows="10" class="formInputWysiwyg"><? echo html_entity_decode(urldecode($tmpContent),ENT_QUOTES); ?></textarea>
			</p>
			<p>
            	<label for="displayDate">Published</label>
                <script>DateInput('displayDate', true, 'YYYY-MM-DD','<?php echo $tmpDisplayDate; ?>')</script>
            </p>
		</fieldset>
		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
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
<?php
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "5";
$subSection = "1";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

?>

<link href="/includes/jquery-ui/css/flick/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/includes/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="/includes/jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>

<script language="javascript">

	jQuery(document).ready(function($){

	});
</script>
<?




	$formErrors = '';
$tmpDbAction = 'Insert';
$tmpForename = '';
$tmpSurname = '';
$tmpEmail = '';
$tmpPassword = '';
$tmpCompanyName = '';
$tmpCompanyId = '';
$tmpAddress = '';
$tmpPostcode = '';
$tmpPhone = '';
$tmpPartner = 'N';
$tmpLoginCount = 0;
$tmpOldActive = 0;
$tmpActive = 0;
$tmpCreated = '';
$tmpReferrers = '';
$tmpATEPanel = 0;
$tmpATEFSAReferral = 0;
$tmpATENonFSAReferral = 0;
$tmpBTEPanel = 0;
$tmpBTEBroker = 0;
$tmpDebtSafe = 0;
$tmpOwnCostsInsurance = 0;



# export anything from this user in jackson format
$tmpExportJacksonFields     = 0;
# show all pages in new jackson design (if applicable)
$tmpShowJacksonPages        = 0;
# show all pages in side menu
$tmpShowAllSideMenuPages    = 0;






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
	$tmpId = $_POST["iId"];
}
elseif(isset($_GET["iId"]))
{
	$tmpId = $_GET["iId"];
}

if($dbAction == "Deactivate" && $tmpId != '')
{
	mysql_query("UPDATE leeward_access SET active = 0 WHERE id = '$tmpId'");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId != '')
{
	mysql_query("UPDATE leeward_access SET status = 1 WHERE id = '$tmpId'");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId != '')
{
	mysql_query("DELETE FROM leeward_access WHERE id = '$tmpId'");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtForename"]) )
{
	$tmpForename = mysql_real_escape_string($_POST["txtForename"]);
	$tmpSurname = mysql_real_escape_string($_POST["txtSurname"]);
	$tmpEmail = mysql_real_escape_string($_POST["txtEmail"]);
	$tmpPassword = mysql_real_escape_string($_POST["txtPassword"]);
	$tmpCompanyName = mysql_real_escape_string($_POST["txtCompanyName"]);
	$tmpCompanyId = mysql_real_escape_string($_POST["selCompanyId"]);
	$tmpAddress = mysql_real_escape_string($_POST["txtAddress"]);
	$tmpPostcode = mysql_real_escape_string($_POST["txtPostcode"]);
	$tmpPhone = mysql_real_escape_string($_POST["txtPhone"]);
	$tmpOldActive = $_POST["oldActive"];

	if(isset($_POST["cbxPartner"]))
	{
		$tmpPartner = 'Y';
	}
	else
	{
		$tmpPartner = 'N';
	}

	if(isset($_POST["cbxActive"]))
	{
		$tmpActive = 1;
	}
	else
	{
		$tmpActive = 0;
	}

	if(isset($_POST["cbxATEPanel"]))
	{
		$tmpATEPanel = 1;
	}
	else
	{
		$tmpATEPanel = 0;
	}
	if(isset($_POST["cbxATEFSAReferral"]))
	{
		$tmpATEFSAReferral = 1;
	}
	else
	{
		$tmpATEFSAReferral = 0;
	}
	if(isset($_POST["cbxATENonFSAReferral"]))
	{
		$tmpATENonFSAReferral = 1;
	}
	else
	{
		$tmpATENonFSAReferral = 0;
	}
	if(isset($_POST["cbxBTEPanel"]))
	{
		$tmpBTEPanel = 1;
	}
	else
	{
		$tmpBTEPanel = 0;
	}
	if(isset($_POST["cbxBTEBroker"]))
	{
		$tmpBTEBroker = 1;
	}
	else
	{
		$tmpBTEBroker = 0;
	}
	if(isset($_POST["cbxDebtSafe"]))
	{
		$tmpDebtSafe = 1;
	}
	else
	{
		$tmpDebtSafe = 0;
	}

	if(isset($_POST["cbxOwnCostsInsurance"]))
	{
		$tmpOwnCostsInsurance = 1;
	}
	else
	{
		$tmpOwnCostsInsurance = 0;
	}



	# added by CM 21/02/13 for jackson testing
	if(isset($_POST["cbxExportJacksonFields"]))
	{
		$tmpExportJacksonFields = 1;
	}
	else
	{
		$tmpExportJacksonFields = 0;
	}
	if(isset($_POST["cbxShowJacksonPages"]))
	{
		$tmpShowJacksonPages = 1;
	}
	else
	{
		$tmpShowJacksonPages = 0;
	}
	if(isset($_POST["cbxShowAllSideMenuPages"]))
	{
		$tmpShowAllSideMenuPages = 1;
	}
	else
	{
		$tmpShowAllSideMenuPages = 0;
	}









	if($tmpForename == "")
	{
		$formErrors .= "<li>Please enter the user's forename</li>";
	}
	if($tmpSurname == "")
	{
		$formErrors .= "<li>Please enter the user's surname</li>";
	}
	if($tmpEmail == "")
	{
		$formErrors .= "<li>Please enter the user's email address</li>";
	}
	if($tmpPassword == "")
	{
		$formErrors .= "<li>Please enter the user's password</li>";
	}


	if(isset($_POST["cbxReferrers"]))
	{
		if(is_array($_POST["cbxReferrers"]))
		{
			foreach($_POST["cbxReferrers"] as $value)
			{
				$tmpReferrers .= $value . ',';
			}
		}
	}

	if($formErrors == "")
	{
		//$tmpDisplayDate = str_pad($tmpYear,4,'0',STR_PAD_LEFT)."-".str_pad($tmpMonth,2,'0',STR_PAD_LEFT)."-".str_pad($tmpDay,2,'0',STR_PAD_LEFT)." 00:00:00";
		/*echo $tmpDisplayDate;
		exit();*/
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO leeward_access (email, forename,surnamecode,master_policy,referrers,status) VALUES ('$tmpName', '$tmpCode', '$tmpMasterPolicy', '$tmpReferrers','A')";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			# updated by CM for jackson (3 extra fields)
			$strSQL = "UPDATE leeward_access SET email = '$tmpEmail', password = '$tmpPassword', forename = '$tmpForename', surname = '$tmpSurname', company_name = '$tmpCompanyName', company_id = '$tmpCompanyId', address = '$tmpAddress', postcode = '$tmpPostcode', telephone  = '$tmpPhone', active = $tmpActive, solicitors = '$tmpReferrers', partner = '$tmpPartner'

			, exportJacksonFields = $tmpExportJacksonFields
			, showJacksonPages = $tmpShowJacksonPages
			, showAllSideMenuPages = $tmpShowAllSideMenuPages
			, OwnCostsInsurance= $tmpOwnCostsInsurance

			WHERE id = '$tmpId'";
			//echo $strSQL; exit();
			mysql_query($strSQL);



			if($tmpActive == 1 && $tmpActive != $tmpOldActive)
			{
				$mailContent = "


				Your Box Legal Leeward Area account has been activated.

				Your login credentials are:

				Email Address: ".$_POST["txtEmail"]."
				Password: ".$_POST["txtPassword"]."

				Login Here: http://www.boxlegal.co.uk/leeward/

				*********************************************
				This is an automated email from Box Legal Ltd
				DO NOT REPLY TO THIS EMAIL. If you need help,
				please contact support@boxlegal.co.uk
				*********************************************

				";
				//exit($mailContent);
				$mailSent = mail ("$tmpEmail", "Box Legal Leeward Area Account Activated", $mailContent, "From: client_area@boxlegal.co.uk");
			}
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

if($tmpId != '' && $formErrors == "")
{
	$tmpDbAction = "Update";
	$dbRsItem = mysql_query("SELECT * FROM leeward_access WHERE id = '" . $tmpId."'");
	$item = mysql_fetch_array($dbRsItem);
	$tmpForename = $item["forename"];
	$tmpSurname = $item["surname"];
	$tmpCompanyName = $item["company_name"];
	$tmpCompanyId = $item["company_id"];
	$tmpEmail = $item["email"];
	$tmpPassword = $item["password"];
	$tmpAddress = $item["address"];
	$tmpPostcode = $item["postcode"];
	$tmpPhone = $item["telephone"];
	$tmpPartner = $item["partner"];
	$tmpActive = $item["active"];
	$tmpCreated = $item["date_created"];
	$tmpLoginCount = $item["login_count"];
	$tmpReferrers = $item["solicitors"];

	# added by CM 21/02/13 jackson
	$tmpExportJacksonFields     = $item["exportJacksonFields"];
	$tmpShowJacksonPages        = $item["showJacksonPages"];
	$tmpShowAllSideMenuPages    = $item["showAllSideMenuPages"];
	$tmpOwnCostsInsurance 		= $item["OwnCostsInsurance"];



	$strSQL = "SELECT * FROM user_levels WHERE user_id = '$tmpId'";
	//exit($strSQL);
	$dbRsLevels = mysql_query($strSQL);
	$item2 = mysql_fetch_array($dbRsLevels);
	$tmpATEPanel = $item2["ate_panel"];
	$tmpATEFSAReferral = $item2["ate_referral"];
	$tmpATENonFSAReferral = $item2["ate_referral_none"];
	$tmpBTEPanel = $item2["bte_panel"];
	$tmpBTEBroker = $item2["bte_broker"];
	$tmpDebtSafe = $item2["debt_safe"];



}

$strSQL = "SELECT id, name, code, referrers FROM solicitor WHERE status = 'A' ORDER BY name";
$dbRsSolicitors = mysql_query($strSQL);

$strSQL = "SELECT id, name, code, referrers FROM solicitor WHERE status = 'A' AND master_policy = '' ORDER BY name";
$dbRsReferrers = mysql_query($strSQL);

$pageTitle = "ATE User";
if($tmpId > 0)
{
	$msgAction = "edit a user";
}
else
{
	$msgAction = "add a user";
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
	<form name="frmData" id="frmData" method="post" enctype="multipart/form-data" class="formEditor" "/>
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpId; ?>" />
        <input type="hidden" name="oldActive" id="oldActive" value="<? echo $tmpActive; ?>" />
		<fieldset>
			<legend>User Details</legend>
			<p>
				<label for="txtForename">Forename</label>
				<input type="text" name="txtForename" id="txtForename" maxlength="200" value="<?php echo $tmpForename; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtSurname">Surname</label>
				<input type="text" name="txtSurname" id="txtSurname" maxlength="200" value="<?php echo $tmpSurname; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtEmail">Email address</label>
				<input type="text" name="txtEmail" id="txtEmail" maxlength="200" value="<?php echo $tmpEmail; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtPassword">Password</label>
				<input type="text" name="txtPassword" id="txtPassword" maxlength="200" value="<?php echo $tmpPassword; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtPhone">Telephone</label>
				<input type="text" name="txtPhone" id="txtPhone" maxlength="20" value="<? echo $tmpPhone; ?>" class="formInputData" />
			</p>
            <p style="width:900px;">
            	<label for="cbxPartner">Partner</label>
                <input type="checkbox" name="cbxPartner" id="cbxPartner"<?php if($tmpPartner == "Y") { echo ' checked'; }?> />
            </p>
            <p style="width:900px;">
            	<label for="cbxActive">Active</label>
                <input type="checkbox" name="cbxActive" id="cbxActive"<?php if($tmpActive == 1) { echo ' checked'; }?> />
            </p>
            <p style="width:900px;">
            	<label>Login Count</label>
                <?php echo $tmpLoginCount; ?>
            </p>
            <p style="width:900px;">
            	<label>Created</label>
                <?php echo date("d/m/Y",strtotime($tmpCreated)); ?>
            </p>
		</fieldset>

		<p>
			<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
			<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
		</p>


        <script>
			// checks that all form items are filled in, or alerts
            function doCheck()
            {
                if  (   $('#txtPostcode').val() == ""  )
                {
                    $("we need a postcode!");


                }
            }

            $("#frmData").submit(function()
            {
				var bSubmit = true;
				var formErrors = "";

                if( $('#txtForename').val() == "" )
                {
                    formErrors += "Please enter the user's forename\n";
                }
                if( $('#txtSurname').val() == "")
                {
                    formErrors += "Please enter the user's surname\n";
                }
                if( $('#txtEmail').val() == "")
                {
                    formErrors += "Please enter the user's email address\n";
                }
                if( $('#txtPassword').val() == "")
                {
                    formErrors += "Please enter the user's password\n";
                }

	            // any errors? jump back to top first
				if  (formErrors.length > 0)
				{

                    formErrors = "You have some errors you need to correct before saving will work.\n\n" + formErrors;

                    window.scrollTo(0, 0);
                    alert(formErrors);
                    bSubmit = false;

				}
                return bSubmit;
            });


        </script>




	</form>
	</div>
	<div class="mainPortfolioRight">
     <p>&nbsp;</p>
    </div>
	</div>


<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>



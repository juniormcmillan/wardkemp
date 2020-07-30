<?php
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "5";
$subSection = "1";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");


require($_SERVER["DOCUMENT_ROOT"]."/lib/lib_mysql.php");
require($_SERVER["DOCUMENT_ROOT"]."/includes/php/common.php");


# create new class
$gMysql	=	new	MySQL_class;
# create connection to dbase
$gMysql->Create(null);


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
	mysql_query("UPDATE user_access SET active = 0 WHERE id = '$tmpId'");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId != '')
{
	mysql_query("UPDATE user_access SET status = 1 WHERE id = '$tmpId'");

	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId != '')
{
	mysql_query("DELETE FROM user_access WHERE id = '$tmpId'");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtForename"]) && isset($_POST["txtCompanyName"]))
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
	if($tmpCompanyName == "")
	{
		$formErrors .= "<li>Please enter the user's company name</li>";
	}
	/*if($tmpCompanyId == "")
	{
		$formErrors .= "<li>Please select the user's company</li>";
	}*/
	if($tmpEmail == "")
	{
		$formErrors .= "<li>Please enter the user's email address</li>";
	}
	if($tmpPassword == "")
	{
		$formErrors .= "<li>Please enter the user's password</li>";
	}
	if($tmpAddress == "")
	{
		$formErrors .= "<li>Please enter the user's address</li>";
	}
	if($tmpPostcode == "")
	{
		$formErrors .= "<li>Please enter the user's postcode</li>";
	}
	if($tmpPhone == "")
	{

	# removed by CM 24/09/2012 as not needed as a check (approved by Daniel)
	#	$formErrors .= "<li>Please enter the user's phone number</li>";
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
			$strSQL = "INSERT INTO user_access (email, forename,surnamecode,master_policy,referrers,status) VALUES ('$tmpName', '$tmpCode', '$tmpMasterPolicy', '$tmpReferrers','A')";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			# updated by CM for jackson (3 extra fields)
			$strSQL = "UPDATE user_access SET email = '$tmpEmail', password = '$tmpPassword', forename = '$tmpForename', surname = '$tmpSurname', company_name = '$tmpCompanyName', company_id = '$tmpCompanyId', address = '$tmpAddress', postcode = '$tmpPostcode', telephone  = '$tmpPhone', active = $tmpActive, solicitors = '$tmpReferrers', partner = '$tmpPartner'

			, exportJacksonFields = $tmpExportJacksonFields
			, showJacksonPages = $tmpShowJacksonPages
			, showAllSideMenuPages = $tmpShowAllSideMenuPages
			, OwnCostsInsurance= $tmpOwnCostsInsurance

			WHERE id = '$tmpId'";
			//echo $strSQL; exit();
			mysql_query($strSQL);

			$strSQL = "UPDATE user_levels SET ate_panel = $tmpATEPanel, ate_referral = $tmpATEFSAReferral, ate_referral_none = $tmpATENonFSAReferral, pi_panel = $tmpBTEPanel, work_doctor = $tmpBTEBroker, debt_safe = $tmpDebtSafe




			WHERE user_id = '$tmpId'";
			//echo $strSQL; exit();
			mysql_query($strSQL);

			if($tmpActive == 1 && $tmpActive != $tmpOldActive)
			{
				$mailContent = "


				Your Fairplanenetwork Client Area account has been activated.

				Your login credentials are:

				Email Address: ".$_POST["txtEmail"]."
				Password: ".$_POST["txtPassword"]."

				Login Here: http://www.fairplanenetwork.co.uk/

				*********************************************
				This is an automated email from Fairplanenetwork Ltd
				DO NOT REPLY TO THIS EMAIL. If you need help,
				please contact support@boxlegal.co.uk
				*********************************************

				";
				//exit($mailContent);
				$mailSent = mail ("$tmpEmail", "Box Legal Client Area Account Activated", $mailContent, "From: client_area@boxlegal.co.uk");


				# active
				$company_id						=	$gMysql->QueryItem("SELECT company_id FROM sm_user_access where id='$tmpId'",__FILE__,__LINE__);
				$applyIndividualPremiumPoints	=	$gMysql->QueryItem("SELECT applyIndividualPremiumPoints FROM sm_solicitor where code='$company_id'",__FILE__,__LINE__);

				if	($applyIndividualPremiumPoints)
				{
					# send email
				$mailContent = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
 <head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
  <title>Demystifying Email Design</title>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
<style>
body {
		font-family : 'Verdana, Times New Roman, serif';
		font-size: 10pt;
	}


h4 {
   font-family: Verdana, 'Times New Roman', serif;
   color: #444444;
   font-size: 14px;
}
</style>
</head>

<body style='margin: 0; padding: 0;'>
 <table border='0' cellpadding='0' cellspacing='0' width='100%'>
  <tr>
   <td><span style='font-family:Verdana, Times New Roman, serif;'>
Dear ". $_POST["txtForename"] ."
<br><br>
As you may know your firm has decided to use our Claimsafe insurance for all of your personal injury cases.  This is because:<br><br>

<ul>
<li>Admin including requests and reporting is minimal</li>
<li>No requirement to obtain permission to take any step or incur any disbursement</li>
<li>All adverse costs covered, including Part 36 risk, application costs, etc</li>
<li>All disbursements covered</li>
<li>Cancellations are permitted at any time for all necessary reasons</li>
<li>Premiums are competitive</li>
</ul>

Another advantage of the Claimsafe ATE insurance scheme is that we also offer a points rewards scheme to all fee earners.  As you arrange policies you will be earning your own 'Premium Points' which can be exchanged for gifts ranging from jewellery to Ipads and even to flat screen televisions (if enough points are earned).  We attach a copy of our premium points brochure which gives full details of the points you will earn, how to claim gifts, etc.<br><br>
Before you start earning your premium points you will have to complete our registration form which can be found at www.premiumpoints.co.uk/your_account/registration/. Please do this as soon as possible, using your work e-mail address as your username and picking your own password, something that is memorable to you. After registering you will receive an e-mail confirming your are authorised to login (using the 'Your Account' page) where you will be able to check how many points you have accumulated and claim gifts.<br><br>
You can visit our dedicated website www.premiumpoints.co.uk to check the gifts we are offering.<br><br>
If you would like any assistance in explaining the benefits of the Claimsafe ATE insurance policy to clients then we are happy to help.  We can send a recommended oral script for explaining the policies and even arrange fee earner training if not already received.  Just let us know.<br><br><br>
Happy shopping...<br><br><br>


<table width='600' border='0' cellspacing='0' cellpadding='1'>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;</font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><a href='http://www.boxlegal.co.uk'>
    <img src='http://".$_SERVER['HTTP_HOST']."/email/bl_logo.gif' width='83' height='96' border='0'></a><a href='http://www.boxlegal.co.uk'>
    <img src='http://".$_SERVER['HTTP_HOST']."/email/10_years.gif' width='151' height='96' border='0'></a></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;</font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Box Legal Limited</strong></font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Woodland House, 20a Woodland House, London, N10 3UG </font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>T:</strong> 0870 766 9997 | <strong>F:</strong> 0870 766 9998 | <strong>DD:</strong> 0203 074 1100 | <strong>DX:</strong> 36003 Muswell Hill </font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>W:</strong> <a href='http://www.boxlegal.co.uk/' style='text-decoration:none'><font color='#000000'>www.boxlegal.co.uk</font></a> | <strong>W:</strong> <a href='http://www.tribunalfees.co.uk/' style='text-decoration:none'><font color='#000000'>www.tribunalfees.co.uk</font></a> </font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;</font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><a href='http://www.linkedin.com/company/box-legal'>
    <img src='http://".$_SERVER['HTTP_HOST']."/email/linkedin.gif' width='47' height='41' border='0'></a><a href='https://www.facebook.com/pages/Box-Legal/164621793566653'>
    <img src='http://".$_SERVER['HTTP_HOST']."/email/facebook.gif' width='45' height='41' border='0'></a><a href='https://twitter.com/BoxLegal'>
    <img src='http://".$_SERVER['HTTP_HOST']."/email/twitter.gif' width='46' height='41' border='0'></a></font></td>

  </tr>
  <tr>
    <td align='left' valign='top'>&nbsp;</td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Registered Office as above </font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Registered in England No.4871657 </font></td>
  </tr>
  <tr>
    <td align='left' valign='top'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Authorised &amp; Regulated by the Financial Conduct Authority </font></td>
  </tr>
</table>



  </span></td>
  </tr>
 </table>
</body>


</html>

		";


#				sendEmailNew("Box Legal Client Area", "client_area@boxlegal.co.uk", $tmpEmail, "Premium Points Reward Scheme", $mailContent, $_SERVER["DOCUMENT_ROOT"] . "/brochures/Premium Points - Final.pdf",true);

				}
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
	$dbRsItem = mysql_query("SELECT * FROM user_access WHERE id = '" . $tmpId."'");
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
				<label for="txtCompanyName">Company Name</label>
				<input type="text" name="txtCompanyName" id="txtCompanyName" maxlength="200" value="<?php echo $tmpCompanyName; ?>" class="formInputData" />
			</p>
            <p>
				<label for="selCompanyID">Select Company</label>
                <?php
				echo '<script language="JavaScript"><!--
						var aReferrers = new Array();
						var i = 0;
						//-->
				';
				while($item3 = mysql_fetch_array($dbRsSolicitors))
				{
					echo '	aReferrers[i] = new Array();
							aReferrers[i][0] = "'.$item3["referrers"].'";
							aReferrers[i][1] = "'.$item3["code"].'";
							i++;
							//-->
						';
				}
				echo '
				</script>';
				mysql_data_seek($dbRsSolicitors,0);
				?>
                <select name="selCompanyId" id="selCompanyId" class="formInputData" onChange="set_referrers()">
                	<option value="">Please choose:</option>
					<?php
                    while($solicitor = mysql_fetch_array($dbRsSolicitors))
                    {
                        if($tmpCompanyId == $solicitor["code"])
                        {
                            $selected = " selected";
                        }
                        else
                        {
                            $selected = "";
                        }
                        echo '<option value="'.$solicitor["code"].'"'.$selected.'>'.$solicitor["name"].'</option>';
                    }
                    ?>
                </select>
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
				<label for="txtAddress">Address</label>
                <textarea name="txtAddress" id="txtAddress" rows="5" class="formInputData"><?php echo $tmpAddress; ?></textarea>
			</p>
            <p>
				<label for="txtPostcode">Postcode</label>
				<input type="text" name="txtPostcode" id="txtPostcode" maxlength="10" value="<? echo $tmpPostcode; ?>" class="formInputData" />
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



	<?php
		echo '<fieldset>
					<legend>Referrers</legend>
					<p>';
					$aReferrers = explode(',',$tmpReferrers);
				while($item1 = mysql_fetch_array($dbRsReferrers))
				{
					$checked = "";
					foreach($aReferrers as $value)
					{
						if($value == $item1["id"])
						{
							$checked = "checked";
							break;
						}
					}
					echo '<span style="width:800px; display:inline; float:left;"><label>'.$item1["name"].'</label><input type="checkbox" name="cbxReferrers[]" id="cbxReferrers[]" value="'.$item1["id"].'" '.$checked.' /></span>
					';
				}
				echo '</p>
					</fieldset>';
		?>
        <fieldset>
        	<legend>User Access</legend>
            <p style="width:900px;">
            	<label for="cbxATEPanel">ATE Panel</label>
                <input type="checkbox" name="cbxATEPanel" id="cbxATEPanel"<?php if($tmpATEPanel == 1) { echo ' checked'; }?> />
            </p>
            <p style="width:900px;">
            	<label for="cbxATEFSAReferral">ATE FSA Referral</label>
                <input type="checkbox" name="cbxATEFSAReferral" id="cbxATEFSAReferral"<?php if($tmpATEFSAReferral == 1) { echo ' checked'; }?> />
            </p>
            <p style="width:900px;">
            	<label for="cbxATENonFSAReferral">ATE Non-FSA Referral</label>
                <input type="checkbox" name="cbxATENonFSAReferral" id="cbxATENonFSAReferral"<?php if($tmpATENonFSAReferral == 1) { echo ' checked'; }?> />
            </p>
            <p style="width:900px;">
            	<label for="cbxBTEPanel">BTE Panel</label>
                <input type="checkbox" name="cbxBTEPanel" id="cbxBTEPanel"<?php if($tmpBTEPanel == 1) { echo ' checked'; }?> />
            </p>
            <p style="width:900px;">
            	<label for="cbxBTEBroker">BTE Broker</label>
                <input type="checkbox" name="cbxBTEBroker" id="cbxBTEBroker"<?php if($tmpBTEBroker == 1) { echo ' checked'; }?> />
            </p>
            <p style="width:900px;">
            	<label for="cbxDebtSafe">DebtSafe</label>
                <input type="checkbox" name="cbxDebtSafe" id="cbxDebtSafe"<?php if($tmpDebtSafe == 1) { echo ' checked'; }?> />
            </p>

            <p style="width:900px;">
            	<label for="cbxOwnCostsInsurance">Own Costs Insurance</label>
                <input type="checkbox" name="cbxOwnCostsInsurance" id="cbxOwnCostsInsurance"<?php if($tmpOwnCostsInsurance == 1) { echo ' checked'; }?> />
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
                if( $('#txtCompanyName').val()  == "")
                {
                    formErrors += "Please enter the user's company name\n";
                }
                if( $('#txtEmail').val() == "")
                {
                    formErrors += "Please enter the user's email address\n";
                }
                if( $('#txtPassword').val() == "")
                {
                    formErrors += "Please enter the user's password\n";
                }
                if( $('#txtAddress').val() == "")
                {
                    formErrors += "Please enter the user's address\n";
                }
                if( $('#txtPostcode').val() == "")
                {
                    formErrors += "Please enter the user's postcode\n";
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



<?php
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "5";
$subSection = "2";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");





$formErrors = '';
$tmpDbAction = 'Insert';
$tmpName = '';
$tmpCode = '';
$tmpMasterPolicy = '';
$tmpReferrers = '';
$tmpMovesafeReferrers = '';


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
	mysql_query("UPDATE sm_solicitor SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE sm_solicitor SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM sm_solicitor WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtName"]) && isset($_POST["txtCode"]))
{
	$tmpName = $txtName;
	$tmpCode = $txtCode;
	$tmpMasterPolicy = $txtMasterPolicy;

	if($tmpName == "")
	{
		$formErrors .= "<li>Please enter a company name</li>";
	}
	if($tmpCode == "")
	{
		$formErrors .= "<li>Please enter a code</li>";
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

	# CM movesafe 13/03/14
	if(isset($_POST["cbxMovesafeReferrers"]))
	{
		if(is_array($_POST["cbxMovesafeReferrers"]))
		{
			foreach($_POST["cbxMovesafeReferrers"] as $value)
			{
				$tmpMovesafeReferrers .= $value . ',';
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
			$strSQL = "INSERT INTO sm_solicitor (name,code,master_policy,referrers,status) VALUES ('$tmpName', '$tmpCode', '$tmpMasterPolicy', '$tmpReferrers','A')";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE sm_solicitor SET name = '$tmpName', code = '$tmpCode', master_policy = '$tmpMasterPolicy', referrers = '$tmpReferrers'  WHERE id = $tmpId";
			//echo $strSQL; exit();
			mysql_query($strSQL);

			if(isset($_POST["cbxUpdateUsers"]))
			{
				$strSQL = "UPDATE sm_user_access SET solicitors = '$tmpReferrers'  WHERE company_id = '$tmpCode'";
				mysql_query($strSQL);
			}


			# CM 2012 - this box is to allow a new field to be updated
			if	(IsChecked("cbxdisableFundLimit","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE sm_solicitor SET disableFundLimit = 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE sm_solicitor SET disableFundLimit = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}




























		}
		ob_end_clean();
		header('Location: '.$_SERVER['REQUEST_URI']);
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
	$dbRsItem = mysql_query("SELECT * FROM sm_solicitor WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpName = $item["name"];
	$tmpCode = $item["code"];
	$tmpMasterPolicy = $item["master_policy"];
	$tmpReferrers = $item["referrers"];
	$tmpMovesafeReferrers = $item["movesafe_referrers"];
	# new item CM 2012
	$disableFundLimit			= intval($item["disableFundLimit"]);


	$cbxdisableFundLimit	=	"";
	if	($disableFundLimit == 0)
	{
	}
	else
	{
		$cbxdisableFundLimit	=	"checked='checked'";
	}

	# new item CM July 17th 2012
	$allowCriminalNegligence	= intval($item["allowCriminalNegligence"]);
	$cbxallowCriminalNegligence	= "";
	if	($allowCriminalNegligence == 0)
	{
	}
	else
	{
		$cbxallowCriminalNegligence	=	"checked='checked'";
	}



	# new item CM Sept 11th 2012
	$allowPIP	= intval($item["allowPIP"]);
	$cbxallowPIP= "";
	if	($allowPIP == 0)
	{
	}
	else
	{
		$cbxallowPIP	=	"checked='checked'";
	}

	$allowPPI	= intval($item["allowPPI"]);
	$cbxallowPPI= "";
	if	($allowPPI == 0)
	{
	}
	else
	{
		$cbxallowPPI	=	"checked='checked'";
	}



	$disableBespoke	= intval($item["disableBespoke"]);
	$cbxdisableBespoke= "";
	if	($disableBespoke == 0)
	{
	}
	else
	{
		$cbxdisableBespoke	=	"checked='checked'";
	}






	$allowStress	= intval($item["allowStress"]);
	$cbxallowStress= "";
	if	($allowStress == 0)
	{
	}
	else
	{
		$cbxallowStress	=	"checked='checked'";
	}


	$allowFinancial	= intval($item["allowFinancial"]);
	$cbxallowFinancial= "";
	if	($allowFinancial == 0)
	{
	}
	else
	{
		$cbxallowFinancial	=	"checked='checked'";
	}


	$allowMedical	= intval($item["allowMedical"]);
	$cbxallowMedical= "";
	if	($allowMedical == 0)
	{
	}
	else
	{
		$cbxallowMedical	=	"checked='checked'";
	}



	$allowInterestRate	= intval($item["allowInterestRate"]);
	$cbxallowInterestRate= "";
	if	($allowInterestRate == 0)
	{
	}
	else
	{
		$cbxallowInterestRate	=	"checked='checked'";
	}




	$allowReferrerCases	= intval($item["allowReferrerCases"]);
	$cbxallowReferrerCases= "";
	if	($allowReferrerCases == 0)
	{
	}
	else
	{
		$cbxallowReferrerCases	=	"checked='checked'";
	}





	$allowFalseImprisonment	= intval($item["allowFalseImprisonment"]);
	$cbxallowFalseImprisonment= "";
	if	($allowFalseImprisonment == 0)
	{
	}
	else
	{
		$cbxallowFalseImprisonment	=	"checked='checked'";
	}

	$allowStoppedFirm	= intval($item["allowStoppedFirm"]);
	$cbxallowStoppedFirm= "";
	if	($allowStoppedFirm == 0)
	{
	}
	else
	{
		$cbxallowStoppedFirm	=	"checked='checked'";
	}









	$allowImmigration	= intval($item["allowImmigration"]);
	$cbxallowImmigration= "";
	if	($allowImmigration == 0)
	{
	}
	else
	{
		$cbxallowImmigration	=	"checked='checked'";
	}



	$allowHousingDisrepair	= intval($item["allowHousingDisrepair"]);
	$cbxallowHousingDisrepair= "";
	if	($allowHousingDisrepair == 0)
	{
	}
	else
	{
		$cbxallowHousingDisrepair	=	"checked='checked'";
	}



	$allowFamilyApplications	= intval($item["allowFamilyApplications"]);
	$cbxallowFamilyApplications= "";
	if	($allowFamilyApplications == 0)
	{
	}
	else
	{
		$cbxallowFamilyApplications	=	"checked='checked'";
	}



	$allowCivilLitigation	= intval($item["allowCivilLitigation"]);
	$cbxallowCivilLitigation= "";
	if	($allowCivilLitigation == 0)
	{
	}
	else
	{
		$cbxallowCivilLitigation	=	"checked='checked'";
	}



	$allowCommercialLitigation	= intval($item["allowCommercialLitigation"]);
	$cbxallowCommercialLitigation= "";
	if	($allowCommercialLitigation == 0)
	{
	}
	else
	{
		$cbxallowCommercialLitigation	=	"checked='checked'";
	}



	$allowInsolvency	= intval($item["allowInsolvency"]);
	$cbxallowInsolvency= "";
	if	($allowInsolvency == 0)
	{
	}
	else
	{
		$cbxallowInsolvency	=	"checked='checked'";
	}



	$allowWillsandProbate	= intval($item["allowWillsandProbate"]);
	$cbxallowWillsandProbate= "";
	if	($allowWillsandProbate == 0)
	{
	}
	else
	{
		$cbxallowWillsandProbate	=	"checked='checked'";
	}


	$allowConveyances	= intval($item["allowConveyances"]);
	$cbxallowConveyances= "";
	if	($allowConveyances == 0)
	{
	}
	else
	{
		$cbxallowConveyances	=	"checked='checked'";
	}




















}

# show the current fund
$strSQL 		=	"SELECT claims_fund,rpt_date  FROM rpt_summary WHERE solicitor_code = '$tmpCode' order by rpt_date desc";
$dbRsRpt 		=	mysql_query($strSQL);
$data			=	mysql_fetch_array($dbRsRpt);
if	(!empty($data))
{
	$claims_fund	=	$data['claims_fund'];
}
else
{
	$claims_fund	=	0;
}

$claims_fund	=	number_format($claims_fund,2);

$strSQL = "SELECT id, name FROM solicitor WHERE status = 'A' AND master_policy = '' ORDER BY name";
$dbRsSolicitors = mysql_query($strSQL);

# cm new for 13/03/2014
$strSQL = "SELECT id, name FROM solicitor WHERE status = 'A' AND master_policy = '' ORDER BY name";
$dbRsMovesafeSolicitors = mysql_query($strSQL);


$pageTitle = "Solicitor Details";
if($tmpId > 0)
{
	$msgAction = "edit a solicitor";
}
else
{
	$msgAction = "add a solicitor";
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
		<input type="hidden" name="iId" id="iId" value="<? echo $tmpId; ?>" />
		<input type="hidden" name="cbxdisableFundLimit" id="cbxdisableFundLimit" value="<? echo $disableFundLimit; ?>" />
		<input type="hidden" name="cbxallowCriminalNegligence" id="cbxallowCriminalNegligence" value="<? echo $allowCriminalNegligence; ?>" />
		<input type="hidden" name="cbxallowPIP" id="cbxallowPIP" value="<? echo $allowPIP; ?>" />
		<input type="hidden" name="cbxallowPPI" id="cbxallowPPI" value="<? echo $allowPPI; ?>" />
		<input type="hidden" name="cbxdisableBespoke" id="cbxdisableBespoke" value="<? echo $disableBespoke; ?>" />

        <input type="hidden" name="cbxallowStress" id="cbxallowStress" value="<? echo $allowStress; ?>" />
        <input type="hidden" name="cbxallowFinancial" id="cbxallowFinancial" value="<? echo $allowFinancial; ?>" />
    <input type="hidden" name="cbxallowMedical" id="cbxallowMedical" value="<? echo $allowMedical; ?>" />
	<input type="hidden" name="cbxallowInterestRate" id="cbxallowInterestRate" value="<? echo $allowInterestRate; ?>" />
	<input type="hidden" name="cbxallowReferrerCases" id="cbxallowReferrerCases" value="<? echo $allowReferrerCases; ?>" />

	<input type="hidden" name="cbxallowStoppedFirm" id="cbxallowStoppedFirm" value="<? echo $allowStoppedFirm; ?>" />
	<input type="hidden" name="cbxallowFalseImprisonment" id="cbxallowFalseImprisonment" value="<? echo $allowFalseImprisonment; ?>" />



	<input type="hidden" name="cbxallowImmigration" id="cbxallowImmigration" value="<? echo $allowImmigration; ?>" />
	<input type="hidden" name="cbxallowHousingDisrepair" id="cbxallowHousingDisrepair" value="<? echo $allowHousingDisrepair; ?>" />
	<input type="hidden" name="cbxallowFamilyApplications" id="cbxallowFamilyApplications" value="<? echo $allowFamilyApplications; ?>" />
	<input type="hidden" name="cbxallowCivilLitigation" id="cbxallowCivilLitigation" value="<? echo $allowCivilLitigation; ?>" />
	<input type="hidden" name="cbxallowCommercialLitigation" id="cbxallowCommercialLitigation" value="<? echo $allowCommercialLitigation; ?>" />
	<input type="hidden" name="cbxallowInsolvency" id="cbxallowInsolvency" value="<? echo $allowInsolvency; ?>" />
	<input type="hidden" name="cbxallowWillsandProbate" id="cbxallowWillsandProbate" value="<? echo $allowWillsandProbate; ?>" />
	<input type="hidden" name="cbxallowConveyances" id="cbxallowConveyances" value="<? echo $allowConveyances; ?>" />





		<fieldset>
			<legend>Solicitor Details</legend>
			<p>
				<label for="txtName">Name</label>
				<input type="text" name="txtName" id="txtName" maxlength="200" value="<?php echo $tmpName; ?>" class="formInputData" />
			</p>
            <p>
				<label for="txtCode">Code</label>
				<input type="text" name="txtCode" id="txtCode" maxlength="25" value="<?php echo $tmpCode; ?>" class="formInputDataSmall" />
			</p>
            <p>
				<label for="txtMasterPolicy">Master Policy No.</label>
				<input type="text" name="txtMasterPolicy" id="txtMasterPolicy" maxlength="200" value="<? echo $tmpMasterPolicy; ?>" class="formInputData" />
			</p>

		</fieldset>


    	<fieldset>
			<legend>Extra Settings</legend>
			<p>

				<label>Disable fund limit</label>
				<input type="checkbox" name="cbxdisableFundLimit" id="cbxdisableFundLimit" value="yes" <? echo $cbxdisableFundLimit; ?>>

			</p>
			<p>
				<label >Current fund:</label>
			 	&pound; <? echo $claims_fund; ?>
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

        $("#frmService").submit(function()
        {
            var bSubmit = true;
            var formErrors = "";

            if( $('#txtName').val() == "" )
            {
                formErrors += "Please enter the Name\n";
            }
            if( $('#txtCode').val() == "")
            {
                formErrors += "Please enter the Code\n";
            }
//            if( $('#txtMasterPolicy').val()  == "")
  //          {
   //             formErrors += "Please enter the Master Policy Number\n";
     //       }

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

function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            $chkval	=	$_POST[$chkname];
            if($chkval == $value)
			{
                return true;
			}
        }
        return false;
    }


include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>
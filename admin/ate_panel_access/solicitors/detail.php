<?php
//ini_set('display_errors', 1);
//phpinfo();
$pageSection = "5";
$subSection = "2";
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");


require($_SERVER["DOCUMENT_ROOT"]."/lib/lib_mysql.php");
require($_SERVER["DOCUMENT_ROOT"]."/lib/common.php");


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
$tmpName = '';
$tmpCode = '';
$tmpMasterPolicy = '';
$tmpReferrers = '';
$tmpMovesafeReferrers = '';


$tmpLev1Indemnity			=	'';
$tmpLev2Indemnity			=	'';
$tmpLev3Indemnity			=	'';
$tmpLev4Indemnity			=	'';

$tmpLev1NewBuildFund			=	'';
$tmpLev1Standard				=	'';
$tmpLev1NewBuild				=	'';
$tmpLev1StandardFund			=	'';

$tmpLev2NewBuild				=	'';
$tmpLev2NewBuildFund			=	'';
$tmpLev2Standard				=	'';
$tmpLev2StandardFund			=	'';

$tmpLev3NewBuild				=	'';
$tmpLev3NewBuildFund			=	'';
$tmpLev3Standard				=	'';
$tmpLev3StandardFund			=	'';

$tmpLev4NewBuild				=	'';
$tmpLev4NewBuildFund			=	'';
$tmpLev4Standard				=	'';
$tmpLev4StandardFund			=	'';

$tmpLite						=	'';
$tmpLiteFund					=	'';

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
	mysql_query("UPDATE solicitor SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE solicitor SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM solicitor WHERE id = $tmpId");
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
			$strSQL = "INSERT INTO solicitor (name,code,master_policy,referrers,status,movesafe_referrers) VALUES ('$tmpName', '$tmpCode', '$tmpMasterPolicy', '$tmpReferrers','A', '$tmpMovesafeReferrers')";
			//echo $strSQL; exit();
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE solicitor SET name = '$tmpName', code = '$tmpCode', master_policy = '$tmpMasterPolicy', referrers = '$tmpReferrers', movesafe_referrers = '$tmpMovesafeReferrers'  WHERE id = $tmpId";
			//echo $strSQL; exit();
			mysql_query($strSQL);

			if(isset($_POST["cbxUpdateUsers"]))
			{
				$strSQL = "UPDATE user_access SET solicitors = '$tmpReferrers'  WHERE company_id = '$tmpCode'";
				mysql_query($strSQL);
			}

			if(isset($_POST["cbxUpdateMovesafeUsers"]))
			{
				$tmpMovesafeReferrers	=	rtrim($tmpMovesafeReferrers,',');
				$strSQL = "UPDATE user_access SET movesafe_solicitors = '$tmpMovesafeReferrers'  WHERE company_id = '$tmpCode'";
				mysql_query($strSQL);
			}



			# CM 2012 - this box is to allow a new field to be updated
			if	(IsChecked("cbxdisableFundLimit","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET disableFundLimit = 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET disableFundLimit = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowCriminalNegligence","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowCriminalNegligence= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowCriminalNegligence = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}


			if	(IsChecked("cbxallowPIP","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowPIP= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowPIP = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}


			if	(IsChecked("cbxallowPPI","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowPPI= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowPPI = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}


			if	(IsChecked("cbxdisableBespoke","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET disableBespoke= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET disableBespoke = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}








			if	(IsChecked("cbxallowStress","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowStress= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowStress = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowFinancial","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowFinancial= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowFinancial = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowMedical","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowMedical= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowMedical = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}


			if	(IsChecked("cbxallowInterestRate","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowInterestRate= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowInterestRate = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}



			if	(IsChecked("cbxallowReferrerCases","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowReferrerCases= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowReferrerCases = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}








			if	(IsChecked("cbxallowStoppedFirm","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowStoppedFirm= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowStoppedFirm = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowFalseImprisonment","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowFalseImprisonment= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowFalseImprisonment = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}





			if	(IsChecked("cbxallowPackageTour","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowPackageTour= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowPackageTour = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}


			if	(IsChecked("cbxallowInjuryAbroad","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowInjuryAbroad= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowInjuryAbroad = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}


			if	(IsChecked("cbxallowAirline","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowAirline= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowAirline = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}






			if	(IsChecked("cbxallowNonTribunal","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowNonTribunal= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowNonTribunal = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}



			if	(IsChecked("cbxallowChildRefusedPayment","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowChildRefusedPayment= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowChildRefusedPayment = 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}













			if	(IsChecked("cbxallowImmigration","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowImmigration= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowImmigration= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowHousingDisrepair","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowHousingDisrepair= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowHousingDisrepair= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowFamilyApplications","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowFamilyApplications= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowFamilyApplications= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowCivilLitigation","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowCivilLitigation= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowCivilLitigation= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowCommercialLitigation","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowCommercialLitigation= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowCommercialLitigation= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowInsolvency","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowInsolvency= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowInsolvency= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowWillsandProbate","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowWillsandProbate= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowWillsandProbate= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}

			if	(IsChecked("cbxallowConveyances","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET allowConveyances= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET allowConveyances= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}


			if	(IsChecked("cbxapplyIndividualPremiumPoints","yes") == true)
			{
				# update new field
				$strSQL = "UPDATE solicitor SET applyIndividualPremiumPoints= 1 WHERE id = $tmpId";
				mysql_query($strSQL);
			}
			else
			{
				$strSQL = "UPDATE solicitor SET applyIndividualPremiumPoints= 0 WHERE id = $tmpId";
				mysql_query($strSQL);
			}




$tmpLev1Indemnity			=	$_POST['Lev1Indemnity'];
$tmpLev2Indemnity			=	$_POST['Lev2Indemnity'];
$tmpLev3Indemnity			=	$_POST['Lev3Indemnity'];
$tmpLev4Indemnity			=	$_POST['Lev4Indemnity'];

$tmpLev1NewBuild			=	$_POST['Lev1NewBuild'];
$tmpLev1NewBuildFund		=	$_POST['Lev1NewBuildFund'];
$tmpLev1Standard			=	$_POST['Lev1Standard'];
$tmpLev1StandardFund		=	$_POST['Lev1StandardFund'];

$tmpLev2NewBuild			=	$_POST['Lev2NewBuild'];
$tmpLev2NewBuildFund		=	$_POST['Lev2NewBuildFund'];
$tmpLev2Standard			=	$_POST['Lev2Standard'];
$tmpLev2StandardFund		=	$_POST['Lev2StandardFund'];

$tmpLev3NewBuild			=	$_POST['Lev3NewBuild'];
$tmpLev3NewBuildFund		=	$_POST['Lev3NewBuildFund'];
$tmpLev3Standard			=	$_POST['Lev3Standard'];
$tmpLev3StandardFund		=	$_POST['Lev3StandardFund'];

$tmpLev4NewBuild			=	$_POST['Lev4NewBuild'];
$tmpLev4NewBuildFund		=	$_POST['Lev4NewBuildFund'];
$tmpLev4Standard			=	$_POST['Lev4Standard'];
$tmpLev4StandardFund		=	$_POST['Lev4StandardFund'];

$tmpLite					=	$_POST['Lite'];
$tmpLiteFund				=	$_POST['LiteFund'];





			# this is for the new movesafe table
			$dbRsTable	=	$gMysql->QueryRow("SELECT * FROM admin_movesafe_table WHERE id='$tmpId'",__FILE__,__LINE__);
			if	(empty($dbRsTable))
			{
				$company_id	=	$gMysql->QueryItem("SELECT code FROM solicitor where id='$tmpId'",__FILE__,__LINE__);
				$gMysql->Insert("insert into admin_movesafe_table (id,company_id) values('$tmpId','$company_id') ",__FILE__,__LINE__);
			}

			$gMysql->Update("update admin_movesafe_table set

Lev1Indemnity='$tmpLev1Indemnity',
Lev2Indemnity='$tmpLev2Indemnity',
Lev3Indemnity='$tmpLev3Indemnity',
Lev4Indemnity='$tmpLev4Indemnity',
Lev1NewBuild='$tmpLev1NewBuild',
Lev2NewBuild='$tmpLev2NewBuild',
Lev3NewBuild='$tmpLev3NewBuild',
Lev4NewBuild='$tmpLev4NewBuild',
Lev1NewBuildFund='$tmpLev1NewBuildFund',
Lev2NewBuildFund='$tmpLev2NewBuildFund',
Lev3NewBuildFund='$tmpLev3NewBuildFund',
Lev4NewBuildFund='$tmpLev4NewBuildFund',
Lev1Standard='$tmpLev1Standard',
Lev2Standard='$tmpLev2Standard',
Lev3Standard='$tmpLev3Standard',
Lev4Standard='$tmpLev4Standard',
Lev1StandardFund='$tmpLev1StandardFund',
Lev2StandardFund='$tmpLev2StandardFund',
Lev3StandardFund='$tmpLev3StandardFund',
Lev4StandardFund='$tmpLev4StandardFund',
Lite='$tmpLite',
LiteFund='$tmpLiteFund'

				WHERE id='$tmpId'",__FILE__,__LINE__);


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
	$dbRsItem = mysql_query("SELECT * FROM solicitor WHERE id = " . $tmpId);
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






	$allowPackageTour	= intval($item["allowPackageTour"]);
	$cbxallowPackageTour= "";
	if	($allowPackageTour == 0)
	{
	}
	else
	{
		$cbxallowPackageTour	=	"checked='checked'";
	}






	$allowInjuryAbroad	= intval($item["allowInjuryAbroad"]);
	$cbxallowInjuryAbroad= "";
	if	($allowInjuryAbroad == 0)
	{
	}
	else
	{
		$cbxallowInjuryAbroad	=	"checked='checked'";
	}




	$allowAirline	= intval($item["allowAirline"]);
	$cbxallowAirline= "";
	if	($allowAirline == 0)
	{
	}
	else
	{
		$cbxallowAirline	=	"checked='checked'";
	}


	$allowNonTribunal	= intval($item["allowNonTribunal"]);
	$cbxallowNonTribunal= "";
	if	($allowNonTribunal == 0)
	{
	}
	else
	{
		$cbxallowNonTribunal	=	"checked='checked'";
	}



	$allowChildRefusedPayment	= intval($item["allowChildRefusedPayment"]);
	$cbxallowChildRefusedPayment= "";
	if	($allowChildRefusedPayment == 0)
	{
	}
	else
	{
		$cbxallowChildRefusedPayment	=	"checked='checked'";
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





	$applyIndividualPremiumPoints	= intval($item["applyIndividualPremiumPoints"]);
	$cbxapplyIndividualPremiumPoints= "";
	if	($applyIndividualPremiumPoints == 0)
	{
	}
	else
	{
		$cbxapplyIndividualPremiumPoints	=	"checked='checked'";
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


$strSQL = "SELECT id, name FROM solicitor WHERE status = 'A' AND LENGTH( master_policy ) <=1 ORDER BY name";
$dbRsSolicitors = mysql_query($strSQL);

# cm new for 13/03/2014
$strSQL = "SELECT id, name FROM solicitor WHERE status = 'A' AND LENGTH( master_policy ) <=1 ORDER BY name";
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




# this is for the new movesafe table
$dbRsTable	=	$gMysql->QueryRow("SELECT * FROM admin_movesafe_table WHERE id='$tmpId'",__FILE__,__LINE__);
if	(empty($dbRsTable))
{
	$company_id	=	$gMysql->QueryItem("SELECT code FROM solicitor where id='$tmpId'",__FILE__,__LINE__);

	$gMysql->Insert("insert into admin_movesafe_table (id,company_id) values('$tmpId','$company_id') ",__FILE__,__LINE__);

	$dbRsTable	=	$gMysql->QueryRow("SELECT * FROM admin_movesafe_table WHERE id='$tmpId'",__FILE__,__LINE__);

}

$tmpLev1Indemnity			=	$dbRsTable['Lev1Indemnity'];
$tmpLev2Indemnity			=	$dbRsTable['Lev2Indemnity'];
$tmpLev3Indemnity			=	$dbRsTable['Lev3Indemnity'];
$tmpLev4Indemnity			=	$dbRsTable['Lev4Indemnity'];

$tmpLev1NewBuild			=	$dbRsTable['Lev1NewBuild'];
$tmpLev1NewBuildFund		=	$dbRsTable['Lev1NewBuildFund'];
$tmpLev1Standard			=	$dbRsTable['Lev1Standard'];
$tmpLev1StandardFund		=	$dbRsTable['Lev1StandardFund'];

$tmpLev2NewBuild			=	$dbRsTable['Lev2NewBuild'];
$tmpLev2NewBuildFund		=	$dbRsTable['Lev2NewBuildFund'];
$tmpLev2Standard			=	$dbRsTable['Lev2Standard'];
$tmpLev2StandardFund		=	$dbRsTable['Lev2StandardFund'];

$tmpLev3NewBuild			=	$dbRsTable['Lev3NewBuild'];
$tmpLev3NewBuildFund		=	$dbRsTable['Lev3NewBuildFund'];
$tmpLev3Standard			=	$dbRsTable['Lev3Standard'];
$tmpLev3StandardFund		=	$dbRsTable['Lev3StandardFund'];

$tmpLev4NewBuild			=	$dbRsTable['Lev4NewBuild'];
$tmpLev4NewBuildFund		=	$dbRsTable['Lev4NewBuildFund'];
$tmpLev4Standard			=	$dbRsTable['Lev4Standard'];
$tmpLev4StandardFund		=	$dbRsTable['Lev4StandardFund'];

$tmpLite					=	$dbRsTable['Lite'];
$tmpLiteFund				=	$dbRsTable['LiteFund'];





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


		<input type="hidden" name="cbxallowAirline" id="cbxallowAirline" value="<? echo $allowAirline; ?>" />
		<input type="hidden" name="cbxallowPackageTour" id="cbxallowPackageTour" value="<? echo $allowPackageTour; ?>" />
		<input type="hidden" name="cbxallowInjuryAbroad" id="cbxallowInjuryAbroad" value="<? echo $allowInjuryAbroad; ?>" />


		<input type="hidden" name="cbxallowNonTribunal" id="cbxallowNonTribunal" value="<? echo $allowNonTribunal; ?>" />
		<input type="hidden" name="cbxallowChildRefusedPayment" id="cbxallowChildRefusedPayment" value="<? echo $allowChildRefusedPayment; ?>" />
















		<input type="hidden" name="cbxallowImmigration" id="cbxallowImmigration" value="<? echo $allowImmigration; ?>" />
	<input type="hidden" name="cbxallowHousingDisrepair" id="cbxallowHousingDisrepair" value="<? echo $allowHousingDisrepair; ?>" />
	<input type="hidden" name="cbxallowFamilyApplications" id="cbxallowFamilyApplications" value="<? echo $allowFamilyApplications; ?>" />
	<input type="hidden" name="cbxallowCivilLitigation" id="cbxallowCivilLitigation" value="<? echo $allowCivilLitigation; ?>" />
	<input type="hidden" name="cbxallowCommercialLitigation" id="cbxallowCommercialLitigation" value="<? echo $allowCommercialLitigation; ?>" />
	<input type="hidden" name="cbxallowInsolvency" id="cbxallowInsolvency" value="<? echo $allowInsolvency; ?>" />
	<input type="hidden" name="cbxallowWillsandProbate" id="cbxallowWillsandProbate" value="<? echo $allowWillsandProbate; ?>" />
	<input type="hidden" name="cbxallowConveyances" id="cbxallowConveyances" value="<? echo $allowConveyances; ?>" />

	<input type="hidden" name="cbxapplyIndividualPremiumPoints" id="cbxapplyIndividualPremiumPoints" value="<? echo $applyIndividualPremiumPoints; ?>" />




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

				<label>Individual Premium Points Apply</label>
				<input type="checkbox" name="cbxapplyIndividualPremiumPoints" id="cbxapplyIndividualPremiumPoints" value="yes" <? echo $cbxapplyIndividualPremiumPoints; ?>>

			</p>
			<p>

				<label>Disable fund limit</label>
				<input type="checkbox" name="cbxdisableFundLimit" id="cbxdisableFundLimit" value="yes" <? echo $cbxdisableFundLimit; ?>>

			</p>
			<p>
				<label >Current fund:</label>
			 	&pound; <? echo $claims_fund; ?></p>
            <p>
                <label>Disable Bespoke policies</label>
                <input type="checkbox" name="cbxdisableBespoke" id="cbxdisableBespoke" value="yes" <? echo $cbxdisableBespoke; ?>>

            </p>


            <p>
                <label>Clinical Negligence Allowed</label>
                <input type="checkbox" name="cbxallowCriminalNegligence" id="cbxallowCriminalNegligence" value="yes" <? echo $cbxallowCriminalNegligence; ?>>

            </p>



			<p>
				<label>Housing Disrepair</label>
				<input type="checkbox" name="cbxallowHousingDisrepair" id="cbxallowHousingDisrepair" value="yes" <? echo $cbxallowHousingDisrepair; ?>>
			</p>


			<p>
				<label>PIP Implant Allowed</label>
				<input type="checkbox" name="cbxallowPIP" id="cbxallowPIP" value="yes" <? echo $cbxallowPIP; ?>>

			</p>
			<p>
				<label>Payment Protection (PPI) Allowed</label>
				<input type="checkbox" name="cbxallowPPI" id="cbxallowPPI" value="yes" <? echo $cbxallowPPI; ?>>

			</p>




            <p>
                <label>Stress Allowed</label>
                <input type="checkbox" name="cbxallowStress" id="cbxallowStress" value="yes" <? echo $cbxallowStress; ?>>

            </p>
            <p>
                <label>Financial Mis-selling Allowed</label>
                <input type="checkbox" name="cbxallowFinancial" id="cbxallowFinancial" value="yes" <? echo $cbxallowFinancial; ?>>

            </p>
            <p>
                <label>Medical Products (HIP) Allowed</label>
                <input type="checkbox" name="cbxallowMedical" id="cbxallowMedical" value="yes" <? echo $cbxallowMedical; ?>>

            </p>

			<p>
				<label>Interest Rate Swap (IRS) Allowed</label>
				<input type="checkbox" name="cbxallowInterestRate" id="cbxallowInterestRate" value="yes" <? echo $cbxallowInterestRate; ?>>

			</p>

			<p>
				<label>Referrer Cases Allowed</label>
				<input type="checkbox" name="cbxallowReferrerCases" id="cbxallowReferrerCases" value="yes" <? echo $cbxallowReferrerCases; ?>>

			</p>



			<p>
				<label>Stopped Firm Activated</label>
				<input type="checkbox" name="cbxallowStoppedFirm" id="cbxallowStoppedFirm" value="yes" <? echo $cbxallowStoppedFirm; ?>>

			</p>

			<p>
				<label>False Imprisonment Cases Allowed</label>
				<input type="checkbox" name="cbxallowFalseImprisonment" id="cbxallowFalseImprisonment" value="yes" <? echo $cbxallowFalseImprisonment; ?>>

			</p>




			<p>
				<label>Food Poisoning in UK or Abroad</label>
				<input type="checkbox" name="cbxallowPackageTour" id="cbxallowPackageTour" value="yes" <? echo $cbxallowPackageTour; ?>>

			</p>



			<p>
				<label>Injury Abroad</label>
				<input type="checkbox" name="cbxallowInjuryAbroad" id="cbxallowInjuryAbroad" value="yes" <? echo $cbxallowInjuryAbroad; ?>>

			</p>


			<p>
				<label>Flight Claims</label>
				<input type="checkbox" name="cbxallowAirline" id="cbxallowAirline" value="yes" <? echo $cbxallowAirline; ?>>

			</p>

			<p>
				<label>Non Tribunal Employment claims</label>
				<input type="checkbox" name="cbxallowNonTribunal" id="cbxallowNonTribunal" value="yes" <? echo $cbxallowNonTribunal; ?>>

			</p>

			<p>
				<label>Cancellations - Allow 'child - court refused payment'</label>
				<input type="checkbox" name="cbxallowChildRefusedPayment" id="cbxallowChildRefusedPayment" value="yes" <? echo $cbxallowChildRefusedPayment; ?>>

			</p>



		</fieldset>






    	<fieldset >
			<legend>Own Costs Insurance Settings</legend>
			<p>
				<label>Immigration</label>
				<input type="checkbox" name="cbxallowImmigration" id="cbxallowImmigration" value="yes" <? echo $cbxallowImmigration; ?>>
			</p>

			<p>
				<label>Family Applications</label>
				<input type="checkbox" name="cbxallowFamilyApplications" id="cbxallowFamilyApplications" value="yes" <? echo $cbxallowFamilyApplications; ?>>
			</p>

			<p>
				<label>Civil Litigation</label>
				<input type="checkbox" name="cbxallowCivilLitigation" id="cbxallowCivilLitigation" value="yes" <? echo $cbxallowCivilLitigation; ?>>
			</p>

			<p>
				<label>Commercial Litigation</label>
				<input type="checkbox" name="cbxallowCommercialLitigation" id="cbxallowCommercialLitigation" value="yes" <? echo $cbxallowCommercialLitigation; ?>>
			</p>

			<p>
				<label>Insolvency</label>
				<input type="checkbox" name="cbxallowInsolvency" id="cbxallowInsolvency" value="yes" <? echo $cbxallowInsolvency; ?>>
			</p>

			<p>
				<label>Wills and Probate</label>
				<input type="checkbox" name="cbxallowWillsandProbate" id="cbxallowWillsandProbate" value="yes" <? echo $cbxallowWillsandProbate; ?>>
			</p>

			<p>
				<label>Conveyancing</label>
				<input type="checkbox" name="cbxallowConveyances" id="cbxallowConveyances" value="yes" <? echo $cbxallowConveyances; ?>>
			</p>
<!---
            <p>
				<label for="">Purchase from developer = YES</label>
				<input type="text" name="txtPurchaseYes" id="txtMasterPolicy" maxlength="200" value="<? echo $tmpMasterPolicy; ?>" class="formInputData" />
			</p>
            <p>
				<label for="">Purchase from developer = NO</label>
				<input type="text" name="txtMasterPolicy" id="txtMasterPolicy" maxlength="200" value="<? echo $tmpMasterPolicy; ?>" class="formInputData" />
			</p>
--->


<style>

.movesafe th
{
background-color:	#EAF1DD;
}
.movesafe input
{
	text-align: center;
	width: 160px;
}


</style>

</fieldset>
<fieldset>
<legend>Movesafe Tables</legend>
<div style="" align=left>
	<table cellspacing=2 border=1 align=center  style="font-size:11pt;" class="movesafe" >
	<tr ><td></td><th>Indemnity</th><th>New Build</th><th>New Build Fund</th><th>Standard</th><th>Standard Fund</th></tr>
	<tr><th>Level 1</th><td><input id="num" name="Lev1Indemnity" value='<? echo $tmpLev1Indemnity;?>'</td><td><input id="num" name="Lev1NewBuild" value='<? echo $tmpLev1NewBuild;?>'></td><td><input id="num" name="Lev1NewBuildFund" value='<? echo $tmpLev1NewBuildFund;?>'></td><td><input id="num" name="Lev1Standard" value='<? echo $tmpLev1Standard;?>'></td><td><input name="Lev1StandardFund" value='<? echo $tmpLev1StandardFund;?>'></td></tr>
	<tr><th>Level 2</th><td><input id="num" name="Lev2Indemnity" value='<? echo $tmpLev2Indemnity;?>'</td><td><input id="num" name="Lev2NewBuild" value='<? echo $tmpLev2NewBuild;?>'></td><td><input id="num" name="Lev2NewBuildFund" value='<? echo $tmpLev2NewBuildFund;?>'></td><td><input id="num" name="Lev2Standard" value='<? echo $tmpLev2Standard;?>'></td><td><input name="Lev2StandardFund" value='<? echo $tmpLev2StandardFund;?>'></td></tr>
	<tr><th>Level 3</th><td><input id="num" name="Lev3Indemnity" value='<? echo $tmpLev3Indemnity;?>'</td><td><input id="num" name="Lev3NewBuild" value='<? echo $tmpLev3NewBuild;?>'></td><td><input id="num" name="Lev3NewBuildFund" value='<? echo $tmpLev3NewBuildFund;?>'></td><td><input id="num" name="Lev3Standard" value='<? echo $tmpLev3Standard;?>'></td><td><input name="Lev3StandardFund" value='<? echo $tmpLev3StandardFund;?>'></td></tr>
	<tr><th>Level 4</th><td><input id="num" name="Lev4Indemnity" value='<? echo $tmpLev4Indemnity;?>'</td><td><input id="num" name="Lev4NewBuild" value='<? echo $tmpLev4NewBuild;?>'></td><td><input id="num" name="Lev4NewBuildFund" value='<? echo $tmpLev4NewBuildFund;?>'></td><td><input id="num" name="Lev4Standard" value='<? echo $tmpLev4Standard;?>'></td><td><input name="Lev4StandardFund" value='<? echo $tmpLev4StandardFund;?>'></td></tr>
	</table>
</div>

<div style="width:900px;" align=left>
<br>
	<table cellspacing=2 border=1 align=center  style="font-size:12pt;" class="movesafe" >
	<tr ><th>Movesafe Lite</th><th>Movesafe Lite Fund</th></tr>
	<tr><td><input id="num" name="Lite" value='<? echo $tmpLite;?>'></td><td><input id="num" name="LiteFund" value='<? echo $tmpLiteFund;?>'></td></tr>
	</table>
</div>




	    </fieldset>

<script>

$('#Lev1NewBuild').val(22.5);

// make sure numeric only
$('[id="num"]').bind('keypress', function (evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))		return false;
	return true;
});

</script>


<?php




?>













        <?php
		echo '<fieldset>
					<legend>Referrers</legend>
					<p>';
					$aReferrers = explode(',',$tmpReferrers);


				while($item1 = mysql_fetch_array($dbRsSolicitors))
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
					echo '<span style="width:300px; display:inline; float:left;"><label>'.$item1["name"].'</label><input type="checkbox" name="cbxReferrers[]" id="cbxReferrers[]" value="'.$item1["id"].'" '.$checked.' /></span>';
				}
				echo '</p></fieldset>';






		echo '<fieldset>
					<legend>MoveSafe Referrers</legend>
					<p>';
					$aReferrers = explode(',',$tmpMovesafeReferrers);

				$data	=	$gMysql->SelectToArray("SELECT id, name FROM solicitor WHERE status = 'A' AND LENGTH( master_policy ) <=1 ORDER BY name",__FILE__,__LINE__);
				foreach ($data as $item1)
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
					echo '<span style="width:300px; display:inline; float:left;"><label>'.$item1["name"].'</label><input type="checkbox" name="cbxMovesafeReferrers[]" id="cbxMovesafeReferrers[]" value="'.$item1["id"].'" '.$checked.' /></span>';
				}
				echo '</p>










				<hr style="width:100%; display:inline; float:left;" />
				<p>
			<label for="txtMasterPolicy">Update users</label>
            <input type="checkbox" name="cbxUpdateUsers" id="cbxUpdateUsers" /> &nbsp;check this box to update the referrers for all users associated with this solicitor

				<hr style="width:100%; display:inline; float:left;" />
				<p>
			<label for="txtMasterPolicy">Update Movesafe users</label>
            <input type="checkbox" name="cbxUpdateMovesafeUsers" id="cbxUpdateMovesafeUsers" /> &nbsp;check this box to update the Movesafe referrers for all users associated with this solicitor
		</p>
					</fieldset>';
		?>

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
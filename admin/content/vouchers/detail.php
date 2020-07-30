<?
define('hasEditor','true');

$pageTitle = "Voucher Detail";
$pageSection = "2";
$subSection = "1";
#include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$gMysql				=	new Mysql_Library();



if(isset($_POST["voucherID"]))
{
	$tmpId = intval($_POST["voucherID"]);
}
elseif(isset($_GET["voucherID"]))
{
	$tmpId = intval($_GET["voucherID"]);
}
else
{
	ob_end_clean();
	header("Location: ./");
	exit();
}


if(isset($_POST["btnSubmit"]))
{
	$strSQL = "UPDATE fp_voucher_code SET code= '$formCode',  value='$formValue', type='$formType', added = NOW() WHERE id = $tmpId";
	$gMysql->update($strSQL,__FILE__,__LINE__);

	ob_end_clean();
	header("Location: ./");
	exit();


}
else if	(isset($_POST['btnDelete']) || isset($_GET['btnDelete']))
{
	$strSQL = "delete from fp_voucher_code where id='$tmpId'";
	$gMysql->Delete($strSQL,__FILE__,__LINE__);

	ob_end_clean();
	header("Location: ./");
	exit();

}







# grab the data
$strSQL				=	"SELECT * FROM fp_voucher_code WHERE id = " . $tmpId;
$data				=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);
$formCode			=	$data['code'];
$formValue			=	$data['value'];
$formType			=	$data['type'];
$formCommission		=	$data['commission'];


$pageTitle 			= 	"Voucher Detail";




?>

<div class="mainAdminPage">


<div class="container-fluid">
	<h1><? echo $pageTitle ?></h1>

	<div class="col-xs-12">
		<form name="frmPage" id="frmPage" method="post" class="formEditor" enctype="multipart/form-data"  onsubmit="">
			<input type="hidden" name="voucherID" id="voucherID" value="<? echo $tmpId; ?>" />

				<div class="row container-fluid " style="padding:0px;margin:0px;">

					<div class="form-group col-xs-6">
						<label >Code</label>
						<input type="text" name="formCode" id="formCode" value="<? echo $formCode ?>" class="form-control input-small">
					</div>
					<div class="form-group col-xs-6">
						<label >Value (Amount off the fees)</label>
						<input type="text" name="formValue" id="formValue" value="<? echo $formValue ?>" class="form-control input-small">
					</div>
					<div class="form-group col-xs-6">
						<label >Commission (not relevant for standard codes)</label>
						<input disabled type="text" name="formCommission" id="formCommission" value="<? echo $formCommission ?>" class="form-control input-small">
					</div>
					<br>
					<div class="form-group col-xs-6" >
						<label class=""  style="width:100%;">Type (normal codes will be type <b>F</b> - fixed by default)</label><br>
						<input type="text" name="formType" id="formType" value="<? echo $formType ?>" class="form-control input-small">
					</div>


					<br><br>

					<div class="form-group col-xs-12" >

						<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
						<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
						<input type="submit" name="btnDelete" id="btnDelete" value="Delete" class="formButton" />

					<br><br>
					</div>


				</div>

		</form>
	</div>
</div>

<script>




















</script>

<?


include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");



?>


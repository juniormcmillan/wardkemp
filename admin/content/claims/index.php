<?
define('hasEditor','false');

$pageTitle = "Settings";
$pageSection = "2";
$subSection = "1";
#include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$gMysql				=	new Mysql_Library();


# values for each item
$fp_value_1 		=	GetVariableString("fp_value_1",$_POST,"",$item['mvc_banner_1']);
$fp_value_2 		=	GetVariableString("fp_value_2",$_POST,"",$item['mvc_banner_2']);
$fp_value_3 		=	GetVariableString("fp_value_3",$_POST,"",$item['mvc_banner_3']);

# values
$fp_value_1 		=	GetVariable("fp_value_1",$_POST,"",$fp_value_1);
$fp_value_2 		=	GetVariable("fp_value_2",$_POST,"",$fp_value_2);
$fp_value_3 		=	GetVariable("fp_value_3",$_POST,"",$fp_value_3);
$fp_value_4 		=	GetVariable("fp_value_4",$_POST,"",$fp_value_4);
$fp_value_5 		=	GetVariable("fp_value_5",$_POST,"",$fp_value_5);

# euros
$fp_euro_1 		=	GetVariable("fp_euro_1",$_POST,"",$fp_euro_1);
$fp_euro_2 		=	GetVariable("fp_euro_2",$_POST,"",$fp_euro_2);
$fp_euro_3 		=	GetVariable("fp_euro_3",$_POST,"",$fp_euro_3);
$fp_euro_4 		=	GetVariable("fp_euro_4",$_POST,"",$fp_euro_4);
$fp_euro_5 		=	GetVariable("fp_euro_5",$_POST,"",$fp_euro_5);


$fp_desc_1 			=	GetVariableString("fp_desc_1",$_POST,"","");
$fp_desc_2 			=	GetVariableString("fp_desc_2",$_POST,"","");
$fp_desc_3 			=	GetVariableString("fp_desc_3",$_POST,"","");
$fp_desc_4 			=	GetVariableString("fp_desc_4",$_POST,"","");
$fp_desc_5 			=	GetVariableString("fp_desc_5",$_POST,"","");


# posting
if(isset($_POST["btnSubmit"]))
{
    # update pound
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_1', value='$fp_value_1' where id=1";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_2', value='$fp_value_2' where id=2";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_3', value='$fp_value_3' where id=3";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_4', value='$fp_value_4' where id=4";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_5', value='$fp_value_5' where id=5";
    $gMysql->update($strSQL,__FILE__,__LINE__);

    # update euro
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_1', euro='$fp_euro_1' where id=1";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_2', euro='$fp_euro_2' where id=2";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_3', euro='$fp_euro_3' where id=3";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_4', euro='$fp_euro_4' where id=4";
    $gMysql->update($strSQL,__FILE__,__LINE__);
    $strSQL = "UPDATE fp_pricepoints SET description='$fp_desc_5', euro='$fp_euro_5' where id=5";
    $gMysql->update($strSQL,__FILE__,__LINE__);


    header("Location: ./");
	exit();
}
else
{
	$strSQL			=	"SELECT * FROM fp_pricepoints where id=1";
	$item			=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);
	$fp_value_1		=	$item['value'];
    $fp_euro_1		=	$item['euro'];
    $fp_desc_1		=	$item['description'];

	$strSQL			=	"SELECT * FROM fp_pricepoints where id=2";
	$item			=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);
	$fp_value_2		=	$item['value'];
    $fp_euro_2		=	$item['euro'];
    $fp_desc_2		=	$item['description'];

	$strSQL			=	"SELECT * FROM fp_pricepoints where id=3";
	$item			=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);
	$fp_value_3		=	$item['value'];
    $fp_euro_3		=	$item['euro'];
	$fp_desc_3		=	$item['description'];

	$strSQL			=	"SELECT * FROM fp_pricepoints where id=4";
	$item			=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);
	$fp_value_4		=	$item['value'];
    $fp_euro_4		=	$item['euro'];
	$fp_desc_4		=	$item['description'];

	$strSQL			=	"SELECT * FROM fp_pricepoints where id=5";
	$item			=	$gMysql->queryRow($strSQL,__FILE__,__LINE__);
	$fp_value_5		=	$item['value'];
    $fp_euro_5		=	$item['euro'];
	$fp_desc_5		=	$item['description'];


}
?>
<style>
.header
{
	font-weight:	bold;
	font-size:12pt;
	background-color: #064A70;
	padding:10px;
	color: #ffffff;
}
.row-items
{
	font-size:14pt;
	padding:10px;
}


</style>

	<div class="mainAdminPages">

	<form name="frmPage" id="frmPage" method="post" class="formEditor" enctype="multipart/form-data">
		<input type="hidden" name="dbAction" id="dbAction" value="<? echo $tmpDbAction; ?>" />

		<div class="container-fluid" style="font-size:larger;">
			<h1>Claim Value Settings </h1><br>

			<div class="row header" >
				<div class="col-xs-2">Item</div>
				<div class="col-xs-8">Description</div>
                <div class="col-xs-1">Pound</div>
                <div class="col-xs-1">Euro</div>
			</div>
			<div class="row row-items">
				<div class="col-xs-2">1</div>
				<div class="col-xs-8"><input id="fp_desc_1" name="fp_desc_1" type="text" style="width:100%; " value="<? echo $fp_desc_1; ?>"></div>
				<div class="col-xs-1"><input id="fp_value_1" name="fp_value_1" type="number" style="width:100%; " value="<? echo $fp_value_1; ?>"></div>
                <div class="col-xs-1"><input id="fp_euro_1" name="fp_euro_1" type="number" style="width:100%; " value="<? echo $fp_euro_1; ?>"></div>
            </div>
			<div class="row row-items">
				<div class="col-xs-2">2</div>
				<div class="col-xs-8"><input id="fp_desc_2" name="fp_desc_2" type="text" style="width:100%; " value="<? echo $fp_desc_2; ?>"></div>
				<div class="col-xs-1"><input id="fp_value_2" name="fp_value_2" type="number" style="width:100%; " value="<? echo $fp_value_2; ?>"></div>
                <div class="col-xs-1"><input id="fp_euro_2" name="fp_euro_2" type="number" style="width:100%; " value="<? echo $fp_euro_2; ?>"></div>
            </div>
			<div class="row row-items">
				<div class="col-xs-2">3</div>
				<div class="col-xs-8"><input id="fp_desc_3" name="fp_desc_3" type="text" style="width:100%; " value="<? echo $fp_desc_3; ?>"></div>
				<div class="col-xs-1"><input id="fp_value_3" name="fp_value_3" type="number" style="width:100%; " value="<? echo $fp_value_3; ?>"></div>
                <div class="col-xs-1"><input id="fp_euro_3" name="fp_euro_3" type="number" style="width:100%; " value="<? echo $fp_euro_3; ?>"></div>
            </div>
			<div class="row row-items">
				<div class="col-xs-2">4</div>
				<div class="col-xs-8"><input id="fp_desc_4" name="fp_desc_4" type="text" style="width:100%; " value="<? echo $fp_desc_4; ?>"></div>
				<div class="col-xs-1"><input id="fp_value_4" name="fp_value_4" type="number" style="width:100%; " value="<? echo $fp_value_4; ?>"></div>
                <div class="col-xs-1"><input id="fp_euro_4" name="fp_euro_4" type="number" style="width:100%; " value="<? echo $fp_euro_4; ?>"></div>
            </div>
			<div class="row row-items">
				<div class="col-xs-2">5</div>
				<div class="col-xs-8"><input id="fp_desc_5" name="fp_desc_5" type="text" style="width:100%; " value="<? echo $fp_desc_5; ?>"></div>
				<div class="col-xs-1"><input id="fp_value_5" name="fp_value_5" type="number" style="width:100%; " value="<? echo $fp_value_5; ?>"></div>
                <div class="col-xs-1"><input id="fp_euro_5" name="fp_euro_5" type="number" style="width:100%; " value="<? echo $fp_euro_5; ?>"></div>
            </div>
			<div class="col-xs-12">
				<input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
				<input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
			</div>
		</div>
	</form>
	</div>

<?

include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");



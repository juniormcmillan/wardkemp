<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 20/05/12
 * Time: 19:10
 * TO DO:  cross reference ID and person in actual database
 *
 */
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

$formBank			= GetVariableString('formBank',$_GET,"");
$formAccount 		= GetVariableString('formAccount',$_GET,"");
$formAccountName 	= GetVariableString('formAccountName',$_GET,"");
$formSortCode1 		= GetVariableString('formSortCode1',$_GET,"");
$formSortCode2 		= GetVariableString('formSortCode2',$_GET,"");
$formSortCode3 		= GetVariableString('formSortCode3',$_GET,"");


$refcode 		= GetVariableString('refcode',$_GET,"");
$surname 		= GetVariableString('surname',$_GET,"");
$childname 		= GetVariableString('childname',$_GET,"");

#AddComment("doConfirm popup Called by $refcode for $surname and (child:$childname) ",false);








$sort_code		=	$formSortCode1	.	$formSortCode2 .	$formSortCode3;
$account_number	=	$formAccount;







$popup=	'
<style>
.control-label
{
	padding-top:5px;
}

</style>
<div id="pop">

	Please confirm that your Bank Details are correct.

<div class="form-group ">
	<br>
	<br>

	<label class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-0 control-label" >Name of account holder:</label>
	<div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-0">
		<input id="popAccount" type="text" disabled class="form-control form-control-inline-fix " style="display:inline-block;  " placeholder="" value="'.  $formAccountName .'" required>
	</div>
	<br>
	<br>
</div>


<div class="form-group ">

	<label class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-0 control-label" >Name of Bank:</label>
	<div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-0">
		<input id="popAccount" type="text" disabled class="form-control form-control-inline-fix " style="display:inline-block;  " placeholder="" value="'.  $formBank .'" required>
	</div>
	<br>
	<br>
<br>
</div>

<div class="form-group ">

	<label class="col-xs-7 col-xs-offset-3 col-sm-4 col-sm-offset-0 control-label" >Account Number:</label>
	<div class="col-xs-7 col-xs-offset-3 col-sm-8 col-sm-offset-0">
		<input id="popAccount" type="text" disabled class="form-control form-control-inline-fix number" style="display:inline-block;  width:165px; letter-spacing:2px;" maxlength="30" placeholder="" value="'.  $formAccount .'" required>
	</div>
	<br>
	<br>
</div>

<div class="form-group sort">
	<label class="col-xs-7 col-xs-offset-3 col-sm-4 col-sm-offset-0  control-label">Sort Code:</label>

	<div class="col-xs-7 col-xs-offset-3 col-sm-8 col-sm-offset-0  form-group2">
		<input id="popSort1" class="scGroup form-control form-control-inline-fix " disabled style="width:45px;" type="text" maxlength="2"  value="'.  $formSortCode1 .'"/>
		-
		<input id="popSort2" class="scGroup form-control form-control-inline-fix " disabled style="width:45px;" type="text" maxlength="2"  value="'.  $formSortCode2 .'"/>
		-
		<input id="popSort3" class="scGroup form-control form-control-inline-fix " disabled style="width:45px;" type="text" maxlength="2"  value="'.  $formSortCode3 .'"/>

		<br>


	</div >
</div>
	<br>
	<br>
	<br>
	<br>

</div>
';















echo	$popup;

#echo json_encode($popup);



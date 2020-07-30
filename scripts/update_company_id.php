<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


# mysql
$gMysql			=	new Mysql_Library();

/*
 *
 *
*/


SetCommentFile("update_info.txt");

# check for variables
$case_key			= GetVariableString('case_key',$_GET,"");
$company_id			= GetVariableString('company_id',$_GET,"");


AddComment("Ok: case_key: $case_key
company_id:$company_id

");


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUserViaCaseKey($case_key)) != NULL)
{
	AddComment("FOUND CASE_KEY / EMAIL: CASE_KEY:$case_key");

	# update details from proclaim - we may have some adjustments to name etc, so accept this
	$gUser->updateCompanyID($case_key,$company_id);
}


echo "OK: ". $case_key . " " . $company_id;

<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


# mysql
$gMysql			=	new Mysql_Library();

/*

*/


SetCommentFile("update_user_statement.txt");

# check for variables
$case_key		= GetVariableString('case_key',$_GET,"");
$q1				= ucfirst(strtolower(GetVariableString('q1',$_GET,"")));
$q2				= ucfirst(strtolower(GetVariableString('q2',$_GET,"")));
$q3				= ucfirst(strtolower(GetVariableString('q3',$_GET,"")));
$q4				= ucfirst(strtolower(GetVariableString('q4',$_GET,"")));
$q5				= ucfirst(strtolower(GetVariableString('q5',$_GET,"")));
$q6				= ucfirst(strtolower(GetVariableString('q6',$_GET,"")));
$q7				= ucfirst(strtolower(GetVariableString('q7',$_GET,"")));
$q8				= ucfirst(strtolower(GetVariableString('q8',$_GET,"")));
$q9				= ucfirst(strtolower(GetVariableString('q9',$_GET,"")));
$q10			= ucfirst(strtolower(GetVariableString('q10',$_GET,"")));
$q11			= ucfirst(strtolower(GetVariableString('q11',$_GET,"")));
$q12			= ucfirst(strtolower(GetVariableString('q12',$_GET,"")));
$q13			= ucfirst(strtolower(GetVariableString('q13',$_GET,"")));
$q14			= ucfirst(strtolower(GetVariableString('q14',$_GET,"")));
$q15			= ucfirst(strtolower(GetVariableString('q15',$_GET,"")));


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUserViaCaseKey($case_key)) != NULL)
{
	AddComment("FOUND CASE_KEY:$case_key");

	# update details from proclaim - we may have some adjustments to name etc, so accept this
	$gUser->updateUserStatement($case_key,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10,$q11,$q12,$q13,$q14,$q15);
}
else
{
	AddComment("CANNOT FIND CASE_KEY:$case_key");

}



echo "OK: ". $case_key;

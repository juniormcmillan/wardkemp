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

$formAccount 		= GetVariableString('formAccount',$_POST,"");
$formAccountName 	= GetVariableString('formAccountName',$_POST,"");
$formSortCode1 		= GetVariableString('formSortCode1',$_POST,"");
$formSortCode2 		= GetVariableString('formSortCode2',$_POST,"");
$formSortCode3 		= GetVariableString('formSortCode3',$_POST,"");


$refcode 		= GetVariableString('refcode',$_POST,"");
$surname 		= GetVariableString('surname',$_POST,"");
$childname 		= GetVariableString('childname',$_POST,"");

AddComment("doAccountCheck Called by $refcode for $surname and (child:$childname) ");







# build sort code
$sort_code		=	$formSortCode1	.	$formSortCode2 .	$formSortCode3;
$account_number	=	$formAccount;

$ip_address		=	$_SERVER['REMOTE_ADDR'];
# http://ws.esortcode.com/bankdetails.asmx/ValidateAccountGetBranchDetails?sSortCode=087199&sAccountNumber=16897876&sUserName=BOXLEGAL01&sGUID=e4b0a618-9e78-48b0-8ee7-e141d90dbe87&sIPAddress=109.158.80.131
$url	=	"https://ws.esortcode.com/bankdetails.asmx/ValidateAccountGetBranchDetails?sSortCode=$sort_code&sAccountNumber=$account_number&sUserName=BOXLEGAL01&sGUID=e4b0a618-9e78-48b0-8ee7-e141d90dbe87&sIPAddress=$ip_address";
$url	=	"https://ws.esortcode.com/bankdetails.asmx/ValidateAccountGetBranchDetails?sSortCode=$sort_code&sAccountNumber=$account_number&sUserName=BOXLEGAL01&sGUID=e4b0a618-9e78-48b0-8ee7-e141d90dbe87&sIPAddress=";

$bank_full_name				=	"";
$bank_short_name			=	"";
$bank_validation_message	=	"";

$returnCode		=	"success";
$error_string	=	"";


# load the XML file with the result
if (($x = @simplexml_load_file($url)))
{
	$bank_validation_message	=	(string)$x->ValidationMessage;

	# if this is valid, then display the
	if	(!empty($bank_validation_message))
	{
		if	(strcasecmp($bank_validation_message,"VALID") == 0)
		{
			$bank_full_name			=	(string)$x->GENERALFullNameOwningBankLine1;
			$bank_short_name			=	(string)$x->GENERALShortNameOwningBank;
AddComment("doAccountCheck $bank_full_name ($bank_short_name)");

		}

		else if	(strpos($bank_validation_message, 'INVALID - Sortcode') !== false)
		{
			$returnCode		=	"error";
			$error_string	=	"Sort Code entered incorrectly, Please check";
			AddComment("doAccountCheck POSSIBLE ERROR:$error_string by $refcode for $surname ");
		}
		else if	(strpos($bank_validation_message, 'INVALID - Account has failed Modulus Check') !== false)
		{
			$returnCode		=	"error";
			$error_string	=	"Account Number or Sort Code entered incorrectly. Please check details.";
			AddComment("doAccountCheck POSSIBLE ERROR:$error_string by $refcode for $surname ");
		}
		else if	(strpos($bank_validation_message, 'INVALID - Account') !== false)
		{
			$returnCode		=	"error";
			$error_string	=	"Account Number entered incorrectly. Please check";
			AddComment("doAccountCheck POSSIBLE ERROR:$error_string by $refcode for $surname ");
		}
		else if	(strpos($bank_validation_message, 'Service Test') !== false)
		{
			$returnCode		=	"error";
			$error_string	=	"Details entered incorrectly. Please check";
			AddComment("doAccountCheck POSSIBLE ERROR:$error_string by $refcode for $surname ");

		}


	}
	# code not found... either IRISH or can't be found. (or there is a problem)
	else
	{
		$returnCode		=	"error";
		$error_string	=	"Sort Code may be incorrect. Please Check";
		AddComment("doAccountCheck POSSIBLE ERROR:$error_string by $refcode for $surname ");
	}
}




AddComment("doAccountCheck bank_validation_message:$bank_validation_message, BANK:$bank_full_name ERROR:$error_string by $refcode for $surname ");




# this is the return array with all the commands
$returnArray	=	array();



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array
	(	"returncode" 		=> 		$returnCode,
		"message"			=>		$error_string,
		"bank"				=>		$bank_full_name,
	)

);




# return two variables
echo json_encode($returnArray);


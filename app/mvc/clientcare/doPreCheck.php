<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql					=	new Mysql_Library();

$message				=	"OK";
$returnCode				=	"success";
# this is the return array with all the commands
$returnArray			=	array();

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|","'",'"',"'");

# grab the basic details
$email					=	str_replace($invalid_characters, "", $_POST['email']);
$case_key				=	str_replace($invalid_characters, "", $_POST['case_key']);
$code					=	str_replace($invalid_characters, "", $_POST['code']);

$uri					=	"";

# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUser($case_key,$email)) == NULL)
{
	$message		=	"Details not found";
	$returnCode			=	"error";
}
else
{
	# go through each link
	$menu	=	array(
		array(	"name" => "Our charges (No Win No Fee Agreements)", 										"link" =>	"clientcare/our-charges"						),
		array(	"name" => "Protecting yourself financially (ATE expense Insurance)", 						"link" =>	"clientcare/ate-insurance"					),
		array(	"name" => "Financial Interests ",									 						"link" =>	"clientcare/financial-interests"			),
	);

	foreach	($menu as $menu_item)
	{
		$link			=	$menu_item['link'];
		$name			=	$menu_item['name'];

		# check that this item has been accepted
		if ($gUser->getUserPage($case_key,$email,$link) == NULL)
		{

			$message			=	"Before you can proceed to sign this document, you will need to view and agree to the contents of the <b>". $name."</b> page.<br><br>You will now be taken to the page";
			$returnCode			=	"error";
			$uri				=	$link."/?case_key=$case_key&email=$email&code=$code";

			break;
		}

	}

	# if we get through this phase without a hit, then lets check if we have signed the document or dsar already
	if (empty($uri))
	{
		# check if we have signed the document
		if ($gUser->isClientCareSigned($case_key,$email) == false)
		{
			$uri	=	"clientcare/authority/?case_key=$case_key&email=$email&code=$code";
		}
		else if ($gUser->isDsarSigned($case_key,$email) == false)
		{
			$uri	=	"clientcare/dsar/?case_key=$case_key&email=$email&code=$code";
		}

	}

}



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,
		"uri"				=>		$uri,

	)

);



# return two variables
echo json_encode($returnArray);

<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


# mysql
$gMysql			=	new Mysql_Library();

/*
The real important data is the pID and the email here.

The email is used to find the person, and the pID is the data we really want to update
Further to this, we would use the postcode to make this unique.

(A person could have multiple claims at different addresses but with same email address)


Since we keep a copy of tenant details locally, we don't need all data sent back
The link to upload the

# Until we get webservices working, we'll use an http request
# and ultimately we should daily (even hourly) update by sending all recent ones in a batch
# in case we have a temporary network failure
#
#
#

*/


SetCommentFile("google_update.txt");
$invalid_characters 	=	array("$", "%", "#", "<", ">", "|",",","'","'");

# check for variables
$google_lead_id		= GetVariableString('google_lead_id',$_GET,"");
$company_id			= GetVariableString('company_id',$_GET,"");
$connex				= strtolower(GetVariableString('connex',$_GET,""));
$case_key			= GetVariableString('case_key',$_GET,"");
$address1			= str_replace($invalid_characters, "",GetVariableString('address1',$_GET,""));
$address2			= str_replace($invalid_characters, "",GetVariableString('address2',$_GET,""));
$town				= str_replace($invalid_characters, "",GetVariableString('address3',$_GET,""));
$postcode			= str_replace($invalid_characters, "",GetVariableString('pcode',$_GET,""));
$title				= str_replace($invalid_characters, "",GetVariableString('title',$_GET,""));
$forename			= ucfirst(str_replace($invalid_characters, "",GetVariableString('forename',$_GET,"")));
$surname			= ucfirst(str_replace($invalid_characters, "",GetVariableString('surname',$_GET,"")));
$email				= GetVariableString('email',$_GET,"");
$mobile				= GetVariableString('mobile',$_GET,"");
$type_code			= GetVariableString('Type-Code',$_GET,"");
$campaign			= GetVariable('campaign',$_GET,"");
$defendant			= GetVariableString('defendant',$_GET,"");



# if first numbers are 44, then delete first two numbers
$first_two 				=	substr($mobile, 0, 2);
if($first_two == "44")
{
	$mobile	=		substr($mobile, 2);
}

$first_one 				=	substr($mobile, 0, 1);

if($first_one != "0")
{
	$mobile	=		"0".$mobile;
}




AddComment("Ok: case_key: $case_key
email:$email
address1:$address1
address2:$address2
town:$town
postcode:$postcode
title:$title
forename:$forename
surname:$surname
typecode:$type_code
mobile:$mobile
campaign:$campaign
defendant:$defendant
");


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUserViaLeadID($google_lead_id)) != NULL)
{
	AddCommentOnly("FOUND USER via lead_id:$google_lead_id");

	# update details from proclaim - we may have some adjustments to name etc, so accept this
	$gUser->updateLeadDetails($google_lead_id,$case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$connex,$type_code,$mobile,$campaign,$defendant);
}
else
{
	AddCommentOnly("CANNOT FIND LEAD ID CASE_KEY:$case_key, lead_id:$google_lead_id");
	# need to create from scratch
	if (!empty($google_lead_id))
	{
AddComment("Creating new... LEAD iD:$google_lead_id CASE KEY:$case_key  ");
		$gUser->createLead($google_lead_id,$email,"$forename $surname",$mobile,$case_key);
		$gUser->updateLeadDetails($google_lead_id,$case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$connex,$type_code,$mobile,$campaign,$defendant);
	}
	else if ((!empty($case_key)) && (!empty($email)))
	{
		AddCommentOnly("******** LAST RESORT - CREATING CASE CASE_KEY:$case_key, lead_id:$google_lead_id ");
		$gUser->createUser($email,$title,$forename,$surname,$mobile,$case_key);
		$gUser->updateUserDetails($case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$defendant,$type_code);
	}
}



# send a connex
if ($connex == "yes")
{
	$campaign	=	trim($campaign);

	if (empty($campaign))
	{
AddCommentOnly("setting campaign to 2008");
		$campaign=2008;
	}
	else if ($campaign==2009)
	{
		$campaign=2009;
	}

	$gUser->sendConnex($case_key,$campaign);
}
else
{
	AddCommentOnly("NO for connex");

}
AddCommentOnly("");
AddCommentOnly("");
AddCommentOnly("");
AddCommentOnly("");
AddCommentOnly("");
AddCommentOnly("");
AddCommentOnly("");
AddCommentOnly("");












echo "OK: ". $case_key;

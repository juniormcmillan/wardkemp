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
$company			= strtoupper(GetVariableString('company_id',$_GET,""));
$solicitor			= GetVariableString('solicitor',$_GET,"");



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
company_id:$company
solicitor name:$solicitor
");


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUserViaLeadID($google_lead_id)) != NULL)
{
	AddCommentOnly("FOUND USER via lead_id:$google_lead_id");

	# update details from proclaim - we may have some adjustments to name etc, so accept this
	$gUser->updateLeadDetails($google_lead_id,$case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$connex,$type_code,$mobile,$campaign,$defendant,$company,$solicitor);
}
else
{
	AddCommentOnly("CANNOT FIND LEAD ID CASE_KEY:$case_key, lead_id:$google_lead_id");
	# need to create from scratch
	if (!empty($google_lead_id))
	{
AddComment("Creating new... LEAD iD:$google_lead_id CASE KEY:$case_key  ");
		$gUser->createLead($google_lead_id,$email,"$forename $surname",$mobile,$case_key);
		$gUser->updateLeadDetails($google_lead_id,$case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$connex,$type_code,$mobile,$campaign,$defendant,$company,$solicitor);
	}
	else if ((!empty($case_key)) && (!empty($email)))
	{
		AddCommentOnly("******** LAST RESORT - CREATING CASE CASE_KEY:$case_key, lead_id:$google_lead_id ");
		$gUser->createUser($email,$title,$forename,$surname,$mobile,$case_key);
		$gUser->updateUserDetails($case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$defendant,$google_lead_id,$type_code,$company,$solicitor);
	}
}

# update the link if possible - fixes bad links
if (($data = $gUser->getUserViaCaseKey($case_key)) != NULL)
{
	# update the link if found
	if (!empty($data['link']))
	{
		$api_key			=	REBRANDLY_API;

		# extract the shortcode
		$slashtag	=	substr($data['link'], strpos($data['link'], "/") + 1);

		$ch = curl_init("https://api.rebrandly.com/v1/links?domain.fullName=pw1.uk&slashtag=$slashtag&orderBy=createdAt&orderDir=desc&limit=25");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"apikey: $api_key",
		));
		curl_setopt($ch, CURLOPT_GET, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		$response	=	json_decode($result, true);
		$id			=	$response[0]["id"];
		$title		=	$response[0]["title"];
		$shortUrl	=	$response[0]["shortUrl"];
		AddCommentOnly("$case_key:  |  id:$id tag:$slashtag");
		$gUser->updateLink($case_key,$shortUrl,$id);

		# update the link
		$destination	= "http://www.wardkemp.uk/clientcare/introduction/?case_key=$case_key&email=$email&code=HD";
		$post_data["id"] 			= $id;
		$post_data["title"] 		= $title;
		$post_data["destination"]	= $destination;
		$post_data["shortUrl"]		= $shortUrl;
		$ch = curl_init("https://api.rebrandly.com/v1/links/$id");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"apikey: $api_key",
			"Content-Type: application/json",
		));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
		$result = curl_exec($ch);
		curl_close($ch);
		$response	=	json_decode($result, true);
		$a=2;

		AddCommentOnly("updated");

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

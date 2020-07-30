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


SetCommentFile("update_info.txt");

# check for variables
$case_key		= GetVariableString('case_key',$_GET,"");
$address1		= GetVariableString('address1',$_GET,"");
$address2		= GetVariableString('address2',$_GET,"");
$town			= GetVariableString('address3',$_GET,"");
$postcode		= GetVariableString('pcode',$_GET,"");
$title			= GetVariableString('client_title',$_GET,"");
$forename		= GetVariableString('client_forename',$_GET,"");
$surname		= GetVariableString('client_surname',$_GET,"");
$defendant		= GetVariableString('defendant',$_GET,"");
$email			= GetVariableString('email',$_GET,"");


AddComment("Ok: case_key: $case_key
email:$email
address1:$address1
address2:$address2
town:$town
postcode:$postcode
title:$title
forename:$forename
surname:$surname


");


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUserViaCaseKey($case_key)) != NULL)
{
	AddComment("FOUND CASE_KEY / EMAIL: CASE_KEY:$case_key, email:$email, SURNAME:$surname");

	# update details from proclaim - we may have some adjustments to name etc, so accept this
	$gUser->updateUserDetails($case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$defendant);
}
else
{
	AddComment("CANNOT FIND CASE_KEY / EMAIL ERROR: CASE_KEY:$case_key, email:$email, SURNAME:$surname");


	# need to create from scratch
	if ((!empty($case_key)) && (!empty($email)))
	{
AddComment("Creating new... $case_key $title $forename $surname ");
		$gUser->createUser($email,$title,$forename,$surname,$mobile,$case_key);
		$gUser->updateUserDetails($case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$defendant);
	}
	else
	{
AddComment("SERIOUS PROBLEM CASE_KEY:$case_key  EMAIl:$email SURNAME: $surname");
	}

}



echo "OK: ". $case_key;

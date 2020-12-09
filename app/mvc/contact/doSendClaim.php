<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/PapApi.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();


# grab the basic details
$formData	=	array();
parse_str($_POST['formData'],$formData);

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|", "'");
$formEmail				=	str_replace($invalid_characters, "", $formData['formEmail']);
$formPhone				=	str_replace($invalid_characters, "", $formData['formTelephone']);
$formName				=	str_replace($invalid_characters, "", $formData['formName']);
$formQuestion			=	str_replace($invalid_characters, "", $formData['formQuestion']);



SetCommentFile("claims.txt");


$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();


# error checks
# error checks
if (empty($formName))
{
	$message		=	"Please enter your <b>name</b> in the box provided";
}
else if (empty($formPhone))
{
	$message		=	"Please enter your <b>phone number</b> in the box provided";
}
else if (empty($formEmail))
{
	$message		=	"Please enter your <b>email address</b> in the box provided";
}
else if (empty($formQuestion))
{
	$message		=	"Please select if you reported your issues to your landlord";
}
else if ($formQuestion == "No")
{
	$message		=	"You must have reported this to your landlord before we can take your case any further";
}


if (!empty($message))
{
	$returnCode		=	"error";
	$returnArray	=

		array	(
			"returncode" 		=> 		$returnCode,
			"message"			=>		$message,
		);
	# return two variables
	echo json_encode($returnArray);
	exit;
}




# details for tracking
$papCookie	=	GetVariableString('PAPVisitorId',$_COOKIE);
$a_aid		=	GetVariableString('a_aid',$_COOKIE,0);
$a_bid		=	GetVariableString('a_bid',$_POST,0);



$order_id	=	getToken(16);


AddComment("SALE pending for: a_aid:$a_aid, a_bid:$a_bid, order_id:$order_id");

$saleTracker = new Pap_Api_SaleTracker('https://network.fairplane.co.uk/scripts/sale.php');
$saleTracker->setAccountId('default1');
$saleTracker->setVisitorId($papCookie);

$sale		=	$saleTracker->createSale();
$sale->setTotalCost(0);
$sale->setOrderID($order_id);
$sale->setStatus('P');
$sale->setAffiliateID($a_aid);

# this is important - the product (eg. housing disrepair)
$sale->setProductID('hd2');

$sale->setData1($formName);
$sale->setData2("");
$sale->setData3($formEmail);

$saleTracker->register();

AddComment("SALE registered");

$affiliate_name			=	"";
$affiliate_email		=	"";
$session = new Gpf_Api_Session('https://network.fairplane.co.uk/scripts/server.php');
if(!@$session->login("cedric@fairplane.co.uk", "fgjs6n!"))
{
	AddComment("Cannot login to main PAP account due to :".$session->getMessage());
}
else
{
	AddComment("Logged in to main PAP account");
	if	($a_aid)
	{
		AddComment("Got Affiliate ID:$a_aid");
		# grab the affiliate info
		$affiliate = new Pap_Api_Affiliate($session);
		$affiliate->setRefid($a_aid);
		if	($affiliate->load() == true)
		{
			$affiliate_user_id 		=	$affiliate->getUserid();
			$affiliate_email		=	$affiliate->getUsername();
			$affiliate_fname		=	$affiliate->getFirstname();
			$affiliate_lname		=	$affiliate->getLastname();

			$affiliate_name			=	$affiliate_fname ." ".$affiliate_lname;
			AddComment("Got Affiliate name:$affiliate_fname $affiliate_lname");
		}
	}
}



AddComment("SALE post pap");



# if first numbers are 44, then delete first two numbers
$first_two 				=	substr($formPhone, 0, 2);
if($first_two == "44")
{
	$formPhone	=		substr($formPhone, 2);
}

$first_one 				=	substr($formPhone, 0, 1);

if($first_one != "0")
{
	$formPhone	=		"0".$formPhone;
}



$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/claim/";
$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/claim/";

# the data
$data	=
	"HD" 						.	">".
	$formName					.	">".
	$formEmail					.	">".
	$formPhone					.	">".
	$a_aid						.	">".
	$order_id					.	">".
	$affiliate_name				.	">".
	$affiliate_email;

$data						=	trim($data). "\n";

$date 						=	date("dmYHis");
$random 					=	rand(1,1000);
$file 						=	"claim_".	$date ."_".	 $random . ".csv";
$fd 						=	fopen("$server_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
$fout 						= 	fwrite($fd, $data);
fclose($fd);

# backup file
$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
$fout 						= 	fwrite($fd, $data);
fclose($fd);


# this should update the user dbase
$gUser	=	new User_Class();
# now add the order_id and corrected the name (26/10/2020)
# the order_id will also be pushed to the google_id for cross references
$gUser->createUser($formEmail,$formTitle,"",$formName,$formPhone,"",$order_id,$order_id);

$returnCode	=	"success";
$message	=	"OK";
AddComment($data);










$htmlMessage = "
		Name: " . $formName ."\n
		Email: " . $formEmail ."\n
		Telephone: " . $formPhone ."\n
		Order ID: " . $order_id."\n

		";

$htmlMessage	=	str_replace('\r\n', "\r\n",$htmlMessage);


$subject 	=	"Registration from Ward Kemp Website";
$email		=	"website@wardkemp.uk";

sendEmailLocal("Claim from Ward Website", $email, "cedric@boxlegal.co.uk", $subject, nl2br($htmlMessage));
sendEmailLocal("Claim from Ward Website", $email, "hannah@fairplane.co.uk", $subject, nl2br($htmlMessage));



# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);

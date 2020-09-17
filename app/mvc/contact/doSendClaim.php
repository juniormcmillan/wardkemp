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


AddComment("SALE pending for: a_aid:$a_aid, a_bid:$a_bid, order_id:$order_id, distance:$distance");

$saleTracker = new Pap_Api_SaleTracker('https://network.fairplane.co.uk/scripts/sale.php');
$saleTracker->setAccountId('default1');
$saleTracker->setVisitorId($papCookie);

$sale		=	$saleTracker->createSale();
$sale->setTotalCost(0);
$sale->setOrderID($order_id);
$sale->setStatus('P');
$sale->setAffiliateID($a_aid);
$sale->setProductID('cmd');

$sale->setData1($formName);
$sale->setData2("");
$sale->setData3($formEmail);

$saleTracker->register();

AddComment("SALE registered");


$session = new Gpf_Api_Session('https://network.fairplane.co.uk/scripts/server.php');
if(!@$session->login("cedric@fairplane.co.uk", "fgjs6n!"))
{
	AddComment("Cannot login to main PAP account due to :".$session->getMessage());
}



AddComment("SALE post pap");





$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/claim/";
$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/claim/";

# the data
$data	=
	"HD" 						.	">".
	$formName					.	">".
	$formEmail					.	">".
	$formPhone					.	">".
	$a_aid						.	">".
	$order_id;

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
# now add the order_id
$gUser->createUser($formEmail,$formTitle,$formForeName,$formSurName,$formPhone);

$returnCode	=	"success";
$message	=	"OK";
AddComment($data);










$htmlMessage = "
		Name: " . $formName ."\n
		Email: " . $formEmail ."\n
		Telephone: " . $formPhone ."\n

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

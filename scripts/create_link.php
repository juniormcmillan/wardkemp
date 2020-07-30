<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


# mysql
$gMysql			=	new Mysql_Library();

/*
 *
 *
 *

*/


SetCommentFile("shortlink.txt");

# check for variables
$case_key		= GetVariableString('case_key',$_GET,"");
$email			= GetVariableString('email',$_GET,"");
$type			= GetVariableString('type',$_GET,"");


# check the user
$gUser	=	new User_Class();
# now add the order_id
if (($data = $gUser->getUser($case_key,$email)) != NULL)
{
	AddComment("FOUND CASE_KEY:$case_key, email:$email");


	$api_key			=	REBRANDLY_API;


	$destination	= "https://www.ppisolicitors.co.uk/clientcare/introduction/?case_key=$case_key&email=$email";

	$domain_data["fullName"] 	= "pw1.uk";
	$post_data["destination"]	= $destination;
	$post_data["domain"] 		= $domain_data;
	$ch = curl_init("https://api.rebrandly.com/v1/links");
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
	$link		=	$response["shortUrl"];


	# lets update the database with the link
# ALTER TABLE `ppi_user` ADD `link` TINYTEXT NOT NULL AFTER `email`;

	$gUser->updateLink($case_key,$link);



	$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/link/";
	$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/link/";

	# the data
	$data	=
		$case_key	.	">".
		$link;

	$data						=	trim($data). "\n";

	$date 						=	date("d-m-Y");
	$random 					=	rand(1,1000);
	$file 						=	"claim_".	$date ."_".	 $case_key . ".csv";
	$fd 						=	fopen("$server_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
	$fout 						= 	fwrite($fd, $data);
	fclose($fd);

	# backup file
	$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
	$fout 						= 	fwrite($fd, $data);
	fclose($fd);



	AddComment("Created link:$link");


	$xml    =  "<?xml version='1.0' encoding='UTF-8'?><LinkMatrixResponse><Link>$link</Link></LinkMatrixResponse>";
	header ("Content-Type:text/xml");
	echo $xml;



}
else
{
	AddComment("CANNOT FIND CASE_KEY / EMAIL ERROR: CASE_KEY:$case_key, email:$email");
}


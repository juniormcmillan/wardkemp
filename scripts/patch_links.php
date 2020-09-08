<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


SetCommentFile("patch_links.txt");


# mysql
$gMysql			=	new Mysql_Library();

# check the user
$gUser			=	new User_Class();


$api_key		=	REBRANDLY_API;


# grab all that have had links created
$data_list	=	$gMysql->selectToArray("select * from ppi_user where link !='' ",__FILE__,__LINE__);


foreach ($data_list as $data)
{

	$case_key	=	$data['case_key'];
	$email		=	$data['email'];
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



	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");
	AddCommentOnly("");

		echo "OK: ". $case_key. " ". $slashtag."<br>";

}



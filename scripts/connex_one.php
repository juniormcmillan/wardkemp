<?php
/**
 * Created by PhpStorm.
 * User: Cedric McMillan Jnr
 * Date: 21/02/2017
 * Time: 01:08
 */


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");
# mysql
$gMysql		=	new Mysql_Library();

ini_set('display_errors', '1');
ini_set('html_errors', true);

# dialable leads id
$data_list_id			=	2008;




SetCommentFile("connex_one.log");


$reference				=	GetVariableString('reference',$_GET,$_POST);
$title					=	GetVariableString('title',$_GET,$_POST);
$first_name				=	GetVariableString('first_name',$_GET,$_POST);
$last_name				=	GetVariableString('last_name',$_GET,$_POST);
$main_phone				=	GetVariableString('main_phone',$_GET,$_POST);
$alternative_phone		=	GetVariableString('alternative_phone',$_GET,$_POST);
$source					=	GetVariableString('source',$_GET,$_POST);

# remove non numeric
$main_phone				=	stripNonNumeric($main_phone);
$alternative_phone		=	stripNonNumeric($alternative_phone);


$username 				=	"FairPlaneNew";
$password 				=	"32574nq6b85n7eib";
$token					=	"vgRIxpt9t2klwrbBbzRjHykSh3EPuuZT3K6OgXyjJkZGVL4JYl";

$source					=	"Facebook";
$main_phone				=	"07828931325";




AddComment("reference:$reference,last_name:$last_name");


//The JSON data.
$jsonData = array(
	'username' 	=> $username,
	'password' 	=> $password,
);
//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);
//Initiate cURL.
#$ch = curl_init("https://api2.cnx1.uk/consumer/login");
$ch = curl_init("https://api5.cnx1.uk/consumer/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
	    'User-Agent: Bespoke Script',
		'Content-Length: ' . strlen($jsonDataEncoded))
);
//Execute the request
$result 	= json_decode(curl_exec($ch));
$header_token	=	$result->token;




$encoded2=var_export($jsonDataEncoded, true);

AddComment("LOGIN: JSONDATA ENCODED:$encoded2");




//The JSON data.
$jsonData = array(
	'token' 			=> $token,
	'comments' 			=> $reference,
	'source_code' 		=> $reference,
	'title' 			=> $title,
	'first_name' 		=> $first_name,
	'last_name' 		=> $last_name,
	'main_phone' 		=> $main_phone,
	'alternative_phone' => $alternative_phone,
	'source' 			=> $source,
	'data_list'			=>	$data_list_id,
	"email"				=> "cedric@boxlegal.co.uk",
);








//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData,true);
//Initiate cURL.
#$ch = curl_init("https://api2.cnx1.uk//index.php/customer/create");
#$ch = curl_init("https://api2.cnx1.uk/customer/create");
$ch = curl_init("https://api5.cnx1.uk/customer/create");


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer ' . $header_token,
		'User-Agent: Bespoke Script',
		'Content-Length: ' . strlen($jsonDataEncoded))
);



$encoded=var_export($jsonDataEncoded, true);

AddComment("JSONDATA ENCODED:$encoded");


//Execute the request
$result2 = json_decode(curl_exec($ch));

print_r($result2);
exit;












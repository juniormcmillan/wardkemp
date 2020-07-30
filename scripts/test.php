<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/google-api-php-client-2.6.0_PHP54/vendor/autoload.php");



$url = "https://spreadsheets.google.com/feeds/list/177WdHHJ5pws9Iz6sPACEOrKn60exJA3T7XSK2l9fpvc/od6/public/basic?hl=en_US&alt=json";
$data = json_decode(file_get_contents($url), true);


#https://docs.google.com/spreadsheets/d/e/2PACX-1vQojUzENrF5Prj4YDP2nn-ZH59Wwu1uqVA8x6Q8mPSxlYfdNsE5jLXDXZoCzJRAFjnLJKSUs596S1aS/pubhtml


$client = new Google_Client();
$client->setDeveloperKey("AIzaSyBscO_7tdsyzQzjTf6z3GymRy8uXYJCk_k");


$service = new Google_Service_Sheets($client);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
$spreadsheetId = '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms';	# test
$spreadsheetId = '1l8C9lEi2vCfh31yBixdB2bOV0isbgx6q578ythfpKxM';	# error
$spreadsheetId = '177WdHHJ5pws9Iz6sPACEOrKn60exJA3T7XSK2l9fpvc';	# error




$range = 'Class Data!A2:P';
$range = 'A2:D';
$range = 'A2:P';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|",",");

if (empty($values)) {
	print "No data found.\n";
} else
{
#	print "Name, Major:\n";
	foreach ($values as $row)
	{
		# grab each row into array
		$id						=	$row[0];
		$created_time			=	$row[1];
		$ad_name				=	$row[3];
		$adset_name				=	$row[5];
		$campaign_name			=	$row[7];
		$platform				=	$row[11];
		$email					=	$row[12];
		$full_name				=	str_replace($invalid_characters, "", $row[13]);
		$phone_number			=	$row[14];



		foreach ($row as $col)
		{
		// Print columns A and E, which correspond to indices 0 and 4.
#		printf("%s, %s\n", $row[0], $row[4]);
			printf("%s" ,$col);
		}
		printf("\n\r\n<br>");
	}
}

exit;


$client = new Google_Client();
$client->setApplicationName("Client_Library_Examples");
$client->setDeveloperKey("AIzaSyBscO_7tdsyzQzjTf6z3GymRy8uXYJCk_k");

$service = new Google_Service_Books($client);
$optParams = array('filter' => 'free-ebooks');
$results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

foreach ($results->getItems() as $item) {
	echo $item['volumeInfo']['title'], "<br /> \n";
}







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


SetCommentFile("rebrand.txt");



$case_key		= GetVariableString('case_key',$_GET,"");
$email			= GetVariableString('email',$_GET,"");

$url			= GetVariableString('url',$_GET,"");



$api_key			=	"6854de4e7b4e4d53aedbe025ec728209";


$domain_data["fullName"] 	= "pw1.uk";
$post_data["destination"]	= "https://www.ppisolicitors.co.uk/clientcare/dsar?case_key=CASEKEY&email=cedric@boxlegal.co.uk";
$post_data["domain"] 		= $domain_data;
//$post_data["slashtag"] = "A_NEW_SLASHTAG";
//$post_data["title"] = "Rebrandly YouTube channel";
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
$response = json_decode($result, true);
AddComment("Short URL is: " . $response["shortUrl"]);


echo "Short URL is: " . $response["shortUrl"];

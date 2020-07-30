<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/google-api-php-client-2.6.0_PHP54/vendor/autoload.php");



SetCommentFile("google_sheet.txt");

AddComment("Starting scan");

# mysql
$gMysql			=	new Mysql_Library();
# check the user
$gUser			=	new User_Class();

$client = new Google_Client();
$client->setDeveloperKey("AIzaSyBscO_7tdsyzQzjTf6z3GymRy8uXYJCk_k");


$service = new Google_Service_Sheets($client);

# disrepairuk sheet
$spreadsheetId = '177WdHHJ5pws9Iz6sPACEOrKn60exJA3T7XSK2l9fpvc';
$range = 'A2:P';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|",",","'");

if (!empty($values)) #
{
#	print "Name, Major:\n";
	foreach ($values as $row)
	{
		# grab each row into array
		$lead_id				=	$row[0];
		$created_time			=	date("d-M-Y__G_i_s",strtotime($row[1]));
		$ad_name				=	$row[3];
		$adset_name				=	$row[5];
		$campaign_name			=	$row[7];
		$platform				=	$row[11];
		$email					=	$row[12];
		$full_name				=	str_replace($invalid_characters, "", $row[13]);
		$phone_number			=	$row[14];

		# if first numbers are 44, then delete first two numbers
		$first_two 				=	substr($phone_number, 0, 2);
		if($first_two == "44")
		{
			$phone_number	=		substr($phone_number, 2);
		}

		$first_one 				=	substr($phone_number, 0, 1);

		if($first_one != "0")
		{
			$phone_number	=		"0".$phone_number;
		}


		# create a lead if needed
		if ($gUser->getUserViaLeadID($lead_id) == false)
		{
			$gUser->createLead($lead_id,$email,$full_name,$phone_number);
			# now send proclaim the data


			$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files/fromgoogle/";
			$backup_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/fromgoogle/";

			# the data
			$data	=
				$lead_id					.	">".
				$email						.	">".
				$full_name					.	">".
				$phone_number;

			$data						=	trim($data). "\n";

			$date 						=	date("dmYHis");
			$random 					=	rand(1,1000);
			$file 						=	"google_".	$created_time ."_".	 $lead_id . ".csv";
			$fd 						=	fopen("$server_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
			$fout 						= 	fwrite($fd, $data);
			fclose($fd);

			# backup file
			$fd 						=	fopen("$backup_location$file", "w+") or die ("<br><b>Error creating file</b><br>");
			$fout 						= 	fwrite($fd, $data);
			fclose($fd);

AddComment("Added: lead_id:$lead_id");
		}
		else
		{
#AddComment("Skipped: lead_id:$lead_id");
		}
	}
}


AddComment("Finished scan");

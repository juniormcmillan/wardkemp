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



# new sheet from Finmedia
$spreadsheetId = '1D4OZu6_elevGJEzS6nYDwI-hYMOxyepalY7FTHPDa_w';
$range = 'A2:P';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();


getValues($values,0);
# disrepairuk sheet
$spreadsheetId = '177WdHHJ5pws9Iz6sPACEOrKn60exJA3T7XSK2l9fpvc';
$range = 'A2:P';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

getValues($values,1);



# grab values from sheetS
function getValues($values,$id)
{
	$num_rows	=	count($values);
	AddComment("Processing $num_rows rows.");

	global $gUser;
	global $gMysql;

	$invalid_characters 	=	array("$", "%", "#", "<", ">", "|",",","'");

	if (!empty($values)) #
	{
		#	print "Name, Major:\n";
		foreach ($values as $row)
		{
			if ($id == 0)
			{
				# grab each row into array
				$lead_id				=	$row[1];
				$created_time			=	date("d-M-Y__G_i_s",strtotime($row[0]));
				$email					=	$row[5];
				$full_name				=	str_replace($invalid_characters, "", $row[4]);
				$phone_number			=	$row[6];
				$came_from				=	"F";
			}
			else if ($id == 1)
			{
				$lead_id				=	$row[0];
				$created_time			=	date("d-M-Y__G_i_s",strtotime($row[1]));
				$email					=	$row[12];
				$full_name				=	str_replace($invalid_characters, "", $row[13]);
				$phone_number			=	$row[14];
				$came_from				=	"D";

			}


			if ((empty($email)) && (empty($phone_number)))
			{
AddComment("lead_id:$lead_id has no number or email address, so skipping");
				continue;
			}

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
					$phone_number				.	">".
					$came_from;

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

	AddComment("Finished");
}

AddComment("Finished scan");

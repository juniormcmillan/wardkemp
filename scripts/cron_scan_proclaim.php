<?php
#!/usr/bin/php
/**
 * Created by PhpStorm.
 * User: Cedric McMillan Jnr
 * Date: 03/04/2017
 * Time: 01:08
 *
 *
 * This reads the whole (latest) proclaim database and for each claim that either approved or declined, it updates the website data
 * To save time it skips ones already approved/declined
 */


error_reporting(E_ALL);
ini_set('memory_limit','900M');

require_once("../../codelibrary/includes/php/autoload.php");
require_once("../../codelibrary/includes/php/common.php");
require_once("../app/config.inc.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('html_errors', true);


set_time_limit(600000);
ignore_user_abort(true);
ini_set('max_execution_time', 600000);



$val =	ini_get('max_execution_time');



unlink($_SERVER["DOCUMENT_ROOT"]."/app/scanproclaim.log");

SetCommentFile("scanproclaim.log");

AddComment("Starting....");


# mysql
$gMysql			=	new Mysql_Library();

echo "starting";

if (strpos($_SERVER['HTTP_HOST'],"ppisolicitors2.co.uk") !== false)
{
	$bLocalhost	=	true;
}
else
{
	$bLocalhost	=	false;
}

import_from_proclaim();


AddComment("finished");
echo "finished";



# imports from proclaim dump and stores some info about them
function import_from_proclaim()
{
	global $gMysql;


	// test for server
	# lets work out where we are going to save the data
	if (strpos($_SERVER['HTTP_HOST'],"ppisolicitors2.co.uk") !== false)
	{
		$dir = $directory = DOCUMENT_ROOT . '../uplink/incoming/';
	}
	else
	{
		$dir = $directory = DOCUMENT_ROOT . '../uplink/incoming/';
	}

	$lastMod = 0;
	$lastModSize= 0;
	$lastModFile = '';
	foreach (scandir($dir) as $entry)
	{
		#		if (is_file($dir.$entry) && filemtime($dir.$entry) >= $lastMod )
		# adjustment to get biggest rather than oldest
		if (is_file($dir.$entry) && filesize($dir.$entry) > $lastModSize)
		{
			$ext = pathinfo($dir.$entry, PATHINFO_EXTENSION);

			if	($ext == 'csv')
			{
				$lastMod 		= 	filemtime($dir.$entry);
				$lastModFile	= 	$entry;
				$lastModSize	=	filesize($dir.$entry);
			}
		}
	}


	$row = 1;
	$added = 0;



#	$csv = array_map('str_getcsv', file($dir.$lastModFile),array(">"));
	$csv					=	array();
	$invalid_characters 	=	array("$", "%", "#", "|","'",'"',"`","<");

	if (($handle = fopen($dir.$lastModFile, "r")) !== FALSE)
	{
AddComment("Opening file: ".$dir.$lastModFile  ."(size:".$lastModSize.")");

		while(($data = fgets($handle)))
		{
			$data		=	str_replace($invalid_characters,"",$data);

			$exploded 	= 	explode(",", $data);

			# now for the CADE KEY - blank ones not accepted
			if ($exploded[0] != '')
			{
				$csv[]	=	$exploded;
			}

		}

			$lines	=	count($csv);

		AddComment("PARSED file: ".$dir.$lastModFile  ."(size:".$lastModSize." lines:$lines)");

		foreach ($csv as $data)
		{
			$num = count($data);
			$row++;

			$case_key		=	$data[0];


			store_status($data,$row);
			$added++;

		}
		fclose($handle);
	}


	AddComment("");
	AddComment("");
	AddComment("");
	AddComment("");
	AddComment("");
	AddComment("");
	AddComment("");
	AddComment("import_from_proclaim() Added:$added to info database");


	$leave_files = array($lastModFile,"fpdata.csv");

	# we should remove all files after this, or we will continuously upload these
	$leave_files = array();

	foreach( glob("$dir/*") as $file )
	{
		if( !in_array(basename($file), $leave_files) )
		{
			unlink($file);
		}
	}


}



# stores a file that can link status of a case to a flight
function store_status($data,$i)
{
	$case_key		=	$data[0];

	if ($case_key == "case")
	{
		return;
	}

	$email			=	$data[1];

	# *** all needs to be encrypted ***
	$title			=	$data[2];
	$forename		=	"";
	$surname		=	$data[3];
	$mobile			=	$data[4];
	$defendant		=	$data[5];


	# this doesn't need to be stored
	$address1		=	$data[6];
	$address2		=	$data[7];
	# this doesn't need to be stored
	$city			=	$data[8];
	# this doesn't need to be stored
	$town			=	$data[9];
	if (!empty($town))
	{
		$city			.=	 ", " . ucwords (strtolower($town)," ") ;
	}

	# this doesn't need to be stored
	$postcode		=	$data[10];


	# check the user
	$gUser	=	new User_Class();
	# now add the order_id
	if (($data = $gUser->getUserViaCaseKey($case_key)) != NULL)
	{
		AddComment("FOUND CASE_KEY / EMAIL: CASE_KEY:$case_key, email:$email, SURNAME:$surname");

		# update details from proclaim - we may have some adjustments to name etc, so accept this
		$gUser->updateUserDetails($case_key,$address1,$address2,$city,$postcode,$email,$title,$forename,$surname,$defendant);
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



}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 13/07/12
 * Time: 9:10
 * This callback will download the signed PDF and store it
 */


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/digisigner-php-client/DigiSignerClient.php");

//import needed classed from DigiSigner namespace
use DigiSigner\DigiSignerClient;
use DigiSigner\SignatureRequest;
use DigiSigner\DigiSignerException;
use DigiSigner\Document;
use DigiSigner\Signer;
use DigiSigner\Field;



// connect and login to FTP server
define	('PPI_FTP_SERVER',					'ftp.ppisolicitors.co.uk');
define	('PPI_FTP_USERNAME',				'ppisolicitors');
define	('PPI_FTP_PASSWORD',				'81tiazgxrtlx');


// connect and login to FTP server
define	('WARDKEMP_FTP_SERVER',					'ftp.wardkemp.uk');
define	('WARDKEMP_FTP_USERNAME',				'wardkempuk');
define	('WARDKEMP_FTP_PASSWORD',				'catch1922');


SetCommentFile("docusign_callback.log");

AddComment("Starting");


# this should deal with the callback
if	(($data =	json_decode(getPutContents(), true)) != NULL)
{
	$event_time  = $data['event_time'];
	$event_type  = $data['event_type'];
	$document_id = $data['signature_request']['documents'][0]['document_id'];

	AddComment("event_time: $event_time");
	AddComment("event_type: $event_type");
	AddComment("document_id: $document_id");

	# mysql
	$gMysql		=	new Mysql_Library();


	doCheckChildForm($document_id);
	doCheckDSAR($document_id);
	doCheckAirline($document_id);
	doCheckPPIDSAR($document_id);
	doCheckPPIClientCare($document_id);

#	doCheckWardKempDSAR($document_id);
#	doCheckWardKempCFA($document_id);
#	doCheckWardKempClientCare($document_id);
#	doCheckWardKempLandlord($document_id);

	doCheckWardKempCFA($document_id);
	doCheckWardKempLandlord($document_id);
	doCheckWardKempInstruction($document_id);

}
else
{

	AddComment("PROBLEM WITH DATA RETURNED!");
	echo "exited";
}

# grab contents from stream
function getPutContents()
{
	$putData = '';
	$fp = fopen('php://input', 'r');
	while (!feof($fp))
	{
	    $s = fread($fp, 64);
	    $putData .= $s;
	}
	fclose($fp);

	return  $putData;
}






function doCheckChildForm($document_id)
{
	global $gMysql;

	$gMysql->setDB("fairplane");
	# grab data about document
	$data		=	$gMysql->queryRow("select * from signed where document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{

		$case_key	=	$data['filename'];

		AddComment("doCheckChildForm CASE_KEY:$case_key  ($document_id) ");


		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/proof/"	.	$data['filename'];

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);

		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update signed set signed='yes', updated='$today' where document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckChildForm ($document_id)_ DONE IT!");
		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckChildForm ($document_id) FAILED");
}







function doCheckDSAR($document_id)
{
	global $gMysql;

	$gMysql->setDB("newfairp_website");
	# grab data about document
	$data		=	$gMysql->queryRow("select * from  fp_flight_master_db_flight_info where dsar_document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{
		$case_key	=	$data['case_key'];

		AddComment("doCheckDSAR CASE_KEY:$case_key  ($document_id) ");




		$name		=	"DS" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-dsar/"	.	$name;



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update fp_flight_master_db_flight_info set dsar_signed='yes', dsar_signed_date='$today' where dsar_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckDSAR ($document_id) NAME:$name DONE IT!");



		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);


		AddComment("doCheckDSAR CASE_KEY:$case_key  Stored initial file");


		$filename			=	"../../../../fpwp-uk-new/files/signed-dsar/"	.	$name;
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);

		AddComment("doCheckDSAR CASE_KEY:$case_key  Stored other file");

		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update fp_flight_master_db_flight_info set dsar_signed='yes', dsar_signed_date='$today' where dsar_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckDSAR ($document_id) NAME:$name DONE IT!");


		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckDSAR ($document_id) FAILED");
}




function doCheckAirline($document_id)
{
	global $gMysql;

	$gMysql->setDB("newfairp_website");
	# grab data about document
	$data		=	$gMysql->queryRow("select * from  fp_flight_master_db_flight_info where airline_signed_doc_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{

		$case_key	=	$data['case_key'];

AddComment("doCheckAirline CASE_KEY:$case_key  ($document_id) ");

		if ($data['airline_signed'] == 'yes')
		{
			AddComment("doCheckAirline ($document_id) ALREADY SIGNED");
			echo "DIGISIGNER_EVENT_ACCEPTED";
			exit;
		}

		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update fp_flight_master_db_flight_info set airline_signed='yes', airline_signed_date='$today' where airline_signed_doc_id='$document_id'", __FILE__, __LINE__);

AddComment("doCheckAirline ($case_key) SET TO DONE IT!");




		$case_key	=	$data['case_key'];
		$name		=	"AA" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-airline/"	.	$name;

AddComment("doCheckAirline CASE_KEY:$case_key  Trying to get $name   ($filename)");

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');

AddComment("doCheckAirline CASE_KEY:$case_key  CLIENT CREATED");

		$client->getDocument($document_id, $filename);

AddComment("doCheckAirline CASE_KEY:$case_key  DOCUMENT RECEIVED");


		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files_backup/signed-airline/"	.	$name;
		$client->getDocument($document_id, $filename);

AddComment("doCheckAirline  ($case_key) NAME:$name BACKUP STORED AGAIN");


		AddComment("");
		AddComment("");



		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckAirline ($document_id) FAILED");
}





# this will need to sent the file to a specific folder
function doCheckPPIDSAR($document_id)
{
	global $gMysql;

	AddComment("doCheckPPIDSAR starting ($document_id) ");

	$gMysql->setDB("ppisolicitors");


	# grab data about document
	$data		=	$gMysql->queryRow("select * from  ppi_user where dsar_document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{
		$case_key	=	$data['case_key'];

		AddComment("doCheckPPIDSAR CASE_KEY:$case_key  ($document_id) ");

		$name		=	"DS" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-dsar-ppi/"	.	$name;



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set dsar_signed='yes', dsar_signed_date='$today' where dsar_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckPPIDSAR ($document_id) NAME:$name DONE IT!");

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);


		AddComment("doCheckPPIDSAR CASE_KEY:$case_key  Stored initial file");



		$server_location	=	$_SERVER["DOCUMENT_ROOT"].	"/files/signed-dsar-ppi/";

		$ftp_dest_folder	=	"public_html/files/dsar/";


		# send externally for backup
		send_ftp_(PPI_FTP_SERVER,PPI_FTP_USERNAME,PPI_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set dsar_signed='yes', dsar_signed_date='$today' where dsar_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckPPIDSAR ($document_id) NAME:$name DONE IT!");


		# now for the backup
		$ftp_dest_folder	=	"public_html/files_backup/dsar/";
		# send externally for backup
		send_ftp_(PPI_FTP_SERVER,PPI_FTP_USERNAME,PPI_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);


		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckPPIDSAR ($document_id) FAILED");


}








# this will need to sent the file to a specific folder
function doCheckPPIClientCare($document_id)
{
	global $gMysql;

	AddComment("doCheckPPIClientCare starting ($document_id) ");

	$gMysql->setDB("ppisolicitors");


	# grab data about document
	$data		=	$gMysql->queryRow("select * from  ppi_user where clientcare_document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{
		$case_key	=	$data['case_key'];

		AddComment("doCheckPPIClientCare CASE_KEY:$case_key  ($document_id) ");

		$name		=	"DB" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-clientcare-ppi/"	.	$name;



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set clientcare_signed='yes', clientcare_signed_date='$today' where clientcare_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckPPIClientCare ($document_id) NAME:$name DONE IT!");

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);


		AddComment("doCheckPPIClientCare CASE_KEY:$case_key  Stored initial file");



		$server_location	=	$_SERVER["DOCUMENT_ROOT"].	"/files/signed-clientcare-ppi/";

		$ftp_dest_folder	=	"public_html/files/clientcare/";


		# send externally for backup
		send_ftp_(PPI_FTP_SERVER,PPI_FTP_USERNAME,PPI_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set clientcare_signed='yes', clientcare_signed_date='$today' where clientcare_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckPPIClientCare ($document_id) NAME:$name DONE IT!");

		# now for the backup
		$ftp_dest_folder	=	"public_html/files_backup/clientcare/";
		# send externally for backup
		send_ftp_(PPI_FTP_SERVER,PPI_FTP_USERNAME,PPI_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckPPIDSAR ($document_id) FAILED");


}








































# this will need to sent the file to a specific folder
function doCheckWardKempDSAR($document_id)
{
	global $gMysql;

	AddComment("doCheckWardKempSAR starting ($document_id) ");

	$gMysql->setDB("wardkemp");


	# grab data about document
	$data		=	$gMysql->queryRow("select * from  ppi_user where dsar_document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{
		$case_key	=	$data['case_key'];

		AddComment("doCheckWardKempDSAR CASE_KEY:$case_key  ($document_id) ");

		$name		=	"C2" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-dsar-ppi/"	.	$name;



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set dsar_signed='yes', dsar_signed_date='$today' where dsar_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempDSAR ($document_id) NAME:$name DONE IT!");

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);


		AddComment("doCheckWardKempDSAR CASE_KEY:$case_key  Stored initial file");



		$server_location	=	$_SERVER["DOCUMENT_ROOT"].	"/files/signed-dsar-ppi/";

		$ftp_dest_folder	=	"public_html/files/dsar/";


		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set dsar_signed='yes', dsar_signed_date='$today' where dsar_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempDSAR ($document_id) NAME:$name DONE IT!");


		# now for the backup
		$ftp_dest_folder	=	"public_html/files_backup/dsar/";
		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);


		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckWardKempDSAR ($document_id) FAILED");


}








# Instruction = C3 this will need to sent the file to a specific folder
function doCheckWardKempInstruction($document_id)
{
	global $gMysql;

	AddComment("doCheckWardKempClientCare starting ($document_id) ");

	$gMysql->setDB("wardkemp");


	# grab data about document
	$data		=	$gMysql->queryRow("select * from  ppi_user where clientcare_document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{
		$case_key	=	$data['case_key'];

		AddComment("doCheckWardKempInstruction CASE_KEY:$case_key  ($document_id) ");

		$name		=	"C3" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-clientcare-ppi/"	.	$name;



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set clientcare_signed='yes', clientcare_signed_date='$today' where clientcare_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempInstruction ($document_id) NAME:$name DONE IT!");

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);


		AddComment("doCheckWardKempInstruction CASE_KEY:$case_key  Stored initial file");



		$server_location	=	$_SERVER["DOCUMENT_ROOT"].	"/files/signed-clientcare-ppi/";

		$ftp_dest_folder	=	"public_html/files/clientcare/";


		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set clientcare_signed='yes', clientcare_signed_date='$today' where clientcare_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempInstruction ($document_id) NAME:$name DONE IT!");

		# now for the backup
		$ftp_dest_folder	=	"public_html/files_backup/clientcare/";
		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckWardKempInstruction ($document_id) FAILED");


}









# Instruction = C4 this will need to sent the file to a specific folder
function doCheckWardKempLandlord($document_id)
{
	global $gMysql;

	AddComment("doCheckWardKempLandlord starting ($document_id) ");

	$gMysql->setDB("wardkemp");


	# grab data about document
	$data		=	$gMysql->queryRow("select * from  ppi_user where landlord_document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{
		$case_key	=	$data['case_key'];

		AddComment("doCheckWardKempLandlord CASE_KEY:$case_key  ($document_id) ");

		$name		=	"C4" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-clientcare-ppi/"	.	$name;



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set landlord_signed='yes', landlord_signed_date='$today' where landlord_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempLandlord ($document_id) NAME:$name DONE IT!");

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);


		AddComment("doCheckWardKempLandlord CASE_KEY:$case_key  Stored initial file");



		$server_location	=	$_SERVER["DOCUMENT_ROOT"].	"/files/signed-clientcare-ppi/";

		$ftp_dest_folder	=	"public_html/files/clientcare/";


		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set landlord_signed='yes', landlord_signed_date='$today' where landlord_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempLandlord ($document_id) NAME:$name DONE IT!");

		# now for the backup
		$ftp_dest_folder	=	"public_html/files_backup/clientcare/";
		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckWardKempLandlord ($document_id) FAILED");


}




# CFA = C1 this will need to sent the file to a specific folder
function doCheckWardKempCFA($document_id)
{
	global $gMysql;

	AddComment("doCheckWardKempCFA starting ($document_id) ");

	$gMysql->setDB("wardkemp");


	# grab data about document
	$data		=	$gMysql->queryRow("select * from  ppi_user where cfa_document_id='$document_id'", __FILE__, __LINE__);
	if	(!empty($data))
	{
		$case_key	=	$data['case_key'];

		AddComment("doCheckWardKempCFA CASE_KEY:$case_key  ($document_id) ");

		$name		=	"C1" . $data['case_key']	.	"_"	.		date('d')	.	date('m')	.	date('y').	"S.pdf";

		$filename	=	$_SERVER["DOCUMENT_ROOT"]	.	"/files/signed-clientcare-ppi/"	.	$name;



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set cfa_signed='yes', cfa_signed_date='$today' where cfa_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempCFA ($document_id) NAME:$name DONE IT!");

		# grab signed document
		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$client->getDocument($document_id, $filename);


		AddComment("doCheckWardKempCFA CASE_KEY:$case_key  Stored initial file");



		$server_location	=	$_SERVER["DOCUMENT_ROOT"].	"/files/signed-clientcare-ppi/";

		$ftp_dest_folder	=	"public_html/files/clientcare/";


		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		$today		=	GetTimeStamp();
		# now, we can set that it was signed
		$gMysql->update("update ppi_user set cfa_signed='yes', cfa_signed_date='$today' where cfa_document_id='$document_id'", __FILE__, __LINE__);

		AddComment("doCheckWardKempCFA ($document_id) NAME:$name DONE IT!");

		# now for the backup
		$ftp_dest_folder	=	"public_html/files_backup/clientcare/";
		# send externally for backup
		send_ftp_(WARDKEMP_FTP_SERVER,WARDKEMP_FTP_USERNAME,WARDKEMP_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);



		echo "DIGISIGNER_EVENT_ACCEPTED";
		exit;
	}

	AddComment("doCheckWardKempCFA ($document_id) FAILED");


}




















function send_ftp_test()
{

AddComment("send_ftp_test");
	$name				=	"DSCASEKEY_290220S.pdf";


	$server_location	=	$_SERVER["DOCUMENT_ROOT"].	"/files/signed-dsar-ppi/";

	$ftp_dest_folder	=	"public_html/files/dsar/";
	$ftp_dest_folder1	=	"/public_html/files/dsar/";




	# send externally for backup
	send_ftp_(PPI_FTP_SERVER,PPI_FTP_USERNAME,PPI_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder);
	send_ftp_(PPI_FTP_SERVER,PPI_FTP_USERNAME,PPI_FTP_PASSWORD,$name,$server_location,$ftp_dest_folder1);

	AddComment("end send_ftp_test");

}


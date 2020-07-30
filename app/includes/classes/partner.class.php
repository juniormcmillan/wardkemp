<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 19-Feb-16
 * Time: 9:14 AM
 */

define	('PARTNER_IMPORT_FROM_AUSTRIA',				1);



# these states are for if we have just imported from austria
define	('DATA_STATE_INITIAL',				1);
# this state is for if we have uploaded from the partner with the filled in data
define	('DATA_STATE_UPDATED_FROM_PARTNER',				2);



class Partner_Class
{
	private	$name					=	"";
	private	$id						=	"";
	private	$partner_code			=	"";
	private	$affiliate_network_code	=	"";
	protected	$partner_full_name	=	"";


	private	$partner		=	"";

	public	$extension				=	"";
	public	$base_filename			=	"";
	public 	$directory				=	"";

	public function __construct($partner_id=0)
	{
		return $this->getByID($partner_id);
	}


	public function __destruct()
	{
	}




	# gets partner by code
	public function getByCode($partner_code)
	{
		global	$gMysql;

		if	(($partner	=	$gMysql->queryRow("select * from fp_partner where partner_code='$partner_code'",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL)) != NULL)
		{
			$this->extractData($partner);
			return	$partner;
		}
	}


	# gets partner by id
	public function getByID($id)
	{
		global	$gMysql;

		if	(($partner	=	$gMysql->queryRow("select * from fp_partner where id='$id'",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL)) != NULL)
		{
			$this->extractData($partner);
			return	$partner;
		}
	}


	# extract data
	private function extractData($partner)
	{
		if	(!empty($partner))
		{
			$this->id						=	$partner['id'];
			$this->name						=	$partner['company'];
			$this->partner_full_name		=	ucfirst($partner['forename']) . " " . ucfirst($partner['surname']);
			$this->partner_code				=	$partner['partner_code'];
			$this->affiliate_network_code	=	$partner['affiliate_network_code'];
		}
	}


	public function update($strSQL)
	{
		global	$gMysql;
		$gMysql->update($strSQL,__FILE__,__LINE__);
	}



	# return items
	public function getID()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}
	public function getPartnerCode()
	{
		return $this->partner_code;
	}
	public function getAffiliateCode()
	{
		return $this->affiliate_network_code;
	}




	# add this claim to the list of claims, but we need to only allow editing once we have the second phase of data from agent
	public function addClaim($job_id,$data)
	{
		require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/claim.class.php");
		$pClaim	=	new Claim_Class();
		return $pClaim->addClaim($job_id,$this->id,$data);
	}




	# add this claim to the list of claims, but we need to only allow editing once we have the second phase of data from agent
	public function updateClaim($data)
	{
		require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/claim.class.php");
		$pClaim	=	new Claim_Class();
		return $pClaim->updateClaim($this->id,$data);
	}



	# grab user session with details or exit

	# replace a default claim for this person/company



	# this sends claim data by email
	public function sendTestClaim($template_id)
	{
		global	$gMysql;
		global	$gSession;
		$email				=	$gSession->getSessionDataVar('email');

		# partner code already exists
		if (($data = $gMysql->queryRow("SELECT * from fp_template where id='$template_id'",__FILE__,__LINE__)) != 0)
		{
			$formFromName		=	$data['from_name'];
			$formFromEmail		=	$data['from_email'];
			$formSubject		=	$data['subject'];
			$htmlContent		=	html_entity_decode($data['html'],ENT_QUOTES);

			# substitute
			$htmlContent		=	$this->getClaimTags($this->id,$htmlContent);

			$htmlMessage = '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>'.$formSubject.'</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="background-color:#f7f7f7;">

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="20" cellspacing="20" width="600" id="emailContainer">
                <tr >
                    <td width="20"></td>
                    <td width="560" style="background-color:#ffffff;">
					'.$htmlContent.'
                    </td>
                    <td width="20"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>



</body>
</html>
';

			$to_email_array	=	array($email);

			# sends the messages
			foreach ($to_email_array as $to_email)
			{
				sendEmailNew($formFromName, $formFromEmail, $to_email, $formSubject, $htmlMessage, "");
			}
		}

	}








	# substitutes data in string
	public function getClaimTags($partner_id,$string)
	{
		require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/claim.class.php");

		global	$gMysql;
		global	$gSession;

		$forename			=	ucfirst($this->partner_full_name);
		$company			=	ucfirst($this->company);

		# we need to create a claim, so delete and add
		$pClaim	=	new Claim_Class();

		# remove the current test claim
		$pClaim->deletePartnerClaim($partner_id,0,"TEST");

		# create a test claim
		$data	=	array(
			"reference"			=>	"TEST",
			"airline_code"		=>	"FR",
			"flight_number"		=>	"8425",
			"flight_date"		=>	"11-08-2013"
		);

		# add a test claim
		if	( $pClaim->addClaim(0,$partner_id,$data) == CLAIM_OK)
		{
			$reference			=	$data['reference'];
			$claim_data			=	$pClaim->getByReference($partner_id,0,$reference);
			$flight_date		=	$claim_data['sched_dep_date'];
			$flight_date		=	date( 'd/m/Y', strtotime($claim_data['sched_dep_date']) );

			$flight_number		=	$claim_data['flight_number'];
			$airline_code		=	$claim_data['airline_code'];
			$dep_airport_code	=	$claim_data['departure_airport'];
			$arr_airport_code	=	$claim_data['arrival_airport'];
			$claim_hash			=	$claim_data['hash'];
			# create data for the link
#			$claim_link			=	"http://ta.fairplane.co.uk/claim/?formID=$claim_hash";
			$claim_link			=	"<a href='http://ta.fairplane.co.uk/claim/?formID=$claim_hash'>Click Here To Make Your Claim</a>";

			# full flight number
			$flight_number_full		=	$data['airline_code']	.	"" .	$data['flight_number'];

			$gMysql->setDB("newfairp_flightdata");

			# we need some info about airline
			$airline_name		=	$gMysql->queryItem("SELECT name from fp_airlines where code='$airline_code'",__FILE__,__LINE__);

			# we need some info about airline
			$departure_airport	=	$gMysql->queryItem("SELECT name from fp_airports where code='$dep_airport_code'",__FILE__,__LINE__);
			$arrival_airport	=	$gMysql->queryItem("SELECT name from fp_airports where code='$arr_airport_code'",__FILE__,__LINE__);


			$gMysql->setDB(MYSQL_DBASE);

			# we need to substitute these tags
			# we can push all these pre-filled tags onto the page
			$tags	=	array(


			"{{FORENAME}}"				=>	$forename,
			"{{COMPANY}}"				=>	$company,

			"{{FLIGHT_DATE}}"			=>	$flight_date,
			"{{FLIGHT_NUMBER}}"			=>	$flight_number,
			"{{FLIGHT_NUMBER_FULL}}"	=>	$flight_number_full,
			"{{AIRLINE_CODE}}"			=>	$airline_code,
			"{{AIRLINE_NAME}}"			=>	$airline_name,
			"{{DEPARTURE_AIRPORT}}"		=>	$departure_airport,
			"{{ARRIVAL_AIRPORT}}"		=>	$arrival_airport,
			"{{CLAIM_LINK}}"			=>	$claim_link,
			"{{CLAIM_LINK_BUTTON}}"		=>	$claim_link,


			);

			# substitutes the tags
			foreach ($tags as $key => $value)
			{
				$string = str_replace ($key, $value, $string);
			}
		}

		return	$string;
	}




















}













<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 2nd Aug 2017
 * Time: 4:17 PM
 */

class User_Class
{
	# table used for claims
	public $gClaimDescArray	=	array(


		array(	"code"	=>"A10"	,  	"text"	=>	"Introduction Email Sent to Client",			"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A20"	,  	"text"	=>	"Chasing Lease Email Sent to Client",			"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A25"	,  	"text"	=>	"Chasing Lease Call Required",					"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A26"	,  	"text"	=>	"Chasing Lease Second Call Required",			"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A28"	,  	"text"	=>	"Second Lease Chase Call Made",					"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A30"	,  	"text"	=>	"Final Lease Chasing Email Sent",				"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A40"	,  	"text"	=>	"Asking Current Tenant If Wish To Claim",		"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A50"	,  	"text"	=>	"Chase Current Tenant If Wish To Claim",		"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"A60"	,  	"text"	=>	"Final to Current Tenant If Wish To Claim",		"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"B1"	,  	"text"	=>	"Lease Uploaded",								"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"B10"	,  	"text"	=>	"Lease Check Due",								"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"C10"	,  	"text"	=>	"Client Care Letters Sent",						"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"C20"	,  	"text"	=>	"Soft Landlord Letter Sent",					"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"C30"	,  	"text"	=>	"LBA Deposit No Protected Sent",				"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"C40"	,  	"text"	=>	"Final 14 Warning - Deposit Not Protected",		"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"D10"	,  	"text"	=>	"DCert Check Due",								"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"D20"	,  	"text"	=>	"LBA Incorrect Notices Sent",					"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"D30"	,  	"text"	=>	"Final 14 Warning - Incorrect Notices",			"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"E10"	,  	"text"	=>	"Pre-Issue Offer Made",							"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"E20"	,  	"text"	=>	"Pre-Issue Offer Accepted",						"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"E30"	,  	"text"	=>	"Post-Issue Offer Made",						"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"E40"	,  	"text"	=>	"Post-Issue Offer Accepted",					"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"L1"	,  	"text"	=>	"Passed to Legal Team",							"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"P1"	,  	"text"	=>	"Proceedings Issued - N1 Sent to Court",		"case_status"	=>	"P", 	"claim_status"	=>	"", 		"claim_pos"	=>	0),
		array(	"code"	=>"R10"	,  	"text"	=>	"Rejected: No Lease Uploaded",					"case_status"	=>	"D",	"claim_status"	=>	"REJECTED", "claim_pos"	=>	0),
		array(	"code"	=>"R20"	,  	"text"	=>	"Rejected: Correct DCert Obtained",				"case_status"	=>	"D",	"claim_status"	=>	"REJECTED", "claim_pos"	=>	0),
		array(	"code"	=>"R30"	,  	"text"	=>	"Rejected: Letting Agent Warranty",				"case_status"	=>	"D", 	"claim_status"	=>	"REJECTED", "claim_pos"	=>	0),
		array(	"code"	=>"R40"	,  	"text"	=>	"Rejected: Not Shorthold Tenancy",				"case_status"	=>	"D",	"claim_status"	=>	"REJECTED", "claim_pos"	=>	0),
		array(	"code"	=>"R50"	,  	"text"	=>	"Rejected: CL doesn't Want to Claim",			"case_status"	=>	"D",	"claim_status"	=>	"REJECTED", "claim_pos"	=>	0),
		array(	"code"	=>"T0"	,  	"text"	=>	"Case Won - Cheque Paid - Pending Clearance",	"case_status"	=>	"A", 	"claim_status"	=>	"WIN", 		"claim_pos"	=>	0),
		array(	"code"	=>"T10"	,  	"text"	=>	"Case Won - BACS Requested From Client",		"case_status"	=>	"A", 	"claim_status"	=>	"WIN", 		"claim_pos"	=>	0),
		array(	"code"	=>"T20"	,  	"text"	=>	"Case Won - BACS Requested Chased",				"case_status"	=>	"A", 	"claim_status"	=>	"WIN", 		"claim_pos"	=>	0),
		array(	"code"	=>"T30"	,  	"text"	=>	"Case Complete - Money Sent By Cheque",			"case_status"	=>	"A", 	"claim_status"	=>	"WIN", 		"claim_pos"	=>	0),
		array(	"code"	=>"T40"	,  	"text"	=>	"Case Complete - Money Sent By BACS",			"case_status"	=>	"A", 	"claim_status"	=>	"WIN", 		"claim_pos"	=>	0),
		array(	"code"	=>"T50"	, 	"text"	=>	"Case Complete - Money Sent By International ",	"case_status"	=>	"A", 	"claim_status"	=>	"WIN", 		"claim_pos"	=>	0),


	);


	# grabs the data row for the claim description
	public function getClaimDescArray($text)
	{
		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|","'",'"',"'");

		foreach ($this->gClaimDescArray as $data)
		{
			if	(strcasecmp(str_replace($invalid_characters,"",$data['text']),$text)==0)
			{
				return $data;
			}
		}

	}

	public function __construct()
	{
	}


	public function __destruct()
	{

	}


	# gets a email
	public function getUser($case_key,$email)
	{
		global $gMysql;

		if	(($data	=	$gMysql->queryRow("select * from ppi_user where case_key='$case_key' and email ='$email' and email !='' ",__FILE__,__LINE__)))
		{
			return $data;
		}
		# catchall if the server has not sent the case_key data across
		else if ( (!empty($case_key)) && ($data	=	$gMysql->queryRow("select * from ppi_user where case_key='' and email ='$email' and email !=''",__FILE__,__LINE__)) )
		{
			# only do this if case_key is empty too
			$gMysql->update("update ppi_user set case_key='$case_key',last_updated=NOW() where email='$email'",__FILE__,__LINE__);

			if	(($data	=	$gMysql->queryRow("select * from ppi_user where case_key='$case_key' and email ='$email'",__FILE__,__LINE__)))
			{
				return $data;
			}

		}
	}

	#
	public function getUserViaCaseKey($case_key)
	{
		global $gMysql;

		if	(($data	=	$gMysql->queryRow("select * from ppi_user where case_key='$case_key' and case_key !='' ",__FILE__,__LINE__)))
		{
			return $data;
		}
	}



	#
	public function getUserViaLeadID($lead_id)
	{
		global $gMysql;

		if	(($data	=	$gMysql->queryRow("select * from ppi_user where google_lead_id='$lead_id' and google_lead_id !='' ",__FILE__,__LINE__)))
		{
			return $data;
		}
	}






	# add / update
	# creates a claim dbase item and stores case_key if passed
	public function createUser($email,$title,$forename,$surname,$mobile,$case_key="")
	{
		global $gMysql;


		if ($this->getUserViaCaseKey($case_key) == false)
		{
			$gMysql->insert("insert into ppi_user 
		
		(id,case_key,email,title,forename,surname,mobile,added,last_updated) VALUES 
		(0,'$case_key','$email','$title','$forename','$surname','$mobile',NOW(),NOW())",__FILE__,__LINE__);

		}
		else
		{
			# only do this if case_key is empty too - eg. a brand new client that needs data filled out
			$gMysql->update("update ppi_user set case_key='$case_key', last_updated=NOW() where email='$email' and case_key='' ",__FILE__,__LINE__);
		}

	}




	# creates a claim dbase item and stores case_key if passed
	public function createLead($lead_id,$email,$full_name,$phone_number,$case_key)
	{
		global $gMysql;
		if ($this->getUserViaLeadID($lead_id) == false)
		{
			$gMysql->insert("insert into ppi_user 
		
			(id,google_lead_id,email,google_name,mobile,case_key,added,last_updated) VALUES 
			(0,'$lead_id','$email','$full_name','$phone_number','$case_key',NOW(),NOW())",__FILE__,__LINE__);

		}
	}








	# updates link
	public function updateLink($case_key,$link,$link_id)
	{
		global $gMysql;
		# only do this if case_key is empty too
		$gMysql->update("update ppi_user set link='$link',link_id='$link_id' where case_key='$case_key' ",__FILE__,__LINE__);
	}


	# updates claimant details with case_key and also address details. multiple claims over time could be an issue if the same email address is used. (use proclaim id or postcode somehow)
	public function updateUserDetails($case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$defendant,$google_lead_id,$type_code,$company,$solicitor_name)
	{
		global $gMysql;
		# only do this if case_key is empty too
		$gMysql->update("update ppi_user set 
		
		address1='$address1',address2='$address2',town='$town',postcode='$postcode',title='$title',forename='$forename',surname='$surname',defendant='$defendant',google_lead_id='$google_lead_id',type_code='$type_code',company_id='$company',solicitor_name='$solicitor_name',last_updated=NOW() 
		
		where (case_key='$case_key' and case_key !='') ",__FILE__,__LINE__);
	}




	# updates a lead
	public function updateLeadDetails($google_lead_id,$case_key,$address1,$address2,$town,$postcode,$email,$title,$forename,$surname,$connex,$type_code,$mobile,$campaign,$defendant,$company,$solicitor_name)
	{
		global $gMysql;
		# only do this if case_key is empty too
		$gMysql->update("update ppi_user set 
		
		connex='$connex',case_key='$case_key',address1='$address1',address2='$address2',town='$town',postcode='$postcode',title='$title',forename='$forename',surname='$surname',email='$email',type_code='$type_code',mobile='$mobile',campaign='$campaign',defendant='$defendant',company_id='$company',solicitor_name='$solicitor_name', last_updated=NOW() 
		
		where (google_lead_id='$google_lead_id' and google_lead_id !='') ",__FILE__,__LINE__);
	}




	# sets state for a page, that shows a user has accepted it
	public function acceptUserPage($case_key,$email,$uri)
	{
		global $gMysql;

		$gMysql->delete("delete from ppi_user_page where email='$email' and case_key='$case_key' and uri='$uri' ",__FILE__,__LINE__);

		$gMysql->insert("insert into ppi_user_page	(id,case_key,email,uri,added) VALUES 	(0,'$case_key','$email','$uri',NOW())",__FILE__,__LINE__);

	}




	# gets  state for a page, that shows a user has accepted it
	public function getUserPage($case_key,$email,$uri)
	{
		global $gMysql;

		return $gMysql->queryRow("select * from ppi_user_page where email='$email' and case_key='$case_key' and uri='$uri'",__FILE__,__LINE__);


	}


	# has user accepted
	public function isSigned($case_key,$email,$var_signed)
	{
		global $gMysql;

		if (($val = $gMysql->queryRow("select * from ppi_user where email='$email' and case_key='$case_key' and $var_signed ='yes'",__FILE__,__LINE__)) != NULL)
		{
			return true;
		}
	}



	# has user accepted clientcare
	public function isClientCareSigned($case_key,$email)
	{
		global $gMysql;

		if (($val = $gMysql->queryRow("select * from ppi_user where email='$email' and case_key='$case_key' and clientcare_signed ='yes'",__FILE__,__LINE__)) != NULL)
		{
			return true;
		}
	}



	# has user accepted dsar
	public function isDsarSigned($case_key,$email)
	{
		global $gMysql;

		if ($val = ($gMysql->queryRow("select * from ppi_user where email='$email' and case_key='$case_key' and dsar_signed ='yes'",__FILE__,__LINE__)) != NULL)
		{
			return true;
		}
	}






	# user statement update
	public function updateUserStatement($case_key,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10,$q11,$q12,$q13,$q14,$q15)
	{
		global $gMysql;
		# only do this if case_key is empty too
		$gMysql->update("replace into ppi_user_statement (case_key,added,q1,q2,q3,q4,q5,q6,q7,q8,q9,q10,q11,q12,q13,q14,q15) values 
		 ('$case_key',NOW(),'$q1','$q2','$q3','$q4','$q5','$q6','$q7','$q8','$q9','$q10','$q11','$q12','$q13','$q14','$q15' ) ",__FILE__,__LINE__);
	}

	# returns the user statement data string for the questions to be asked
	public function getUserStatementData($case_key)
	{
		global $gMysql;

		$string	=	"";

		$data		= 	$gMysql->queryRow("select * from ppi_user_statement where case_key='$case_key'",__FILE__,__LINE__);

		$string		.=	($data['q1'] ==	"Yes")		?	'[".ppi-q1 "],'		:	'[""],';
		$string		.=	($data['q2'] ==	"Yes")		?	'[".ppi-q2 "],'		:	'[""],';
#		$string		.=	($data['q2a'] ==	"Yes")	?	'[".ppi-q2a "],'	:	'[""],';
#		$string		.=	($data['q2b'] ==	"Yes")	?	'[".ppi-q2b "],'	:	'[""],';
		$string		.=	($data['q3'] ==	"Yes")		?	'[".ppi-q3 "],'		:	'[""],';
#		$string		.=	($data['q3a'] ==	"Yes")	?	'[".ppi-q3a "],'	:	'[""],';
#		$string		.=	($data['q3b'] ==	"Yes")	?	'[".ppi-q3b "],'	:	'[""],';
		$string		.=	($data['q4'] ==	"Yes")		?	'[".ppi-q4 "],'		:	'[""],';
#		$string		.=	($data['q4a'] ==	"Yes")	?	'[".ppi-q4a "],'	:	'[""],';
		$string		.=	($data['q5'] ==	"Yes")		?	'[".ppi-q5 "],'		:	'[""],';
		$string		.=	($data['q6'] ==	"Yes")		?	'[".ppi-q6 "],'		:	'[""],';
#		$string		.=	($data['q6a'] ==	"Yes")	?	'[".ppi-q6a "],'	:	'[""],';
#		$string		.=	($data['q6b'] ==	"Yes")	?	'[".ppi-q6b "],'	:	'[""],';
#		$string		.=	($data['q6c'] ==	"Yes")	?	'[".ppi-q6c "],'	:	'[""],';
		$string		.=	($data['q7'] ==	"Yes")		?	'[".ppi-q7"],'		:	'[""],';
		$string		.=	($data['q8'] ==	"Yes")		?	'[".ppi-q8"],'		:	'[""],';
		$string		.=	($data['q9'] ==	"Yes")		?	'[".ppi-q9"],'		:	'[""],';
		$string		.=	($data['q10'] ==	"Yes")	?	'[".ppi-q10"],'		:	'[""],';
		$string		.=	($data['q11'] ==	"Yes")	?	'[".ppi-q11 "],'	:	'[""],';
		$string		.=	($data['q12'] ==	"Yes")	?	'[".ppi-q12 "],'	:	'[""],';
		$string		.=	($data['q13'] ==	"Yes")	?	'[".ppi-q13 "],'	:	'[""],';
		$string		.=	($data['q14'] ==	"Yes")	?	'[".ppi-q14 "],'	:	'[""],';
		$string		.=	($data['q15'] ==	"Yes")	?	'[".ppi-q15 "],'	:	'[""],';
		$string		.=	'[".ppi-q16 "],';			// 16

		return $string;
	}


	# this just updates the acceptance
	public function updateAccepted($case_key,$company_id,$accepted)
	{
		global $gMysql;

		# only do this if case_key is empty too
		$gMysql->update("update ppi_user set 

		company_id='$company_id', accepted='$accepted', last_updated=NOW() 
		
		where case_key='$case_key' ",__FILE__,__LINE__);

	}


	# this changes the company associated
	public function updateCompanyID($case_key,$company_id)
	{
		global $gMysql;

		# only do this if case_key is empty too
		$gMysql->update("update ppi_user set 

		company_id='$company_id', last_updated=NOW() 
		
		where case_key='$case_key' ",__FILE__,__LINE__);

	}



	# sends lead via connex
	public function sendConnex($case_key,$data_list_id)
	{
		global $gMysql;


		settype($data_list_id, "integer");

		# dialable leads id 2008
		$username 				=	"FairPlaneNew";
		$password 				=	"32574nq6b85n7eib";
		$token					=	"vgRIxpt9t2klwrbBbzRjHykSh3EPuuZT3K6OgXyjJkZGVL4JYl";


		if (($data = $this->getUserViaCaseKey($case_key)) != NULL)
		{
			$phone				=	$data['mobile'];
			# this MUST have a value
			$type_code			=	$data['type_code'];
			$name				=	$data['surname'];
			$email				=	$data['email'];
			$connex_sent		=	$data['connex_sent'];
AddCommentOnly("sendConnex() case_key:$case_key,name:$name connex_sent:$connex_sent , data_list_id:$data_list_id");

			if ($connex_sent == "")
			{
				//The JSON data.
				$jsonData = array(
					'username' 	=> $username,
					'password' 	=> $password,
				);
				//Encode the array into JSON.
				$jsonDataEncoded = json_encode($jsonData);
				//Initiate cURL.
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



				//The JSON data.
				$jsonData = array(
					'token' 			=> $token,
					'comments' 			=> '',
					'source_code' 		=> $case_key,
					'title' 			=> '',
					'first_name' 		=> '',
					'last_name' 		=> $name,
					'main_phone' 		=> $phone,
					'alternative_phone' => $phone,
					'email'				=> $email,
					'source' 			=> $type_code,
					'data_list'			=>	$data_list_id,
					'dupe_check_data_list'	=> true,
					'dupe_check_everything'	=> true,
				);


				//Encode the array into JSON.
				$jsonDataEncoded = json_encode($jsonData);
				//Initiate cURL.
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
				//Execute the request
				$result2 = json_decode(curl_exec($ch));
				# set field
				if ($result2->success == true)
				{
					AddCommentOnly("sendConnex() case_key:$case_key, Connex has been sent");
					# set field
					$gMysql->update("update ppi_user set connex_sent='yes' where case_key='$case_key'",__FILE__,__LINE__);
				}
				else
				{
					AddCommentOnly("sendConnex() case_key:$case_key, FAILED:". $result2->message . " data_list_id:$data_list_id");
				}
			}
			else
			{
				AddCommentOnly("sendConnex() case_key:$case_key, ALREADY SENT data_list_id:$data_list_id");
			}
		}
		else
		{

			AddCommentOnly("couldn't find in database data_list_id:$data_list_id");
		}
	}
}
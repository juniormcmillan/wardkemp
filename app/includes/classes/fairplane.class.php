<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 21-Nov-16
 * Time: 4:17 PM
 */

class Fairplane_Class
{

	public function __construct()
	{
	}


	public function __destruct()
	{

	}



	# max claim amount
	public function getMaxClaimAmount()
	{
		global	$gMysql;
		# max amount
		return 	$gMysql->queryItem("SELECT value FROM fp_pricepoints where id=5",__FILE__,__LINE__);
	}



	# max claim amount
	public function getMaxClaimAmountEuros()
	{
		global	$gMysql;
		# max amount
		return 	$gMysql->queryItem("SELECT euro FROM fp_pricepoints where id=5",__FILE__,__LINE__);
	}



	# get claim amount
	public function getClaimAmount($index=0)
	{
		global	$gMysql;
		# max amount
		return 	$gMysql->queryItem("SELECT euro FROM fp_pricepoints where fp_pricepoints.index='$index';",__FILE__,__LINE__);
	}

	# get claim amount
	public function getClaimAmountPounds($index=0)
	{
		global	$gMysql;
		# max amount
		return 	$gMysql->queryItem("SELECT value FROM fp_pricepoints where fp_pricepoints.index='$index';",__FILE__,__LINE__);
	}



	# get claim amount for export to proclaim (euros
	public function getClaimAmountForExport($index=0)
	{
		global	$gMysql;
		# max amount
		return 	$gMysql->queryItem("SELECT euro FROM fp_pricepoints where fp_pricepoints.index='$index';",__FILE__,__LINE__);
	}


	# get claim amount
	public function getClaimAmountEuros($index=0)
	{
		global	$gMysql;
		# max amount
		$pounds	=	$gMysql->queryItem("SELECT value FROM fp_pricepoints where fp_pricepoints.index='$index';",__FILE__,__LINE__);
		$rate	=	$gMysql->queryItem("SELECT rate FROM fp_pricepoints_eurorate;",__FILE__,__LINE__);
		$euros	=	ceil(($pounds * $rate) / 5) * 5;

		return $euros;
	}


	# get claim amount
	public function getClaimAmountsString($index=0)
	{
		global	$gMysql;
		# max amount
		$pounds	=	$gMysql->queryItem("SELECT value FROM fp_pricepoints where fp_pricepoints.index='$index';",__FILE__,__LINE__);
		$rate	=	$gMysql->queryItem("SELECT rate FROM fp_pricepoints_eurorate;",__FILE__,__LINE__);
		$euros	=	min(ceil(($pounds * $rate) / 5) * 5,600);

		$string	=	"&pound;$pounds (&euro;$euros) ";

		return $string;
	}





	# claim button
	public function getClaimButton()
	{
		return 	'<button id="makeClaim" onclick="doMakeClaim();" type="button" class="btn-lg btn-info btn btn-block">Click here to make a claim</button>';
	}


	# the image
	public function getMaxClaimImage()
	{
		# pick the version of the claim image
		global	$fp_max_claim_array;
		$fp_max_claim_image		=	"";
		$fp_max_claim_amount	=	$this->getMaxClaimAmount();

		for ($i = 0;$i< count($fp_max_claim_array); $i++)
		{
			if	($fp_max_claim_array[$i]['price'] == $fp_max_claim_amount)
			{
				$fp_max_claim_image 	=	$fp_max_claim_array[$i]['image'];
			}
		}

		if	(empty($fp_max_claim_image))
		{
			$fp_max_claim_image	=	"fairplane_fred.png";
		}


		return	$fp_max_claim_image;
	}



	# gets a strip
	public function getPayoutStrip()
	{
		$fp_max_claim_amount	=	$this->getMaxClaimAmountEuros();
		$fp_max_claim_image		=	$this->getMaxClaimImage();

		$payout_strip_text_1	=	'Claim up to €'.$fp_max_claim_amount.' in compensation.';
		$payout_strip_text_2	=	'Quick, easy and risk free compensation claims';
		$payout_strip_text_3	=	'*£25 Admin Fee plus 25% + VAT of the compensation recovered';

		$payout_strip_image		=	'

		<div class=" row fp-cta-wide v-center hidden-xs ">
			<div class="col-sm-2 " >
				<img class="img-responsive center-block" src="/app/images/website/pricepoints/'.$fp_max_claim_image.'" alt="Flight delay compensation claim amount">
			</div>
			<div class="col-sm-6" >
				<div class="fp-cta-wide-top">'.$payout_strip_text_1.'</div>
				<div class="fp-cta-wide-mid">'.$payout_strip_text_2.'</div>
				<div class="fp-cta-wide-bot">'.$payout_strip_text_3.'</div>
			</div>
			<div class="col-sm-4 " style="margin-bottom:10px;" >
				<a onclick="doMakeClaim();" href="/make-a-flight-delay-claim"><img class="img-responsive" src="/app/images/website/claim_button.png" alt="Flight Delay Claim Button"></a>
			
			</div>
		</div>



		<div class=" row fp-cta-wide  hidden-lg hidden-md hidden-sm">
			<div class="col-sm-2 " >
				<img class="img-responsive center-block" src="/app/images/website/pricepoints/'.$fp_max_claim_image.'" alt="Flight delay compensation claim amount">
				<br>
			</div>
			<div class="col-sm-6" >
				<div class="fp-cta-wide-top text-center" >'.$payout_strip_text_1.'</div>
				<div class="fp-cta-wide-mid text-center">'.$payout_strip_text_2.'</div>
				<div class="fp-cta-wide-bot text-center">'.$payout_strip_text_3.'<br><br></div>
			</div>
			<div class="col-sm-4 text-center" >
				<a class="text-center" onclick="doMakeClaim();" href="#"><img src="/app/images/website/claim_button.png" alt="Make a flight delay compensation claim button"></a>
				<br>
				<br>
			</div>
		</div><br>

		';

		return	$payout_strip_image;
	}






	# returns a string of login or logout based on status
	public function getLoggedInLink()
	{
		global	$gSession;

		if	(LOGIN_AVAILABLE == false)
		{
			return;
		}

		# check
		if	($gSession->isLoggedIn() == true)
		{

			$string	=	'
		<a href="'. $gSession->getLoggedInUrl() .'"\'><img class="hidden-xs hidden-sm img-responsive" src="/app/images/website/track-claim1.png">
		</a>';

			$string	.=	'
		<a href="'. $gSession->getLoggedInUrl() .'"\'><img style="float:right;" class="hidden-md hidden-lg img-responsive" src="/app/images/website/track-claim3.png">
		</a>';


		}
		else
		{
			$string	=	'
		<a href="/login"><img class="hidden-xs hidden-sm img-responsive" src="/app/images/website/track-claim-1.png">
		</a>';


			$string	=	'<a href="/login" class="btn btn-sm btn-info">Track your claim online&nbsp;&nbsp;<i class="fa fa-user fa-lgs" aria-hidden="true"></i></a>';
			$string	=	'<a href="/login" class="btn btn-sm btn-info">Login&nbsp;&nbsp;<i class="fa fa-user fa-lg" aria-hidden="true"></i></a>';



		}


		$string	=	"<div class='top_login_section' style='z-index:99; float:right;'>$string</div>";



		return	$string;
	}


	public function getMailToLink()
	{
		return	"subject=Making a Web Claim&body=I am interested in making a claim.%0D%0DPlease contact me on:%0DThe best time to contact me is:%0D";

	}



	# checks if this claim has a case_key - means is this case already set up
	public function checkInitialClaimInProclaim($order_id)
	{
		global $gMysql;

		# if we have a match with a filled in case-key then return true
		if (($val = $gMysql->queryItem("SELECT count(*) from fp_flight_master_db_flight_info where order_id='$order_id' and case_key!=''",__FILE__,__LINE__)) != 0)
		{
			return true;
		}

		return 	false;

	}





	# checks if this claim has a case_key - means is this case already set up
	public function checkInitialClaimClicked($order_id)
	{
		global $gMysql;

		# if we have a match with a filled in case-key then return true
		if (($val = $gMysql->queryItem("SELECT count(*) from fp_flight_master_db_flight_info where order_id='$order_id' and (ta_clicked!='' or case_key!='') ",__FILE__,__LINE__)) != 0)
		{
			return true;
		}

		return 	false;

	}





	# sets it to having been sent
	public function setInitialClaimClicked($order_id)
	{
		global $gMysql;

		# if we have a match with a filled in case-key then return true
		$gMysql->update("update fp_flight_master_db_flight_info set ta_clicked=1 where order_id='$order_id' ",__FILE__,__LINE__);

	}




	# create the initial bare-bones claim for this person - so we can perform some actions before the upload from proclaim.
	# there will be some other calls to update various parts of the data via the SMS additions
	public function createInitialClaim($order_id,$forename,$surname,$email,$phone,$a_aid,$code,$split_test_version)
	{
		global $gMysql;

		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|","'",'"',"'");
		$forename		=	str_replace($invalid_characters,"",$forename);
		$surname		=	str_replace($invalid_characters,"",$surname);


		if (($val = $gMysql->queryItem("SELECT count(*) from fp_flight_master_db_flight_info where order_id='$order_id'",__FILE__,__LINE__)) == 0)
		{
			$gMysql->insert("insert into fp_flight_master_db_flight_info (id,forename,surname,phone,email,order_id,affiliate_id,last_updated,case_input_date,code,split_test_version) values(0,'$forename','$surname','$phone','$email','$order_id','$a_aid',NOW(),NOW(),'$code','$split_test_version'	)", __FILE__, __LINE__);
		}


	}


	# create the initial bare-bones claim for this person - so we can perform some actions before the upload from proclaim.
	# there will be some other calls to update various parts of the data via the SMS additions
	# this addition is for the travel-agent splice, from the excel sheet
	public function createInitialClaimTravelAgent($reference,$order_id,$title,$forename,$surname,$email,$phone,$a_aid,$flight_date,$airline_code,$flight_number,$ta_batch_id,$bOverwrite=false)
	{
		global $gMysql;

		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|","'",'"',"'");
		$forename		=	str_replace($invalid_characters,"",$forename);
		$surname		=	str_replace($invalid_characters,"",$surname);

		# create the
		if (($data	=	$this->flightCheck($airline_code,$flight_number,$flight_date)) != NULL)
		{
			$dep_airport_code	=	$data['dep_airport_code'];
			$arr_airport_code	=	$data['arr_airport_code'];
		}
		else
		{
			$dep_airport_code	=	"";
			$arr_airport_code	=	"";
		}
		# important
		$flight_id	=	$airline_code . "_" . $flight_number . "_" .	str_replace("-","",$flight_date) . "_" .  $dep_airport_code	. "_" . $arr_airport_code	;

		if ($bOverwrite == true)
		{
			$gMysql->delete("delete from fp_flight_master_db_flight_info where order_id='$order_id'",__FILE__,__LINE__);
		}

		if (($val = $gMysql->queryItem("SELECT count(*) from fp_flight_master_db_flight_info where order_id='$order_id'",__FILE__,__LINE__)) == 0)
		{

			$gMysql->insert("insert into fp_flight_master_db_flight_info (id,ta_reference,flight_date,title,forename,surname,phone,email,order_id,affiliate_id,last_updated,flight_id,ta_batch_id) values
			(0,'$reference','$flight_date','$title','$forename','$surname','$phone','$email','$order_id','$a_aid',NOW(),'$flight_id','$ta_batch_id'	)", __FILE__, __LINE__);

			return true;
		}
	}






	# now we have a case_key attached, we
	public function updateInitialClaim($order_id,$case_key,$case_input_date)
	{
		global $gMysql;

		# check the database for this person with the order_id
		if (($data =	$gMysql->queryRow("SELECT * from fp_flight_master_db_flight_info where (order_id='$order_id' and order_id !='')",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
		{
			# update the database - YOU CAN FIGURE THIS BIT OUT
			$gMysql->update("UPDATE fp_flight_master_db_flight_info set case_key='$case_key',case_input_date='$case_input_date'  where order_id='$order_id'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);

			AddComment("UPDATED: - case_key: $case_key, order_id:$order_id, case_input_date:$case_input_date");

			return true;
		}
		# if it gets here, then this person does have an order_id and not exist... figure out what the issue is: bad name spelling??
		else
		{
			AddComment("COULD NOT FIND : case_key: $case_key, order_id:$order_id, case_input_date:$case_input_date ");
		}
	}






	# export to CSV
	public function exportTravelAgentClaims($a_aid,$date)
	{
	}





	# grab the flight data. we can improve to show any delay etc
	public function flightCheck($airline_code,$flight_number,$flight_date)
	{
		global $gMysql;

		$data_array	=	array();

		$data_array['airline_code']			=	$airline_code;
		$data_array['flight_number']		=	$flight_number;
		$data_array['flight_date']			=	$flight_date;

		# test the full flight number (WAS $formFlight and changed 07/04/16 )
		$data	=	$gMysql->queryRow("SELECT DepartureAirport,ArrivalAirport from flights where (Carrier='$airline_code' and FlightNumber='$flight_number' ) limit 1",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
		if (empty($data))
		{
			# test using the standard code if IACO may have been used
			$airline_standard_code		=	$gMysql->QueryItem("SELECT code from fp_airlines where code = '$airline_code' or icao='$airline_code' ",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
			$data	=	$gMysql->queryRow("SELECT DepartureAirport,ArrivalAirport from flights where (Carrier='$airline_standard_code' and FlightNumber='$flight_number' ) limit 1",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
		}


		# this means we have no valid flight data that we can find in the austrian dbase
		if (!empty($data))
		{
			# short code of the airports
			$dep_airport		=	$data['DepartureAirport'];
			$arr_airport		=	$data['ArrivalAirport'];

			# get the full name and others
			$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat,code from fp_airports where code='$dep_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
			$departure_airport	=	$data['name'];
			$dep_airport		=	$data['code'];

			$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat,code from fp_airports where code='$arr_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
			$arrival_airport	=	$data['name'];
			$arr_airport		=	$data['code'];

			$data_array['departure_airport']		=	$departure_airport;
			$data_array['arrival_airport']			=	$arrival_airport;

			$data_array['dep_airport_code']			=	$dep_airport;
			$data_array['arr_airport_code']			=	$arr_airport;


		}
		# check the flights database
		else
		{
			$query	=	"select dep_airport_code,arr_airport_code from fp_flight_master_db where airline_code='$airline_standard_code' and flight_number='$flight_number' limit 1";

			if (($data	=	$gMysql->queryRow("$query",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
			{
				$returnCode			=	"success";
				# short code of the airports
				$dep_airport		=	$data['dep_airport_code'];
				$arr_airport		=	$data['arr_airport_code'];

				# get the full name and others
				$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat from fp_airports where code='$dep_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
				$departure_airport	=	$data['name'];

				$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat from fp_airports where code='$arr_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
				$arrival_airport	=	$data['name'];


				$data_array['departure_airport']		=	$departure_airport;
				$data_array['arrival_airport']			=	$arrival_airport;
				$data_array['dep_airport_code']			=	$dep_airport;
				$data_array['arr_airport_code']			=	$arr_airport;

			}
			# not in the fairplane databases, so try API
			else
			{
				# **** Now try the API for the flight details (WE NEED A DATE THOUGH)
				#if	(empty($formDate))

				# get the structure back from the API with this date included
				if (($flight_data	=	getFlightDataFromAPI($airline_code,$flight_number,$flight_date)) != NULL)
				{
					$dep_airport		=	substr($flight_data['departure_airport'],0,3);
					$arr_airport		=	substr($flight_data['arrival_airport'],0,3);

					$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat from fp_airports where code='$dep_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
					$departure_airport	=	$data['name'];

					$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat from fp_airports where code='$arr_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
					$arrival_airport	=	$data['name'];

					$data_array['departure_airport']		=	$departure_airport 	. "(" .	$dep_airport	.")";
					$data_array['arrival_airport']			=	$arrival_airport	. "(" .	$arr_airport	.")";
					$data_array['dep_airport_code']			=	$dep_airport;
					$data_array['arr_airport_code']			=	$arr_airport;
				}
				else
				{

					return false;

				}

			}
		}

		return	$data_array;
	}



	# get the voucher string
	public function getVoucherString($voucher_code)
	{
		global $gMysql;

		$voucher_code_box	=	"";

		# get the code data
		if (($data =	$gMysql->QueryRow("SELECT * from fp_voucher_code where code = '$voucher_code' and type='F'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
		{
			# for fixed codes, we can build the message slightly differently
			$value			=	$data['value'];
			$message		=	"£"	.	$value ." voucher has been applied";
			$message_box	=	"This voucher reduces the admin fee for successful claims from £25 to £"	.	(25 - $value);

			#	$message_box	=	"Voucher code "	.  strtoupper($voucher_code) . " accepted";
			$voucher_code_box	=	'<div class="col-xs-12" id="voucher-code-box" name="voucher-code-box" style="margin-top:10px;padding:10px; border:2px solid #3fd2ef;">'.  $message_box .'</div>';
			$voucher_code_box	=	$message_box;
		}




		return $voucher_code_box;
	}



	# get the voucher string
	public function getVoucherData($voucher_code)
	{
		global $gMysql;

		# get the code data
		if (($data =	$gMysql->QueryRow("SELECT * from fp_voucher_code where code = '$voucher_code' and type='F'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
		{


		}

		# for personal coces, there will need to be a special extraction m



		return $data;
	}


	# updates the usage stats
	public function updateVoucherUse($voucher_code)
	{
		global $gMysql;

		$gMysql->update("update fp_voucher_code set times_submitted=times_submitted+1,last_used=NOW() where (code = '$voucher_code' and code !='')",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
	}

}
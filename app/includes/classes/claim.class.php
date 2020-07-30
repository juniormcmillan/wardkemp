<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 19-Feb-16
 * Time: 9:14 AM
 */

# if the end-user has edited the claim
define	('CLAIM_STATE_UNEDITED',				0);
# partner updated the claim (only allow re-uploading if previously unedited)
define	('CLAIM_STATE_PARTNER_UPDATED',		1);
# submitted the changes to the claim
define	('CLAIM_STATE_CLAIMANT_SUBMITTED',				2);
# processed and claim file sent to proclaim (no more editing)
define	('CLAIM_STATE_PROCESSED',				3);



define	('CLAIM_OK',							0);
define	('CLAIM_ERROR',							1);
define	('CLAIM_ERROR_ALREADY_SUBMITTED',		2);
define	('CLAIM_ERROR_ALREADY_PROCESSED',		3);
define	('CLAIM_ERROR_FLIGHT',					4);
define	('CLAIM_NOT_FOUND_ERROR',				5);
define	('CLAIM_STATE_ERROR',					6);
define	('CLAIM_BLANK_LINE_ERROR',				7);






class Claim_Class
{
	private	$name					=	"";
	private	$id						=	"";
	private	$partner_code			=	"";




	public function __construct()
	{
	}


	public function __destruct()
	{
	}




	# gets claim by hash
	public function getByHash($hash)
	{
		global	$gMysql;

		if	(($claim	=	$gMysql->queryRow("select * from fp_claim where hash='$hash'",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL)) != NULL)
		{
			return	$claim;
		}
	}



	# gets claim by reference
	public function getByReference($partner_id,$job_id,$reference)
	{
		global	$gMysql;

		if	(($claim = $gMysql->queryRow("select * from fp_claim where reference='$reference' and partner_id='$partner_id' and job_id='$job_id'",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL)) != NULL)
		{
			return	$claim;
		}
	}

	# remove a claim from this partner and this reference
	public function deletePartnerClaim($partner_id,$job_id,$reference)
	{
		global $gMysql;

		$gMysql->delete("delete from fp_claim where reference='$reference' and partner_id='$partner_id' and job_id='$job_id' ",__FILE__,__LINE__);
	}




	# remove a claim
	public function deleteClaim($hash)
	{
		global $gMysql;

		$claim_state_submitted	=	CLAIM_STATE_CLAIMANT_SUBMITTED;
		$claim_state_processed	=	CLAIM_STATE_PROCESSED;

		# make sure we don't have any claims that have been edited
		if (($num = $gMysql->queryItem("select count(*) from fp_claim where hash='$hash' and (claim_state='$claim_state_submitted' or claim_state='$claim_state_processed')",__FILE__,__LINE__)) == 0)
		{
			$job_id		=	$gMysql->queryItem("select job_id from fp_claim where hash='$hash' ",__FILE__,__LINE__);

			$gMysql->update("update fp_job set entitled_claims=entitled_claims-1, total_lines=total_lines-1 where id='$job_id' ",__FILE__,__LINE__);

			$gMysql->delete("delete from fp_claim where hash='$hash' ",__FILE__,__LINE__);

			return true;
		}
	}



	# add this claim to the list of claims, but we need to only allow editing once we have the second phase of data from agent
	public function addClaim($job_id,$partner_id,$data,$bMoreDataIncluded=false,$claim_state=CLAIM_STATE_UNEDITED,$check_only=false)
	{
		global $gMysql;


		$reference			=	$data['reference'];
		$airline_code		=	$data['airline_code'];
		# strip any letters
		$flight_number		=	$data['flight_number'];

		# remove the airline code from this (if it is attached)
		$flight_number		=	str_replace($airline_code,"",$flight_number);
		# remove all non alphas (such as '-' and spaces)
		$flight_number		=	preg_replace("/[^A-Za-z0-9 ]/", '', $flight_number);


		$flight_date		=	date( 'Y-m-d', strtotime($data['flight_date']) );
		$array 				=	explode('-',$flight_date);
		# YYYY-MM-DD (mysql)
		$year	=	$array[0];
		$month	=	$array[1];
		$day	=	$array[2];

		# make sure we do not have a blankline (skip this)
		if	( (empty($airline_code)) || (empty($flight_number)) )
		{
			return	CLAIM_BLANK_LINE_ERROR;
		}

		if ((checkdate($month,$day,$year) == false) || (empty($data['flight_date'])))
		{
AddComment("BAD DATE $airline_code $flight_number on $flight_date");
			# BAD DATE
			return CLAIM_ERROR;
		}


		# we need to check if flight is valid
		if	(($flight_data = $this->CheckFlight($flight_number,$airline_code,$flight_date)) != NULL)
		{
AddComment("Flight found $airline_code $flight_number on $flight_date");

			$notes						=	"";
			$uk_arrival_value			=	0;
			$delay						=	$flight_data['Delay'];
			$distance_km				=	$flight_data['DistanceKm'];

			$combined_id				=	$flight_data['Id'];

			# airports
			$departure_airport			=	$flight_data['DepartureAirport'];
			$arrival_airport			=	$flight_data['ArrivalAirport'];


			# make sure we have the distance, for the claim check computation - Austria don't always store it in their dbase for some reason
			if	($distance_km == 0)
			{
				# now grab the distance from API
				# we could keep a dbase of all distances, in case we fall onto issues later
				# get the full name and others
				$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat from fp_airports where code='$departure_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
				$lon1				=	$data['lon'];
				$lat1				=	$data['lat'];

				$data				=	$gMysql->queryRow("SELECT CONCAT(name, ' (' ,code ,')' ) as name,lon,lat from fp_airports where code='$arrival_airport'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
				$lon2				=	$data['lon'];
				$lat2				=	$data['lat'];
				# computes distance long/lat
				$distance_km			=	round($this->getDistance($lat1, $lon1, $lat2, $lon2, "K"));
			}


				$carrier					=	$flight_data['Carrier'];
			$flight_number				=	$flight_data['FlightNumber'];

			$scheduled_departure		=	$flight_data['ScheduledDeparture'];
			$actual_departure			=	$flight_data['ActualDeparture'];
			$scheduled_arrival			=	$flight_data['ScheduledArrival'];
			$actual_arrival				=	$flight_data['ActualArrival'];

			$sched_dep_date	=	date("Y-m-d",strtotime($flight_data['ScheduledDeparture']));
			if	($sched_dep_date == '1970-01-01')	$sched_dep_date	=	'0000-00-00';

			$sched_dep_time	=	date("H:i:s",strtotime($flight_data['ScheduledDeparture']));

			$act_dep_date		=	date("Y-m-d",strtotime($flight_data['ActualDeparture']));
			if	($act_dep_date == '1970-01-01')	$act_dep_date	=	'0000-00-00';

			$act_dep_time		=	date("H:i:s",strtotime($flight_data['ActualDeparture']));

			$sched_arr_date		=	date("Y-m-d",strtotime($flight_data['ScheduledArrival']));
			if	($sched_arr_date == '1970-01-01')	$sched_arr_date	=	'0000-00-00';

			$sched_arr_time		=	date("H:i:s",strtotime($flight_data['ScheduledArrival']));

			$act_arr_date		=	date("Y-m-d",strtotime($flight_data['ActualArrival']));
			if	($act_arr_date == '1970-01-01')	$act_arr_date	=	'0000-00-00';

			$act_arr_time		=	date("H:i:s",strtotime($flight_data['ActualArrival']));

			$austria_arrival_value		=	$flight_data['ActualArrivalValue'];

			# delay in minutes for both these (in case there is a connecting flight)
			$delay_depart				=	$flight_data['timediff_depart'] / 60;
			$delay_arrive				=	$flight_data['timediff_arrive'] / 60;

			# we can build some notes for review
			if	($delay_depart < $delay_arrive)
			{
				$notes	.=	" - DELAYED ARRIVAL - ";
			}
			else
			{
				# normal, but we need to check that the time is valid
				if	( ($actual_arrival == "0000-00-00 00:00:00"))
				{
					$notes	.=	" - DELAYED ARRIVAL - ";
				}

			}



			# assume that this flight is in the system, so it is delayed or cancelled

			#  more than 3hrs
			if	($distance_km < 1500)
			{
				$compensation	=	200;
			}
			# within eu over 1500km or any flight
			else if (($distance_km > 1500) && ($distance_km < 3500))
			{
				$compensation	=	310;
			}
			# more than 3500km and 3-4 hrs
			else if (($distance_km > 3500) && (($delay > 180) && ($delay < 240)) )
			{
				$compensation	=	235;
			}
			# more than 3500km and more than 4hrs
			else if (($distance_km > 3500) && ($delay > 240))
			{
				$compensation	=	475;
			}
			else
			{
				$compensation	=	0;
			}






/*			# top tier compensation 3.5k miles and over 4hrs delay
			if (($distance_km > 3500) && ($delay >= 240))
			{
				$uk_arrival_value	=	4;
#				$cost				=	COMPENSATION_OVER_3500_DELAY_OVER_4HRS;
			}
			# second top compensation 3.5k miles and over 3hrs delay
			else if (($distance_km > 3500) && ($delay >= 180))
			{
				$uk_arrival_value	=	3;
#				$cost				=	COMPENSATION_OVER_3500_DELAY_OVER_3HRS;
			}
			# third top compensation 3.5k miles and in EU (Not valid)
			else if (($distance_km > 3500) && ($delay >= 180) && $bInEU)
			{
				$uk_arrival_value	=	2;
#				$cost				=	COMPENSATION_OVER_3500_DELAY_OVER_3HRS_EU;
			}
			# fourth top compensation 1.5k miles and 3hrs delay
			else if (($distance_km > 1500) && ($delay >= 180))
			{
				$uk_arrival_value	=	1;
#				$cost				=	COMPENSATION_OVER_1500_DELAY_OVER_3HRS;
			}
			# fifth top compensation under 1.5k miles and 3hrs delay
			else if (($distance_km <= 1500) && ($delay >= 180))
			{
				$uk_arrival_value	=	0;
#				$cost				=	COMPENSATION_UNDER_1500_DELAY_OVER_3HRS;
			}
			# sixth = cancellation?
			else
			{
				$uk_arrival_value 	=	-1;
				$notes				.=	" - CANCELLATION? - ";
#				$cost				=	COMPENSATION_CANCELLATION;
			}
*/


			# key for accessing this new record
			$random_hash 		=	md5(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

			# check that we do not have a live item
			if	(($claim =	$gMysql->queryRow("select * from fp_claim where (reference='$reference' and partner_id='$partner_id')",__FILE__,__LINE__)) != NULL)
			{
				# 1. scenario is that we have several passengers in a group using the same reference. We want to make note that this passenger could be in a group
				#    if we are only checking, we don't want to update here, as it will skew things later
				if	($check_only == 0)
				{
					# update number of duplicates for this claim (this may not be 100% accurate if we upload the same file twice, then delete one )
					# however, it may be a good indicator to allow us to send out different emails tailored to the customer
					$gMysql->update("update fp_claim set duplicates=duplicates+1 where (reference='$reference' and partner_id='$partner_id')",__FILE__,__LINE__);
				}


				# we can probably delete, but perhaps keep the original one there
				if	(($claim['claim_state'] == CLAIM_STATE_UNEDITED) || ($claim['claim_state'] == CLAIM_STATE_PARTNER_UPDATED ))
				{
					return CLAIM_ERROR_ALREADY_SUBMITTED;

					# change the consignment id
					$job_id		=	$gMysql->queryItem("select job_id from fp_claim where (reference='$reference' and partner_id='$partner_id')",__FILE__,__LINE__);

					# delete
					$gMysql->delete("delete from fp_claim where (reference='$reference' and partner_id='$partner_id')",__FILE__,__LINE__);
				}
				# this claim cannot be updated.
				else if ($claim['claim_state'] == CLAIM_STATE_CLAIMANT_SUBMITTED)
				{
					return CLAIM_ERROR_ALREADY_SUBMITTED;
				}
				else if ($claim['claim_state'] == CLAIM_STATE_PROCESSED)
				{
					return CLAIM_ERROR_ALREADY_PROCESSED;
				}
				# this claim cannot be updated.
				else
				{
					return CLAIM_ERROR;
				}
			}

			# this is if we attach more information (eg. by creating new passengers from single claim)
			if	($bMoreDataIncluded == true)
			{
				# now we need to find out number of referrers are using this code, and add 1
				$strSQL	=	"insert into fp_claim (id, reference, partner_id, job_id, airline_code,flight_number,
				sched_dep_date,	sched_dep_time,	act_dep_date,	act_dep_time,
				sched_arr_date,	sched_arr_time,	act_arr_date,	act_arr_time,
				departure_airport,arrival_airport,
				austria_arrival_value,uk_arrival_value,delay_arrive,delay_depart,delay,notes,
				first_name,last_name,address,city,country,postcode,telephone,email,isChild,
				added,last_updated,	data_state,claim_state,distance, hash,passenger,parent_reference,estimated_compensation)

				values (
				'" . 0 ."',
				'" . $reference ."',
				'" . $partner_id ."',
				'" . $job_id ."',
				'" . $airline_code ."',
				'" . $flight_number ."',
				'" . $sched_dep_date   ."',
				'" . $sched_dep_time   ."',
				'" . $act_dep_date     ."',
				'" . $act_dep_time     ."',
				'" . $sched_arr_date   ."',
				'" . $sched_arr_time   ."',
				'" . $act_arr_date     ."',
				'" . $act_arr_time           ."',
				'" . $departure_airport      ."',
				'" . $arrival_airport        ."',
				'" . $austria_arrival_value  ."',
				'" . $uk_arrival_value       ."',
				'" . $delay_arrive           ."',
				'" . $delay_depart           ."',
				'" . $delay           ."',
				'" . $notes."',

				'" . $data['first_name']."',
				'" . $data['last_name']."',
				'" . $data['address']."',
				'" . $data['city']."',
				'" . $data['country']."',
				'" . $data['postcode']."',
				'" . $data['telephone']."',
				'" . $data['email']."',
				'" . $data['isChild']."',

				NOW(),
				NOW(),
				'" . DATA_STATE_INITIAL."',
				'" . $claim_state."',
				'" . $distance_km."',
				'". $random_hash ."',
				'". $data['passenger'] ."',
				'". $data['parent_reference']."',
				'". $compensation	."'
				)";
			}
			# this is for standard claims (4-col)
			else
			{
				# now we need to find out number of referrers are using this code, and add 1
				$strSQL	=	"insert into fp_claim (id, reference, partner_id, job_id,	 airline_code,flight_number,

				sched_dep_date,	sched_dep_time,	act_dep_date,	act_dep_time,
				sched_arr_date,	sched_arr_time,	act_arr_date,	act_arr_time,
				departure_airport,arrival_airport,
				austria_arrival_value,uk_arrival_value,delay_arrive,delay_depart,delay,notes,
				added,last_updated,	data_state,claim_state,distance,hash,estimated_compensation)

				values (
				'" . 0 ."',
				'" . $reference ."',
				'" . $partner_id ."',
				'" . $job_id ."',
				'" . $airline_code ."',
				'" . $flight_number ."',
				'" . $sched_dep_date   ."',
				'" . $sched_dep_time   ."',
				'" . $act_dep_date     ."',
				'" . $act_dep_time     ."',
				'" . $sched_arr_date   ."',
				'" . $sched_arr_time   ."',
				'" . $act_arr_date     ."',
				'" . $act_arr_time           ."',
				'" . $departure_airport      ."',
				'" . $arrival_airport        ."',
				'" . $austria_arrival_value  ."',
				'" . $uk_arrival_value       ."',
				'" . $delay_arrive           ."',
				'" . $delay_depart           ."',
				'" . $delay					."',
				'" . $notes."',
				NOW(),
				NOW(),
				'" . DATA_STATE_INITIAL."',
				'" . CLAIM_STATE_UNEDITED ."',
				'" . $distance_km."',
				'". $random_hash ."',
				'". $compensation	."'
				)";
			}

			# older version has levels too
			$gMysql->insert($strSQL,__FILE__,__LINE__);


#	return CODE (valid)
			return CLAIM_OK;
		}


		return CLAIM_ERROR_FLIGHT;
	}







	# add this claim to the list of claims, but we need to only allow editing once we have the second phase of data from agent
	public function updateClaim($partner_id,$data)
	{
		global $gMysql;


		$reference			=	$data['reference'];

		$title				=	$data['title'];
		$first_name			=	$data['first_name'];
		$last_name			=	$data['last_name'];
		$address			=	$data['address'];
		$postcode			=	$data['postcode'];
		$city				=	$data['city'];
		$country			=	$data['country'];
		$telephone			=	$data['telephone'];
		$email				=	$data['email'];


		# check that we do not have a live item
		if	(($claim =	$gMysql->queryRow("select * from fp_claim where (reference='$reference' and partner_id='$partner_id')",__FILE__,__LINE__)) != NULL)
		{
			# if a claimant hasn't edited this, we change this to
			if	(($claim['claim_state'] == CLAIM_STATE_UNEDITED) || ($claim['claim_state'] == CLAIM_STATE_PARTNER_UPDATED ))
			{
				# now we need to find out number of referrers are using this code, and add 1
				$strSQL	=	"update fp_claim set

				title				=	'$title',
				first_name			=	'$first_name',
				last_name			=	'$last_name',
				address				=	'$address',
				postcode			=	'$postcode',
				city				=	'$city',
				country				=	'$country',
				telephone			=	'$telephone',
				email				=	'$email',
				last_updated		=	NOW(),

				claim_state			=	". CLAIM_STATE_PARTNER_UPDATED . "

				where (reference='$reference' and partner_id='$partner_id')

				";
				# older version has levels too
				$gMysql->update($strSQL,__FILE__,__LINE__);

				# we have successfully updated the data
				return	CLAIM_OK;

			}
			# we can re-submit but this is may only work before system updates
			else if ($claim['claim_state'] == CLAIM_STATE_CLAIMANT_SUBMITTED)
			{
				$isChild			=	$data['isChild'];
				# now we need to find out number of referrers are using this code, and add 1
				$strSQL	=	"update fp_claim set

				title				=	'$title',
				first_name			=	'$first_name',
				last_name			=	'$last_name',
				address				=	'$address',
				postcode			=	'$postcode',
				city				=	'$city',
				country				=	'$country',
				telephone			=	'$telephone',
				email				=	'$email',
				isChild				=	'$isChild',
				last_updated		=	NOW(),

				claim_state			=	'" . CLAIM_STATE_CLAIMANT_SUBMITTED. "'

				where (reference='$reference' and partner_id='$partner_id')

				";
				# older version has levels too
				$gMysql->update($strSQL,__FILE__,__LINE__);

AddComment("CLAIM ALREADY SUBMITTED, $first_name  so exiting");
				return CLAIM_ERROR_ALREADY_SUBMITTED;
			}
			else if ($claim['claim_state'] == CLAIM_STATE_PROCESSED)
			{
				return CLAIM_ERROR_ALREADY_PROCESSED;
			}
			# an error with the state
			else
			{
				return CLAIM_STATE_ERROR;
			}
		}

		return CLAIM_NOT_FOUND_ERROR;

	}





	# checks flight using the database delay_mins to exit if the flight is delayed less than this number
	public function CheckFlight($flight_number,$airline_code,$flight_date,$bUseApi=false,$delay_mins=0)
	{
		global $gMysql;

		$gMysql->setDB("newfairp_flightdata");

		$sql		=	"select *, TIME_TO_SEC(TIMEDIFF(ActualDeparture,ScheduledDeparture)) as timediff_depart, TIME_TO_SEC(TIMEDIFF(ActualArrival,ScheduledArrival)) as timediff_arrive from flights where FlightNumber='$flight_number' and Carrier='$airline_code' and date(ScheduledDeparture)='$flight_date'";

		# check this flight for delays in our database
		$flight_data	=	$gMysql->queryRow($sql, __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);

		$gMysql->setDB(MYSQL_DBASE);


#		# check our homegrown dbase
#		$sql		=	"select *, TIME_TO_SEC(TIMEDIFF(act_dep_time,ScheduledDeparture)) as timediff_depart, TIME_TO_SEC(TIMEDIFF(ActualArrival,ScheduledArrival)) as timediff_arrive from flights where FlightNumber='$flight_number' and Carrier='$airline_code' and date(ScheduledDeparture)='$flight_date'";
#		# check this flight for delays in our database
#		$flight_data	=	$gMysql->queryRow($sql, __FILE__, __LINE__,CACHE_CACHE_TIME_NORMAL);


		# perhaps use the GetFlights API?? - only if set
		if ((CLAIM_USE_API == true) || ($bUseApi == true))
		{
			if	(empty($flight_data))
			{
				if (($data_packet = getFlightDataFromAPI($airline_code,$flight_number,$flight_date)) != false)
				{
					# if the flight is deemed cancelled or delayed in some way, we need to populate the data packet we returning
					if	(getFlightDelayedFromAPI($data_packet) == true)
					{
						$flight_data	=	array();

						$flight_data['Carrier']				=	$airline_code;
						$flight_data['FlightNumber']		=	$flight_number;
						$flight_data['DistanceKm']			=	$data_packet['distance'];
						$flight_data['DepartureAirport']	=	substr($data_packet['departure_airport'],0,3);
						$flight_data['ArrivalAirport']		=	substr($data_packet['arrival_airport'],0,3);
						$flight_data['ScheduledDeparture']	=	date( 'Y-m-d H:i:s', strtotime($data_packet['scheduled_time']) );


						if	(isset($data_packet['actual_time']))
						{
							$flight_data['ActualDeparture']		=	date( 'Y-m-d H:i:s', strtotime($data_packet['actual_time']) );
						}
						else
						{
							$flight_data['ActualDeparture']		=	"0000-00-00 00:00:00";
						}

						$flight_data['ScheduledArrival']	=	date( 'Y-m-d H:i:s', strtotime($data_packet['scheduled_arrival_time']) );


						if	(isset($data_packet['actual_arrival_time']))
						{
							$flight_data['ActualArrival']		=	date( 'Y-m-d H:i:s', strtotime($data_packet['actual_arrival_time']) );
						}
						else
						{
							$flight_data['ActualArrival']		=	"0000-00-00 00:00:00";
						}

						$flight_data['ActualArrival']		=	date( 'Y-m-d H:i:s', strtotime($data_packet['actual_arrival_time']) );
						$flight_data['ActualDeparture']		=	date( 'Y-m-d H:i:s', strtotime($data_packet['actual_time']) );



						$flight_data['timediff_depart']		=	(strtotime($flight_data['ActualDeparture']) - strtotime($flight_data['ScheduledDeparture']))	/	60;
						$flight_data['timediff_arrive']		=	(strtotime($flight_data['ActualArrival']) - strtotime($flight_data['ScheduledArrival']))		/	60;


						$timediff_depart					=	$flight_data['timediff_depart'];
						$timediff_arrive					=	$flight_data['timediff_arrive'];

						$flight_data['Delay']				=	($timediff_depart > $timediff_arrive) ?	$timediff_depart	:	$timediff_arrive;
						$flight_data['Id']					=	$data_packet['key'];

						# we need a rough idea of if it could be delayed or cancelled
						if	( ($timediff_depart < $delay_mins) && ($timediff_arrive < $delay_mins) )
						{
AddComment("POSSIBLE DELAY Flight Number:$flight_number  Airline:$airline_code  Date:$flight_date  Found via the API");
							return false;
						}
AddComment("Flight Number:$flight_number  Airline:$airline_code  Date:$flight_date  Found via the API");

					}
				}
			}
		}

		return $flight_data;

	}






	# computes the claim amount based on the supplied data
	private function computeClaim()
	{
	}






	# gets distance
	public function getDistance($lat1, $lon1, $lat2, $lon2, $unit="K")
	{

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}










}













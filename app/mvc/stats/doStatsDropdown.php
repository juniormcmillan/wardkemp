<?php
/**
 * Created by PhpStorm.
 * User: Jack Heeney
 * Date: 14/03/18
 * Time: 00:49
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                 *
 *          doStatsDropdown - ajax php             *
 *                                                 *
 *  This file is called doStatsDropdown.php,       *
 *  and is linked to the controller_stat index.    *
 *                                                 *
 *  It is used directly with the JQuery / Ajax on  *
 *  on the index.html page.  It initialises the    *
 *  connection to the database ad then will grab   *
 *  the string requested from the $_POST.          *
 *                                                 *
 *  "Referrer"                                     *
 *                                                 *
 *  Next we write a couple of SQL Queries that,    *
 *  count the amount of claims there are are       *
 *  certain claim stages. We also use a            *
 *  selectToArray to grab the referrers and put    *
 *  them into an array.                            *
 *                                                 *
 *                                                 *
 *                                                 *
 *                                                 *
 *                                                 *
 *                                                 *
 *  After we have grabbed this information create  *
 *  a result string variable and put the results   *
 *  of the queries into this (structured).         *
 *                                                 *
 *  Remember to return to data to the javascript   *
 *  ajax.                                          *
 *                                                 *
 *                                                 *
 * * * * * * * * * * * * * * * * * * * * * * * * * */


# include these files from codelibrary
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/common.php");
require_once("../../config.inc.php");

# mysql - opens a new connection to the database
$gMysql				=	new Mysql_Library();

# this is the return array with all values
$returnArray		=	array();

# these variables use the GetVariableString function which grabs the string from the requested variable in the the $_POST
$fromDate			    = GetVariableString('fromDate',$_POST,"");
$toDate			        = GetVariableString('toDate',$_POST,"");



$fromDate		= 	str_replace("/","-",$fromDate);
#$fromDate		=	date( 'd/m/Y', strtotime($fromDate) );
$fromDate		=	date( 'Y-m-d', strtotime($fromDate) );

$toDate		    = 	str_replace("/","-",$toDate);
#$toDate		    =	date( 'd/m/Y', strtotime($toDate) );
$toDate		    =	date( 'Y-m-d', strtotime($toDate) );

# $data                     =   $gMysql access object from queryRow
# (SELECT all FROM ppi_user WHERE only the following information from $referrer)
# SELECT count(*) from ppi_user WHERE ppi_user.case_key in (select distinct(ppi_user_page.case_key) from ppi_user_page)  and (ppi_user.added>='01/04/2020' AND ppi_user.added<='28/04/2020')
$number_signed 	            =	$gMysql->queryItem("SELECT count(*) from ppi_user WHERE (clientcare_signed='yes' ) and (DATE(clientcare_signed_date)>='$fromDate' AND DATE(clientcare_signed_date)<='$toDate') ",__FILE__,__LINE__);
$number_signed_data	        =	$gMysql->selectToArray("SELECT case_key from ppi_user WHERE (clientcare_signed='yes' ) and (DATE(clientcare_signed_date)>='$fromDate' AND DATE(clientcare_signed_date)<='$toDate') ",__FILE__,__LINE__);
$number_signed_list			=	doList($number_signed_data);

$number_signed_dsar	        =	$gMysql->queryItem("SELECT count(*) from ppi_user WHERE (dsar_signed='yes' ) and (	DATE(dsar_signed_date)>='$fromDate' AND 	DATE(dsar_signed_date)<='$toDate') ",__FILE__,__LINE__);
$number_signed_dsar_data	=	$gMysql->selectToArray("SELECT case_key from ppi_user WHERE (dsar_signed='yes' ) and (	DATE(dsar_signed_date)>='$fromDate' AND 	DATE(dsar_signed_date)<='$toDate') ",__FILE__,__LINE__);
$number_signed_dsar_list	=	doList($number_signed_dsar_data);

$number_opened 	            =	$gMysql->queryItem("SELECT count(*) from ppi_user WHERE (visited !='0000-00-00 00:00:00' or clientcare_document_id !='' or dsar_document_id !='') and (ppi_user.added>='$fromDate' AND ppi_user.added<='$toDate') ",__FILE__,__LINE__);
$number_opened_data         =	$gMysql->selectToArray("SELECT case_key from ppi_user WHERE (visited !='0000-00-00 00:00:00' or clientcare_document_id !='' or dsar_document_id !='') and (ppi_user.added>='$fromDate' AND ppi_user.added<='$toDate') ",__FILE__,__LINE__);
$number_opened_list			=	doList($number_opened_data);

$number_looked 	            =	$gMysql->queryItem("SELECT count(*) from ppi_user WHERE (clientcare_document_id !='' and clientcare_signed='') and (ppi_user.added>='$fromDate' AND ppi_user.added<='$toDate') ",__FILE__,__LINE__);
$number_looked_data         =	$gMysql->selectToArray("SELECT case_key from ppi_user WHERE (clientcare_document_id !='' and clientcare_signed='') and (ppi_user.added>='$fromDate' AND ppi_user.added<='$toDate') ",__FILE__,__LINE__);
$number_looked_list			=	doList($number_looked_data);

$number_not_opened          =	$gMysql->queryItem("SELECT count(*) from ppi_user WHERE (clientcare_document_id='' ) and (added>='$fromDate' AND added<='$toDate')",__FILE__,__LINE__);
$number_not_opened_data     =	$gMysql->selectToArray("SELECT case_key from ppi_user WHERE (clientcare_document_id='' ) and (added>='$fromDate' AND added<='$toDate')",__FILE__,__LINE__);
$number_not_opened_list		=	doList($number_not_opened_data);


$number_waiting	            =	$gMysql->queryItem("SELECT count(*) from ppi_user WHERE case_key='' and (added>='$fromDate' AND added<='$toDate') ",__FILE__,__LINE__);

$number_total 	            =	$gMysql->queryItem("SELECT count(*) from ppi_user WHERE (added>='$fromDate' AND added<='$toDate')",__FILE__,__LINE__);

# $data_array                     =   $gMysql access object with selectToArray
# (("SELECT * from ppi_user WHERE referrer='$referrer'") - Puts the data into an array
$data_array 	                =	$gMysql->selectToArray("SELECT * from ppi_user ",__FILE__,__LINE__);




$date_range = 	"There are " . $number_total . " claims found between: " . $fromDate . " and " . $toDate . "<br/><br/>";
$date_range.=	"$number_opened opened the email <br/>";
$date_range.=	"$number_looked looked at the clientcare document, but did not sign  [ ".  $number_looked_list  ." ]<br/>";
$date_range.=	"$number_signed signed the client care document between these dates [ ".  $number_signed_list  ." ]<br>";
$date_range.=	"$number_signed_dsar signed the DSAR document between these dates  [ ".  $number_signed_dsar_list  ." ]<br>";
$date_range.=	"$number_waiting are waiting to be assigned a case_key (website claims)<br>";
$date_range.=	"$number_not_opened claims added have not viewed the client care documents [ ".  $number_not_opened_list  ." ]<br><br>";



	# append the above variables into a new variable called string
$string = ""; #We found: " . "<br/>" .  "$number_opened that opened the email," . "<br/>" .  " $number_signed that signed the client care document";


# returns the data in this array to the javascript ajax
$returnArray    =   array	(

    "string" => $string,
    "date_range" => $date_range,
/*    "date_results" => $date_results*/

);


# return array is encoded so that jquery can understand it
echo json_encode($returnArray);


function doList($data_list)
{
	$total	=	count($data_list);
	$num	=	0;
	$string	=	"<b>";

	foreach ($data_list as $data)
	{
		$string	.=	$data['case_key'];

		if (++$num < $total)
		{
			$string .= " , ";
		}
	}
	$string	.=	"</b>";
	return $string;
}
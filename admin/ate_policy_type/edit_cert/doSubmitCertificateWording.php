<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *               doSubmitCertificationWording              *
 *                                                         *
 * This php file opens the connection to the database so   *
 * that it can check the variables are equal to            *
 * what is in the database or not.  If the information is  *
 * not there it will insert this information into the      *
 * database if it exists already then it will update the   *
 * information stored in the database.                     *
 *                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

# include these files from codelibrary
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config.inc.php");


# mysql - opens a new connection to the database
$gMysql				=	new Mysql_Library();

# default returnCode is equal to error
$returnCode			=	"error";

# this is the return array with all values
$returnArray		=	array();

# initialize this variable, as it may be added to later.
$message			=	"";

# these variables use the GetVariableString function which grabs the string from the requested variable in the the $_POST
$proclaim_code			= GetVariableString('proclaim_code',$_POST,"");
$id			            = GetVariableString('$id',$_POST,"");
$class_cover			= GetVariableString('class_cover',$_POST,"");
$wording_bottom			= GetVariableString('wording_bottom',$_POST,"");
$extra_wording			= GetVariableString('extra_wording',$_POST,"");
$cert_wording_number	= GetVariableString('cert_wording_number',$_POST,"");



#if statement if variable is empty $message variable says "this variable" is missing
if	(empty($proclaim_code))
{
    # append string & <br> to variable $message
    $message			.=	"Proclaim Code Missing." . "<br>";
}
if	(empty($id))
{
    $message			.=	"Policy Type Missing." . "<br>";
}
if	(empty($class_cover))
{
    $message			.=	"Class Cover Missing." . "<br>";
}
if	(empty($wording_bottom))
{
    $message			.=	"Wording Bottom Missing." . "<br>";
}
if	(empty($extra_wording))
{
    $message			.=	"Extra Wording Missing." . "<br>";
}
if	(empty($cert_wording_number))
{
    $message			.=	"Certificate Wording Number Missing" . "<br>";
}


# we can safely assume that if the error message is empty, that we can now submit the form
if (empty($message))
{
    # attempts to find the recorded data stored in $id and stores this in the variable called $data
    $data	=	$gMysql->queryRow("SELECT * from policy_document_details WHERE id='$id'",__FILE__,__LINE__);


    # INSERTS THE VALUES INTO THE DATABASE IF THEY DO NOT EXIST
    # if the variable $data is empty then insert the data from the form fields into the database sytem
    if	(empty($data))
    {
        # variable gMysql - > inserts data into SQL INSERT INTO table policy_document_details inserts the values entered for the variables $id etc
        $gMysql->insert("INSERT INTO policy_document_details 

        (id,proclaim_code,class_cover,wording_bottom,extra_wording,cert_wording_number,last_updated) VALUES  
        
        $id','$proclaim_code','$class_cover','$wording_bottom','$extra_wording','$cert_wording_number',NOW())",__FILE__,__LINE__);

        $message	=	"Policy Wording Saved to new record in Database";

    }

    # UPDATES THE VALUES IN THE DATABASE IF THEY EXIST ALREADY
    # else if the field exists already the update this the database.
    else
    {
        $gMysql->update("UPDATE policy_document_details set 
	    
		proclaim_code='$proclaim_code',class_cover='$class_cover',wording_bottom='$wording_bottom',extra_wording='$extra_wording',cert_wording_number='$cert_wording_number',last_updated=NOW() 
        
		WHERE id='$id'",__FILE__,__LINE__);


        $message	=	"Updated Policy Wording in Database";
    }

    # make sure that we return to the ajax that we are successful
    $returnCode	=	"success";


}
# this means there is an error message, so we need to adjust this variable
else
{
    $returnCode			=	"error";
}


# returns the data in this array to the javascript ajax
$returnArray    =   array	("returncode" => $returnCode,  "message" => $message,);
# return array is encoded so that jquery can understand it
echo json_encode($returnArray);

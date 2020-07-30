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
$id                             = GetVariableString('id',$_POST, "");

#if statement if variable is empty $message variable says "this variable" is missing
if	(empty($id))
{
    # append string & <br> to variable $message
    $message			.=	"ID Missing." . "<br>";
}
if	(empty($type))
{
    $message			.=	"Policy Type Missing." . "<br>";
}
if	(empty($title))
{
    $message			.=	"Policy Title Missing." . "<br>";
}
if	(empty($text))
{
    $message			.=	"Policy Text Missing." . "<br>";
}


# we can safely assume that if the error message is empty, that we can now submit the form
if (empty($message))
{
    # attempts to find the recorded data stored in$ id and stores this in the variable called $data
    $data	=	$gMysql->queryRow("SELECT * from master_code WHERE id='$id'",__FILE__,__LINE__);


    # INSERTS THE VALUES INTO THE DATABASE IF THEY DO NOT EXIST
    # if the variable $data is empty then insert the data from the form fields into the database system
    if	(empty($data))
    {
        # variable gMysql - > inserts data into SQL INSERT INTO table master_code inserts the values entered for the variables id etc
        $gMysql->insert("INSERT INTO master_code 

        (id,type,title,text,last_updated) VALUES  
        
        ('$id','$type','$title',$text',NOW())",__FILE__,__LINE__);

        $message	=	"Policy Wording Saved to new record in Database";

    }

    # UPDATES THE VALUES IN THE DATABASE IF THEY EXIST ALREADY
    # else if the field exists already the update this the database.
    else
    {
        $gMysql->update("UPDATE master_code set 
	    
		type='$type', title='$title',text='$text',last_updated=NOW() 
        
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

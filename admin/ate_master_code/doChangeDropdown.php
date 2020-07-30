<?php
/* * * * * * * * * * * * * * * * * * * * *
 *          doChangeDropDown             *
 *                                       *
 *    This file checks that the user     *
 * has made a selection from a drop down *
 *  menu and that it should replace the  *
 *     other fields to match the new     *
 *               selection               *
 *                                       *
 * * * * * * * * * * * * * * * * * * * * */

# include these files from codelibrary
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config.inc.php");

# mysql
$gMysql				=	new Mysql_Library();

# default return code
$returnCode			=	"error";
# this is the return array with all values
$returnArray		=	array();

# initialize this variable, as it may be added to later.
$message			=	"";

# get the variables from _POST
$id     = GetVariableString('id',$_POST);
$type   = GetVariableString('type',$_POST);
$title  = GetVariableString('title',$_POST);
$text   = GetVariableString('text',$_POST);


# gMysql attempting to SELECT the entire table called master_code from row policy_type, the info from id is stored in $data
# __FILE__, __LINE__ for debugging purpose shows the filename and line)
$data	=	$gMysql->queryRow("SELECT * from master_code WHERE id='$id'",__FILE__,__LINE__);

# this if else statement is checking if data is empty
# message variable says "Policy ($name of that policy) Not Found in Databse
# returnCode variable says "error"
if	(empty($data))
{
    $message		=	"Policy ($id) Not Found in Database";
    $returnCode		=	"error";
}

# else the master_code variable is = to the master_code information stored in data
# so on and so on for the rest of the variables.
else
{

    $type			    =	$data['type'];
    $title	            =	$data['title'];
    $text			    =	$data['text'];

    # make sure that we return to the ajax that we are successful
    $returnCode	=	"success";
}



# returns the data in this array to the javascript ajax
$returnArray    =   array	(

    "returncode" 		=> $returnCode,
    "message" 			=> $message,

    "type" 		        => $type,
    "title"             => $title,
    "text" 	            => $text,

);

# return array is encoded so that jquery can understand it
echo json_encode($returnArray);


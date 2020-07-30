<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 13/06/2018
 * Time: 16:27
 */

if(!isset($pageTitle))
{
    $pageTitle = "Master Code";
}
if(!isset($pageSection))
{
    $pageSection = "2";
}
//phpinfo();
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
# include these files from codelibrary
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config.inc.php");


# this includes the file check_customer_login.php
include "../includes/php/getPolicyCertificateData.php";
include "../includes/php/getAdditionalCertificationWording.php";


include "../includes/php/template.php";

# mysql library
$gMysql				=	new Mysql_Library();

# $master_code is equal to MASTERC
$type  =   "A";

# the_data the function getPolicyCertificateData(from $master_code)
$additional_data    = getAdditionalCertificateWording($type);


# load your login template and call it $template
$template = getTemplate("edit_certificate_wording.html");
# load your header and footer templates also
/*$header = getTemplate("../app/templates/header.html");
$footer = getTemplate("../app/templates/footer.html");*/

# get the variables from _POST
$id                             = GetVariableString('id',$_POST, "");
$type                           = GetVariableString('type',$_POST, "");
$title      			        = GetVariableString('title',$_POST,"");
$text       			        = GetVariableString('text',$_POST,"");

# $data is equal to $gMysql access object from queryRow
# (SELECT all FROM master_code WHERE only the following information from $id)
$data	=	$gMysql->queryRow("SELECT * from master_code WHERE id='$id'",__FILE__,__LINE__);

# if  (the data variable is not empty) then take data from database and put it into local variables
if	(!empty($data))
{
    $id                         =	$data['id'];
    $type                       =	$data['type'];
    $title                      =	$data['title'];
    $text                       =	$data['text'];
}
# nothing in database for this proclaim_code
else
{
    # either initialise all variables to nothing eg ""
    $id                         =	"";
    $type                       =	"";
    $title                      =	"";
    $text                       =	"";

}


# variable select_string is equal to '<select class="form-control" id="policy_type" name="policy_type" onchange="ddChange();" >';
$select_string  =   '<select class="formInputData" id="id" name="id" onchange="ddChange(this);" >';

$options_names  =   array(
    '-- Select --',                                     #
    'A',                                                #01
    'B',                                                #02
    'C',                                                #03
    'D',                                                #04
    'E',                                                #05
    'F',                                                #06
    'G',                                                #07
    'H',                                                #08
    'I',                                                #09
    'J',                                                #10
    'K',                                                #11
    'L',                                                #12
    'M',                                                #13
);

# variable options_values is equal to an array list with the drop down men  us values held inside.
$options_values  =   array(
    '',                                                 # -- Select --
    '1',                                                # A
    '2',                                                # B
    '3',                                                # C
    '4',                                                # D
    '5',                                                # E
    '6',                                                # F
    '7',                                                # G
    '8',                                                # H
    '9',                                                # I
    '10',                                               # J
    '11',                                               # K
    '12',                                               # L
    '13',                                               # M
);


# variable num is equal to count($options_name);
# count = counts all the elements in an array
$num    =   count($options_names);

# for (i = 0; if i is less than the variable num (above) then stop the loop; add 1 to i;)
for ($i=0;  $i < $num;  $i++)
{
    # variable name is equal to variable options_names[i]
    $name = $options_names[$i];
    # variable value is equal to variable options_values[i]
    $value = $options_values[$i];

    # if (policy type is equal to value)
    # insert the selected command into the $select_string
    if ($id == $value) {
        $select_string .= "<option value='".$value."' selected=''>$name</option>";
    }
    # else don't put the selected command into the $select_string
    else
    {
        $select_string .= "<option value='".$value."'>$name</option>";
    }

}

# append </select> to variable $select_string
# (closes the select input)
$select_string .= "</select>";


# replaces TAG with the file (string)
# str_replace(" find this word/phrase " , replace it with variable $header, in $template)
/*$template 	=	str_replace("{{header}}",$header,$template);
$template 	=	str_replace("{{footer}}",$footer,$template);*/
$template 	=	str_replace("{{select_string}}",$select_string,$template);
$template 	=	str_replace("{{type}}",$type,$template);
$template 	=	str_replace("{{title}}",$title,$template);
$template 	=	str_replace("{{text}}",$text,$template);
$template 	=	str_replace("{{additional_data}}",$additional_data,$template);
$template   =   str_replace("{{page_title}}", $pageTitle, $template);

# display the final template
echo $template;
exit;

?>







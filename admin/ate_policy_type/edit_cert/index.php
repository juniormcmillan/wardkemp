<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 13/06/2018
 * Time: 16:27
 */

if(!isset($pageTitle))
{
    $pageTitle = "Edit Certificate Wording";
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
include "../../includes/php/getPolicyCertificateData.php";


include "../../includes/php/template.php";

# mysql library
$gMysql				=	new Mysql_Library();

# proclaim_code is equal to F
$proclaim_code  =   "F";

# the_data the function getPolicyCertificateData(from $proclaim_code)
$the_data = getPolicyCertificateData($proclaim_code);



# load your login template and call it $template
$template = getTemplate("edit_certificate_wording.html");
# load your header and footer templates also
/*$header = getTemplate("../app/templates/header.html");
$footer = getTemplate("../app/templates/footer.html");*/

# get the variables from _POST
$proclaim_code			= GetVariableString('proclaim_code',$_POST,"");
$id                     = GetVariableString('id',$_POST, "");
$class_cover			= GetVariableString('class_cover',$_POST,"");
$wording_bottom			= GetVariableString('wording_bottom',$_POST,"");
$extra_wording			= GetVariableString('extra_wording',$_POST,"");
$cert_wording_number	= GetVariableString('cert_wording_number',$_POST,"");


# $data is equal to $gMysql access object from queryRow
# (SELECT all FROM policy_document_details WHERE only the following information from $proclaim_code)
$data	=	$gMysql->queryRow("SELECT * from policy_document_details WHERE id='$id'",__FILE__,__LINE__);

# if  (the data variable is not empty) then take data from database and put it into local variables
if	(!empty($data))
{
    $proclaim_code               =	$data['proclaim_code'];
    $class_cover                 =	$data['class_cover'];
    $wording_bottom              =	$data['wording_bottom'];
    $extra_wording               =	$data['extra_wording'];
    $cert_wording_number         =	$data['cert_wording_number'];
}
# nothing in database for this proclaim_code
else
{
    # either initialise all variables to nothing eg ""
    $proclaim_code                =	"";
    $class_cover                 =	"";
    $wording_bottom              =	"";
    $extra_wording               =	"";
    $cert_wording_number         =	"";

}


# variable select_string is equal to '<select class="form-control" id="id" name="id" onchange="ddChange();" >';
$select_string  =   '<select class="formInputData" id="id" name="id" onchange="ddChange(this);" >';




# grab all policies in order
$data_array	=	$gMysql->selectToArray("SELECT * from policy_document_details order by dropdown_option desc",__FILE__,__LINE__);

foreach ($data_array as $data)
{
	# variable name is equal to variable options_names[i]
	$name = $data['dropdown_option'];
	# variable value is equal to variable options_values[i]
	$value = $data['id'];

	# if (policy type is equal to value)
	# insert the selected command into the $select_string
	if ($policy_type == $value)
	{
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
$template 	=	str_replace("{{proclaim_code}}",$proclaim_code,$template);
$template 	=	str_replace("{{class_cover}}",$class_cover,$template);
$template 	=	str_replace("{{wording_bottom}}",$wording_bottom,$template);
$template 	=	str_replace("{{extra_wording}}",$extra_wording,$template);
$template 	=	str_replace("{{cert_wording_number}}",$cert_wording_number,$template);

$template    =   str_replace("{{page_title}}", $pageTitle, $template);

# display the final template
echo $template;
exit;

?>







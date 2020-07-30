<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 10/05/12
 * Time: 10:19
 * To change this template use File | Settings | File Templates.
 */

require($_SERVER["DOCUMENT_ROOT"]."/lib/lib_mysql.php");
require($_SERVER["DOCUMENT_ROOT"]."/lib/common.php");


# create new class
$mysql	=	new	MySQL_class;
# create connection to dbase
$mysql->Create(null);


include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

/*I
$dbRsList = mysql_query("SELECT id, title, status FROM page WHERE status = 'A' ORDER BY title");

# needs

# section_id \ these are to be added
# prod_slots /

$dbRsList = mysql_query("SELECT id, title, status FROM page WHERE status = 'A' ORDER BY title");


$newPageName	=	" * " . date("Y-m-d H:i");

$sql	=	"INSERT INTO page (id,title,cms,seo,banner_slots,title_image,status) values(0,'$newPagename','y','y','3','-1','A');";

$mysql->Insert("INSERT INTO page (id,title,cms,seo,banner_slots,title_image,status) values(0,'$newPageName','y','y','3','-1','A');",__FILE__,__LINE__);

*/
$strSQL = "INSERT into user_access (id, email, password, forename, surname, company_name, address, postcode, telephone, access_code,date_created) values ('$rand_id','$ateEmail', '$atePassword', '$ateForename', '$ateSurname', '$ateCompany', '$ateAddress', '$atePostcode', '$atePhone', Now())";
//exit($strSQL);
$insert = mysql_query($strSQL) or die ("Error: Creating User");



# we need to add a page to the database
echo "adding user to database $sql";

exit;

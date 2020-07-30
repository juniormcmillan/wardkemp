<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 30/08/2015
 * Time: 13:42
 */


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$gMysql			=	new Mysql_Library();


#include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");


$newPageName	=	"NEW " . date("l, jS F");
$gMysql->Insert("INSERT INTO fp_voucher_code (id,code,type,added) values(0,'$newPageName','F',NOW());",__FILE__,__LINE__);

$id = mysql_insert_id();

header("location: /admin/content/vouchers/detail.php?voucherID=$id");

# we need to add a page to the database
echo "adding $newPageName page to database $sql";

exit;

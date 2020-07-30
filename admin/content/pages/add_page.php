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
$gMysql->Insert("INSERT INTO sm_page (id,title,status,updated,mvc_controller,mvc_method,mvc_action,mvc_template,mvc_header,mvc_footer,ref) values(0,'$newPageName','A',NOW(),'page','get','render','index.html','header.html','footer.html','new');",__FILE__,__LINE__);

$id = mysql_insert_id();


# we need to add a page to the database
echo "adding $newPageName page to database $sql";


#header( "Refresh:25; url=/admin/content/pages/detail.php?pageId=$id", true, 303);

exit;

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
$gMysql->Insert("INSERT INTO sm_post (id,title,status,published,updated,ref,mvc_header,mvc_footer,mvc_controller,mvc_action,mvc_template,mvc_banner_1) values(0,'$newPageName','A',NOW(),NOW(),'blogitem','header_blog.html', 'footer_homepage.html', 'blogitem', 'render', 'index.html','desk-2.jpg');",__FILE__,__LINE__);


$id = mysql_insert_id();

header("location: /admin/ate_news/detail.php?pageId=$id");

# we need to add a page to the database
echo "adding $newPageName page to database $sql";

exit;

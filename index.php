<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 27/02/14
 * Time: 17:10
 */

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
#require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/BrowserDetection.php");

require_once("app/config.inc.php");
require_once("app/text.inc.php");











/*


Each company has a solicitor_id which is attached to the policy (as well as the policy_type)  on creation.
	We will associate the company_id with a table_id that gives the type of text to display.
	This can be shared between companies, so they display the same text - which may be common.
	It also allows for two companies with the same policy_type to have different text etc.

*/





















# session
$gSession		=	new Session_Library(

	array(

		"session_id"			=>	SESSION_ID,
		"table"					=>	SESSION_TABLE,
		"logged_in_url"			=>	SESSION_LOGGED_IN_URL,
		"logged_out_url"		=>	SESSION_LOGGED_OUT_URL,
		"login_url"				=>	SESSION_LOGIN_URL,
		"registered_url"		=>	SESSION_REGISTERED_URL
	)

);

# profiler
$gProfiler		= 	new Profiler_Library();

$gProfiler->logMemory();
# mysql
$gMysql			=	new Mysql_Library();


# sets the mysql pointer
$gProfiler->setup($gMysql);

# cache
$gCache			=	new Cache_Library();



$url			=	$_REQUEST['uri'];







# create router
$router = new Router_Library();

# create a routes map based on the sm_page database
$router->createRoutesMap("sm_page");

# add a route array of hand-crafted routes
$router->addRoutes($route_map);


# creates pages based on routerable
$router->dispatch($url);



exit;


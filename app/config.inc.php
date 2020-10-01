<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 14/10/13
 * Time: 17:20
 * To change this template use File | Settings | File Templates.
 */
$gMysql	=	NULL;
$gCache	=	NULL;

/*** error reporting on ***/
error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);


ini_set('display_errors', '0');
ini_set('html_errors', true);

/*** define the site path constant ***/
$site_path = realpath(dirname(__FILE__));
define ('__SITE_PATH', $site_path);
define ('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);
define ('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/app/");

# domain name
$domain_path = $_SERVER['HTTP_HOST'];


$version				=		0;

# allow passing variable names in URI
define	('DEBUG_URI_VARIABLES',	true);

# defines if we echo add comment data
define	('DEBUG_ADD_COMMENT',	true);
# my pqp debug which also logs mysql queries and EXPLAINS them
define	('DEBUG_PROFILER',		false);

# minifies the loaded templates HTML
define	('DEBUG_MINIFY',		false);
# set this to turn on caching
define	('DEBUG_CACHE_ON',		false);

# debug will log all queries to file and EXPLAIN them
define	('DEBUG_MYSQL',			false);





# new caching modes
define	('CACHE_ENGINE_MEMCACHE',			0);
define	('CACHE_ENGINE_APC',				1);
define	('CACHE_ENGINE_FILE',				3);

# default engine (at present, APC is causing issues - ** 11/03/2014 ** since front controller method used)
define	('CACHE_ENGINE',				CACHE_ENGINE_MEMCACHE);
# set this to turn on logging
define	('DEBUG_CACHE_LOG',				false);
# set if we want to debug mysql queries and store in MYSQL_LOG_FILE
define	('CACHE_CACHE_TIME_TINY',		30);
define	('CACHE_CACHE_TIME_NORMAL',		5*60);
define	('CACHE_CACHE_TIME_LONG',		60*60);
define	('CACHE_CACHE_TIME_HUGE',		24*60*60);
define	('CACHE_CACHE_TIME_INFINITE',	7*24*60*60);
define	('CACHE_CACHE_TIME_BEYOND',		60*24*60*60);



// connect and login to FTP server
define	('FTP_SERVER',					'wardkemp.co.uk');
define	('FTP_USERNAME',				'wardkemp');
define	('FTP_PASSWORD',				'catch1922');


# cache mysql queries
define	('MYSQL_CACHE',					false);
define	('MYSQL_CACHE_ENGINE',			CACHE_ENGINE_MEMCACHE);

define	('MYSQL_LOG_FILE',				$site_path . '/logfile_mysql.txt');
define	('MYSQL_HOST',					'localhost');	#	was 109.203.113.96
define	('MYSQL_USER',					'7apO3nBbB2a');
define	('MYSQL_PASS',					'PIKkip91sD312');
define	('MYSQL_DBASE',					'wardkemp');


# set if we want to debug mysql queries and store in MYSQL_LOG_FILE
define	('MYSQL_CACHE_TIME_TINY',		30);
define	('MYSQL_CACHE_TIME_NORMAL',		5*60);
define	('MYSQL_CACHE_TIME_LONG',		60*60);
define	('MYSQL_CACHE_TIME_HUGE',		24*60*60);
define	('MYSQL_CACHE_TIME_INFINITE',	7*24*60*60);
define	('MYSQL_CACHE_TIME_BEYOND',		60*24*60*60);



#define('IMAGES_LOCATION',			'http://boxlegal-new.5xzx00ckc5kosnsn0.maxcdn-edge.com');
define('IMAGES_LOCATION',			'');
define('LEGACY_IMAGES_LOCATION',	'https://test.fairplane.co.uk/legacy');


$gLogfile	=	"logfile.log";




# can we login (button etc)
define	('LOGIN_AVAILABLE',				true);








# session details
define	('SESSION_ID',					"wardkemp");
define	('SESSION_TABLE',				"table");
define	('SESSION_LOGGED_IN_URL',		"/dashboard");
define	('SESSION_LOGGED_OUT_URL',		"/");
define	('SESSION_LOGIN_URL',			"/login");
define	('SESSION_REGISTERED_URL',		"/?pUpdate=registered");
define	('SESSION_TIMEOUT',				60*60*6);




# DEPRECIATED
define ('JQUERY_JS',			'');
define ('JQUERY_UI_JS',			'');
define ('JQUERY_UI_CSS',		'');

define('WEBSITE_CANONICAL',				"https://www.wardkemp.co.uk/");





define('REBRANDLY_API',				"6854de4e7b4e4d53aedbe025ec728209");




# routes used by the router
$route_map             = array
(

	# if a pattern is passed, use another setup
	array(
		# url
		'pattern' 		=>	'',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> '',
		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'homepage',
		'action'		=>	'render',
		'page_title' 		=> 	'Ward Kemp',
		'page_subtitle' 	=> 	'',
		'page_meta_title' 	=> 	'Ward Kemp Limited',
		'page_meta_breadcrumb'	=>	'Breadcrumbs',

		// multiple versions of the page located here and these variables will be copied to the route
		'v'	=>	array (

			array(
				'version' 		=>	'0',
				# passed params - these are hardcoded for the route
				'passed_params'	=>	array(
					'header'	=>	'header.html',
					'footer'	=>	'footer_homepage.html',
					'template' 	=> '',
				),
			),
			array(
				'version' 		=>	'1',
				'passed_params'	=>	array(
					'header'	=>	'header.html',
					'footer'	=>	'footer_homepage.html',
					'template' 	=> 'index2.html',
				),
			),
			array(
				'version' 		=>	'2',
				'passed_params'	=>	array(
					'header'	=>	'header.html',
					'footer'	=>	'footer_homepage.html',
					'template' 	=> 'index.html',
				),
			),

		),

	),







	array(
		# url
		'pattern' 		=>	'',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'			=>	'header.html',
			'footer'			=>	'footer_homepage.html',
			'template'		 	=> 	'index.html',
			# force this naming to grab data
			'landing_color'		=>	'',
			'page_title' 			=> 	'',
			'page_subtitle' 		=> 	'',
			'page_meta_title' 		=> 	'',
			'page_meta_breadcrumb'	=>	'',
		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'post',
		'controller'	=>	'homepage',
		'action'		=>	'doRegister',
	),






	array(
		# url
		'pattern' 		=>	'dsar',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-short.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_desc'				=> 'DSAR',
			'meta_kw'				=> 'DSAR',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'dsar',
		'action'		=>	'render',

	),



	array(
		# url
		'pattern' 		=>	'id-upload',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-short.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_desc'				=> 'Upload Your ID',
			'meta_kw'				=> 'Upload Your ID',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'idupload',
		'action'		=>	'render',

	),






	array(
		# url
		'pattern' 		=>	'damage-upload',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index-damage.html',
			'page_title'			=> 'Upload Your Damage Photos',
			'meta_title'			=> 'Upload Your Damage Photos',
			'meta_desc'				=> 'Upload Your Damage Photos',
			'meta_kw'				=> 'Upload Your Damage Photos',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'iduploaddamage',
		'action'		=>	'render',

	),




	array(
		# url
		'pattern' 		=>	'offer-upload',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-short.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_desc'				=> 'Upload Your Offer',
			'meta_kw'				=> 'Upload Your Offer',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'offerupload',
		'action'		=>	'render',

	),






	array(
		# url
		'pattern' 		=>	'details',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-short.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_description'		=> 'Upload Your ID',
			'meta_kw'				=> 'Upload Your ID',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'details',
		'action'		=>	'render',

	),








	array(
		# url
		'pattern' 		=>	'hd2',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-hd.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index-hd.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_description'		=> 'Housing Disrepair',
			'meta_kw'				=> 'Housing Disrepair',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'contact',
		'action'		=>	'render',

	),









	array(
		# url
		'pattern' 		=>	'affiliate',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-affiliate.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'affiliate.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_description'		=> 'Housing Disrepair',
			'meta_kw'				=> 'Housing Disrepair',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'page',
		'action'		=>	'render',

	),






	array(
		# url
		'pattern' 		=>	'about_us',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'			=>	'header.html',
			'footer'			=>	'footer_homepage.html',
			'template'		 	=> 	'index.html',
			# force this naming to grab data
			'landing_color'		=>	'',
			'page_title' 			=> 	'About',
			'page_subtitle' 		=> 	'',
			'page_meta_title' 		=> 	'About Us',
			'page_meta_breadcrumb'	=>	'About Us',
		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'page',
		'action'		=>	'render',
	),







	array(
		# url
		'pattern' 		=>	'stats',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'			=>	'header-short.html',
			'footer'			=>	'footer_homepage.html',
			'template'		 	=> 	'index.html',
			# force this naming to grab data
			'landing_color'		=>	'',
			'page_title' 			=> 	'About',
			'page_subtitle' 		=> 	'',
			'page_meta_title' 		=> 	'About Us',
			'page_meta_breadcrumb'	=>	'About Us',
		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'stats',
		'action'		=>	'render',
	),




	array(
		# url
		'pattern' 		=>	'documents',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'introduction.html',
			'page_title'			=> 'Client Care Introduction',
			'meta_title'			=> 'Client Care Introduction',
			'meta_desc'				=> 'Client Care Introduction',
			'meta_kw'				=> 'Client Care Introduction',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'render',

	),





	array(
		# url
		'pattern' 		=>	'authority',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-short.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_desc'				=> 'Client Statement',
			'meta_kw'				=> 'Client Statement',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'authority',

	),





	array(
		# url
		'pattern' 		=>	'clientcare',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'introduction.html',
			'page_title'			=> 'Client Care Introduction',
			'meta_title'			=> 'Client Care Introduction',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'nothing',

	),



	array(
		# url
		'pattern' 		=>	'clientcare/introduction',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'introduction.html',
			'page_title'			=> 'Client Care Introduction',
			'meta_title'			=> 'Client Care Introduction',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'render',

	),


	array(
		# url
		'pattern' 		=>	'clientcare/letter',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index_clientcare.html',
			'page_title'			=> 'Client Care Letter',
			'meta_title'			=> 'Client Care Letter',
			'meta_desc'				=> 'Client Care Letter',
			'meta_kw'				=> 'Client Care Letter',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'render',

	),






	array(
		# url
		'pattern' 		=>	'clientcare/terms',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'terms.html',
			'page_title'			=> 'Terms & Conditions',
			'meta_title'			=> 'Terms & Conditions',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'render',

	),




	array(
		# url
		'pattern' 		=>	'clientcare/cfa',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'CFA Agreement',
			'meta_title'			=> 'CFA Agreement',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'cfa',

	),



	array(
		# url
		'pattern' 		=>	'clientcare/instructions',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Instructions To Act',
			'meta_title'			=> 'Instructions To Act',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'instructionsToAct',

	),




	array(
		# url
		'pattern' 		=>	'clientcare/landlord',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Landlord',
			'meta_title'			=> 'Landlord',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'landlord',

	),








	array(
		# url
		'pattern' 		=>	'clientcare/ate-insurance',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index_ate-insurance.html',
			'page_title'			=> 'ATE Insurance',
			'meta_title'			=> 'ATE Insurance',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'render',

	),


	array(
		# url
		'pattern' 		=>	'clientcare/ipid',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index_ipid.html',
			'page_title'			=> 'Insurance Product Information Document (IPID)',
			'meta_title'			=> 'Insurance Product Information Document (IPID)',
			'meta_desc'				=> 'Insurance',
			'meta_kw'				=> 'IPID',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'render',

	),




	array(
		# url
		'pattern' 		=>	'clientcare/sign',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-shorter.html',
			'footer'	=>	'footer_basic.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_desc'				=> 'Client Care',
			'meta_kw'				=> 'Client Care',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'clientcare',
		'action'		=>	'authority',

	),




	array(
		# url
		'pattern' 		=>	'case-accept',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-short.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_desc'				=> 'Case Acceptance',
			'meta_kw'				=> 'Case Acceptance',
			'content'				=> '',
			'accept'				=>	'accept',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'caseaccept',
		'action'		=>	'doCaseAccept',

	),




array(
	# url
	'pattern' 		=>	'case-reject',
	# passed params - these are hardcoded for the route
	'passed_params'	=>	array(
		'header'	=>	'header-short.html',
		'footer'	=>	'footer_homepage.html',
		'template' 	=> 'index.html',
		'page_title'			=> 'Ward Kemp',
		'meta_title'			=> 'Ward Kemp',
		'meta_desc'				=> 'Case Reject',
		'meta_kw'				=> 'Case Reject',
		'content'				=> '',
		'accept'				=>	'reject',

	),
	# parameters list to extract from url
	'params_to_extract' 		=> array(''),
	# routing data
	'method'		=>	'',
	'controller'	=>	'caseaccept',
	'action'		=>	'doCaseReject',

),






	array(
		# url
		'pattern' 		=>	'report-upload',
		# passed params - these are hardcoded for the route
		'passed_params'	=>	array(
			'header'	=>	'header-short.html',
			'footer'	=>	'footer_homepage.html',
			'template' 	=> 'index.html',
			'page_title'			=> 'Ward Kemp',
			'meta_title'			=> 'Ward Kemp',
			'meta_desc'				=> 'Report Upload',
			'meta_kw'				=> 'Report Upload',
			'content'				=> '',

		),
		# parameters list to extract from url
		'params_to_extract' 		=> array(''),
		# routing data
		'method'		=>	'',
		'controller'	=>	'reportupload',
		'action'		=>	'render',

	),
























);













# popups should be defined here perhaps
# list of the above

$gPopupLookUp	=
	array(


		"noClaimRecord" 		=> array("html" =>	"
		$.alert({
								theme: 'material',
								animation: 'scale',
								type: 'red',
								typeAnimated: 'true',
		
			
				title: 'Warning',
				confirmButton: 'Ok',
				content: 'No record of this claim exists. Please check the link supplied.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
				confirmButtonClass: 'btn-mustard',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			});

				"),

		# completed DSAR popup (controller_dsar)
		"formSigned" 		=> array("html" =>	"
		$.alert({
		
				title: 'Form Signed',
				confirmButton: 'Ok',
				content: 'Thank You. We will now continue to progress your claim. We will update you in due course.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'bootstrap',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
    			});

				"),

		"detailsSent" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Thank you for providing us with these details.<br><br>We will contact you shortly.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
							

    			});

				"),


		"claimSent" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Thank you for providing us with these details.<br><br>We will contact you shortly.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
							

    			});

				"),



		"claimUpdated" 		=> array("html" =>	"
		$.alert({
								theme: 'material',
								animation: 'scale',
								type: 'green',
								typeAnimated: 'true',
								
								
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Thank you for updating us.<br><br>We will contact you shortly.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
				confirmButtonClass: 'btn-mustard',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			});

				"),



		"filesUploaded" 		=> array("html" =>	"
		$.alert({
								theme: 'material',
								animation: 'scale',
								type: 'green',
								typeAnimated: 'true',
								
								
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Thank you for providing us with these documents.<br><br>We will contact you via email once this has been reviewed.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
				confirmButtonClass: 'btn-mustard',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			});

				"),

		"proofUploaded" 		=> array("html" =>	"
		$.alert({
								theme: 'material',
								animation: 'scale',
								type: 'green',
								typeAnimated: 'true',
								
								
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Thank you for providing us with these documents.<br><br>We will contact you via email once this has been reviewed.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
				confirmButtonClass: 'btn-mustard',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			});

				"),



		"thankYou" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Thank you for registering. An account activation link has been sent to your email address.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
							

    			});

				"),



		"enquirySent" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Your enquiry has been sent.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
							

    			});

				"),







		"newFirmEnquiry" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'We will review your registration and contact you when your account has been activated',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
							

    			});

				"),








		"ateRegistered" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'We will review your registration and contact you when your account has been activated.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
							

    			});

				"),

























		"passwordSent" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Your password change link has been sent. Please use it to create a new password for your account.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
							

    			});

				"),

		"passwordChanged" 		=> array("html" =>	"
		$.alert({
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Your password has been changed. Please use it to log in to your account.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
				escapeKey: true,
				backgroundDismiss: true,

		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
	
    			});

				"),

		"loginProblem" 		=> array("html" =>	"
		$.alert({
		
			
				title: 'Error',
				confirmButton: 'Ok',
				content: 'Your login details were not recognised.<br>Please try again.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'red',
				typeAnimated: 'true',
	    			
    			
    			});

				"),

		"loggedOut" 		=> array("html" =>	"
		$.alert({
		
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'You have now been logged out.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,

		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
	
    			});

				"),

		"contactSent" 		=> array("html" =>	"
		$.alert({
		
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Your message has been received.<br>We will be in touch shortly',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'material',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
	    			
    			
    			});

				"),

		"activateOK" 		=> array("html" =>	"
		$.alert({
		
			
				title: 'Thank You',
				confirmButton: 'Ok',
				content: 'Your account has been activated.<br>You may now login',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'bootstrap',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'green',
				typeAnimated: 'true',
	    			
    			
    			});

				"),



		"claimError" 		=> array("html" =>	"
		$.alert({
		
			
				title: 'A Problem has occured',
				confirmButton: 'Ok',
				content: 'There was an error in the policy reference supplied.<br>Please select a policy to view',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'bootstrap',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'red',
				typeAnimated: 'true',
	    			
    			
    			});

				"),



		"IDNotFound" 		=> array("html" =>	"
		$.alert({
		
			
				title: 'A Problem has occured',
				confirmButton: 'Ok',
				content: 'There was an error in the reference supplied.<br>Please select check code',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'bootstrap',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'red',
				typeAnimated: 'true',
	    			
    			
    			});

				"),







		# error message for if wrong or missing ID (controller_dsar)
		"error" 		=> array("html" =>	"
		$.alert({
		
				title: 'A problem has occured',
				confirmButton: 'Ok',
				content: 'There has been a mistake. Case Key is incorrect or missing.<br><br>Please check the link and try again.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'bootstrap',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'red',
				typeAnimated: 'true',
    			});

				"),


		# error message for if wrong or missing ID (controller_dsar)
		"noPolicyCode" 		=> array("html" =>	"
		$.alert({
		
				title: 'A problem has occured',
				confirmButton: 'Ok',
				content: 'There has been a mistake. Policy Code is incorrect or missing.<br><br>Please check the link and try again.',
 				closeIcon: true,
    			closeIconClass: 'fa fa-close',
    			animationSpeed: 200,
 	 			theme: 'bootstrap',
				confirmButtonClass: 'btn-success',
				cancelButtonClass: 'btn-danger',
				confirmButton: 'Ok',
				cancelButton: 'Cancel',
    			escapeKey: true,
    			
		
				theme: 'material',
				animation: 'scale',
				type: 'red',
				typeAnimated: 'true',
    			});

				"),




	);






























































































define ('MENU_STRING',
'
<!-- desktop --->
	<div class="bl-top-area ppi-desktop " >
		<!-- info bar area--->
		<div class="container-fluid  ppi-top-first-row " >
			<div class="ppi-website-container " style="	margin:auto;">
		
			<!-- info bar area--->
				<div class="ppi-top-info-bar "  >
					<img style="" width="26" height="15" src="/app/images/wk-mail.png">&nbsp; <a class="ppi-header-contact-link" href="mailto:team@wardkemp.co.uk?{{mailto_link}}" target="_top">team@wardkemp.co.uk</a>&nbsp;&nbsp;&nbsp;&nbsp;<img style="" width="18" height="30" src="/app/images/wk-mobile.png">&nbsp; <a class="ppi-header-contact-link" href="tel:0870 766 9997">0870 766 9997</a>
	
				</div>
			</div>	
		</div>	
	

		<div class="container-fluid ppi-website-container " >
			<!-- second row -->
			<div class="ppi-top-second-row " >
		

				<div class="col-xs-12 navbar-ppi"  style="z-index:99;position:relative;">
				
					<div class="navbar yamm navbar-default " >
						<div class="navbar-header" >
							<button class="navbar-toggle " type="button" data-toggle="collapse" data-target="#navbar-collapse-1"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
							</button>

							<a  class="navbar-brand" href="/" >
								<img src="/app/images/{{logo}}" border=0 title="Ward Kemp Logo" alt="Ward Kemp Logo" width=100% >
							</a>
						</div>
						<div class="navbar-collapse collapse " id="navbar-collapse-2"  >
						
							
		
						
							<ul class="nav navbar-nav navbar-right "  style="">
							
								<li ><a class="dropdown" href="/">HOME</a>
								</li>


								<li class="dropdown"><a class="dropdown-toggle" href="/about_us" data-toggle="dropdown">ABOUT US<b class="caret"></b></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="/about_us">About Us</a></li>
										<li><a href="/privacy">Privacy</a></li>
										<li><a href="/terms-and-conditions">Terms & Conditions</a></li>
										<li><a href="/complaints">Complaints</a></li>
									</ul>
								</li>

								

								<li class="dropdown"><a class="dropdown-toggle" href="" data-toggle="dropdown">CONTACT<b class="caret"></b></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="/contact/claim">Claims</a></li>
										<li><a href="/contact/solicitors">Solicitors</a></li>
										<li><a href="/contact/surveyors">Surveyors</a></li>
									</ul>
								</li>
								
						</ul>
							
						</div>
					</div>
				</div>

			</div>
	
			
		</div>

	</div>


	<!-- mobile --->
	<div class="ppi-mobile ppi-top-area " >

		<!-- second row -->
		<div class="ppi-top-second-row col-xs-12 "  >
			<div class="col-xs-12 navbar-ppi"   >
				<div class="navbar  navbar-default "  >
					<div class="navbar-header">
						<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse-1"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
						</button>
						

					</div>
					
								
					
					
					<div class="navbar-collapse collapse " id="navbar-collapse-1">
						
						<ul class="nav navbar-nav  " >
						
							<li ><a class="dropdown" href="/">HOME</a>
							</li>
							<li ><a class="dropdown" href="/about_us">ABOUT US</a>
							</li>
							<li ><a class="dropdown" href="/service-and-charges">SERVICES & CHARGES</a>
							</li>

							<li class="dropdown"><a class="dropdown-toggle" href="/contact" data-toggle="dropdown">CONTACT<b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="/contact/">Contact</a></li>
									<li><a href="/privacy">Privacy</a></li>
								</ul>
							</li>
						</ul>
							
						
						
					</div>
				</div>

			</div>
		</div>


	</div>


'	);






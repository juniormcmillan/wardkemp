<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 20-Jul-16
 * Time: 11:05 AM
 */

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/ssp.class.php");

require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");




$gMysql					=	new Mysql_Library();



# clause
$where = " 1=1 ";


// Table's primary key
$primaryKey = 'id';

// DB table to use
$table = 'page';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(

	array(
		'db'        => 'updated',
		'dt'        => 0,
		'formatter' => function( $d, $row ) {
			return date( 'D M j, Y, g:i a', strtotime($d));
		},
	),
	array( 'db' => 'title',  				'dt' => 1 ),
	array( 'db' => 'ref',  					'dt' => 2 ),
	array( 'db' => 'section_id',  			'dt' => 3 ),
	array( 'db' => 'prod_slots',  			'dt' => 4 ),
	array( 'db' => 'status',  				'dt' => 5 ),


	# can we export
	array(
		'db' => 'id',
		'dt' => 6 ,

		'formatter' => function( $d, $row )
		{
			return "<button type='button' class='btn-sm btn-success btn ' title='view' onclick=\"window.location='/admin/content/pages/detail.php?pageId=$d'\">Edit Page</button>";
		},
	),

	array(
		'db' => 'id',
		'dt' => 7 ,
		'formatter' => function( $d, $row )
		{
			global	$gMysql;

			$job_id			=	$row[0];
			$partner_id		=	$d;

			$button			=	"<button type='button' class='btn-sm btn-danger btn' onclick=\"redirect('Are you sure you want to delete this?','/admin/content/pages/detail.php?pageId=$d&btnDelete=yes');\">Delete</button>";

			return $button;

		},

	),




);


// SQL server connection information
$sql_details = array(
	'user' => MYSQL_USER,
	'pass' => MYSQL_PASS,
	'db'   => MYSQL_DBASE,
	'host' => MYSQL_HOST
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */


echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where )
);






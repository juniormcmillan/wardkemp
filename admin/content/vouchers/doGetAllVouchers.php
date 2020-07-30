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
$table = 'fp_voucher_code';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(

	array(
		'db'        => 'added',
		'dt'        => 0,
		'formatter' => function( $d, $row ) {
			return date( 'D M j, Y, g:i a', strtotime($d));
		},
	),
	array( 'db' => 'code',  				'dt' => 1 ),
	array( 'db' => 'value',  					'dt' => 2 ),
	array( 'db' => 'type', 			'dt' => 3 ),
	array( 'db' => 'commission',  			'dt' => 4 ),
	array( 'db' => 'times_submitted',  			'dt' => 5 ),

	array(
		'db'        => 'added',
		'dt'        => 6,
		'formatter' => function( $d, $row ) {
			return date( 'D M j, Y, g:i a', strtotime($d));
		},
	),



	#edit etc
	array(
		'db' => 'id',
		'dt' => 7 ,

		'formatter' => function( $d, $row )
		{
			return "<button type='button' class='btn-sm btn-success btn ' title='view' onclick=\"window.location='/admin/content/vouchers/detail.php?voucherID=$d'\">Edit Voucher</button>";
		},
	),

	array(
		'db' => 'id',
		'dt' => 8 ,
		'formatter' => function( $d, $row )
		{
			$button			=	"<button type='button' class='btn-sm btn-danger btn' onclick=\"redirect('Are you sure you want to delete this?','/admin/content/vouchers/detail.php?voucherID=$d&btnDelete=yes');\">Delete</button>";

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






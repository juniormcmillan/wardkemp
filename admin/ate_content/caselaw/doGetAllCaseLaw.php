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

$category 				= 	GetVariableString('category',$_POST,$_GET);


# clause
$where = " category='$category'  ";


// Table's primary key
$primaryKey = 'sort_order';

// DB table to use
$table = 'ate_caselaw';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(


	array( 'db' => 'sort_order',  				'dt' => 0 ),
	array( 'db' => 'id',  						'dt' => 1 ),
	array( 'db' => 'headline',  				'dt' => 2 ),
	array(
		'db'        => 'updated',
		'dt'        => 3,
		'formatter' => function( $d, $row ) {
			return date( 'D M j, Y', strtotime($d));
		},
	),
	array(
		'db' => 'status',
		'dt' => 4 ,
		'formatter' => function( $d, $row )
		{

			$id	=	$row[1];

			if (strtoupper($d) != "A")
			{
				$string	=	"<input data-id='".$id."'         class='toggle-demo' id='status".$id."' type='checkbox' data-toggle='toggle' data-on='On' data-off='Off' data-onstyle='default' data-offstyle='warning'>";
			}
			else
			{
				$string	=	"<input checked data-id='".$id."' class='toggle-demo' id='status".$id."' type='checkbox' data-toggle='toggle' data-on='On' data-off='Off' data-onstyle='default' data-offstyle='warning'>";
			}


			return	$string;
		},

	),
	# can we export
	array(
		'db' => 'id',
		'dt' => 5 ,

		'formatter' => function( $d, $row )
		{
			return "<button type='button' class='btn-sm btn-success btn ' title='view' onclick=\"window.location='/admin/ate_content/caselaw/detail.php?cId=$d'\">Edit Page</button>";
		},
	),




	array(
		'db' => 'id',
		'dt' => 6 ,
		'formatter' => function( $d, $row )
		{

			$button			=	"<button type='button' class='btn-sm btn-danger btn' onclick=\"redirect('Are you sure you want to delete this?','/admin/ate_content/caselaw/detail.php?cId=$d&dbAction=Delete');\">Delete</button>";

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






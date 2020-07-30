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




/*

$item_array	=	$gMysql->selectToArray("select * from sm_page where language='en' ORDER BY section_id,ref,updated desc",__FILE__,__LINE__);

foreach ($item_array as $item)
{
$id = $item["id"];
$datetime = $item["updated"];
$ref 	= $item["ref"];
$sectionId 	= $item["section_id"];
$order	 	= $item["prod_slots"];
$status 	= $item["status"];

# extract language... for flag
$language			= $item["language"];
# and we need the UK version to be a link (if there is an ID)
$data				=	$gMysql->queryRow("select * from sm_page where version_of='$id' ",__FILE__,__LINE__);
if	(!empty($data))
{
$version_of		 	= 	$data["id"];
$language_of		=	$data["language"];
$language_of_image	=	$gLanguage->getLanguageFlag($language_of);
$language_of_link	=	"<a href='detail.php?pageId=$version_of'>$language_of_image</a>";
}
else
{
$language_of_link	=	"";
}

$title = $item["title"];
echo '<tr valign="top" class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
	<td>'.  date("l jS \of M Y h:i A", strtotime($datetime)).'</td>
	<td>'.urldecode($title).'</td>
	<td>'.urldecode($ref).'</td>
	<td align=center>'.$sectionId.'</td>
	<td align=center>'.$order.'</td>
	<td align=center>'.$status.'</td>

	<td><a href="detail.php?pageId='.$id.'"></a></td>
	<td>' . $language_of_link. '</td>


	<td><a href="detail.php?pageId='.$id.'">EDIT</a></td>
	<td><a href="detail.php?pageId='.$id.'&btnDelete=yes">DELETE</a></td>



</tr>';
}
echo '</table>';
}
else
{
echo '<p>There are no Pages to show</p>';
}
*/

# clause
$where = " 1=1 ";


// Table's primary key
$primaryKey = 'id';

// DB table to use
$table = 'sm_post';

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
	array(
		'db'        => 'title',
		'dt'        => 1,
		'formatter' => function( $d, $row ) {
			return stripNonAlphaNumericSpaces($d);
		},
	),



	array( 'db' => 'ref',  					'dt' => 2 ),
	array(
		'db'        => 'published',
		'dt'        => 3,
		'formatter' => function( $d, $row ) {
			return date( 'D M j, Y, g:i a', strtotime($d));
		},
	),
	array( 'db' => 'author',  		'dt' => 4 ),
	array( 'db' => 'status',  				'dt' => 5 ),


	array(
		'db' => 'id',
		'dt' => 6 ,

		'formatter' => function( $d, $row )
		{
			return "<button type='button' class='btn-sm btn-success btn ' title='view' onclick=\"window.location='/admin/ate_news/detail.php?pageId=$d'\">Edit Page</button>";
		},
	),

	array(
		'db' => 'id',
		'dt' => 7 ,
		'formatter' => function( $d, $row )
		{
			global	$gMysql;

			$button			=	"<button type='button' class='btn-sm btn-danger btn' onclick=\"redirect('Are you sure you want to delete this?','/admin/ate_news/detail.php?pageId=$d&btnDelete=yes');\">Delete</button>";

			return $button;

		},

	),


	# grabs the text and changes the formatting
	array(
		'db' => 'content',
		'dt' => 8 ,
		'formatter' => function( $d, $row )
		{
			$text	=	htmlentities($d);	#"; #html_entity_decode($d,ENT_QUOTES);

			return $text;
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


$aa	=	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where );

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where )
);






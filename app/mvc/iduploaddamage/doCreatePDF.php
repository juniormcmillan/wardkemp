 <?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cedric
 * Date: 20/05/17
 * Time: 19:10
 *
 */

 // include the pdf factory
 require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf_multicell/pdfFactory.php" );

 // Include the Advanced Multicell Class
require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf_multicell/classes/pdfmulticell.php" );

 /**
  * Include my Custom PDF class This is required only to overwrite the header
  */
require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf_multicell/mypdf-multicell.php" );



 require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");


$invalid_characters 	=	array("$", "%", "#", "<", ">", "|","'",'"',"'");

# check for referrer and set
$case_key 		=	strtoupper(GetVariableString('case_key',$_POST,""));

$rooms_obj = array(

	"1"	=>	array( "name"=> 'Kitchen',			"code"=> 'K'	),
	"2"	=>	array( "name"=>  'Bathroom(s)',		"code"=> 'B'	),
	"3"	=>	array( "name"=> 'Bedroom(s)',		"code"=> 'D'	),
	"4"	=>	array( "name"=> 'Living Room',		"code"=> 'L'	),
	"5"	=>	array( "name"=> 'Hallway/Other',	"code"=> 'H'	),
	"6"	=>	array( "name"=> 'External',			"code"=> 'E'	),
 );




if	(empty($case_key))
{
	$returnCode			=	"error";
	$popupID			=	"fileNoRef";


	$output 			=	array	(	"popupID" 		=> 		"fileNoRef" 	);


	# return two variables
	echo json_encode($output);
	exit;



}
else
{

	$factory = new pdfFactory();
	// create new PDF document
	$oPdf = new myPdfMulticell("P", "mm", "A4");
	$factory->initPdfObject( $oPdf );
	$oMulticell = new PdfMulticell( $oPdf );

	$oPdf->SetFont('Arial', '', 20);

	# tag,font,style,size,color
	$oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 9, "0,0,0" );
	$oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "B", 9, "0,0,0" );
	$oMulticell->setStyle( "i", $oPdf->getDefaultFontName(), "I", 7, "0,0,0" );
	$oMulticell->SetStyle( "h1", $oPdf->getDefaultFontName(), "", 9, "80,80,260" );
	$oMulticell->SetStyle( "h3", $oPdf->getDefaultFontName(), "B", 9, "203,0,48" );
	$oMulticell->SetStyle( "h4", $oPdf->getDefaultFontName(), "BI", 9, "0,151,200" );
	$oMulticell->SetStyle( "hh", $oPdf->getDefaultFontName(), "B", 9, "255,189,12" );
	$oMulticell->SetStyle( "ss", $oPdf->getDefaultFontName(), "", 7, "203,0,48" );
	$oMulticell->SetStyle( "font", $oPdf->getDefaultFontName(), "", 9, "0,0,255" );
	$oMulticell->SetStyle( "style", $oPdf->getDefaultFontName(), "BI", 9, "0,0,220" );
	$oMulticell->SetStyle( "size", $oPdf->getDefaultFontName(), "BI", 9, "0,0,120" );
	$oMulticell->SetStyle( "smallest", $oPdf->getDefaultFontName(), "", 8, "0,0,0" );

	$oPdf->SetCreator("WardKemp");
	$oPdf->SetFont('Arial', 'B', 20);

	$oPdf->SetFillColor(221, 221, 221);
	$oPdf->SetDrawColor(10, 10, 10);

	$oPdf->SetXY(10,10);
	$oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 16, "0,0,0" );
	$oMulticell->MultiCell(200, 8, '<p>Damages Images</p>', 0, "C", false);





	# get all files
	$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/damage/";
	# this will get the names of all the images, except these...
	$scanned_directory	=	array_diff(scandir($server_location), array('..', '.'));
	# we should have a table of all images
	$remove_array		=	array();				#array('error','base');
	$images_array		=	array_values(array_diff($scanned_directory, $remove_array));

	$y=	30;

	# loop through the room (adding to PDF eventually)
	foreach ($rooms_obj as $room)
	{
		$room_name		=	$room['name'];
		$room_code		=	$room['code'];
		$room_comment	=	"";

		# file of comment for this room
		$server_location	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/damagecomment/".$room_code."1_".$case_key.".csv";
		if (($comment_file	=	file_get_contents ($server_location))!==false)
		{
			$comment_file	=	explode(">",$comment_file);
			$room_comment	=	$comment_file[2];
		}



		# loop thru directory and only grab items with the correct name & case key combo
		$image_file_array	=	getTheseImages($room_code,$case_key,$images_array);

		# now add these images to the document
		$oPdf->SetXY(20, $y);
		$oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
		$oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
		$oMulticell->MultiCell(200, 4,	'<p><b>' . $room_name . '</b></p>'	, 0, "J", false);

		$y+=5;

		$oPdf->SetXY(20, $y);
		$oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
		$oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
		$oMulticell->MultiCell(200, 4,	'<p><b>' . $room_comment. '</b></p>'	, 0, "J", false);

		$y+=20;

		foreach($image_file_array as $image)
		{
			$image1		=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/damage/".$image;

			$oPdf->Cell( 10, 10, $oPdf->Image($image1, $oPdf->GetX(), NULL, 33.78), 0, 0, 'L', false );
		}

		$y+=20;

	}


	# variable is equal to oPdf->Output( to
	$pdf_string = $oPdf->Output('file', 'S');
	$filename	=	$_SERVER["DOCUMENT_ROOT"]."/files_backup/damagepdf/".$case_key.".pdf";
	# store the PDF we have generated
	file_put_contents($filename, $pdf_string);


	$a=1;
}



$output 			=	array	(	"popupID" 		=> 		"" 	);


# return two variables
echo json_encode($output);





# go through the images list
function getTheseImages($room,$case_key,$images_array)
{
	$image_list	=	array();

	foreach	($images_array as $image)
	{
		if (strpos($image, $case_key) !== false)
		{
			if (substr($image, 0, 1) == $room)
			{
				$image_list[]	=	$image;
			}
		}
	}

	return	$image_list;
}



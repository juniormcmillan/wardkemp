<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 29-Jun-16
 * Time: 10:35 AM
 */


    // Allowed extentions.
    $allowedExts = array("gif", "jpeg", "jpg", "png", "blob");

    // Get filename.
    $temp = explode(".", $_FILES["file"]["name"]);

    // Get extension.
    $extension = end($temp);

    // An image check is being done in the editor but it is best to
    // check that again on the server side.
    // Do not use $_FILES["file"]["type"] as it can be easily forged.
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

    if ((($mime == "image/gif")
			|| ($mime == "image/jpeg")
			|| ($mime == "image/pjpeg")
			|| ($mime == "image/x-png")
			|| ($mime == "image/png"))
		&& in_array(strtolower($extension), $allowedExts)) {
		// Generate new random name.
		$name = sha1(microtime()) . "." . $extension;

		// Save file in the uploads folder.
#		move_uploaded_file($_FILES["file"]["tmp_name"], getcwd() . "/banners/" . $name);
		move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]. "/UserFiles/Image/" . $name);

		// Generate response.
		$response = new StdClass;
		$response->link =  "/UserFiles/Image/" . $name;
		echo stripslashes(json_encode($response));
	}
?>
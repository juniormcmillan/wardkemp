<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 29-Jun-16
 * Time: 11:47 AM
 */

// Get src.
$src = $_POST["src"];

// Check if file exists.
if (file_exists($_SERVER["DOCUMENT_ROOT"]. $src)) {
	// Delete file.
	unlink($_SERVER["DOCUMENT_ROOT"]. $src);
}
?>
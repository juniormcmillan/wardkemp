<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 13/06/2018
 * Time: 17:13
 */

# function called getTemplate(points to $fileName)
function getTemplate($fileName)
{
    # $fileName is given value when function is called getTemplate(page1_template.html)
    # file_get_contents = reads entire file into a string
    $file = file_get_contents($fileName);
    # IF we point to the file
    if($file ) {
        # returns the contents of the file.
        return $file;
    }
    # ELSE (the file is not pointing to the correct place)
    else {
        # print the message "File not found" .
        echo("File not found: " . $fileName);
    }

}

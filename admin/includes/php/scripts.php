<?php
$GLOBALS["cid"];
//error_reporting(0);

function is_valid_email ($email)
	{ 
    return (eregi( 
        '^[-!#$%&\'*+\\./0-9=?A-Z^_`{|}~]+'.      // the user name 
        '@'.                                      // the ubiquitous at-sign 
        '([-0-9A-Z]+\.)+' .                       // host, sub-, and domain names 
        '([0-9A-Z]){2,4}$',                       // top-level domain (TLD) 
        trim($email))); 
	} 

function check($table,$query)
	{
	//this simply checks to see if the query will have any results, so run this function before the main one, and you'll be able to check
	//for missing stuff before it happens.
	global $checked;
	$strSQL = "select count(*) from $table $query";
	$getResults = mysql_query($strSQL);
	$results = mysql_result($getResults,0);
	if($results > 0)
		{
		$checked = true;
		}
	else
		{
		$checked = false;
		}
	}


function build($table,$query,$results)
	{
	//this makes the result a variable that you can just echo when everything else is sussed.
	global $output;
	
	//If you ever need to see the mysql query to check it, just uncomment the next line
	//$output = "<p>select * from $table $query</p>";

	//get the results from the table according to the query
	$getResults = mysql_query("select * from $table $query");
	//count the number of results that match
	$countResults = mysql_query("select count(*) from $table $query");
	$resultsCount = mysql_result($countResults,"");

	if($resultsCount == 0)
		{
		$output = "No matching";
		}
	else
		{
		//get the column names and put them in an array
		$getcols = mysql_query("show columns from $table");	
		while($cols = mysql_fetch_array($getcols))
			{
			$columns[]=$cols[0];
			}
		$number = count($columns);
		while($result = mysql_fetch_array($getResults))
			{	
			for($i=0;$i<$resultsCount;$i++)
				{
				for($count=0;$count<$number;$count++)
					{
					$field = $columns[$count];
					$$field = stripslashes($result[$field]);
					}
				}
			
		//And this is the real meat. Conditional building of layouts depending on what you've specified in the variable $results. 
		if($results == "sidemenu")
			{
			$catName = stripslashes($title);
			$catid = $id;
			//this gets the currently selected category (if applicable) from the $GLOBALS supper variable - defined at the top of the page
			//We use this to see if the current category is selected, and if so change the appearance of the menu item accordingly.
			$selectedCat =  $GLOBALS["cid"];
			if($catid == $selectedCat)
				{
				$selected = "class=\"selected\"";
				}
			else
				{
				$selected = "";
				}
			$output.= "<li><a href=\"index.php?page=category&amp;cid=$catid\" $selected>$catName</a></li>";
			}
	
		//This is the main category page
		if($results == "category")
			{
			$title = stripslashes($title);
			$description = stripslashes($description);
			$output.= "<h1>$title</h1>";
			$output.= "<p>$description</p>";
			}
		
		//This pulls out the products in a given category
		if($results == "categorised")
			{
			$name = stripslashes($name);
			if($type=="multipack-box")
				{
				$typeLink = "&amp;type=multibox";
				}
			$dimension = nl2br($dimension);
			$output.= "<div class=\"product\"><h3><a href=\"index.php?page=product&amp;id=$id&amp;cid=$category$typeLink\">$name</a></h3>";
			$thumb = "tn_".$image;
			
			if(file_exists("images/products/$thumb"))
				{
				$output.= "<a href=\"index.php?page=product&amp;id=$id&amp;cid=$category\"><img src=\"images/products/$thumb\" title=\"$name\" style=\"float:right;\"/></a>";
				}
			elseif(file_exists("images/products/$image"))
				{
				$output.= "<a href=\"index.php?page=product&amp;id=$id&amp;cid=$category\"><img src=\"images/products/$image\" title=\"$name\" style=\"float:right;\" /></a>";
				}
			$output.= "<p style=\"padding-left:6px\">$dimension</p>";
			$output.= "<p style=\"padding-left:6px\"><a href=\"index.php?page=product&amp;id=$id&amp;cid=$category\" class=\"clink\">Click to view  &#187;</a></p></div>";
		}
		
		//This pulls out the products in a given category
		if($results == "var2")
			{
			$name = stripslashes($name);
			$output.= "<div class=\"product\">";
			
			$thumb = "tn_".$image;
			
			if(file_exists("images/products/$thumb"))
				{
				$output.= "<a href=\"index.php?page=multipack&amp;id=$id&amp;cid=$category\"><img src=\"images/products/$thumb\" title=\"$name\" style=\"float:right;\" /></a>";
				}
			elseif(file_exists("images/products/$image"))
				{
				$output.= "<a href=\"index.php?page=product&amp;id=$id&amp;cid=$category\" ><img src=\"images/products/$image\" title=\"$name\" style=\"float:right\"/></a>";
				}
			$output.="<h2><a href=\"index.php?page=multipack&amp;id=$id&amp;cid=$category\">$name</a></h2>";
			$output.= "</div>";
			}
		}
	}
}

//functions for generating random string for session / customer id
function random_char($string)
	{
	$length = strlen($string);
	$position = mt_rand(0, $length - 1);
	return($string[$position]);
	}
	
function random_string ($charset_string, $length)
	{
	$return_string = random_char($charset_string);
	for ($x = 1; $x < $length; $x++)
		{
		$return_string .= random_char($charset_string);
		}
	return($return_string);
	}

?>
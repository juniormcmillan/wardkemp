<?php
/**
 * Created by PhpStorm.
 * User: Jack Heeney
 * Date: 14/03/18
 * Time: 00:49
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                 *
 *         Dynamically created dropDown            *
 *                                                 *
 *  This file is called controller_stats.php,      *
 *  and is linked to an MVC.  It uses a class      *
 *  to which the MVC finds and uses to run our     *
 *  code.  Firstly it declares any variables       *
 *  we may use later.                              *
 *                                                 *
 *  Next a render function is created and          *
 *  inside this function we call another           *
 *  function created to make the dropdown          *
 *  that we desire.                                *
 *                                                 *
 *  The functioned called createDynamically        *
 *  firstly starts off by setting its connection   *
 *  up to the database. Next you create a variable *
 *  and inside this create a html string           *
 *  containing paart of the dropdown information.  *
 *                                                 *
 *  We had to append a special-case option         *
 *  for the No Referrers as they have blank        *
 *  values and we needed to set this to be         *
 *  blank rather than generated dynamically.       *
 *                                                 *
 *  Then we needed a loop, in this instance we     *
 *  used a foreach loop that checked each instant  *
 *  of the data and place it inbetween the tags    *
 *  and added them to the values where needed.     *
 *                                                 *
 *  Unless given special-case with if statement    *
 *  inside our loop.                               *
 *                                                 *
 *  Finish up the html select string and finally   *
 *  return the whole html string variable.         *
 *                                                 *
 *                                                 *
 * * * * * * * * * * * * * * * * * * * * * * * * * */

class Stats_Controller extends Base_Controller
{

    # variables that might get called
    private	$data_array  		            = 	"";


    # display the contents of the page (this part should be in the view)
    public function render()
    {
        $todayDateInput     = $this->todayDateInput();

		$yesterdayDate		=	date("d/m/Y", time() - 60 * 60 * 24);

        # we can push all these pre-filled tags onto the page
        $tags	=	array(


			"{{todayDate}}"		    =>	$todayDateInput,
            "{{yesterdayDate}}"	=>	$yesterdayDate

        );

        $this->appendTags($tags);


        # the parent render function
        parent::render();


    }

    // This function formats todays date to be d/m/Y.
    // Then it puts our input field into a string and returns this for us.
    public function todayDateInput ()
    {
        $month = date('m');
        $day = date('d');
        $year = date('Y');

        $today = $day . '/' . $month . '/' . $year;

        $todayDate = "<input type='text' class='form-control datepicker' id='toDate' name='toDate' value='$today'  >";
        return $todayDate;

    }





}





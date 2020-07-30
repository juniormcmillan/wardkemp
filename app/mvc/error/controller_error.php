<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49



 now this page needs to be mapped to the correct class




 if page is found in the database, spawn a view based on the page found (mapped via dbase)

one idea is that the controller/view could be spawned after initial dbase mapping which is kept in the front controller





 */

class Error_Controller extends Page_Controller
{

	# appends error text
	public function render()
	{
		$tags			=	array(

				"{{error_title}}"				=>	$this->params['error_title'],
				"{{error_message}}"				=>	$this->params['error_message'],
				"{{error_debug}}"				=>	$this->params['error_debug'],
								);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();

	}


}

<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49
 */



class Rss_View
{
	protected	$controller;

	# constructor
	public function __construct($controller)
	{
		$this->controller	=	$controller;
	}

	# display the contents of the page (this part should be in the view)
	public function render()
	{
	
	
		# pointer to the template library and pass it the template (we can probably do without passing the cache pointer)
		$gTemplate		=	new	Template_Library($this->controller->getTemplate());

		# how we want to display the page
		$gTemplate->display($this->controller->tags);

	}



}




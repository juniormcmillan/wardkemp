<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49
 */



class Base_View
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

		# set the header
		$gTemplate->setHeader($this->controller->getHeader());
		# set the footer
		$gTemplate->setFooter($this->controller->getFooter());

		# check for popup and append the old way
		$this->controller->checkAppendPopup();

		# how we want to display the page
		$gTemplate->display($this->controller->tags);

	}



}




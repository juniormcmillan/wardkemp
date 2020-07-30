<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49
 */



class Page_View extends Base_view
{
	protected	$controller;
	protected	$template_name;


	# display the contents of the page (this part should be in the view)
	public function show($template_name=NULL)
	{
		# pointer to the templating library and pass it the template (we can probably do without passing the cache pointer)
		$gTemplate		=	new	Template_Library($template_name);

		# set the header
		$gTemplate->setHeader("views/templates/header_home.html");
		# set the footer
		$gTemplate->setFooter("views/templates/footer_home.html");


		# we should use the SEO version if it is valid
		$pageTitle		=	(empty($this->controller->page_data["title_seo"])) ? $this->controller->page_data["title"]	:	$this->controller->page_data["title_seo"];
		$pageContent	=	html_entity_decode($this->controller->page_data["content"],ENT_QUOTES);
		$metaTitle		=	$this->controller->page_data["meta_title"];


		# This hash contains the tags that need to be replaced.
		# these need to happen at the end
		$tags	=

				array(
						"{{page_title}}"			=>	$pageTitle,
						"{{page_title_meta}}"		=>	$metaTitle,
						"{{page_content}}"			=>	$pageContent,
						"{{page_version}}"			=>	7,


				);

		$this->controller->appendTags($tags);
		# check for popup and append the old way
		$this->controller->checkAppendPopup();


		# how we want to display the page
		$gTemplate->display($this->controller->tags);



	}



}




<?php
/**
 * This file handles the default page request
 */
class Rss_Controller
{

	protected	$route		=	array();

	public		$tags		=	array();

	public		$params		=	array();

	protected	$method		=	"";

	protected	$view		=	"";

	public		$directory;

	# template
	protected	$template;


	# constructor
	public function __construct($route,$view_name)
	{
		global	$gSession;

		$this->route	=	$route;
		# create a view pointer (should be passed along with construction)
		$this->view			=	new $view_name($this);

		$this->directory	=	"mvc/" . $this->route['controller'] .	"/";

		$this->method	=	$_SERVER['REQUEST_METHOD'];
	}




	# prepare the contents of the page
	public function render()
	{
		global $gMysql;

		$feed_url	=	"https://". $_SERVER['HTTP_HOST']	. "/rss";
		$canonical	=	WEBSITE_CANONICAL	;

	#	$feed_url	=	"https://beta.boxlegal.co.uk/rss";
		$canonical	=	"https://beta.boxlegal.co.uk";







		/*
				$rssfeed = '<?xml version="1.0" encoding="utf-8"?>';
				$rssfeed .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
		*/
		header("Content-Type: application/rss+xml; charset=utf-8");

		$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
		$rssfeed .= '<rss version="2.0" 

xmlns:content="http://purl.org/rss/1.0/modules/content/" 
xmlns:wfw="http://wellformedweb.org/CommentAPI/" 
xmlns:dc="http://purl.org/dc/elements/1.1/" 
xmlns:atom="http://www.w3.org/2005/Atom" 
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" 
xmlns:slash="http://purl.org/rss/1.0/modules/slash/" >';


		$rssfeed .= '<channel>';
		$rssfeed .= '<title>After The Event Insurance Blog</title>';
		$rssfeed .= "\r\n";
		$rssfeed .= '<atom:link href="'.$feed_url.'" rel="self" type="application/rss+xml"/>';
		$rssfeed .= "\r\n";
		$rssfeed .= '<link>'.$canonical .  'ate_blog'. '</link>';
		$rssfeed .= "\r\n";
		$rssfeed .= '<description>Box Legal ATE Insurance News, Views and Guidance. A Blog for Legal Practitioners</description>';
		$rssfeed .= "\r\n";
		//Create RFC822 Date format to comply with RFC822
		$date_f 		= date("D, d M Y H:i:s T", time());
		$lastBuildDate 	= gmdate(DATE_RFC2822, strtotime($date_f));
		$rssfeed .= '<lastBuildDate>'.$lastBuildDate.'</lastBuildDate>';
		$rssfeed .= "\r\n";
		$rssfeed .= '<language>en-GB</language>';
		$rssfeed .= "\r\n";
		$rssfeed .= '<sy:updatePeriod>hourly</sy:updatePeriod>';
		$rssfeed .= '<sy:updateFrequency>1</sy:updateFrequency>';


		$rssfeed .= "\r\n";
		$post_data	=	$gMysql->selectToArray("select * from sm_post order by published desc limit 30",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL);

		foreach($post_data as $data)
		{

			$news_date			= 	date(DATE_RSS, strtotime($data['published']));
			$news_title			=	$data['title'];
			$news_url			=	$canonical .	 $data['ref'];
			$news_content		=	(nl2br(htmlspecialchars_decode ($data['content_snippet'], ENT_QUOTES) ));
			$news_content		=	($data['content_snippet']);
			$news_author		=	$data['author'];
			$news_ref			=	$canonical .'/'. $data['ref'];


			$rssfeed .= '<item>';
			$rssfeed .= '<title><![CDATA['. $news_title . ']]></title>';
			$rssfeed .= '<link>' . $news_url . '</link>';
			$rssfeed .= '<pubDate>' . $news_date. '</pubDate>';
			$rssfeed .= '<dc:creator><![CDATA['.$news_author.']]></dc:creator>';
			$rssfeed .= '<category><![CDATA[After The Event Insurance]]></category>';

			$rssfeed .= '<guid isPermaLink="false">'.$news_ref.'</guid>';


			$rssfeed .= '<description><![CDATA['. $news_content .']]></description>';
			$rssfeed .= '</item>';
			$rssfeed .= "\r\n";
			$rssfeed .= "\r\n";

		}

		$rssfeed .= '</channel>';
		$rssfeed .= '</rss>';

		echo $rssfeed;

	}







}


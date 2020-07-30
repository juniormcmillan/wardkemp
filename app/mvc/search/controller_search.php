<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49



 now this page needs to be mapped to the correct class




 if page is found in the database, spawn a view based on the page found (mapped via dbase)

one idea is that the controller/view could be spawned after initial dbase mapping which is kept in the front controller








 This controller is used when displaying standard pages with pretty-urls


 */



class Search_Controller extends Page_Controller
{
	protected	$search;
	protected	$search_results;



	# constructor
	public function __construct($route,$view_name)
	{
		global $gMysql;

		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|","'","`");

		parent::__construct($route,$view_name);


		#  sanitise the search string
		$this->search		=	strtolower(urldecode(GetVariableString('bl-search',$this->params)));
		$this->search		=	str_replace($invalid_characters,"",$this->search);


	}





	# displays the search results
	public function render()
	{
		global $gMysql;

		$limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
		$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
		$links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 5;

		$query = "SELECT id, title AS headline, content AS intro, 'page' AS result_type, ref FROM sm_page WHERE status = 'A' AND (CONCAT('%',LOWER(title),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT ref as id, title as headline, content_snippet AS intro, 'blog' AS result_type, '' AS filename FROM sm_post WHERE status = 'A' AND (CONCAT('%',LOWER(title),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content_snippet),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT ref, headline, summary AS intro, 'caselaw' AS result_type, ref FROM ate_caselaw WHERE status = 'A' AND (CONCAT('%',LOWER(headline),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(summary),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT id, name AS headline, position AS intro, 'person' AS result_type, '' AS filename FROM person WHERE status = 'A' AND (CONCAT('%',LOWER(name),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(position),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(profile),'%') LIKE CONCAT('%','".$this->search."','%'))
 " ;

/*

		$query = "SELECT id, title AS headline, content AS intro, 'page' AS result_type, filename FROM page WHERE status = 'A' AND (CONCAT('%',LOWER(title),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT ref as id, title as headline, content_snippet AS intro, 'blog' AS result_type, '' AS filename FROM sm_post WHERE status = 'A' AND (CONCAT('%',LOWER(title),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content_snippet),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT id, headline, summary AS intro, 'news' AS result_type, '' AS filename FROM news WHERE status = 'A' AND (CONCAT('%',LOWER(headline),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(summary),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT id, headline, summary AS intro, 'press' AS result_type, '' AS filename FROM press WHERE status = 'A' AND (CONCAT('%',LOWER(headline),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(summary),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT id, name AS headline, position AS intro, 'person' AS result_type, '' AS filename FROM person WHERE status = 'A' AND (CONCAT('%',LOWER(name),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(position),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(profile),'%') LIKE CONCAT('%','".$this->search."','%'))
UNION
SELECT id, headline, summary AS intro, 'news' AS result_type, '' AS filename FROM news WHERE status = 'A' AND (CONCAT('%',LOWER(headline),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(summary),'%') LIKE CONCAT('%','".$this->search."','%') OR CONCAT('%',LOWER(content),'%') LIKE CONCAT('%','".$this->search."','%'))
 " ;

*/

		$gPaginator  =	new Paginator_Library($query );

		$paged_data  =	$gPaginator->getData( $page, $limit );

		$total		=	$gPaginator->getTotal();

		$search_results_string	=	"";

		for ($index=0;$index < count($paged_data->data) ;$index++)
		{


			$row_string	=	'<div class="pressReleaseItem">';


			if($paged_data->data[$index]["result_type"] == "page")
			{
				$tmpIntro = strip_tags(html_entity_decode($paged_data->data[$index]["intro"]));
				if(strlen($tmpIntro) > 300)
				{
					$tmpIntro = substr($tmpIntro,0,297)."...";
				}
				$tmpUrl = $paged_data->data[$index]["ref"];
			}
			elseif($paged_data->data[$index]["result_type"] == "blog")
			{
				$tmpIntro = $paged_data->data[$index]["intro"];
				if(strlen($tmpIntro) > 300)
				{
					$tmpIntro = substr($tmpIntro,0,297)."...";
				}
				$tmpUrl = "/".$paged_data->data[$index]["id"];
			}
			elseif($paged_data->data[$index]["result_type"] == "person")
			{
				$tmpIntro = $paged_data->data[$index]["intro"];
				if(strlen($tmpIntro) > 300)
				{
					$tmpIntro = substr($tmpIntro,0,297)."...";
				}
				$tmpUrl = "/key_people/#_P".$paged_data->data[$index]["id"];
			}
			elseif($paged_data->data[$index]["result_type"] == "caselaw")
			{
				$tmpIntro = $paged_data->data[$index]["intro"];
				if(strlen($tmpIntro) > 300)
				{
					$tmpIntro = substr($tmpIntro,0,297)."...";
				}
				$tmpUrl = "/".$paged_data->data[$index]["ref"];
			}
			else
			{
				$tmpIntro = urldecode($paged_data->data[$index]["intro"]);
				if($paged_data->data[$index]["result_type"] == "news")
				{
					$tmpUrl = "/news_room/article/?nId=".$paged_data->data[$index]["id"];
				}
				else
				{
					$tmpUrl = "/news_room/press_release/?pId=".$paged_data->data[$index]["id"];
				}
			}
			$row_string .=	'<h3 class="bl-search-result-header"><strong><a href="'.$tmpUrl.'">'.urldecode($paged_data->data[$index]["headline"]).'</a></strong></h3>
                <p>'.$tmpIntro.'</p>
                <a class="btn btn-dark btn-sm" href="'.$tmpUrl.'">More...</a>
            </div>';

			$search_results_string 	.=	$row_string;
		}


		$page_links	=	$gPaginator->createSearchLinks( $links, 'pagination pagination-sm',$this->search );



		# we can push all these pre-filled tags onto the page
		$tags	=	array(

			"{{search_results}}"			=>	$search_results_string,
			"{{search_string}}"				=>	$this->search,
			"{{page_links}}"				=>	$page_links,

			);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();
	}







































}




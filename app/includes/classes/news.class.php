<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 21-Nov-16
 * Time: 4:17 PM
 */

class News_Class
{
	# dataabse table for news posts
	private	$table						=	"";
	private	$news_per_page				=	2;
	private $current_page				=	0;
	private $max_pages					=	0;
	private $num_items					=	0;

	public	$path_to_image_directory	=	"legacy/";
	public	$path_to_thumbs_directory	=	"legacy/thumbs/";

	public 	$final_width_of_image		=	640;


	public function __construct()
	{
	}


	public function __destruct()
	{

	}

	# grab all news items by published date, with a set limit per page
	public function getAllNewsHTML($page=0,$limit=6)
	{
		global	$gMysql;

	}


	public function getNewsItemByID($id)
	{
		global	$gMysql;

		if	(($news_data	=	$gMysql->queryRow("select * from sm_post where id='$id'",__FILE__,__LINE__)) != NULL)
		{
			$html	=	$this->prepareHTMLSmall($news_data);

			return $html;
		}
	}


	public function getNewsItemByURL($url)
	{
		global	$gMysql;

	}

	# gets the latest news item with this preferred slot
	public function getNewsItemByPreferredSlot($slot)
	{
		global	$gMysql;

		if	(($news_data	=	$gMysql->queryRow("select * from sm_post where status='A' and preferred_slot='$slot' order by published desc limit 1",__FILE__,__LINE__)) != NULL)
		{
			$html	=	$this->prepareHTMLSmall($news_data);

			return $html;
		}
	}


	# gets the latest news item with this offset
	public function getNewsItemByIndex($offset=0)
	{
		global	$gMysql;

		if	(($news_data	=	$gMysql->queryRow("select * from sm_post where status='A' order by published desc limit $offset,1",__FILE__,__LINE__)) != NULL)
		{
			$html	=	$this->prepareHTMLSmall($news_data);

			return $html;
		}
	}



	# gets random news items in the taglist that have
	public function getRandomNewsItems($tags="")
	{
		global	$gMysql;

		$news_data_array	=	$gMysql->selectToArray("select * from sm_post where status='A' order by published DESC limit 4 ",__FILE__,__LINE__);

		$string	=	"
<div style='font-size:16px; font-weight:bold; background:#07638C; color:#ffffff; padding:4px;' ><div class='container-fluid fp-website-container' style='padding-left:20px;'>
Flight Delay Claim News</div></div>
<div class='container-fluid fp-website-container' style='padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;background-color:#ffffff; '>";

		$i=0;
		foreach	($news_data_array as $news_data)
		{
			$html	=	$this->prepareHTMLCarousel($news_data,100);

			$string	.=	"<div style='border:0px solid red;' class='col-sm-6 col-xs-12  fp-blog-news-item'>$html</div>";

			# after 2 stories, clear this to stop float left/height issues
			if (($i++  &1) == 1)
			{
				$string	.=	"<div style='clear:both;'></div>";
			}
		}

		$string	.=	"</div>";

		return $string;
	}





	# prepares the snippet html form the data
	private function prepareHTMLSmall($data,$snippet_length=300)
	{
		$news_date			=	date("d-m-Y", strtotime($data['published']));
		$news_title			=	strip_tags($data['title']);

		$news_url			=	$data['ref'];
		$news_image			=	$data['story_image'];

		# recent ?
		if	(strtotime($data['published']) > strtotime('2017-01-27 00:00:00'))
		{
			$news_content			=	htmlspecialchars_decode ($data['content'], ENT_QUOTES);


			$news_snippet			=	 html_entity_decode($data['content_snippet'],ENT_QUOTES);

		}
		else
		{
			$news_content			=	htmlspecialchars_decode ($data['content'], ENT_QUOTES) ;
			$news_content		=	strip_tags( $news_content,"<br>");

			$pos	 			=	strpos($news_content,' ', $snippet_length);
			$news_snippet 		=	$this->closetags(substr($news_content,0,$pos) . "...");


		}





		$this->checkForThumb($news_image);
		$thumb_image		=	"/" .  $this->path_to_thumbs_directory	.	$news_image;

#		$thumb_image		=	"https://d1tmglvz7f8o08.cloudfront.net/legacy/thumbs/".	$news_image;
#		$thumb_image		=	"/app/images/legacy/thumbs/".	$news_image;

		$html	=	'
				<div class="fp-blog-news-item">
								<div class="fp-blog-news-item-inner">
									<div class="fp-blog-image">
										<a href="/'.$news_url.'"><img class="img-responsive" src="'.$thumb_image.'" alt="'.$news_title.' image"></a>
									</div>
									<h4 class="fp-blog-link ">
										<a class="" href="/'.$news_url.'">'.$news_title.'</a>
									</h4>
									<div class="fp-blog-story-snippet">
									'.$news_snippet.'
									</div>
									<div class="fp-blog-story-read-more">
										<a class="btn btn-primary" href="/'.$news_url.'" role="button">Read more</a>
									</div>
									<hr class="fp-blog-line">
									'.$news_date.'
								</div>
							</div>
							';

		return	$html;

	}







	# prepares the snippet html form the data
	private function prepareHTMLCarousel($data,$snippet_length=300)
	{
		$news_date			=	date("d-m-Y", strtotime($data['published']));
		$news_title			=	strip_tags($data['title']);

		$news_url			=	$data['ref'];
		$news_image			=	$data['story_image'];

		# recent ?
		if	(strtotime($data['published']) > strtotime('2017-01-27 00:00:00'))
		{
			$news_snippet			=	 html_entity_decode($data['content_snippet'],ENT_QUOTES);

		}
		else
		{
			$news_content			=	htmlspecialchars_decode ($data['content'], ENT_QUOTES) ;
			$news_content		=	strip_tags( $news_content,"<br>");

			$pos	 			=	strpos($news_content,' ', $snippet_length);
			$news_snippet 		=	$this->closetags(substr($news_content,0,$pos) . "...");

		}




		$html	=	'
								<div style="">
									<div style="border:0px solid red;color:#08638c; padding-bottom:2px;margin:0px;font-size: 12px;">'.$news_date.'</div>
									<h4 style="padding:0px;margin:0px;border:0px; font-size: 14px; ">
										<a class="" style="font-size: 14px; font-weight : bold;	text-decoration :none;	color:#08638c;" href="/'.$news_url.'">'.$news_title.'</a>
									</h4>
									<div style="color:#08638c; padding:0px;font-size: 12px; " class="fp-blog-story-snippet-carousel">
									'.$news_snippet.'
									</div>
								</div>
							';

		return	$html;

	}



	# returns latest links
	public function getRecentPosts($num=3)
	{
		global	$gMysql;

		$string	=	"";

		if	(($news_data	=	$gMysql->selectToArray("select * from sm_post order by published desc limit $num",__FILE__,__LINE__)) != NULL)
		{
			foreach ($news_data as $data)
			{
				$url			=	$data['ref'];
				$title			=	$data['title'];
				$published		=	$data['published'];

				$string			.=	'<a href="'.$url.'">'.$title.'</a><h4 class="fp"></h4>';
			}
		}

		return	$string;
	}



	public function checkForThumb($filename)
	{
		$thumb_filename		=	$this->path_to_thumbs_directory . $filename;

		if (!file_exists($thumb_filename))
		{
			$this->createThumbnail($filename);
		}
	}



	# creates same image name in different directory
	public function createThumbnail($filename)
	{
		if(preg_match('/[.](jpg)$/', $filename)) {
			$im = imagecreatefromjpeg($this->path_to_image_directory . $filename);
		} else if (preg_match('/[.](gif)$/', $filename)) {
			$im = imagecreatefromgif($this->path_to_image_directory . $filename);
		} else if (preg_match('/[.](png)$/', $filename)) {
			$im = imagecreatefrompng($this->path_to_image_directory . $filename);
		}

		$ox = imagesx($im);
		$oy = imagesy($im);

		$nx = $this->final_width_of_image;
		$ny = floor($oy * ($this->final_width_of_image / $ox));

		$nm = imagecreatetruecolor($nx, $ny);

		imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

		if(!file_exists($this->path_to_thumbs_directory)) {
			if(!mkdir($this->path_to_thumbs_directory)) {
				die("There was a problem. Please try again!");
			}
		}

		imagejpeg($nm, $this->path_to_thumbs_directory . $filename);

		$tn = '<img src="' . $this->path_to_thumbs_directory . $filename . '" alt="image" />';
		$tn .= '<br />Congratulations. Your file has been successfully uploaded, and a      thumbnail has been created.';
	}


	public function closetags($html)
	{
		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];
		$len_opened = count($openedtags);
		if (count($closedtags) == $len_opened) {
			return $html;
		}
		$openedtags = array_reverse($openedtags);
		for ($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	}
}
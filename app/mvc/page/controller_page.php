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



class Page_Controller extends Base_Controller
{
    # database reference of the page
    protected	$pattern;
    # data extracted about the page
    protected	$page_data;

    protected	$page_title;
    protected	$page_subtitle;
    protected	$page_meta_keywords;
    protected	$page_meta_title;
    protected	$page_meta_description;
    protected	$page_content;
    protected	$page_meta_breadcrumb;

    /*

        # constructor
        public function __construct($route,$view_name)
        {
            # if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
            parent::__construct($route,$view_name);

        }

    */


    # display the contents of the page (this part should be in the view)
    public function render()
    {
        global	$gMysql;


        # the page name for querying the database (but it can be hardcoded as a passed_param)
        $this->pattern					=	($this->params['pattern']) ? $this->params['pattern'] : $this->route['pattern'];

        # this is in the cache for a long time, as we won't be updating often, but we need to make sure slashes are trimmed
        $this->page_data				=	$gMysql->queryRow("select * from sm_page where ref = '$this->pattern'",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL);

        #if the page data does not
        if	(!empty($this->page_data))
        {
            $this->page_title				=	htmlspecialchars_decode($this->page_data['title']);
            $this->page_subtitle			=	html_entity_decode($this->page_data['subtitle']);
            $this->page_meta_description	=	html_entity_decode($this->page_data['meta_desc']);
            $this->page_meta_title			=	html_entity_decode($this->page_data['meta_title']);
            $this->page_meta_keywords		=	htmlspecialchars_decode($this->page_data['meta_kw']);
            $this->page_content				=	htmlspecialchars_decode($this->page_data['content']);
            $this->page_meta_breadcrumb		=	htmlspecialchars_decode($this->page_data['meta_breadcrumb_text']);

			$this->page_title			=	(!empty($this->route['page_title'])) 			?  $this->route['page_title']			:	htmlspecialchars_decode($this->page_data['title']);
			$this->page_subtitle		=	(!empty($this->route['page_subtitle']))			?  $this->route['page_subtitle']		:	html_entity_decode($this->page_data['subtitle']);
			$this->page_meta_title		=	(!empty($this->route['page_meta_title'])) 		?  $this->route['page_meta_title']		:	html_entity_decode($this->page_data['meta_title']);
			$this->page_meta_breadcrumb	=	(!empty($this->route['page_meta_breadcrumb'])) 	?  $this->route['page_meta_breadcrumb']	:	htmlspecialchars_decode($this->page_data['meta_breadcrumb_text']);


        }
        # we can grab the data from the passed route
        else
        {
            $this->page_title			=	(!empty($this->route['passed_params']['page_title'])) ?  $this->route['passed_params']['page_title']	:	"";
            $this->page_subtitle		=	(!empty($this->route['passed_params']['page_subtitle'])) ?  $this->route['passed_params']['page_subtitle']	:	"";
            $this->page_meta_title		=	(!empty($this->route['passed_params']['page_meta_title'])) ?  $this->route['passed_params']['page_meta_title']	:	"";
            $this->page_meta_breadcrumb	=	(!empty($this->route['passed_params']['page_meta_breadcrumb'])) ?  $this->route['passed_params']['page_meta_breadcrumb']	:	"";

        }




        # this removes some automatic color styling
        $this->page_content				=	str_replace("color: rgb(0, 0, 0)","",$this->page_content);



        # associated links
        $links_string					=	$this->getLinks($this->page_data['id']);

		$mvc_banner_1					=	$this->page_data['mvc_banner_1'];

		if (empty($this->page_data['mvc_banner_1']))
		{
			$banner		=	"bl-test-banner.jpg";
		}
		else
		{
			$banner		=	$this->page_data['mvc_banner_1'];
		}

		$canonical						=	WEBSITE_CANONICAL	.	$this->pattern;


		$login_link						=	"<a href='#' onclick='showLoginForm();' class='btn btn-sm btn-blue '  style='z-index:99; float:right;'>Account Login&nbsp;&nbsp;<i class='fa fa-user-circle fa-lg' aria-hidden='true'></i></a>";
		# login check
		if (isset($_SESSION['user']['id']))
		{
			if ($_SESSION['user']['id'] != '')
			{
				$login_link			=	"<a href='/secure_client_area' class='btn btn-sm btn-success'  style='z-index:99; float:right;'>My Account &nbsp;&nbsp;<i class='fa fa-user-circle fa-lg' aria-hidden='true'></i></a>";
			}
		}

		$loginRememberMe	=	"";
		if(isset($_COOKIE["loginRememberMe"]))
		{
			$loginEmail			=	 $_COOKIE["loginRememberMe"];
			$loginRememberMe	=	 " checked";
		}


		$summary_benefits	=	"<div class='bl-products-page-summary'>Summary Benefits</div>";
		$key_benefits		=	"<div class='bl-products-page-benefits'>Key Benefits</div>";
		$pricing			=	"<div class='bl-products-page-pricing'>Pricing</div>";


		$feature_title		=	$this->page_data['feature_title'];
		$feature_content	=	$this->page_data['feature_content'];
		$feature_image		=	$this->page_data['feature_banner'];
		$feature_link		=	$this->page_data['feature_link'];

		$feature_banner		=	 "/app/images/banners/" . $feature_image;

		$feature_box	=	"";

		#
		if (!empty($feature_link))
		{

			$feature_box	=	"
		<div class='col-xs-12 bl-feature-snippet' >

			<div class='col-xs-12 col-sm-4 hidden-xs'  >
				<img class='img-responsive bl-feature-image' src='$feature_banner'>
			</div>

		<div class='col-xs-12 col-sm-8 bl-feature-text' >
			<div class='bl-feature-title'>$feature_title</div>
			<div class='bl-feature-description'>$feature_content</div>
			<br>
			<br>
		</div>
		<div class='bl-feature-read-more'  ><a class='btn btn-dark btn-sm' href='$feature_link'>Read more...</a></div>

		</div>";

		}



		$mailto_link					=	"";

        # initial breadcrumb
        $breadcrumb_link	=	'<a href="/">Home</a>';

        if (!empty($this->page_meta_breadcrumb))
		{
	        $breadcrumb_link	.=	" > " . $this->page_meta_breadcrumb . "";
		}
		else
		{
			$breadcrumb_link	.=	" > " . $this->page_title . "";
		}

		# this will create slider or banner and append tags
		$this->createSlider();




		$this->policy_code			=	GetVariableString("code", $this->params);

		# this simpler version assumes there are no company-specific texts
		if (($this->pPolicyText	= 	getPolicyText($this->policy_code)) != NULL)
		{
			$this->logo		=	$this->pPolicyText['logo'];
		}
		else
		{
			$this->logo		=	"wk-logo-web-1.png";
		}



		# adds additional tags
        $tags			=	array(


			"{{schema_title}}"		    =>	stripNonAlphaNumericSpaces($this->page_title),
            "{{page_title}}"		    =>	$this->page_title,
			"{{meta_title}}"		    =>	$this->page_meta_title,
            "{{page_subtitle}}"		    =>	$this->page_subtitle,
            "{{page_content}}"		    =>	$this->page_content,
            "{{meta_description}}"	    =>	$this->page_meta_description,
            "{{meta_keywords}}"		    =>	$this->page_meta_keywords,
			"{{menu_string}}"		    =>	MENU_STRING,
			"{{mobile_menu_string}}"	=>	MOBILE_MENU_STRING,
            "{{links_string}}"		    =>	$links_string,
			"{{login_link}}"		    =>	$login_link,
			"{{loginEmail}}"		    =>	$loginEmail,
			"{{loginRememberMe}}"	    =>	$loginRememberMe,


            "{{mailto_link}}"		    =>	$mailto_link,
			"{{meta_canonical}}"		=>	$canonical,
			"{{website_canonical}}"		=>	WEBSITE_CANONICAL,


			"{{breadcrumb_link}}"		=>	$breadcrumb_link,
			"{{summary_benefits}}"		=>	$summary_benefits,
			"{{key_benefits}}"			=>	$key_benefits,
			"{{pricing}}"				=>	$pricing,
			"{{banner}}"				=>	$banner,

			"{{feature_box}}"			=>	$feature_box,
			"{{logo}}"					=>	$this->logo,



        );


        $this->appendTags($tags);

        # check for popup and append the old way
        $this->checkAppendPopup();


        # ultimately show the page
        parent::render();



    }


    # gets links associated with page
    private function getLinks($page_id)
    {
        global $gMysql;

        $links_array		=	$gMysql->selectToArray("select id,title,ref from sm_page where section_id !=0 and status='A' and id in (select link_id from sm_page_link where page_id='$page_id')",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL);
        $links_string		=	"";
        # page text
        if	(!empty($links_array))
        {
            $links_string	.=	"
			<div class='row col-xs-12 row_style'>
				<div class='col-xs-12 divider_dotted'></div>
				<div class='col-xs-12 '>Read more about...<br><br></div>
			</div>
			<div class='contentPageAlsoInterested' style='clear:both;'>
			<ul>";

            foreach ($links_array as $link)
            {
                $links_string	.=	"<li><a href='".$link['ref']."'>".$link['title']."'</a></li>";
            }

            $links_string	.="</ul></div>";
        }

        return	$links_string;


    }







    # this will create slider or banner and append tags as required
    protected function createSlider()
    {
        $slider_header	=	"";
        $slider_footer	=	"";
        $slider_page	=	"";

        $mvc_slider		=	$this->page_data['mvc_slider'];
        $mvc_banner_1	=	$this->page_data['mvc_banner_1'];
        $mvc_banner_2	=	$this->page_data['mvc_banner_2'];
        $mvc_banner_3	=	$this->page_data['mvc_banner_3'];

        # just a banner
        if	(strcasecmp($mvc_slider,'banner') == 0)
        {

            # banner is first image
            if	((strcasecmp($mvc_banner_1,"") != 0) && (strcasecmp($mvc_banner_1,"nothing") != 0))
            {
                $slider_page	=	"<div class='row-fluid' ><img class='sky-background lazy' data-original='/app/images/slider/" . $mvc_banner_1	. "'  width=1900 height=400></div>";
            }
            else if	((strcasecmp($mvc_banner_2,"") != 0) && (strcasecmp($mvc_banner_2,"nothing") != 0))
            {
                $slider_page	=	"<div class='row-fluid'><img class='sky-background lazy' data-original='/app/images/slider/" . $mvc_banner_2	. "'  width=1900 height=400></div>";
            }
            else if	((strcasecmp($mvc_banner_3,"") != 0) && (strcasecmp($mvc_banner_3,"nothing") != 0))
            {
                $slider_page	=	"<div class='row-fluid'><img class='sky-background lazy'  data-original='/app/images/slider/" . $mvc_banner_3	. "'  width=1900 height=400></div>";
            }



            $slider_header	=	"
			<script src='/app/includes/js/jquery.lazyload.js'></script>
			";

            $slider_footer	=	"
			$(function() {
				$('img.lazy').lazyload({

				effect : 'fadeIn'

				});
			});

			";


        }
        # this is for building a slider
        else if	(strcasecmp($mvc_slider,'slider') == 0)
        {

            if	((strcasecmp($mvc_banner_1,"") != 0) && (strcasecmp($mvc_banner_1,"nothing") != 0))
            {
                $slider_page	=	"<div class='kr-sky' data-duration='5000' data-transition='10'>
										<img class='sky-background' src='/app/images/slider/" . $mvc_banner_1	. "'>
									</div>";
            }
            if	((strcasecmp($mvc_banner_2,"") != 0) && (strcasecmp($mvc_banner_2,"nothing") != 0))
            {
                $slider_page	.=	"<div class='kr-sky' data-duration='5000' data-transition='10'>
										<img class='sky-background' src='/app/images/slider/" . $mvc_banner_2	. "'>
									</div>";
            }
            if	((strcasecmp($mvc_banner_3,"") != 0) && (strcasecmp($mvc_banner_3,"nothing") != 0))
            {
                $slider_page	.=	"<div class='kr-sky' data-duration='5000' data-transition='10'>
										<img class='sky-background' src='/app/images/slider/" . $mvc_banner_3	. "'>
									</div>";
            }

            # only set up the slider if there are images to show
            if	(!empty($slider_page))
            {
                $slider_page		=	"<div id='cloudslider'>" .$slider_page	.	"</div>";

                # header code
                $slider_header	=	"
				<!-- Cloud Slider Style Sheet -->
				<link rel='stylesheet' href='/app/includes/cloudslider/css/cloudslider.css' type='text/css'>
				<!-- Importing Google Fonts -->
				<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
				<!-- Cloud Slider Library -->
				<script src='/app/includes/cloudslider/js/cloudslider.jquery.min.js' type='text/javascript'></script>";

                # footer jScript
                $slider_footer	=	"
				$('#cloudslider').cloudSlider({
					width: 1900,
					height: 400,
					onHoverPause: false,
					fullWidth: true
				});";


            }
        }

        # adds additional tags
        $tags			=	array(
            "{{slider_header}}"		=>	$slider_header,
            "{{slider_page}}"		=>	$slider_page,
            "{{slider_footer}}"		=>	$slider_footer,


        );
        $this->appendTags($tags);
    }







































    public function getSitemap()
    {

        $sitemap_string	=	$this->buildSitemap();


        # adds additional tags
        $tags			=	array(
            "{{sitemap}}"		=>	$sitemap_string,

        );


        $this->appendTags($tags);

        $this->render();

    }



    # builds a sitemap like boxlegal
    private function buildSiteMap()
    {
        global $gMysql;

        $string				=	"";

        $menu_data			=	$gMysql->selectToArray("select * from sm_page where mvc_method != 'post' and section_id !=0 and status='A' and admin_only=0 and prod_slots=0 group by section_id order by section_id,prod_slots",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL);

        # grabs the list of sections
        #		select section_id from sm_page where mvc_method != 'post' and section_id !=0 and status='A' and admin_only=0 and prod_slots=0 group by section_id order by section_id,prod_slots

        $num				=	count($menu_data);

        # for each section
        for ($i=0;$i<$num;$i++)
        {
            $section_id		=	$menu_data[$i]['section_id'];

            $link			=	$menu_data[$i]['ref'];
            $title			=	$menu_data[$i]['title'];
            $tag			=	$menu_data[$i]['text_tag'];

            $string			.=	"<p><b><a href='$link'>$title</a></b></p>";


            # for airlines and airports, we want to grab all airport-related items

            # submenu
            $sub_menu_data			=	$gMysql->selectToArray("select * from sm_page where section_id='$section_id' and ref != '$link' and mvc_method != 'post' and status='A' and admin_only=0 and prod_slots!=0 order by section_id,prod_slots",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL);

            foreach ($sub_menu_data as $data)
            {
                $link			=	$data['ref'];
                $title			=	$data['title'];
                $tag			=	$data['text_tag'];

                # if we have a tag, hilite it
                if	(!empty($tag))
                {
                    $string			.=	"<br><p><b><a href='$link'>$title</a></b></p>";

                    # this also means there could be children
                    $children_data		=	$gMysql->selectToArray("select * from sm_page where text_tag='$tag' and ref != '$link' and mvc_method != 'post' and status='A' and admin_only=0 order by section_id,prod_slots",__FILE__,__LINE__,CACHE_CACHE_TIME_NORMAL);
                    foreach ($children_data as $data2)
                    {
                        $link			=	$data2['ref'];
                        $title			=	$data2['title'];
                        $string			.=	"<p><a href='$link'>$title</a></p>";
                    }
                    $string	.=	"<br>";
                }
                else
                {
                    $string			.=	"<p><a href='$link'>$title</a></p>";
                }



            }

            $string	.=	"<br>";

        }

        return	$string;


    }





}




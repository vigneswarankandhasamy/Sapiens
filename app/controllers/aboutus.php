<?php

class Aboutus extends Controller
{
	
	public function index()
	{		
		
		$user        = $this->model('Front');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='aboutus' AND is_draft='0' AND status='1' ");
		$info 	     = $user->getDetails(SEO_TBL,"*","page_token='aboutus'");
		$this->view('home/about', 
			[	
				'active_menu' 		=> 'aboutus',
				'meta_title'  		=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'page_title'  		=>  COMPANY_NAME,
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'page_banner'   	=>  $page_banner,
				'location' 			=>  $user->getLocationList(),
				'siteinfo' 			=>  $user->siteInfo($page_token="aboutus"),
			]);
		
	}

	public function mabout()
	{		
		
		$user = $this->model('Front');
		$info = $user->getDetails(SEO_TBL,"*","page_token='aboutus'");
		$this->view('home/mabout', 
			[	
				'active_menu' 		=> 'aboutus',
				'meta_title'  		=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'page_title'  	    =>  COMPANY_NAME,
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'location' 			=>  $user->getLocationList(),
				'siteinfo' 			=>  $user->siteInfo($page_token="aboutus"),
			]);
		
	}

	public function error()
	{	 
		$user = $this->model('Front');
		$seo_info = $user->getDetails(SEO_TBL,"*","page_token='error'");
		$this->view('home/error', 
			[	
				'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'   			=>  $user->cartInfo(),
				'location' 			=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo($page_token="error"),
				'page_title'  		=> '404 Error - Page Not Found'
				
			]);
	}
	
}

?>

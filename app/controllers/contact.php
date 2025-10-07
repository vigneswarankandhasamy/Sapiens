<?php

class Contact extends Controller
{
	
	public function index()
	{		
		$user = $this->model('Front');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='contact' AND is_draft='0' AND status='1' ");
		$info = $user->getDetails(SEO_TBL,"*","page_token='contactus'");
		$this->view('home/contactus', 
			[	
				'active_menu' 			=> 'Contact',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="contactus"),
				'page_banner'  			=>  $page_banner,
				'location' 				=>  $user->getLocationList(),
				'page_title'  			=>  COMPANY_NAME,
			]);
		
	}


	public function mcontact()
	{		
		$user = $this->model('Front');
		$info = $user->getDetails(SEO_TBL,"*","page_token='contactus'");
		$this->view('home/mcontact', 
			[	
				'active_menu' 			=> 'Contact',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo(),
				'location' 				=>  $user->getLocationList(),
				'page_title'  			=>  COMPANY_NAME,
			]);
		
	}

	public function workwithus()
	{
		$user = $this->model('Front');
		$info = $user->getDetails(SEO_TBL,"*","page_token='workwithus'");
		$this->view('home/workwithus', 
			[	
				'active_menu' 			=> 'Contact',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="workwithus"),
				'location' 				=>  $user->getLocationList(),
				'page_title'  			=>  COMPANY_NAME,
			]);
	}


	public function api($type)
	{
			$user  = $this->model('Front');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'contactUsInfo':
					echo $user->contactUsInfo($_POST);
				break;
				case 'workWithUsInfo':
					echo $user->workWithUsInfo($_POST);
				break;
				case 'subscriberInfo':
					echo $user->subscriberInfo($_POST);
				break;

				default:
				break;
		}
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

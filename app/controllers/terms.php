<?php

class Terms extends Controller
{
	
	public function index()
	{		
		
		$user = $this->model('Front');
		$info = $user->getDetails(SEO_TBL,"*","page_token='terms-conditions'");
		$this->view('home/terms', 
			[	
				'active_menu' 		=> 'Terms',
				'meta_title'  		=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'				=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'location' 			=>  $user->getLocationList(),
				'siteinfo' 			=>  $user->siteInfo($page_token="terms-conditions"),
				'page_title'  		=>  COMPANY_NAME,
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

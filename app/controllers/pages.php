<?php

class Pages extends Controller
{
	
	public function index()
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

	public function details($token="")
	{	
		$user = $this->model('Front'); 
		$check = $user->check_query(LEGAL_PAGE_TBL,"page_url"," page_url='".$token."' AND delete_status='0' AND status='1' AND is_draft='0' ");
        if($check){
            $info = $user->getDetails(LEGAL_PAGE_TBL,"*"," page_url='".$token."' AND delete_status='0' AND status='1' AND is_draft='0' ");
			$this->view('home/pages', 
			  	[   
				   	'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
					'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
					'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
					'meta'  				=> 'dynamic',
					'page_title'  			=>  COMPANY_NAME,
				    'info'					=>  $info,
				    'description'			=>  $user->publishContent($info['content']),
				    'legal_pages'			=>  $user->getLegalPages(),
					'cart'   				=>  $user->cartInfo(),
					'menu_items'			=>  $user->menuItems(),
					'location' 				=>  $user->getLocationList(),
					'siteinfo' 				=>  $user->siteInfo($page_token=$token),
			 	]);
		}else{
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


	public function mdetails($token="")
	{	
		$user = $this->model('Front'); 
		$check = $user->check_query(LEGAL_PAGE_TBL,"page_url"," page_url='".$token."' ");
        if($check){
            $info = $user->getDetails(LEGAL_PAGE_TBL,"*"," page_url='".$token."' ");
			$this->view('home/mpages', 
			  	[   
				   	'meta_title'  	=>  COMPANY_NAME.' | Blog',
					'page_title'  	=>  COMPANY_NAME,
				    'info'			=>  $info,
				    'description'	=>  $user->publishContent($info['content']),
				    'legal_pages'	=>  $user->getLegalPages(),
					'cart'   		=>  $user->cartInfo(),
					'menu_items'	=>  $user->menuItems(),
					'location' 		=>  $user->getLocationList(),
					'siteinfo' 		=>  $user->siteInfo(),
			 	]);
		}else{
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

	public function error()
	{
		
		$user = $this->model('Front');
		$info 	= $user->getDetails(SEO_TBL,"*","page_token='error'");
		$this->view('home/error', 
			[
				'meta_title'  	=> '404 Error - Page Not Found',
				'cart'   		=>  $user->cartInfo(),
				'location' 		=>  $user->getLocationList(),
				'legal_pages'	=>  $user->getLegalPages(),
				'menu_items'	=>  $user->menuItems(),
				'siteinfo' 		=>  $user->siteInfo(),
				'page_title'  	=> '404 Error - Page Not Found'
			]);
	}
	
}

?>

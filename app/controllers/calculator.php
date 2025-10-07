<?php

class Calculator extends Controller
{
	
	public function plastering()
	{		
		$user 			= $this->model('Front');
		$page_banner 	= $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='plastering' AND is_draft='0' AND status='1' ");
		$info    		= $user->getDetails(SEO_TBL,"*","page_token='plastering'");
		$this->view('home/plastering', 
			[	
				'active_menu' 			=> 'calculator',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="plastering"),
				'page_banner'			=>  $user->getPageBanner($page_token="plastering"),
				'location' 				=>  $user->getLocationList(),
				'related_products'  	=>  $user->getCalculatorRelatedproductsList("plastering"),
 				'page_title'  			=>  COMPANY_NAME
			]);
		
	}

	public function brickwork()
	{		
		$user 			= $this->model('Front');
		$page_banner 	= $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='brickwork' AND is_draft='0' AND status='1' ");
		$info        	= $user->getDetails(SEO_TBL,"*","page_token='brickwork'");
		$this->view('home/brickwork', 
			[	
				'active_menu' 			=> 'calculator',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="brickwork"),
				'page_banner'			=>  $user->getPageBanner($page_token="brickwork"),
				'location' 				=>  $user->getLocationList(),
				'related_products'  	=>  $user->getCalculatorRelatedproductsList("brickwork"),
 				'page_title'  			=>  COMPANY_NAME
			]);
		
	}

	public function concrete()
	{		
		$user 			= $this->model('Front');
		$page_banner 	= $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='concrete' AND is_draft='0' AND status='1' ");
		$info        	= $user->getDetails(SEO_TBL,"*","page_token='concrete'");
		$this->view('home/concrete', 
			[	
				'active_menu' 			=> 'calculator',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="concrete"),
				'page_banner'			=>  $user->getPageBanner($page_token="concrete"),
				'location' 				=>  $user->getLocationList(),
				'related_products'  	=>  $user->getCalculatorRelatedproductsList("concrete"),
 				'page_title'  			=>  COMPANY_NAME
			]);
		
	}

	public function painting()
	{		
		$user 			= $this->model('Front');
		$page_banner 	= $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='painting' AND is_draft='0' AND status='1' ");
		$info        	= $user->getDetails(SEO_TBL,"*","page_token='painting'");
		$this->view('home/wallfinish', 
			[	
				'active_menu' 			=> 'calculator',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="painting"),
				'page_banner'			=>  $user->getPageBanner($page_token="painting"),
				'location' 				=>  $user->getLocationList(),
				'related_products'  	=>  $user->getCalculatorRelatedproductsList("painting"),
 				'page_title'  			=>  COMPANY_NAME
			]);
		
	}

	public function floring()
	{		
		$user 			= $this->model('Front');
		$page_banner 	= $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='floring' AND is_draft='0' AND status='1' ");
		$info        	= $user->getDetails(SEO_TBL,"*","page_token='floring'");
		$this->view('home/tilework', 
			[	
				'active_menu' 			=> 'calculator',
				'meta_title'  			=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  		=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description'  	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  				=> 'dynamic',
				'cart'   				=>  $user->cartInfo(),
				'legal_pages'			=>  $user->getLegalPages(),
				'menu_items'			=>  $user->menuItems(),
				'siteinfo' 				=>  $user->siteInfo($page_token="floring"),
				'page_banner'			=>  $user->getPageBanner($page_token="floring"),
				'location' 				=>  $user->getLocationList(),
				'related_products'  	=>  $user->getCalculatorRelatedproductsList("floring"),
 				'page_title'  			=>  COMPANY_NAME
			]);
		
	}

	public function api($type)
	{
			$user  	= $this->model('Front');
			$post 	= @$_POST["result"];	
			switch ($type) {
				case 'plasterCalculation':
					echo $user->plasterCalculation($_POST);
				break;
				case 'brickWorkClaculation':
					echo $user->brickWorkClaculation($_POST);
				break;
				case 'concreteCalculation':
					echo $user->concreteCalculation($_POST);
				break;
				case 'tileWorkCalculation':
					echo $user->tileWorkCalculation($_POST);
				break;
				case 'wallFinishCalculation':
					echo $user->wallFinishCalculation($_POST);
				break;

				default:
				break;
		}
	}
	
	public function error()
	{	 
		$user 		= $this->model('Front');
		$seo_info 	= $user->getDetails(SEO_TBL,"*","page_token='error'");
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
				'siteinfo' 			=>  $user->siteInfo($page_token='error'),
				'page_title'  		=> '404 Error - Page Not Found'
				
			]);
	}


	
}?>

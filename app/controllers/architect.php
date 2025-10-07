<?php

class architect extends Controller
{
	
	public function index()
	{		
		$user = $this->model('Front');
		$count = $user->architectPaginationCount();
			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."architect?p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."architect?p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}
		$this->view('home/contractors', 
			[	
				'active_menu' 	=> 'architect',
				'meta_title'  	=>  COMPANY_NAME.' | Architect',
				'cart'   		=>  $user->cartInfo(),
				'legal_pages'	=>  $user->getLegalPages(),
				'profile_type'	=>  'architect',
				'menu_items'	=>  $user->menuItems(),
				'siteinfo' 		=>  $user->siteInfo(),
				'contractors'	=>  $user->getArchitectList($page,$search_key=""),
				'count'			=>  $count,
				'previous' 		=>  $previous,
				'next' 			=>  $next,
				'location' 		=>  $user->getLocationList(),
				'page' 			=>  $user->architectPagination($page),
 				'page_title'  	=>  COMPANY_NAME
			]);
		
	}
	
	public function details($token="")
	{	
		$user = $this->model('Front');
		$check = $user->check_query(CONTRACTOR_TBL,"id"," token='".$token."' AND profile_type='2' ");
        if($check){
            $info = $user->getDetails(CONTRACTOR_TBL,"*"," token='".$token."' AND profile_type='2' ");
            $pic  = $info['file_name']!="" ? SRCIMG.$info['file_name']: ASSETS_PATH."logo.png" ;

			$this->view('home/contractordetails',
			 	[   
				    'active_menu' 	=> 'architect',
					'meta_title'  	=>  COMPANY_NAME.' | Architect',
					'cart'   		=>  $user->cartInfo(),
					'legal_pages'	=>  $user->getLegalPages(),
					'service_tags'	=>  $user->getServiceTags($info['service_tags']),
					'projects_imgs'	=>  $user->getProjectImgs($info['id']),
					'profile_type'	=>  'architect',
					'menu_items'	=>  $user->menuItems(),
					'siteinfo' 		=>  $user->siteInfo(),
					'page_title'  	=>  COMPANY_NAME,
					'location' 		=>  $user->getLocationList(),
					'token'			=>  $user->encryptData($info['id']),
				    'info' 			=>  $info,
				    'pic' 			=>  $pic,
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
	public function search($search_key="")
	{		
		$user = $this->model('Front');
		$count = $user->architectPaginationCount($profile="2");
			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."architect/search/".$search_key."?p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."architect/search/".$search_key."?p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}
		$this->view('home/contractors', 
			[	
				'active_menu' 	=> 'architect',
				'meta_title'  	=>  COMPANY_NAME.' | Architect',
				'cart'   		=>  $user->cartInfo(),
				'legal_pages'	=>  $user->getLegalPages(),
				'profile_type'	=>  'architect',
				'menu_items'	=>  $user->menuItems(),
				'siteinfo' 		=>  $user->siteInfo(),
				'contractors'	=>  $user->getArchitectList($page,$search_key),
				'count'			=>  $count,
				'previous' 		=>  $previous,
				'next' 			=>  $next,
				'location' 		=>  $user->getLocationList(),
				'page' 			=>  $user->architectPagination($page),
 				'page_title'  	=>  COMPANY_NAME
			]);
		
	}

	public function api($type)
	{
		$user  = $this->model('Front');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'contractorEnquiry':
					echo $user->contractorEnquiry($_POST);
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

<?php

class hire extends Controller
{
	
	public function index($token="")
	{		
		$user 	= $this->model('Front');
		$count 	= $user->contractorProfilePaginationCount();
			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."hire?p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."hire?p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}
		
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='hirelist' AND is_draft='0' AND status='1' ");
		$info 		 = $user->getDetails(SEO_TBL,"*","page_token='hirelist'");

		$this->view('home/classifieds', 
			[	
				'active_menu' 	   => 'classified',
				'meta_title'  	   =>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'    =>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' =>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  		   => 'dynamic',
				'cart'   		   =>  $user->cartInfo(),
				'legal_pages'	   =>  $user->getLegalPages(),
				'menu_items'	   =>  $user->menuItems(),
				'siteinfo' 		   =>  $user->siteInfo($page_token="hirelist"),
				'calssified_btns'  =>  $user->getClassifiedBtns(),
				'contractors'	   =>  $user->getClassifiedList($token,$page,$search_key=""),
				'profile_type'	   =>  'Hire List',
				'search_key'	   =>  '0',
				'count'			   =>  $count,
				'previous' 		   =>  $previous,
				'next' 			   =>  $next,
				'page_banner'	   =>  $user->getPageBanner($page_token="hirelist"),
				'location' 		   =>  $user->getLocationList(),
				'page' 			   =>  $user->contractorProfilePagination($page),
 				'page_title'  	   =>  COMPANY_NAME
			]);
		
	}

	public function hirelist($token="")
	{		
		$user = $this->model('Front');


		if($token!="") {
			$check = $user->check_query(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
			$url_token = "/".$token;
		} else {
			$check = 1;
			$url_token = "";
		}

		if($check) {
			$count = $user->contractorPaginationCount($token);
			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."hire/hirelist".$url_token."?p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."hire/hirelist".$url_token."?p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}


			$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='hirelist' AND is_draft='0' AND status='1' ");
			$info 		 = $user->getDetails(SEO_TBL,"*","page_token='hirelist'");

			$this->view('home/classifiedlist', 
			[	
				'active_menu' 	        => 'classified',
				'meta_title'  		    =>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	    =>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	    =>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			    => 'dynamic',
				'cart'   		        =>  $user->cartInfo(),
				'legal_pages'	        =>  $user->getLegalPages(),
				'menu_items'	        =>  $user->menuItems(),
				'siteinfo' 		        =>  $user->siteInfo($page_token="hirelist"),
				'token'			        =>  $token,
				'calssified_btns'       =>  $user->getClassifiedBtns($token),
				'contractors'	        =>  $user->getHireList($token,$page,$search_key=""),
				'page_banner'  			=>  $user->getPageBanner($page_token="hirelist"),
				'best_seller_products'  =>  $user->getBestSellerProductCarouselList(),
				'most_viewed_products'  =>  $user->getMostViewedProductCarouselList(),
				'profile_type'	    	=>  'classifieds',
				'search_key'	    	=>  '0',
				'count'			    	=>  $count,
				'previous' 		    	=>  $previous,
				'next' 			    	=>  $next,
				'location' 		    	=>  $user->getLocationList(),
				'page' 			    	=>  $user->contractorPagination($page,$token,$search_key="",$search_key_link="",$url_token),
 				'page_title'  	    	=>  COMPANY_NAME
			]);
	    } else {
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

	public function details($token="")
	{	
		$user 	= $this->model('Front');
		$check 	= $user->check_query(CONTRACTOR_TBL,"id"," token='".$token."'");
        if($check){
            $info 	 = $user->getDetails(CONTRACTOR_TBL,"*"," token='".$token."'");
            $cp_info = $user->getDetails(CONTRACTOR_PROFILE_TBL,"*"," id='".$info['profile_type']."'");
            $pic  	 = $info['file_name']!="" ? SRCIMG.$info['file_name']: ASSETS_PATH."logo.png" ;

            $profile = explode(",",$info['profile_type']);
  			$profile_types = "";

  			foreach ($profile as $key => $value) {
    			$profile_date   = $user->getDetails(CONTRACTOR_PROFILE_TBL,"*","id='".$value."' ");
    			$Comma          = (($key==0)? "" : ",");
  				$profile_types .= $Comma." ".$user->unHyphenize($profile_date['token']);
  			}
  			
  			$project  	= $user->getDetails(CONTRACTOR_PROJECT_TBL,"count(*) as id"," contractor_id='".$info['id']."' AND delete_status='0' AND status='1' ");
			
			$seo_info 	 = $user->getDetails(SEO_TBL,"*","page_token='hiredetails'");

			$this->view('home/classifieddetails',
			 	[   
				    'active_menu' 		    => 'classified',
					'meta_title'  		    =>  $seo_info['meta_title'].' | '.COMPANY_NAME,
					'meta_keywords'  	    =>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
					'meta_description' 	    =>  $seo_info['meta_description'].' | '.COMPANY_NAME,
					'meta'  			    => 'dynamic',
					'cart'   			    =>  $user->cartInfo(),
					'legal_pages'		    =>  $user->getLegalPages(),
					'service_tags'		    =>  $user->getServiceTags($info['service_tags']),
					'projects_imgs'		    =>  $user->getProjectSliderImgs($info['id']),
					'project_list'  	    =>  $user->getClassifiedProjects($info['id']),
					'menu_items'		    =>  $user->menuItems(),
					'siteinfo' 			    =>  $user->siteInfo(),
					'offer_banner'          =>  $user->getofferBanner(),
					'best_seller_products'  =>  $user->getBestSellerProductCarouselList(),
					'page_banner'  			=>  $user->getPageBanner($page_token='hiredetails'),
					'profile_type'		    =>  'hire',
					'project' 			    =>  $project,
					'profile_types'		    =>  $profile_types,
					'info' 				    =>  $info,
					'cp_info' 			    =>  $cp_info,
					'page_title'  		    =>  COMPANY_NAME,
					'location' 			    =>  $user->getLocationList(),
					'token'				    =>  $user->encryptData($info['id']),
				    'info' 				    =>  $info,
				    'pic' 				    =>  $pic,
			 	]);
		}else{
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
					'siteinfo' 			=>  $user->siteInfo($page_token="error"),
					'page_title'  		=> '404 Error - Page Not Found'
					
				]);
		}
			
	}
	public function search()
	{		
		$user = $this->model('Front');

			$search_key_link        = "";
			$search_key             = array();

			if(isset($_GET['hire'])) {
				$search_key['hire'] = $_GET['hire'];
				$search_key_link    = "?hire=".$_GET['hire'];
			} 

			if(isset($_GET['experience'])) {
				$search_key['experience'] = $_GET['experience'];
				if(isset($_GET['hire'])) {
					$q_or_and       = "&";
				} else {
					$q_or_and       = "?";
				}
				$search_key_link   .= $q_or_and."experience=".$_GET['experience'];
			}

			if(isset($_GET['location'])) {
				$search_key['location'] = $_GET['location'];
				if(isset($_GET['hire']) || isset($_GET['experience']) ) {
					$q_or_and             = "&";
				} else {
					$q_or_and             = "?";
				}
				$search_key_link    	 .= $q_or_and."location=".$_GET['location'];
			}


			if(isset($_GET['hire']) && isset($_GET['location']) && isset($_GET['experience']))
			{
				$search_key['hire']       = $_GET['hire'];
				$search_key['location']   = $_GET['location'];
				$search_key['experience'] = $_GET['experience'];
				$search_key_link          = "?hire=".$_GET['hire']."&location=".$_GET['location']."&experience='".$_GET['experience']."'";
			}

			$count = $user->contractorSearchPaginationCount($page="",$search_key);

			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."hire/search".$search_key_link."&p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."hire/search".$search_key_link."&p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}

			$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='hiresearch' AND is_draft='0' AND status='1' ");
			$seo_info 	 = $user->getDetails(SEO_TBL,"*","page_token='hirelist'");

			$this->view('home/classifiedlist', 
			[	
				'active_menu' 		=> 'classified',
				'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	=>  $seo_info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'   		    =>  $user->cartInfo(),
				'legal_pages'	    =>  $user->getLegalPages(),
				'menu_items'	    =>  $user->menuItems(),
				'siteinfo' 		    =>  $user->siteInfo(),
				'calssified_btns'   =>  $user->getClassifiedBtns($token="",$page,$search_key),
				'contractors'	    =>  $user->getHireSearchList($token="",$page,$search_key),
				'profile_type'	    =>  'classified',
				'search_key'	    =>  $search_key,
				'count'			    =>  $count,
				'location' 		    =>  $user->getLocationList(),
				'previous' 		    =>  $previous,
				'next' 			    =>  $next,
				'page_banner' 	    =>  $page_banner,
				'page' 			    =>  $user->contractorSearchPagination($page,$token="",$search_key,$search_key_link),
 				'page_title'  	    =>  COMPANY_NAME
			]);
		
	}

	public function api($type)
	{
		$user  = $this->model('Front');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'classifiedEnquiry':
					echo $user->classifiedEnquiry($_POST);
				break;
				case 'viewdClassifiedDetails':
					echo $user->viewdClassifiedDetails($_POST);
				break;
				case 'projectInfo':
					echo $user->projectInfo($post);
				break;
				case 'profileSearchItems':
					echo $user->profileSearchItems($post);
				break;
				case 'locationSearchItems':
					echo $user->locationSearchItems($post);
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
				'siteinfo' 			=>  $user->siteInfo($page_token="error"),
				'page_title'  		=> '404 Error - Page Not Found'
				
			]);
	}
	
}

?>

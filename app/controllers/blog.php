<?php

class Blog extends Controller
{
	
	public function index()
	{		
		$user = $this->model('Front');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='blog' AND is_draft='0' AND status='1' ");
		$count = $user->blogsPaginationCount("");
			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."blog?p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."blog?p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}
		$info = $user->getDetails(SEO_TBL,"*","page_token='blog'");
		$this->view('home/blog', 
			[	
				'active_menu' 		=> 'blog',
				'meta_title'  		=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo($page_token="blog"),
				'blog_list'			=>  $user->getBlogList($page,$category="",$search_key=""),
				'recent_blogs'		=>  $user->getRecentBlogs(),
				'blog_category' 	=>  $user->getBlogCategories(),
				'previous' 			=>  $previous,
				'next' 				=>  $next,
				'count'				=>  $count,
				'page_banner'   	=>  $page_banner,
				'location' 			=>  $user->getLocationList(),
				'page' 				=>  $user->blogsPagination($page,$category="",$search_key=""),
 				'page_title'  		=>  COMPANY_NAME
			]);
		
	}
	public function search($search_key="")
	{		
		$user = $this->model('Front');
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='blog' AND is_draft='0' AND status='1' ");
		$count = $user->blogsPaginationCount("");
			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."blog/search/".$search_key."?p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."blog/search/".$search_key."?p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}
		$info = $user->getDetails(SEO_TBL,"*","page_token='blogsearch'");
		$this->view('home/blog', 
			[	
				'active_menu' 		=> 'blog',
				'meta_title'  		=>  $info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	=>  $info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo($page_token="blogsearch"),
				'blog_list'			=>  $user->getBlogList($page,$category="",$search_key),
				'recent_blogs'		=>  $user->getRecentBlogs(),
				'blog_category' 	=>  $user->getBlogCategories(),
				'previous' 			=>  $previous,
				'next' 				=>  $next,
				'page_banner'   	=>  $page_banner,
				'location' 			=>  $user->getLocationList(),
				'page' 				=>  $user->blogsPagination($page,$category="",$search_key),
 				'page_title'  		=>  COMPANY_NAME
			]);
		
	}


	public function details($token="")
	{	
		$user = $this->model('Front');
		$check = $user->check_query(BLOG_TBL,"id"," page_url='".$token."' AND is_draft='0' AND delete_status='0' AND status='1' ");
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='blog' AND is_draft='0' AND status='1' ");
        if($check){
            $info = $user->getDetails(BLOG_TBL,"*"," page_url='".$token."' AND is_draft='0' AND delete_status='0' AND status='1' ");
            $cat  = $user->getDetails(BLOG_CATEGORY_TBL,"id,category"," id='".$info['category_id']."' ");
            $pic  = $info['file_name']!="" ? SRCIMG.$info['file_name']: ASSETS_PATH."no_img.jpg";
			$seo_info = $user->getDetails(SEO_TBL,"*","page_token='blogdetails'");
			$this->view('home/blogdetails',
			 	[   
				    'active_menu' 		=> 'blog',
					'meta_title'  		=>  $info['meta_title'].' | '.COMPANY_NAME,
					'meta_keywords'  	=>  $info['meta_keyword'].' | '.COMPANY_NAME,
					'meta_description' 	=>  $info['meta_description'].' | '.COMPANY_NAME,
					'meta'  			=> 'dynamic',
					'cart'   			=>  $user->cartInfo(),
					'legal_pages'		=>  $user->getLegalPages(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo($page_token="blogdetails"),
					'page_title'  		=>  COMPANY_NAME,
				    'info' 				=>  $info,
				    'pic' 				=>  $pic,
				    'cat' 				=>  $cat,
				    'location' 			=>  $user->getLocationList(),
					'page_banner'   	=>  $page_banner,
				    'related_blogs'		=>  $user->getRelatedBlogs($info['category_id']),
				    'recent_blogs'		=>  $user->getRecentBlogs(),
					'blog_category'		=>  $user->getBlogCategories(),
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

	public function category($token="")
	{		
		$user = $this->model('Front');
        $info = $user->getDetails(BLOG_CATEGORY_TBL,"*"," token='$token' ");
		$page_banner = $user->getDetails(PAGE_BANNER_TBL,"*"," page_token='blog' AND is_draft='0' AND status='1' ");

		$count = $user->blogsPaginationCount($info['id']);
			if (isset($_GET['p'])) {
				$page = @$_GET['p'];
			}else{
				$page = 1;
			}
			if ($page==1) {
				$previous = "javascript:void();";
			}else {
				$previous = BASEPATH."blog/category/".$token."?p=".($page-1);
			}
			if ($page < $count) {
				$next = BASEPATH."blog/category/".$token."?p=".($page+1);			
			}else {
				$next = "javascript:void();";
			}

		$check = $user->check_query(BLOG_CATEGORY_TBL,"id"," token='$token' ");

		if($check){
            $info = $user->getDetails(BLOG_CATEGORY_TBL,"*"," token='$token' ");
			$seo_info = $user->getDetails(SEO_TBL,"*","page_token='blogcategory'");
            $this->view('home/blog', 
			[	
				'active_menu' 		=> 'blog',
				'meta_title'  		=>  $seo_info['meta_title'].' | '.COMPANY_NAME,
				'meta_keywords'  	=>  $seo_info['meta_keyword'].' | '.COMPANY_NAME,
				'meta_description' 	=>  $seo_info['meta_description'].' | '.COMPANY_NAME,
				'meta'  			=> 'dynamic',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo($page_token="blogcategory"),
				'blog_list'			=>  $user->getBlogList($page,$info['id'],$search_key=""),
				'previous' 			=>  $previous,
				'next' 				=>  $next,
				'count' 			=>  $count,
				'page_banner'   	=>  $page_banner,
				'location' 			=>  $user->getLocationList(),
				'page' 				=>  $user->blogsPagination($page,$info['id'],$search_key=""),
				'recent_blogs'		=>  $user->getRecentBlogs(),
				'blog_category' 	=>  $user->getBlogCategories(),
 				'page_title'  		=>  COMPANY_NAME
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

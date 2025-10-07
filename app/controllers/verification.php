<?php

class Verification extends Controller
{
	
	// Default Method

	public function index()
	{
		if(isset($_GET['t'])){
			$token 	= $_GET['t'];
			$user 	= $this->model('User');
			$check 	= $user->check_query(CUSTOMER_TBL,"id", " token='".$token."'");
			if($check ==1){
				$info 			= $user->getDetails(CUSTOMER_TBL,"email", " token='".$token."' ");
				$check_reentry 	= $user->check_query(CUSTOMER_TBL,"id", " token='".$token."' AND email_verify='0' AND status='1' ");
				if($check_reentry==1){
					$validate = "ok";
				}else{
					$validate = "duplicate";
					header('Location:'.BASEPATH);
				}
			}else{
				$validate = "fail";
			}
			$this->view('home/verification',
				[
					'meta_title'	=>	'Account Verfication - '.COMPANY_NAME,
					'meta' 			=>  'static',
					'validate'		=>	$validate, 
					'token_name' 	=>	$user->findTempName($token),
					'cart'   		=>  $user->cartInfo(),
					'menu_items'	=>  $user->menuItems(),
					'location' 		=>  $user->getLocationList(),
					'legal_pages'	=>  $user->getLegalPages(),
					'siteinfo' 		=>  $user->siteInfo()
				]);			
		}else{
			header('Location:'.BASEPATH."register");
			exit;
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

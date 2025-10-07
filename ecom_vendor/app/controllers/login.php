<?php

class Login extends Controller
{
	
	public function index()
	{
		$user = $this->model('Admin');
		if (isset($_SESSION["ecom_vendor_id"])) {
			header("Location: ".COREPATH);
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function reset($vendor_token="",$token="")
	{	
		$user = $this->model('Admin');
		if(isset($_SESSION["ecom_vendor_id"])){
			header("Location: ".BASEPATH);
		} else {
			$decrypt = $user->decryptData($vendor_token);
			$check 		= $user->check_query(VENDOR_FORGOT_PASSWORD,"id","vendor_token ='".$decrypt."' AND token = '".$token."' AND update_status ='0' ");
			if ($check==1) {
				if(!isset($_SESSION['user_reset_password'])){
					$_SESSION['user_reset_password'] = $decrypt;
				}	
				$this->view('home/resetpassword', 
				[	
					'active_menu' 		=> 'login',
					'meta_title'  		=> 'User Login',
					'meta_keywords'  	=> 'User Login',
					'meta_description'  => 'User Login',
					'meta' 				=> 'static',
				]);
			} else {
				$user = $this->model('User');
				$this->view('home/error', 
				[	
					'meta_title'  		=>  '404 Error - Page Not Found',
					'meta_keywords'  	=>  '404 Error - Page Not Found',
					'meta_description'  =>  '404 Error - Page Not Found',
					'meta' 				=> 'static',
					'cart'   			=>  $user->cartInfo(),
					'location' 			=>  $user->getLocationList(),
					'legal_pages'		=>  $user->getLegalPages(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo()
				]);
			}
			
			
		}
	}

	public function error()
	{
		$this->view('home/error', 
		[	
			'meta_title'  	=> '404 Error - Page Not Found',
		]);
	}

}


?>
<?php

class Login extends Controller
{
	
	public function index()
	{	
		$user = $this->model('User');
		if(isset($_SESSION["user_session_id"])){
			header("Location: ".BASEPATH);
		}
		$this->view('home/login', 
			[	
				'active_menu' 		=> 'login',
				'meta_title'  		=> 'User Login',
				'meta_keywords'  	=> 'User Login',
				'meta_description'  => 'User Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'location'	 		=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo()
				
			]);
	}

	public function register()
	{	
		$user = $this->model('User');
		if(isset($_SESSION["user_session_id"])){
			header("Location: ".BASEPATH);
		}
		$this->view('home/register', 
			[	
				'active_menu' 		=> 'login',
				'meta_title'  		=> 'User Login',
				'meta_keywords'  	=> 'User Login',
				'meta_description'  => 'User Login',
				'meta' 				=> 'static',
				'cart'   			=>  $user->cartInfo(),
				'location'	 		=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo()
				
			]);
	}
	public function classifiedregister()
	{	
		$user = $this->model('User');
		if(isset($_SESSION["user_session_id"])){
			header("Location: ".BASEPATH);
		}
		$this->view('home/classifiedregister', 
			[	
				'active_menu' 		=> 'login',
				'meta_title'  		=> 'User Login',
				'meta_keywords'  	=> 'User Login',
				'meta_description'  => 'User Login',
				'meta' 				=> 'static',
				'cart'   			=>  $user->cartInfo(),
				'location'	 		=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo()
				
			]);
	}
	
	public function forgotpassword()
	{	
		$user = $this->model('User');
		$this->view('home/forgotpassword', 
			[	
				'active_menu' 		=> 'forgotpassword',
				'meta_title'  		=> 'User Login',
				'meta_keywords'  	=> 'User Login',
				'meta_description'  => 'User Login',
				'meta' 				=>  'static',
				'cart'   			=>  $user->cartInfo(),
				'location' 			=>  $user->getLocationList(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo()
			]);
	}
	


	public function reset($user_token="",$token="")
	{	
		$user = $this->model('User');
		if(isset($_SESSION["user_session_id"])){
			header("Location: ".BASEPATH);
		}else {
			$decrypt = $user->decryptData($user_token);
			$check 		= $user->check_query(FORGOT_PASSWORD,"id","user_token ='".$decrypt."' AND token = '".$token."' AND update_status ='0' ");
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
					'cart'   			=>  $user->cartInfo(),
					'legal_pages'		=>  $user->getLegalPages(),
					'location' 			=>  $user->getLocationList(),
					'menu_items'		=>  $user->menuItems(),
					'siteinfo' 			=>  $user->siteInfo()
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
		$user = $this->model('User');
		$this->view('home/error', 
			[	
				'meta_title'  		=>  '404 Error - Page Not Found',
				'meta_keywords'  	=>  '404 Error - Page Not Found',
				'meta_description'  =>  '404 Error - Page Not Found',
				'meta' 				=> 'static',
				'cart'   			=>  $user->cartInfo(),
				'legal_pages'		=>  $user->getLegalPages(),
				'menu_items'		=>  $user->menuItems(),
				'siteinfo' 			=>  $user->siteInfo()
			]);
	}


	public function api($type)
	{
		if(isset($_SESSION["user_session_id"])){
			header("Location: ".BASEPATH);
		}else {
			$user  = $this->model('User');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'userlogin':
					echo $user->userLogin($_POST);
				break;
				case 'userregister':
					echo $user->userRegister($_POST);
				break;
				case 'classifiedregister':
					echo $user->classifiedRegister($_POST);
				break;
				case 'verifyUserAccount':
					echo $user->verifyUserAccount($post);
				break;
				case 'forgotpassword':
					echo $user->forgotPassword($_POST);
				break;
				case 'resetPassword':
					echo $user -> resetPassword($_POST);
				break;
				case 'resendCode':
					echo $user -> resendCode($post);
				break;
				case 'validateEmail':
					echo $user -> validateEmail($_POST);
				break;

				default:
				break;
			}
		}
	}

}


?>
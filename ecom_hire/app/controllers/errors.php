<?php
class Errors extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}
	
	public function error()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}
			
}

?>
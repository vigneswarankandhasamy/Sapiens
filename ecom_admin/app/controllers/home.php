<?php

class Home extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user               = $this->model('Admin');
			$check 		        = $user->checkPermissionPage(DASHBOARD); 
			$check_sitesettings = $user->checksitesettings(DASHBOARD);
			if ($check==1 && $check_sitesettings) {
				$this->view('home/index', 
					[	
						'active_menu' 	=>  'dashboard',
						'page_title'	=>  'Dashboard',
						'scripts'		=>  'dashboard',
						'user_info'		=>	$user->userInfo(),
						'card_data'		=>  $user->getOrderReportCardData(),
						'sitesettings'	=>	$user->filtersiteSettings()
					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings()
					]);
			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function error()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
					'sitesettings'	=>	$user->filtersiteSettings()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}


	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Admin');
			$post = @$_POST["result"];	
			switch ($type) {

				case 'getChartDatas':
					echo $user->getChartDatas($post);
				break;
				case 'getOrderTotals':
					echo $user->getOrderTotals($post);
				break;
				case 'getOrderAverage':
					echo $user->getOrderAverage($post);
				break;
				case 'getTotalOrders':
					echo $user->getTotalOrders($post);
				break;
				
				default:
				break;
			}
		}
	}

	public function logout()
	{
		if(isset($_SESSION['ecom_admin_id'])){
			$user = $this->model('Admin');
			if($user->sessionOut($_SESSION['ecom_admin_id'])){
				unset($_SESSION['ecom_admin_id']);
				// session_destroy();
			}	
		}
		header("Location:".COREPATH);
	}


}

?>

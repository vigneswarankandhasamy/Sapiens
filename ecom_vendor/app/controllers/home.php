<?php

class Home extends Controller
{
	
	public function index($from="",$to="")
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user 		= $this->model('Admin');
			// $check = $user->checkPermissionPage(DASHBOARD);
			$check = 1;

			if ($check==1) {
				$this->view('home/index', 
					[	
						'active_menu' 	=> 'dashboard',
						'page_title'	=> 'Dashboard',
						'scripts'		=> 'dashboard',
						'card_data'		=>  $user->getOrderReportCardData($from,$to,$home="1"),
						'InventoryList'	=>  $user->getVendorStockList(),
						'user_info'		=>	$user->userInfo(),
						'notification'  =>  $user->getOrderNotification(),
						'vendor_active' =>  $user->CheckVendorStatus()
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function api($type)
	{
		if(isset($_SESSION["ecom_vendor_id"])){
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
				case 'UpdateVendorStatus':
					 echo $user->UpdateVendorStatus($post);
				break;
				case 'CheckVendorStatus':
					 echo $user->CheckVendorStatus();
				break;
				
				default:
				break;
			}
		}
	}

	public function error()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
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

	public function logout()
	{
		if(isset($_SESSION['ecom_vendor_id'])){
			$user = $this->model('Admin');
			if($user->sessionOut($_SESSION['ecom_vendor_id'])){
				unset($_SESSION['ecom_vendor_id']);
				// session_destroy();
			}	
		}
		header("Location:".COREPATH);
	}


}

?>

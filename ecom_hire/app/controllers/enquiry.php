<?php

class enquiry extends Controller
{
	public function index($from="",$to="",$type="")
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user 				= $this->model('Admin');
			$check 				= 1;

			if($from=="") {
				$from = "today";
			}
			// $check 				= $user->checkPermissionPage(BLOG);
			if ($check==1 ) {
				$this->view('home/manageenquirylist', 
				[	
					'active_menu' 	=> 'enquiry',
			        'page_title'    => 'Manage Enquiry',
			        'meta_title'    => 'Manage Enquiry - '.COMPANY_NAME,
			        'scripts' 		=>  'datatable',
			        'from_date'		=>  $from,
					'to_date'		=>  $to,
					'type'		    =>  $type,
					'user_info'		=>  $user->userInfo(),
			        'list' 			=>  $user->manageEnquiry($_SESSION["ecom_contractor_id"],$from,$to,$type),
				]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
					]);
			}
		}else{
			$this->view('home/login',[]);
		}		
	}

	

	public function api($type)
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user = $this->model('Admin');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'trashEnquiry':
					echo $user->trashEnquiry($post);
				break;
				
				case 'info':
					echo $user->getEnquiryDetails($post);
				break;
				case 'toggleReadStatus':
					echo $user->toggleEnquiryReadStatus($post);
				break;
				
				default:
				break;
			}
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
					'user_info'		=>	$user->userInfo(),
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}
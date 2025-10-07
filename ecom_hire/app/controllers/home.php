<?php

class Home extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user 			= $this->model('Admin');
			$admin_profile 	= $this->model('AdminProfile');
			$check 			= 1;
			$project  		= $user->getDetails(CONTRACTOR_PROJECT_TBL,"count(*) as id"," contractor_id='".$_SESSION["ecom_contractor_id"]."' AND delete_status='0' AND status='1' ");
			if ($check==1) {
				$info = $user->getDetails(CONTRACTOR_TBL,"*","id='".$_SESSION["ecom_contractor_id"]."'");
				$this->view('home/index', 
					[	
						'active_menu' 	  		=> 'dashboard',
						'page_title'	  		=> 'Dashboard',
						'scripts'		  		=> 'dashboard',
						'project' 		  		=>  $project,
						'index_info' 	  		=>  $user->getIndexInfo(),
						'user_info'		  		=>  $user->userInfo(),
						'chart_data'	  		=>  $user->getEnquiryChartData(),
						'todays_enquiry'  		=>  $user->manageEnquiry($_SESSION["ecom_contractor_id"],$from='today'),
						'projects_list'   		=>  $user->manageProjectList($_SESSION["ecom_contractor_id"],$from='today'),
					]);
			}else{

			}
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

	public function logout()
	{
		if(isset($_SESSION['ecom_contractor_id'])){
			$user = $this->model('Admin');
			if($user->sessionOut($_SESSION['ecom_contractor_id'])){
				unset($_SESSION['ecom_contractor_id']);
				// session_destroy();
			}	
		}
		header("Location:".COREPATH);
	}

		public function api($type)
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user  = $this->model('Admin');
			$post = @$_POST["result"];	
			switch ($type) {
				
				case 'trashClassifiedProject':
					echo $user->trashClassifiedProject($post);
				break;

			}
		}
	}


}

?>

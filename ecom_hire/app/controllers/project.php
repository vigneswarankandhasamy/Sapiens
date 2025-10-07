<?php

class project extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user 	= $this->model('Admin');
			$check 	= 1;
			if ($check==1 ) {
				$this->view('home/manageprojects', 
					[	
						'active_menu' 	=> 'my_project',
						'page_title'	=> 'Manage Projects',
						'scripts'		=> 'addproduct',
						'user_info'		=>  $user->userInfo(),
						'projects_list' =>  $user->manageProjectList(),
					]);
			}else{
				$user = $this->model('Admin');
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo()
					]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}


	public function add()
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user 		= $this->model('Projects');
			$check = 1;
			// $check = $user->checkPermissionPage(DASHBOARD);
			$info = $user->getDetails(CONTRACTOR_TBL,"*"," id='".$_SESSION["ecom_contractor_id"]."'  ");
			if ($check==1) {
				$this->view('home/myprojects', 
					[	
						'active_menu' 				=> 'my_project',
						'page_title'				=> 'Add Projects',
						'scripts'					=> 'addproduct',
						'info'						=> $info,
						'user_info'					=> $user->userInfo(),
						'csrf_add_project_images'  	=> $user->getCSRF("add_project_images"),
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}
		
	}

	public function edit($token="")
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user 		= $this->model('Projects');
			$check = 1;
			// $check = $user->checkPermissionPage(DASHBOARD);
			$info = $user->getDetails(CONTRACTOR_TBL,"*"," id='".$_SESSION["ecom_contractor_id"]."'");
            $editDetail = $user->getDetails(CONTRACTOR_PROJECT_TBL,"*"," id='".$token."'");
			$editImgDetail = $user->getProjectImage($token,$_SESSION["ecom_contractor_id"]);
		
			if ($check==1) {
				$this->view('home/myprojectsedit', 
					[	
						'active_menu' 				=> 'dashboard',
						'page_title'				=> 'Edit Project',
						'scripts'					=> 'addproduct',
						'info'						=> $info,
                        'editDetail'				=> $editDetail,
						'editImgDetail'			    => $editImgDetail,
						'user_info'					=> $user->userInfo(),
						'csrf_add_project_images'  	=> $user->getCSRF("edit_project_images"),
					]);
			}else{

			}
		}else{
			$this->view('home/login',[]);
		}

	}

	public function api($type)
	{
		if(isset($_SESSION["ecom_contractor_id"])){
			$user  = $this->model('Projects');
			$post = @$_POST["result"];	

			switch ($type) {
				
				case 'addProjectImages':
					$upload = new MultiFileUploader();
					$files  = $upload->uploadFile($_FILES, $_POST['title_name']);
					echo $user->addProjectImages($_POST,$files);
				break;

				case 'imageinfo':
					echo json_encode($user->projectImageInfo($post));
				break;
				
				case 'editProjectImages':
					$upload = new MultiFileUploader();
					$files  = $upload->uploadFile($_FILES, $_POST['title_name']);
					echo $user->editProjectImages($_POST,$files);
				break;

				case 'imageedit':
					echo json_encode($user->editProductImage($_POST));
				break;

				case 'removeImg':
					echo $user->removeProjectImages($post);
				break;

				case 'trashClassifiedProject':
					echo $user->trashClassifiedProject($post);
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
					'user_info'		=>	$user->userInfo()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}

?>

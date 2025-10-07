<?php

class Attributegroup extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(ATTRIBUTES);
			$check_sitesettings = $user->checkSiteSettings(ATTRIBUTES);
			if ($check==1 && $check_sitesettings==1) {

				$info  = $user->getDetails(ATTRIBUTE_GROUP_TBL,"*"," 1 ORDER BY id DESC "); 
				$sort_order = (($info==true)? $info['sort_order'] + 1 : 1 );

				$this->view('home/manageAttributegroup', 
					[	
						'active_menu' 				=>  'productsettings',
						'page_title'				=>  'Attribute Group',
						'scripts'					=>  'Product',
						'attributegroup_menu'		=>  'attributegroup',
						'attribute_menu'			=>  '',
						'sort_order'         		=>  $sort_order,
						'csrf_add_attribute_group' 	=>  $user->getCSRF("add_attribute_group"),
						'csrf_edit_attribute_group' =>  $user->getCSRF("edit_attribute_group"),
						'list'						=>  $user->manageAttributeGroup(),	
						'user_info'					=>  $user->userInfo(),
						'sitesettings'				=>	$user->filtersiteSettings(),
						'notification'  			=>  $user->getOrderNotification()

					]);
			}else{
				$this->view('home/error', 
					[	
						'active_menu' 	=>  '',
						'page_title'	=>	'404 - Page Not Found',
						'scripts'		=>	'error',
						'user_info'		=>	$user->userInfo(),
						'sitesettings'	=>	$user->filtersiteSettings(),
						'notification'  =>  $user->getOrderNotification()

					]);
			}
		}else{
			$this->view('home/login',[]);
		}
	}

	public function api($type)
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  = $this->model('Product');
			$post = @$_POST["result"];	
			switch ($type) {
				case 'add':
					echo $user->addAttributeGroup($_POST);
				break;
				case 'update':
					echo $user->editAttributeGroup($_POST);
				break;
				case 'info':
					echo $user->getAttributeGroupItemDetails($post);
				break;
				case 'status':
					echo $user->changeAttributeGroupStatus($post);
				break;
				case 'delete':
					echo $user->deleteAttributeGroup($post);
				break;
				case 'restore':
					echo $user->restoreAttributeGroup($post);
				break;
				
				default:
				break;
			}
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
					'sitesettings'	=>	$user->filtersiteSettings(),
					'notification'  =>  $user->getOrderNotification()

				]);
		}else{
			$this->view('home/login',[]);
		}
	}



	/*-----------Dont'delete---------*/

}


?>
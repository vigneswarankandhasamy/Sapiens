<?php

class Attribute extends Controller
{
	
	public function index()
	{
		if(isset($_SESSION["ecom_admin_id"])){
			$user  				= $this->model('Product');
			$check 				= $user->checkPermissionPage(ATTRIBUTES);
			$check_sitesettings = $user->checkSiteSettings(ATTRIBUTES);
			if ($check==1 && $check_sitesettings==1) {

				$info  = $user->getDetails(ATTRIBUTE_TBL,"*"," 1 ORDER BY id DESC "); 
				$sort_order = (($info==true)? $info['sort_order'] + 1 : 1 );

				$this->view('home/manageAttribute', 
					[	
						'active_menu' 				=>  'productsettings',
						'page_title'				=>  'Attribute',
						'scripts'					=>  'Product',
						'attribute_menu'			=>  'attribute',
						'sort_order'         		=>  $sort_order,
						'attributegroup_menu'		=>  '',
						'csrf_add_Attribute' 		=>  $user->getCSRF("add_Attribute"),
						'csrf_edit_Attribute' 		=>  $user->getCSRF("edit_Attribute"),
						'attribute_group' 			=>  $user->getAttributeGroup(),
						'list'						=>  $user->manageAttribute(),	
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
				case 'addAttribute':
					echo $user->addAttribute($_POST);
				break;
				case 'attributestatus':
					echo $user->changeAttributeStatus($post);
				break;
				case 'attributeinfo':
					echo $user->getAttributeItemDetails($post);
				break;
				case 'attributeupdate':
					echo $user->editAttribute($_POST);
				break;
				case 'attributedelete':
					echo $user->deleteAttribute($post);
				break;
				case 'restore':
					echo $user->restoreAttribute($post);
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
<?php

class Login extends Controller
{
	
	public function index()
	{
		$user = $this->model('Admin');
		if (isset($_SESSION["ecom_admin_id"])) {
			header("Location: ".COREPATH);
		}else{
			$this->view('home/login',[]);
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
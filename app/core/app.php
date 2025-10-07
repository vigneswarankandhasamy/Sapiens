<?php

require_once 'Common.php';
	
/**
* Core App
*/

class App extends constants
{
	
	

	protected $controller = 'home';

	protected $method = 'index';

	protected $params = [];


	// Define Hypenzied controllers

	function customController($current_controller="")
	{
		$custom_routes = $this->controllernames();
		if (array_key_exists($current_controller, $custom_routes)) {
			return $custom_routes[$current_controller];
		}else{
			return $current_controller;
		}
	}

	// Define Hypenzied methods

	function customMethods($current_method="")
	{
		$custom_methods = $this->methodnames();
		if (array_key_exists($current_method, $custom_methods)) {
			return $custom_methods[$current_method];
		}else{
			return $current_method;
		}
	}


	public  function __construct()
	{
		$url = $this-> parseUrl();
		// Check Controller
 
		if($url[0]=="index.php"){
			if(file_exists('app/controllers/'.$url[0].'.php'))
			{
				$this->controller = $url[0];
				unset($url[0]);
			}
		}else {

			if(file_exists('app/controllers/'.$this->customController($url[0]).'.php'))
			{
				$this->controller = $this->customController($url[0]);
				unset($url[0]);
			}else{
				$this->controller = "errors";
			}
		}

		
		require_once 'app/controllers/'.$this->controller.'.php';
		$this->controller = new $this->controller;


		// Method Exists

		if (isset($url[1]))
		{
				
			if (method_exists($this->controller, $this->customMethods($url[1])))
			{
				$this->method = $this->customMethods($url[1]);
				unset($url[1]);
			}else{
				$this->method = "error";
			}

		}

		// Param Checks

		$this->params = $url ? array_values($url) : [] ;
		call_user_func_array([$this->controller,$this->method], $this->params);

	}

	public function parseUrl()
	{
		if(isset($_GET['url']))
		{	
			return $url = explode("/", filter_var(rtrim($_GET['url'],'/'), FILTER_SANITIZE_URL));
		}
	}
	
}



?>
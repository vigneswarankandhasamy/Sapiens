<?php
/*require_once '../config/config.php';*/
require_once '../app/core/admin_ajaxcontroller.php';
$route 		= new Ajaxcontroller();

// print_r($_FILES);

// Add New Product productimage

$errors = array();
$msg = array();


 
// data[1]

if(!empty($_FILES["image_one"]["type"]))
{
    $validextensions = array("jpeg", "jpg", "png", "JPG", "JPEG", "PNG");
    $temporary = explode(".", $_FILES["image_one"]["name"]); 
    $file_extension = end($temporary);

    if(($_FILES["image_one"]["type"] == "image/png") || ($_FILES["image_one"]["type"] == "image/jpg") || ($_FILES["image_one"]["type"] == "image/jpeg")
            )
	{
		if(($_FILES["image_one"]["size"] < 900000000) && in_array($file_extension, $validextensions))
		{
			$rands = $route -> generateRandomString("10");
			$date = date("dmYhis");

	        if ($_FILES["image_one"]["error"] > 0)
			{
	           $errors[] = "Return Code: " . $_FILES["image_one"]["error"] . "<br/>";
	        } 
			else 
			{ 
				if (file_exists("uploads/srcimg/".$_FILES["image_one"]["name"])) {
	            	$err =  $_FILES["image_one"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
	            	$errors[] = $err;
				} 
				else 
				{					
					$sourcePath = $_FILES["image_one"]['tmp_name'];
					$post_image_name = 'cat_'.$rands.$date.$route->hyphenize($_FILES["image_one"]['name']);
					$targetPath = "uploads/srcimg/".$post_image_name;
					move_uploaded_file($sourcePath,$targetPath) ;
					//$source_url = $targetPath;
					//$destination_url = $targetPath;
					//$process_img = $route->compress_image($source_url, $destination_url, 60);
					//$output = str_replace("uploads/customers/", "", $process_img);
					$msg[] = $post_image_name;
				}				       
	        }
		}
		else{
			$errmsg =  "Image One Size Exceed 800 KB. Please upload proper Image with proper file size.<br/>";
        	$errors[] = $errmsg;
		}
    }   
	else 
	{
        $errmsg =  "Invalid Image One type. Please upload images only in the JPG, JPEG, PNG or GIF Formats.<br/>";
        $errors[] = $errmsg;
    }
}else{
	$msg[] = "";
}


// data[2]


if(!empty($_FILES["image_two"]["type"]))
{	
    // var_dump($_FILES["image_two"]["type"]); 
    $validextensions = array("jpeg", "jpg", "png", "JPG", "JPEG", "PNG");
    $temporary = explode(".", $_FILES["image_two"]["name"]); 
    $file_extension = end($temporary);

    if(($_FILES["image_two"]["type"] == "image/png") || ($_FILES["image_two"]["type"] == "image/jpg") || ($_FILES["image_two"]["type"] == "image/jpeg")
            )
	{
		if(($_FILES["image_two"]["size"] < 900000000) && in_array($file_extension, $validextensions))
		{
			$rands = $route -> generateRandomString("10");
			$date = date("dmYhis");

	        if ($_FILES["image_two"]["error"] > 0)
			{
	           $errors[] = "Return Code: " . $_FILES["image_two"]["error"] . "<br/>";
	        } 
			else 
			{ 
				if (file_exists("uploads/srcimg/".$_FILES["image_two"]["name"])) {
	            	$err =  $_FILES["image_two"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
	            	$errors[] = $err;
				} 
				else 
				{					
					$sourcePath = $_FILES["image_two"]['tmp_name'];
					$post_image_name = $rands.$date.$route->hyphenize($_FILES["image_two"]['name']);
					$targetPath = "uploads/srcimg/".$post_image_name;
					move_uploaded_file($sourcePath,$targetPath) ;
					//$source_url = $targetPath;
					//$destination_url = $targetPath;
					//$process_img = $route->compress_image($source_url, $destination_url, 60);
					//$output = str_replace("uploads/customers/", "", $process_img);
					$msg[] = $post_image_name;
				}				       
	        }
		}
		else{
			$errmsg =  "Image Two Size Exceed 800 KB. Please upload proper Image with proper file size.<br/>";
        	$errors[] = $errmsg;
		}
    }   
	else 
	{
        $errmsg =  "Invalid Image Two type. Please upload images only in the JPG, JPEG, PNG or GIF Formats.<br/>";
        $errors[] = $errmsg;
    }
}else{
	$msg[] = "";
}

// data[3]

if(!empty($_FILES["image_three"]["type"]))
{
	// var_dump($_FILES["image_three"]["type"]); 
    $validextensions = array("jpeg", "jpg", "png", "JPG", "JPEG", "PNG");
    $temporary = explode(".", $_FILES["image_three"]["name"]); 
    $file_extension = end($temporary);

    if(($_FILES["image_three"]["type"] == "image/png") || ($_FILES["image_three"]["type"] == "image/jpg") || ($_FILES["image_three"]["type"] == "image/jpeg")
            )
	{
		if(($_FILES["image_three"]["size"] < 900000000) && in_array($file_extension, $validextensions))
		{
			$rands = $route -> generateRandomString("10");
			$date = date("dmYhis");

	        if ($_FILES["image_three"]["error"] > 0)
			{
	           $errors[] = "Return Code: " . $_FILES["image_three"]["error"] . "<br/>";
	        } 
			else 
			{ 
				if (file_exists("uploads/srcimg/".$_FILES["image_three"]["name"])) {
	            	$err =  $_FILES["image_three"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
	            	$errors[] = $err;
				} 
				else 
				{					
					$sourcePath = $_FILES["image_three"]['tmp_name'];
					$post_image_name = $rands.$date.$route->hyphenize($_FILES["image_three"]['name']);
					$targetPath = "uploads/srcimg/".$post_image_name;
					move_uploaded_file($sourcePath,$targetPath) ;
					//$source_url = $targetPath;
					//$destination_url = $targetPath;
					//$process_img = $route->compress_image($source_url, $destination_url, 60);
					//$output = str_replace("uploads/customers/", "", $process_img);
					$msg[] = $post_image_name;
				}				       
	        }
		}
		else{
			$errmsg =  "Image Three Size Exceed 800 KB. Please upload proper Image with proper file size.<br/>";
        	$errors[] = $errmsg;
		}
    }   
	else 
	{
        $errmsg =  "Invalid image Three type. Please upload images only in the JPG, JPEG, PNG or GIF Formats.<br/>";
        $errors[] = $errmsg;
    }
}else{
	$msg[] = "";
}


// data[4]

if(!empty($_FILES["image_four"]["type"]))
{	
    // var_dump($_FILES["image_four"]["type"])); 
    $validextensions = array("jpeg", "jpg", "png", "JPG", "JPEG", "PNG");
    $temporary = explode(".", $_FILES["image_four"]["name"]); 
    $file_extension = end($temporary);

    if(($_FILES["image_four"]["type"] == "image/png") || ($_FILES["image_four"]["type"] == "image/jpg") || ($_FILES["image_four"]["type"] == "image/jpeg")
            )
	{
		if(($_FILES["image_four"]["size"] < 900000000) && in_array($file_extension, $validextensions))
		{
			$rands = $route -> generateRandomString("10");
			$date = date("dmYhis");

	        if ($_FILES["image_four"]["error"] > 0)
			{
	           $errors[] = "Return Code: " . $_FILES["image_four"]["error"] . "<br/>";
	        } 
			else 
			{ 
				if (file_exists("uploads/srcimg/".$_FILES["image_four"]["name"])) {
	            	$err =  $_FILES["image_four"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
	            	$errors[] = $err;
				} 
				else 
				{					
					$sourcePath = $_FILES["image_four"]['tmp_name'];
					$post_image_name = $rands.$date.$route->hyphenize($_FILES["image_four"]['name']);
					$targetPath = "uploads/srcimg/".$post_image_name;
					move_uploaded_file($sourcePath,$targetPath) ;
					//$source_url = $targetPath;
					//$destination_url = $targetPath;
					//$process_img = $route->compress_image($source_url, $destination_url, 60);
					//$output = str_replace("uploads/customers/", "", $process_img);
					$msg[] = $post_image_name;
				}				       
	        }
		}
		else{
			$errmsg =  "Image Four Size Exceed 800 KB. Please upload proper Image with proper file size.<br/>";
        	$errors[] = $errmsg;
		}
    }   
	else 
	{
        $errmsg =  "Invalid Image Four type. Please upload images only in the JPG, JPEG, PNG or GIF Formats.<br/>";
        $errors[] = $errmsg;
    }
}else{
	$msg[] = "";
}


if(count($errors)==0){
	$success = "";
	foreach ($msg as $key =>  $value) {
		$success .= "`".$value;
	}
	echo "1".$success;
}else{
	$op = "";
	foreach ($errors as $value) {
		$op .= $value;
	}
	echo "0`".$op;
}



?>
<?php

require_once 'Model.php';
require_once 'app/core/classes/PHPMailerAutoload.php';

class Front extends Model
{	
	/*----------------------------------------------
				Manage Home Slider 
	----------------------------------------------*/

	function getHomeSlider()
  	{
  		$layout = "";
		$q = "SELECT *  FROM ".HOME_BANNER_TBL." WHERE delete_status='0' AND status='1' AND is_draft='0' ORDER BY sort_order ASC" ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   = $this->editPagePublish($details);
	    		$pic 	   = $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];

	    	if ($list['button_name']!="" && $list['button_link']!="#" && $list['button_link']!="") {

				if($list['button_type'] == "main_category"){
					$link_info 			= $this->getDetails(MAIN_CATEGORY_TBL,"page_url","id='". $list['button_link']."' ");
					$info = "<a href='".BASEPATH."product/category/".$link_info['page_url']."' class='button cssbuttons-io-button' style='text-decoration: none;'>
								".$list['button_name']."
								<div class='icon'>
									<svg height='24' width='24' viewBox='0 0 24 24'>
									<path d='M0 0h24v24H0z' fill='none'></path>
									<path d='M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z' fill='currentColor'></path>
									</svg>
								</div>
							</a>";

				}else if($list['button_type'] == "sub_category"){
					$link_info 			= $this->getDetails(SUB_CATEGORY_TBL,"page_url","id='". $list['button_link']."' ");
					$info = "<a href='".BASEPATH."product/subcategory/".$link_info['page_url']."' class='button cssbuttons-io-button' style='text-decoration: none;'>
								".$list['button_name']."
								<div class='icon'>
									<svg height='24' width='24' viewBox='0 0 24 24'>
									<path d='M0 0h24v24H0z' fill='none'></path>
									<path d='M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z' fill='currentColor'></path>
									</svg>
								</div>
							</a>";;

				}else if($list['button_type'] == "product"){
					$link_info 			= $this->getDetails(PRODUCT_TBL,"page_url","id='". $list['button_link']."' ");
					$info = "<a href='".BASEPATH."product/details/".$link_info['page_url']."' class='button cssbuttons-io-button' style='text-decoration: none;'>
								".$list['button_name']."
								<div class='icon'>
									<svg height='24' width='24' viewBox='0 0 24 24'>
									<path d='M0 0h24v24H0z' fill='none'></path>
									<path d='M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z' fill='currentColor'></path>
									</svg>
								</div>
							</a>";
				}

			}else{
				$info = "";
			}

	    		$layout .= "
		    		<div class='custom-carousel-slide active' style='background-image: url(".$pic.")'>
						<div class='custom-carousel-overlay'></div>
						<div class='custom-carousel-content'>
							<h1>
								<a href='#' style='color:white;text-decoration:none' target='_blank'>".$list['title']."</a>
							</h1>
							<p>".$list['message']."</p>
							".$info."
						</div>
					</div>";
	    		$i++;
	    	}
	    }
	    return $layout;
  	}

  	/*----------------------------------------------
				Manage Offer Banner
	----------------------------------------------*/

	function getofferBanner()
  	{
  		$layout = "";
		$q = "SELECT *  FROM ".OFFER_BANNER_TBL." WHERE delete_status='0' AND status='1' AND is_draft='0' ORDER BY id DESC LIMIT 1" ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   = $this->editPagePublish($details);
	    		$pic 	   = $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];


	    		if($list['button_type']=="product") 
	    		{	
	    			$product_info    = $this->getDetails(PRODUCT_TBL,"*","id='".$list['button_link']."'");  
					$banner_category = $product_info['product_name'];
					$btn_link        = BASEPATH."product/details/".$product_info['page_url'];
	    		} elseif ($list['button_type']=="main_category") {
					$category_info    = $this->getDetails(MAIN_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category  = $category_info['category'];
					$btn_link        = BASEPATH."product/category/".$category_info['page_url'];
	    		} else {
					$sub_category_info = $this->getDetails(SUB_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category   = $sub_category_info['subcategory'];
					$btn_link        = BASEPATH."product/subcategory/".$sub_category_info['page_url'];
	    		}


	    		$layout .= "
					<section class='hot-sale-section' style='background-image: url(".$pic.");'>
						<h1>".$list['title']."</h1>
						<p class='banner-message'>".$list['message']."</p>
						<a href='".$btn_link."' class='cta-btn'>".$list['button_name']."</a>
					</section>";
	    		$i++;
	    	}
	    }
	    return $layout;
  	}

  	/*----------------------------------------------
				Manage Page Banner
	----------------------------------------------*/

	function getPageBanner($token)
  	{
  		$layout = "";
		$q = "SELECT *  FROM ".PAGE_BANNER_TBL." WHERE page_token='".$token."' AND delete_status='0' AND status='1' AND is_draft='0' " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   = $this->editPagePublish($details);
	    		$pic 	   = $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];


	    		if($list['button_type']=="product") 
	    		{	
	    			$product_info    = $this->getDetails(PRODUCT_TBL,"*","id='".$list['button_link']."'");  
					$banner_category = $product_info['product_name'];
					$btn_link        = BASEPATH."product/details/".$product_info['page_url'];
	    		} elseif ($list['button_type']=="main_category") {
					$category_info    = $this->getDetails(MAIN_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category  = $category_info['category'];
					$btn_link        = BASEPATH."product/category/".$category_info['page_url'];
	    		} else {
					$sub_category_info = $this->getDetails(SUB_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category   = $sub_category_info['subcategory'];
					$btn_link        = BASEPATH."product/subcategory/".$sub_category_info['page_url'];
	    		}


	    		$layout .= "
					<div style='background-image: url(".$pic.");' class='offer_banner_widh_hight'>
					<div class='overlay_banner_last'></div>
					<div class='banner_text'>
						<h2>".$list['title']."</h2>
						<p>".$list['message']."</p>
						<a class='rounded-pill' href='".$btn_link."'>".$list['button_name']."</a>
					</div></div>";
	    		$i++;
	    	}
	    }
	    return $layout;
  	}

  	/*----------------------------------------------
				Manage Special Banner 
	----------------------------------------------*/

	function getSpecialofferBanner()
  	{
  		$layout = "";
		$q = "SELECT *  FROM ".SPECIAL_BANNER_TBL." WHERE delete_status='0' AND status='1' AND is_draft='0' ORDER BY id DESC LIMIT 2" ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   = $this->editPagePublish($details);

	    		if($list['button_type']=="product") 
	    		{	
	    			$product_info    = $this->getDetails(PRODUCT_TBL,"*","id='".$list['button_link']."'");  
					$banner_category = $product_info['product_name'];
					$btn_link        = BASEPATH."product/details/".$product_info['page_url'];
	    		} elseif ($list['button_type']=="main_category") {
					$category_info    = $this->getDetails(MAIN_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category  = $category_info['category'];
					$btn_link        = BASEPATH."product/category/".$category_info['page_url'];
	    		} else {
					$sub_category_info = $this->getDetails(SUB_CATEGORY_TBL,"*","id='".$list['button_link']."'");  
					$banner_category   = $sub_category_info['subcategory'];
					$btn_link        = BASEPATH."product/subcategory/".$sub_category_info['page_url'];
	    		}

	    		$pic 	 = $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];
	    		$layout .= "
                        <div class='single_banner'>
                            <div class='banner_thumb'>
                                <a href='".$btn_link."'><img src='".$pic."' alt=''></a>
                                <div class='banner_text'>
                                    <h3>".$list['title']."</h3>
                                    <h2>".$banner_category."</h2>
                                    <a href='".$btn_link."' class='rounded-pill'>".$list['button_name']."</a>
                                </div>
                            </div>
                        </div>";
	    		$i++;
	    	}
	    }
	    return $layout;
  	}

  	/*----------------------------------------------
				Manage Brands 
	----------------------------------------------*/

	function getBrands()
  	{
  		$layout = "";
		$q = "SELECT *  FROM ".BRAND_TBL." WHERE delete_status='0' AND status='1' AND is_draft='0' ORDER BY id DESC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 	   = $this->editPagePublish($details);
	    		$pic 	   = $list['file_name']=="" ? ASSETS_PATH."logo.png" : SRCIMG.$list['file_name'];
	    		$layout .= "
		    		 		<div class='single_brand'>
                            	<a href='#'><img src='".$pic."' alt='' class='brand_img'></a>
                        	</div>
                        ";
	    		$i++;
	    	}
	    }
	    return $layout;
  	}

  	/*----------------------------------------------
				Manage Blogs 
	----------------------------------------------*/

	function blogsPaginationCount($category_id="")
	{
		$layout ="";

		if ($category_id!="") {
  			$condition = "AND category_id='".$category_id."' ";
  		}
  		else{
  			$condition = "";
  		}

		$num_rec_per_page = 4;
		$sql = "SELECT id FROM ".BLOG_TBL." WHERE 1 $condition AND is_draft='0' AND status='1' AND delete_status='0' ORDER BY id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page);
		return $total_pages;
	}

	function blogsPagination($current="",$category="",$search_key="")
	{
		$layout ="";
		if ($category!="") {
  			$condition = "AND category_id='".$category."' ";
  			$c_info    = $this->getDetails(BLOG_CATEGORY_TBL,"*"," id='".$category."' ");
   			$page_link = "blog/category/".$c_info['token']."";
  		} elseif ($category=="" && $search_key!="") {
  			$condition = "AND title LIKE '%$search_key%'";
   			$page_link = "blog/search/".$search_key."";
  		} else {
  			$condition = "";
  			$page_link = "blog";
  		}
		$num_rec_per_page = 4;
		$sql = "SELECT * FROM ".BLOG_TBL." WHERE 1 $condition AND is_draft='0' ORDER BY id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page);
		$page = $current;
		$limit= 4;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";
			if ($page > ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."".$page_link."?p=1' >1</a></li>".$dot_link;
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit)
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."".$page_link."?p=".$i."'>".$i."</a></li>";
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."".$page_link."?p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	function getBlogList($page="",$category="",$search_key="")
	{	
		$layout = "";
  		if ($category!="") {
  			$condition = "AND B.category_id='".$category."' ";
  		}
  		elseif($category=="" && $search_key!=""){
  			$condition = "AND B.title LIKE '%$search_key%' ";
  		} else {
  			$condition = "";
  		}
		$start_from = ($page-1)*4;
  		$page_count = 4;
		$q ="SELECT B.id,B.page_url,B.title,B.category_id,B.file_name,B.short_description,B.created_at,C.token,C.category FROM ".BLOG_TBL." B LEFT JOIN ".BLOG_CATEGORY_TBL." C ON (C.id=B.category_id) WHERE B.status='1' AND B.delete_status='0' AND B.is_draft='0' AND B.is_draft='0' $condition ORDER BY B.id ASC LIMIT $start_from , $page_count ";
		$query = $this->exeQuery($q);
		if(mysqli_num_rows($query)>0) {
			$i=1;
			while ($details = mysqli_fetch_array($query)) {
				$list  = $this->editPagePublish($details);
	    		$pic 		= $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];

				$layout .= "
							<div class='single_blog'>
                                <div class='blog_thumb'>
                                    <a href='".BASEPATH."blog/details/".$list['page_url']."' ><img src='".$pic."' alt='' class='blog_list_img'></a>
                                </div>
                                <div class='blog_content'>
                                    <h3><a href='".BASEPATH."blog/details/".$list['page_url']."' >".$list['title']."</a></h3>
                                    <div class='blog_meta'>
                                        <span class='post_date'><i class='fa-calendar fa'></i> ".date("F d, Y", strtotime($list['created_at']))."</span>
                                        <span class='category'>
                                            <i class='fas fa-folder-open'></i>
                                            <a href='".BASEPATH."blog/category/".$list['token']."' >".$list['category']."</a>
                                        </span>
                                    </div>
                                    <div class='blog_desc'>
                                        <p>".$list['short_description']."</p>
                                    </div>
                                    <div class='readmore_button'>
                                        <a href='".BASEPATH."blog/details/".$list['page_url']."' >read more</a>
                                    </div>
                                </div>
                            </div>";
	    		$i++;
			}
		} else {
			$layout = "No Records Found";
		}
		return $layout;
	}

	function getRecentBlogs()
  	{
  		$layout = "";
	    $q = "SELECT B.id,B.page_url,B.title,B.file_name,B.short_description,B.created_at,C.category FROM ".BLOG_TBL." B LEFT JOIN ".BLOG_CATEGORY_TBL." C ON (C.id=B.category_id) WHERE B.status='1' AND B.delete_status='0' AND B.is_draft='0' AND B.is_draft='0' ORDER BY B.id DESC LIMIT 4" ;
	    $query = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0) 
		    {
		    	$i=1;
		    	while($details = mysqli_fetch_array($query)){
		    		$list  = $this->editPagePublish($details);
		    		$image = $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];
		    		$layout .= "
							<div class='post_wrapper'>
								<div class='post_thumb'>
									<a href='".BASEPATH."blog/details/".$list['page_url']."'><img src='".$image."' alt='' class='recent_blog_img'></a>
									</div>
									<div class='post_info'>
									<h3><a href='".BASEPATH."blog/details/".$list['page_url']."'>".$list['title']."</a></h3>
									<span>".date("F d, Y", strtotime($list['created_at']))."</span>
								</div>
							</div>
		    		";
		    		$i++;
		    	}
		    } else {
		    	$layout =  "No Records Found";
		    }
	    return $layout;
  	}

  	function getRelatedBlogs ($category="")
  	{
  		$layout = "";
  		$q = "SELECT B.id,B.page_url,B.title,B.file_name,B.short_description,B.created_at,C.category FROM ".BLOG_TBL." B LEFT JOIN ".BLOG_CATEGORY_TBL." C ON (C.id=B.category_id) WHERE B.status='1' AND B.delete_status='0' AND B.is_draft='0' AND B.is_draft='0' AND B.category_id='".$category."' ORDER BY B.id DESC LIMIT 3";
  		$query = $this->exeQuery($q);
  		if(mysqli_num_rows($query)>0) 
  		{
  			$i=1;
  			while($details = mysqli_fetch_array($query)){
		    	$list  = $this->editPagePublish($details);
	    		$image = $list['file_name']=="" ? ASSETS_PATH."file_upload.jpg" : SRCIMG.$list['file_name'];
	    		$layout .= "
						<div class='col-lg-4 col-md-12'>
                            <div class='single_related'>
                                <div class='related_thumb'>
                                    <img src='".$image."' alt='' class='related_blog_img'>
                                </div>
                                <div class='related_content'>
                                    <h3><a href='".BASEPATH."blog/details/".$list['page_url']."'>".$list['title']."</a></h3>
									<span><i class='fas fa-calendar-alt' aria-hidden='true'></i> ".date("F d, Y", strtotime($list['created_at']))."</span>
                                </div>
                            </div>
                        </div>
	    		";
	    		$i++;

  			}
  		} else {
		    	$layout =  "No Records Found";
		}
		return $layout;
  	}


  	function getBlogCategories()
  	{
  		$layout = "";
	    $q = "SELECT * FROM ".BLOG_CATEGORY_TBL."  WHERE status='1' AND delete_status='0' ORDER BY id ASC" ;
	    $query = $this->exeQuery($q);
	    if(mysqli_num_rows($query)>0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
		    	$list  = $this->editPagePublish($details);
		    	$check_blog = $this->check_query(BLOG_TBL,"category_id","category_id='".$list['id']."' ");
		    	if($check_blog) {
		    		$layout .= "
		    		<li><a href='".BASEPATH."blog/category/".$list['token']."'>".$list['category']." <i class='fas fa-angle-double-right float-end'></i></a></li> ";
		    		$i++;
		    	}
	    	}
	    }
	    return $layout;
  	}

  	/*-----------------------------------------------
				Work With Us Info
  	-----------------------------------------------*/

  	function workWithUsInfo($data)
  	{
  		$curr 		= date("Y-m-d H:i:s");
		$check 		= $this ->check_query(WORK_WITH_US_TBL,"email"," email='".$data['email']."'");
		if($check==0) {
		    $query = "INSERT INTO ".WORK_WITH_US_TBL." SET 
		        name 			= '".$this->cleanString($data['name'])."',
		        company_name 	= '".$this->cleanString($data['company_name'])."',
		        email 			= '".$this->cleanString($data['email'])."',
		        mobile 			= '".$this->cleanString($data['mobile'])."',
		        city 			= '".$this->cleanString($data['city'])."',
		        pincode 		= '".$this->cleanString($data['pincode'])."',
		        state 			= '".$this->cleanString($data['state'])."',
		        address 		= '".$this->cleanString($data['address'])."',
		        message 		= '".$this->cleanString($data['message'])."',
		        gst_no 			= '".$this->cleanString($data['gst_no'])."',
				status			= '1',
		        delete_status 	= '0',
		        created_at 		= '".$curr."',
		        updated_at 		= '".$curr."' ";
		    $exe = $this->exeQuery($query);
		    if ($exe) {
		        return "1";
		    } else {
		        return "0"."`Sorry!! Unexpected Error Occurred. Please try again.";
		    }
		} else {
			return "0"."`Entered email-id already exists";
		}
  	}


  	/*-----------------------------------------------
				Contact Us Info
  	-----------------------------------------------*/

  	function contactUsInfo($data)
  	{
  		$curr  = date("Y-m-d H:i:s");
	    $query = "INSERT INTO ".CONTACT_US_TBL." SET 
	    	name 		= '".$this->cleanString($data['name'])."',
	    	mobile 		= '".$this->cleanString($data['mobile'])."',
	        email 		= '".$this->cleanString($data['email'])."',
	        subject 	= '".$this->cleanString($data['subject'])."',
	        message 	= '".$this->cleanString($data['message'])."',
	        location 	= '".$this->cleanString($_SESSION['group_name'])."',
	        area 		= '".$this->cleanString($_SESSION['area_name'])."',
	        status 		= '1',
	        created_at 	= '".$curr."',
	        updated_at 	= '".$curr."' ";
	    $result 	= $this->lastInserID($query);
	    if ($result) {
	    	$query = "SELECT * FROM ".NOTIFICATION_EMAIL_TBL." WHERE 1";
            $e_info = $this->exeQuery($query);
            if(mysqli_num_rows($e_info) > 0){
                $i = 1;
                while($details = mysqli_fetch_array($e_info)){
		    		$list  = $this->editPagePublish($details);
					$sender 		= COMPANY_NAME;
					$sender_mail 	= NO_REPLY;
					$subject 		= COMPANY_NAME." ".$data['name']."'s Contact Enquiry has been submitted on ".date("d.m.Y");
					$receiver_mail 	= $this->cleanString($list['email']);
					$message  		= $this->contactEnquiry($data);
					$send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
                }
            }
	        return 1;
	    } else {
	        return "Sorry!! Unexpected Error Occurred. Please try again.";
	    }

  	}

  	function subscriberInfo($data)
  	{
  		$curr 		= date("Y-m-d H:i:s");
		$check 		= $this ->check_query(SUBSCRIBE_TBL,"email"," email='".$data['email']."'");
		if($check==0) {
		    $query = "INSERT INTO ".SUBSCRIBE_TBL." SET 
		        email 			= '".$this->cleanString($data['email'])."',
		       	sub_date		= '".$curr."',
				status			= '1',
		        delete_status 	= '0',
		        created_at 		= '".$curr."',
		        updated_at 		= '".$curr."' ";
		    $exe = $this->exeQuery($query);
		    if ($exe) {
		        return "1"."`1";
		    } else {
		        return "0"."`Sorry!! Unexpected Error Occurred. Please try again.";
		    }
		} else {
			return "0"."`You are already subscribed!";
		}
  	}

  	// Aboutus Testimonials

	function getTestimonials()
	{
		$layout ="";
		$q = "SELECT name,description,designation,file_name FROM ".TESTIMONIALS_TBL." WHERE delete_status='0' AND status='1' ORDER BY  sort_order ASC ";
		$exe = $this->exeQuery($q);
	    if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($exe)){
			$list 			= $this->editPagePublish($details);
			$image = (($list['file_name']=='') ? SRCIMG.'file_upload.jpg'  : SRCIMG.$list['file_name']);
            $layout .= "
					<div class='testimonial-card'>
						<div class='testimonial-content'>
							<p>".$list['description']."</p>
							<div class='customer-info'>
								<img src='".$image."' alt='Customer'>
								<div>
									<h4>".$list['name']."</h4>
									<span>".$list['designation']."</span>
								</div>
							</div>
						</div>
					</div>";
	    	$i++;
	    	}
	    }
	    return $layout;
	}

	function getMTestimonials()
	{
		$layout ="";
		$q = "SELECT name,description,designation,file_name FROM ".TESTIMONIALS_TBL." WHERE delete_status='0' AND status='1' ORDER BY  sort_order ASC ";
		$exe = $this->exeQuery($q);
	    if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($exe)){
			$list 			= $this->editPagePublish($details);
			$image = (($list['file_name']=='') ? SRCIMG.'file_upload.jpg'  : SRCIMG.$list['file_name']);
            $layout .= "
				<div class='col-12'>
				    <div class='single_m_testimonial'>
				        <p>".$list['description']."</p>
				        <img src='".$image."' alt='no image'>
				        <span class='name'>".$list['name']."</span>
				        <span class='job_title'>".$list['designation']."</span>
				    </div>
				</div>";
	    	$i++;
	    	}
	    }
	    return $layout;
	}
	/*--------------------------------------------
				Manage Product List
	--------------------------------------------*/

	function getOurproductsList($page="")
	{
		$layout = array();
		$result = "";
		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.display_tag,P.display_tag_end_date,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url,DT.display_tag as display_tag_title,DT.status as tag_status, 
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img 
			FROM ".PRODUCT_TBL." P 	LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
									LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
									LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
									LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
									LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id)  
			WHERE 1 AND P.delete_status='0' AND P.is_draft='0'  AND P.status='1' AND SC.status='1' AND C.status='1'  GROUP BY P.id DESC  " ;
		$exe = $this->exeQuery($q);
		if(mysqli_num_rows($exe)>0){
			$i=0;
			while($details = mysqli_fetch_array($exe))
			{
	    		$list  = $this->editPagePublish($details);
				$wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
				$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");

				$cartInfo = $this->cartInfo();
	            $cart_products = $cartInfo['cart_product_ids'];

	            if(in_array($list['id'], $cart_products)) {
	            	$add_to_cart ="Already in cart";
	            } else {
	            	$add_to_cart ="Add to Cart";
	            }

	            // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if(isset($product_price['vendor_id']))
				{
					$vendor_id = $product_price['vendor_id'];
				} else {
					$vendor_id = "Sapiens";
				}

				

				if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<div class='label_product display_tag'>
								".$list['display_tag_title']."
							</div>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}



				if(isset($_SESSION['user_session_id'])) {
					$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

				}else{
					$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
				}


				$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

				$secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

	            $product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
				$layout [$i]= "
					<div class='single_product'>
						<div class='product_name grid_name'>
							<h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
							<p class='manufacture_product'>$product_category</p>
						</div>
						<div class='product_thumb'>
							<a class='primary_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image." ' alt='".$list['product_name']."' class='our_product_lis_img_size' title='".$list['product_name']."'></a>
							<a class='secondary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$secondary_image."' alt='".$list['product_name']."' class='our_product_lis_img_size' title='".$list['product_name']."'></a>
							".$display_tag."
						</div>
						<div class='action_links'>
							<ul>
							".$wishlist."
							</ul>
						</div>
						<div class='product_content grid_content'>
							<div class='content_inner'>
								<div class='product_footer d-flex align-items-center'>
									<div class='price_box'>
										<span class='current_price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
										<span class='old_price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
									</div>
									<div class='add_to_cart'>
										<a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'  data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
									</div>
								</div>
							</div>
						</div>
					</div>";
				$i++;
			}

				$i=0;  
				$count = count($layout);

				foreach ($layout as $key => $value) 
				{ 
					if($count >= $i) {
						$result.="
						<div class='col-lg-12 col-md-12 col-sm-12 col-xl-12 clearfix'><div class='single_product_list'>";
						if(isset($layout[$i])) { 
							$result.= $layout[$i]; 
						} 
						if(isset($layout[$i+1])) { 
							$result.= $layout[$i+1]; 
						} 
						$result.="</div></div>";
						$i = $i+2; 
					}
				} 
		}
			
		return $result;
	}
	

	function getFeatureproductsList($page="")
	{
		$sample_product = "";
		$small_product  = array();
		
		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.featured_product,T.tax_class as taxClass, P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url , 
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img 
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id)
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
								   LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
			WHERE 1 AND P.delete_status='0' AND P.is_draft='0'  AND P.status='1' AND P.featured_product='1' AND SC.status='1' AND C.status='1'  GROUP BY P.id DESC  " ;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	    	$i=0;
	    	while($details = mysqli_fetch_array($exe)){
	    		$list  = $this->editPagePublish($details);
	    		$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

	    		$secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

	    		 // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<span class='product-badge'>".$list['display_tag_title']."</span>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}

	    		$sample_product .= " 
					<div class='product-card' data-tab-group='featured_product'>
						<a href='".BASEPATH."product/details/".$list['page_url']."'>
						<div class='product-image min-img-card'>
							<img src='".$product_image."' alt='".$list['product_name']."'>
							".$display_tag."
							<div class='product-actions'>
							<button class='action-btn wishlist-action'>
								<i class='fas fa-heart'></i>
							</button>
							<button class='action-btn quick-view'>
								<i class='fas fa-shopping-cart'></i>
							</button>
							</div>
						</div>
						<div class='product-info'>
							<h3 class='product-title'>".$list['product_name']."</h3>
							<div class='product-price'>
							<span class='current-price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
							<span class='original-price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
							</div>
						</div>
						</a>
					</div>";
            	$i++;
    		}
	    }
	 	$result = array();
	 	$result['sample_product'] 	= $sample_product;
	 	return $result;
	}

	function getBestSellerproductsList($page="")
	{
		$sample_product = "";
		$small_product  = array();

		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.best_seller,T.tax_class as taxClass, P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url , 
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
								   LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
			WHERE 1 AND P.delete_status='0' AND P.is_draft='0'  AND P.status='1' AND P.best_seller='1' AND SC.status='1' AND C.status='1'  GROUP BY P.id DESC  " ;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	    	$i=0;
	    	while($details = mysqli_fetch_array($exe)){
	    		$list  = $this->editPagePublish($details);
	    		$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
	    		$secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

	    		 // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<span class='product-badge'>".$list['display_tag_title']."</span>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}

				$sample_product .= " 
						<div class='product-card' data-tab-group='best_seller'>
							<a href='".BASEPATH."product/details/".$list['page_url']."'>
							<div class='product-image min-img-card'>
								<img src='".$product_image."' alt='".$list['product_name']."'>
								".$display_tag."
								<div class='product-actions'>
								<button class='action-btn wishlist-action'>
									<i class='fas fa-heart'></i>
								</button>
								<button class='action-btn quick-view'>
									<i class='fas fa-shopping-cart'></i>
								</button>
								</div>
							</div>
							<div class='product-info'>
								<h3 class='product-title'>".$list['product_name']."</h3>
								<div class='product-price'>
								<span class='current-price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
								<span class='original-price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
								</div>
							</div>
							</a>
						</div>";
            	$i++;
    		}
	    }
	 	$result = array();
	 	$result['sample_product'] 	= $sample_product;
	 	return $result;
	}

	
	
	function getMostViewproductsList($page="")
	{
		$sample_product = "";
		$small_product  = array();
		
		$best_seller_product_list = $this->getMostViewProductsIds();

		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass, P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url , 
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img 
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
								   LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
			WHERE P.id IN (" . implode(',', array_map('intval',$best_seller_product_list)). ") AND P.delete_status='0' AND P.is_draft='0'  AND P.status='1' AND SC.status='1' AND C.status='1' GROUP BY P.id ASC  " ;
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	    	$i=0;
	    	while($details = mysqli_fetch_array($exe)){
	    		$list  = $this->editPagePublish($details);
	    		$product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;

				$secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

	    		 // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<span class='product-badge'>".$list['display_tag_title']."</span>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}


	    		$sample_product .= " 
						<div class='product-card' data-tab-group='new_arrival'>
							<a href='".BASEPATH."product/details/".$list['page_url']."'>
							<div class='product-image min-img-card'>
								<img src='".$product_image."' alt='".$list['product_name']."'>
								".$display_tag."
								<div class='product-actions'>
								<button class='action-btn wishlist-action'>
									<i class='fas fa-heart'></i>
								</button>
								<button class='action-btn quick-view'>
									<i class='fas fa-shopping-cart'></i>
								</button>
								</div>
							</div>
							<div class='product-info'>
								<h3 class='product-title'>".$list['product_name']."</h3>
								<div class='product-price'>
								<span class='current-price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
								<span class='original-price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
								</div>
							</div>
							</a>
						</div>";
            	$i++;
    		}
	    }
	 	$result = array();
	 	$result['sample_product'] 	= $sample_product;
	 	return $result;
	}

	function getMostViewProductsIds()
	{	
		$data = array();
		$q    = "SELECT product_id FROM ".ORDER_ITEM_TBL." ";
		$exe  = $this->exeQuery($q);
		if(mysqli_num_rows($exe)>0){
	    	while($list = mysqli_fetch_array($exe)){
	    		$data[] = $list['product_id'];
    		}
	    } 
	    $result = array_unique($data);

	    if(count($data)==0) {
	    	$result[] = 0;
	    }
		return $result;
	}
	
	function getSpecialOffproductsList($page="")
	{
		$layout = "";
		
		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.hot_deals,T.tax_class as taxClass , P.display_tag,P.display_tag_end_date,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url , DT.display_tag as display_tag_title,DT.status as tag_status,
			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img 
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
								   LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
			WHERE 1 AND P.delete_status='0' AND P.is_draft='0' AND P.hot_deals='1'  AND P.status='1' AND SC.status='1' AND C.status='1'  GROUP BY P.id DESC ";
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($exe)){	
		    	$list  = $this->editPagePublish($details);

	            $wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
				$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");

	            // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if(isset($product_price['vendor_id']))
				{
					$vendor_id = $product_price['vendor_id'];
				} else {
					$vendor_id = "Sapiens";
				}

				if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<div class='label_product display_tag'>
								".$list['display_tag_title']."
							</div>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}


				if(isset($_SESSION['user_session_id'])) {
					$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

				}else{
					$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
				}


	            $cartInfo = $this->cartInfo();
	            $cart_products = $cartInfo['cart_product_ids'];

	            if(in_array($list['id'], $cart_products)) {
	            	$add_to_cart ="Already in cart";
	            } else {
	            	$add_to_cart ="Add to Cart";
	            }
	            $product_image 		= $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
	            $secondary_image   	= $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

		    	$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
		    	$layout .= "
		    		 	<div class='single_product_list'> ";
	                    $layout .= "<div class='single_product'>
	                        <div class='product_name grid_name'>
	                            <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
	                            <p class='manufacture_product'>$product_category</p>
	                        </div>
	                        <div class='product_thumb'>
	                            <a class='primary_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' class='special_offer_img' title='".$list['product_name']."'></a>
	                            <a class='secondary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$secondary_image."' alt='".$list['product_name']."' class='special_offer_img' title='".$list['product_name']."'></a>
	                                ".$display_tag."
	                        </div>
							<div class='action_links'>
								<ul>
									
									".$wishlist."
								</ul>
							</div>
	                        <div class='product_content grid_content'>
	                            <div class='content_inner'>
	                                <div class='product_footer d-flex align-items-center'>
	                                    <div class='price_box'>
	                                        <span class='current_price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
											<span class='old_price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
	                                    </div>
	                                    <div class='add_to_cart'>
	                                        <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>";
	            $layout .= "</div>";
	            $i++;
    		}
	    }
	 	return $layout;
	}

	function getBestSellerProductCarouselList($page="")
	{
		$layout = "";
		
		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.best_seller,T.tax_class as taxClass, P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url , (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image , (SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_image FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=P.main_category_id) LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) WHERE 1 AND P.delete_status='0' AND P.is_draft='0'  AND P.status='1' AND P.best_seller='1'  GROUP BY P.id DESC   ";
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($exe)){	
		    	$list  = $this->editPagePublish($details);

	            $wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
				$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");

	            // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if(isset($product_price['vendor_id']))
				{
					$vendor_id = $product_price['vendor_id'];
				} else {
					$vendor_id = "Sapiens";
				}

				if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<div class='label_product display_tag'>
								".$list['display_tag_title']."
							</div>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}


				if(isset($_SESSION['user_session_id'])) {
					$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

				}else{
					$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
				}


	            $cartInfo = $this->cartInfo();
	            $cart_products = $cartInfo['cart_product_ids'];

	            if(in_array($list['id'], $cart_products)) {
	            	$add_to_cart ="Already in cart";
	            } else {
	            	$add_to_cart ="Add to Cart";
	            }
	            $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
	            $secondary_image = $list['secondary_image']!='' ? SRCIMG.$list['secondary_image'] : ASSETS_PATH."no_img.jpg" ;
		    		$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;$layout .= "
		    		 	<div class='single_product_list'> ";
	                    $layout .= "<div class='single_product'>
	                        <div class='product_name grid_name'>
	                            <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
	                            <p class='manufacture_product'>$product_category</p>
	                        </div>
	                        <div class='product_thumb'>
	                            <a class='primary_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' class='special_offer_img' title='".$list['product_name']."'></a><a class='secondary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$secondary_image."' alt='".$list['product_name']."' class='special_offer_img' title='".$list['product_name']."'></a>
	                                ".$display_tag."
	                        </div>
							<div class='action_links'>
								<ul>
									
									".$wishlist."
								</ul>
							</div>
	                        <div class='product_content grid_content'>
	                            <div class='content_inner'>
	                                <div class='product_footer d-flex align-items-center'>
	                                    <div class='price_box'>
	                                        <span class='current_price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
											<span class='old_price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
	                                    </div>
	                                    <div class='add_to_cart'>
	                                        <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>";
	            $layout .= "</div>";
	            $i++;
    		}
	    }
	 	return $layout;
	}

	function getMostViewedProductCarouselList($page="")
	{
		$layout = "";
		
		$best_seller_product_list = $this->getMostViewProductsIds();

		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass, P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url , 
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image 
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
								   LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id) 
			WHERE P.id IN (" . implode(',', array_map('intval',$best_seller_product_list)). ") AND P.delete_status='0' AND P.is_draft='0'  AND P.status='1' AND SC.status='1' AND C.status='1' GROUP BY P.id ASC  " ;
			
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($exe)){	
		    	$list  = $this->editPagePublish($details);

	            $wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
				$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");

	            // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if(isset($product_price['vendor_id']))
				{
					$vendor_id = $product_price['vendor_id'];
				} else {
					$vendor_id = "Sapiens";
				}

				if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<div class='label_product display_tag'>
								".$list['display_tag_title']."
							</div>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}


				if(isset($_SESSION['user_session_id'])) {
					$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

				}else{
					$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
				}


	            $cartInfo = $this->cartInfo();
	            $cart_products = $cartInfo['cart_product_ids'];

	            if(in_array($list['id'], $cart_products)) {
	            	$add_to_cart ="Already in cart";
	            } else {
	            	$add_to_cart ="Add to Cart";
	            }
	            $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
		    		$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;$layout .= "
		    		 	<div class='single_product_list'> ";
	                    $layout .= "<div class='single_product'>
	                        <div class='product_name grid_name'>
	                            <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
	                            <p class='manufacture_product'>$product_category</p>
	                        </div>
	                        <div class='product_thumb'>
	                            <a class='primary_img no-hide' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' class='special_offer_img' title='".$list['product_name']."'></a>
	                                ".$display_tag."
	                        </div>
							<div class='action_links'>
								<ul>
									
									".$wishlist."
								</ul>
							</div>
	                        <div class='product_content grid_content'>
	                            <div class='content_inner'>
	                                <div class='product_footer d-flex align-items-center'>
	                                    <div class='price_box'>
	                                        <span class='current_price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
											<span class='old_price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
	                                    </div>
	                                    <div class='add_to_cart'>
	                                        <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>";
	            $layout .= "</div>";
	            $i++;
    		}
	    }
	 	return $layout;
	}

	function getCalculatorRelatedproductsList($token)
	{
		$layout = "";

		$category_filter = $this->getCalculatorCategories($token);
		
		$q = "SELECT P.id,P.page_url,P.has_variants,P.sku,P.product_name,P.category_type,P.main_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,P.hot_deals,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url , 
				(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image 
			FROM ".PRODUCT_TBL." P LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
								   LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=P.main_category_id) 
								   LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
								   LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
			WHERE  P.delete_status='0'  AND P.is_draft='0' AND P.status='1' ".$category_filter."  GROUP BY P.id DESC ";
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($exe)){	
		    	$list  = $this->editPagePublish($details);

	            $wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
				$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");

	            // Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if(isset($product_price['vendor_id']))
				{
					$vendor_id = $product_price['vendor_id'];
				} else {
					$vendor_id = "Sapiens";
				}



				if(isset($_SESSION['user_session_id'])) {
					$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

				}else{
					$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
				}


	            $cartInfo = $this->cartInfo();
	            $cart_products = $cartInfo['cart_product_ids'];

	            if(in_array($list['id'], $cart_products)) {
	            	$add_to_cart ="Already in cart";
	            } else {
	            	$add_to_cart ="Add to Cart";
	            }
	            $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
		    		$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;$layout .= "
		    		 	<div class='single_product_list'> ";
	                    $layout .= "<div class='single_product'>
	                        <div class='product_name grid_name'>
	                            <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
	                            <p class='manufacture_product'>$product_category</p>
	                        </div>
	                        <div class='product_thumb'>
	                            <a class='primary_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' class='special_offer_img' title='".$list['product_name']."'></a>
	                           
	                            <div class='label_product'>
	                                
	                            </div>
	                        </div>
							<div class='action_links'>
								<ul>
									
									".$wishlist."
								</ul>
							</div>
	                        <div class='product_content grid_content'>
	                            <div class='content_inner'>
	                                <div class='product_footer d-flex align-items-center'>
	                                    <div class='price_box'>
	                                        <span class='current_price'>Rs.".$this->inrFormat($product_price['selling_price'])."</span>
											<span class='old_price'>Rs.".$this->inrFormat($list['actual_price'])."</span>
	                                    </div>
	                                    <div class='add_to_cart'>
	                                        <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>";
	            $layout .= "</div>";
	            $i++;
    		}
	    }
	 	return $layout;
	}

	function getCalculatorCategories($token)
	{	

		if($token=="plastering") {
			$cat_name = ["cement","sand"];
		} elseif ($token=="brickwork") {
			$cat_name = ["cement","Bricks","sand"];
		} elseif ($token=="concrete") {
			$cat_name = ["cement","sand","aggregate"];
		} elseif ($token=="wallfinish") {
			$cat_name = ["paint","primer","putty"];
		} elseif ($token=="tilework") {
			$cat_name = ["tiles","cement","sand"];
		}

		$cat_ids = array();

		foreach ($cat_name as $key => $value) {
			$query = "SELECT id FROM ".MAIN_CATEGORY_TBL." WHERE category LIKE '%".$value."%' AND delete_status='0' AND is_draft='0' AND status='1' ";
			$exe   = $this->exeQuery($query);
			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$cat_ids[] = $list['id'];
				}
			}
		}

		if(count($cat_ids) > 0) {
			
			$cat_filter = "AND P.main_category_id IN (".implode(",", $cat_ids).") ";

			$sub_cat_ids = array();

			foreach ($cat_ids as $key => $value) {
				$query = "SELECT id FROM ".SUB_CATEGORY_TBL." WHERE category_id='".$value."' AND delete_status='0' AND is_draft='0' AND status='1' ";
				$exe   = $this->exeQuery($query);
				if(mysqli_num_rows($exe) > 0) {
					while ($list = mysqli_fetch_assoc($exe)) {
						$sub_cat_ids[] = $list['id'];
					}
				}
			}

			if(count($sub_cat_ids) > 0) {
				$cat_filter = "AND ( P.main_category_id IN (".implode(",", $cat_ids).") OR P.sub_category_id IN (".implode(",", $sub_cat_ids).") )";
			} 
		} else {
			
			$cat_filter = "P.main_category_id='0' AND P.sub_category_id='0' ";

		}

		return $cat_filter;
	}

	function getCategoryframe()
 	{
 		$layout = "";

 		$query  = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE delete_status='0' AND is_draft='0' AND status='1' ORDER BY id DESC ";
 		$exe    = $this->exeQuery($query);
 		if(mysqli_num_rows($exe) > 0) {
 			while ($list = mysqli_fetch_assoc($exe)) {
 				$check_sub_cat = $this->check_query(SUB_CATEGORY_TBL,"id","category_id='".$list['id']."' AND delete_status='0' AND is_draft='0' AND status='1' ");
 				if($check_sub_cat > 0 ) {
 					$sub_cat_ids    = $this->getSubCategoryList($list['id']);
 					$check_products = $this->check_query(PRODUCT_TBL,"id","category_type='sub' AND sub_category_id IN (".implode(",", array_map("intval", $sub_cat_ids)).")  AND delete_status='0' AND is_draft='0' AND status='1' ");
					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);
 					if($check_products > 0) {
 						$layout .= "<div class='category-card'>
										<a href='".BASEPATH."product/category/".$list['page_url']."'>
										<div class='category-image'>
											<img src='".$image."' alt=".$list['category']." class='default-img'>
											<img src='".$image."' alt=".$list['category']." class='hover-img'>
										</div>
										</a>
									</div>";
 					}
	 				
				}
 			}
 		}
 		return  $layout;
 	}

 	
 	function getCategoryContainer()
 	{
 		$layout = "";

 		$query  = "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE delete_status='0' AND is_draft='0' AND status='1' ORDER BY id DESC ";
 		$exe    = $this->exeQuery($query);
 		if(mysqli_num_rows($exe) > 0) {
 			while ($list = mysqli_fetch_assoc($exe)) {
 				$check_sub_cat = $this->check_query(SUB_CATEGORY_TBL,"id","category_id='".$list['id']."' AND delete_status='0' AND is_draft='0' AND status='1' ");
 				if($check_sub_cat > 0 ) {
 					$sub_cat_ids    = $this->getSubCategoryList($list['id']);
 					$check_products = $this->check_query(PRODUCT_TBL,"id","category_type='sub' AND sub_category_id IN (".implode(",", array_map("intval", $sub_cat_ids)).")  AND delete_status='0' AND is_draft='0' AND status='1' ");
 					if($check_products > 0) {
 						$layout .= "<div class='col-lg-3 col-md-6 col-sm-12 mt-3'>
				                    <div class='home-box-products'>
				                        <div class='boxed-header'>
				                            <h4>".$list['category']."</h4>
				                        </div>
										<div class='boxed-body'>
											<div class='boxed-inner-section'>
												".$this->getCategoryContainerSubCatProducts($list['id'])."
											</div>
										</div>
				                        <div class='boxed-footer'>
				                            <a href='".BASEPATH."product/category/".$list['page_url']."' class='text-decoration-underline'>View more</a>
				                        </div>
				                    </div>
				                </div>";
 					}
	 				
				}
 			}
 		}
 		return  $layout;
 	}

 	function getCategoryContainerSubCat($category_id='')
 	{	
 		$layout = "<div class='boxed-body'>";
		for ($i=0; $i < 2; $i++) { 
			$layout .= $this->getCategoryContainerSubCatProducts($category_id);
		}
 		$layout .= "</div>";
 		return $layout;
 	}

 	function getCategoryContainerSubCatProducts($category_id='')
 	{	
 		$layout      = "";
 		$sub_cat_ids = $this->getSubCategoryList($category_id);
 		$query       = "SELECT * FROM ".PRODUCT_TBL." WHERE category_type='SUB' AND sub_category_id IN (".implode(",", array_map("intval", $sub_cat_ids)).")  AND delete_status='0' AND is_draft='0' AND status='1' ORDER BY id DESC LIMIT 4 ";
 		$exe         = $this->exeQuery($query);
 		if(mysqli_num_rows($exe) > 0) {
 			$i = 1;
 			
 			while ($list = mysqli_fetch_assoc($exe)) {
 				$product_image_info = $this->getDetails(MEDIA_TBL,"file_name","item_id='".$list['id']."' AND item_type='product' AND delete_status='0' ORDER BY id ASC LIMIT 1");
				$product_image = (($product_image_info)? (($product_image_info['file_name']!="")? SRCIMG.$product_image_info['file_name']  :  ASSETS_PATH."no_img.jpg" ) : ASSETS_PATH."no_img.jpg" );
				$float_end     = (($i % 2 == 0)? "float-end" : "" );
 				$layout       .= "	<div class='boxed-category $float_end'>
			                            <a href='".BASEPATH."product/details/".$list['page_url']."' class='image-category-box'>
			                                <div class='ct-box-inner'>
			                                    <img src='".$product_image."' alt='' class=''>
			                                </div>
			                                <div class='ct-box-label'>
			                                    <span>".$list['product_name']."</span>
			                                </div>
			                            </a>
			                        </div> ";
                $i++;
 			}


 		}
 		return $layout;
 	}

	/*----------------------------------------------
				Manage Search items 
	----------------------------------------------*/

	//Product Pagination Count

	function productsPaginationCount($brands,$category,$cat_id,$price_filter,$page_amount,$search_key="")
	{
		$layout ="";
		$condition = "";

		$total_products = "";
		if ($search_key!="") {
  			$search_condition  = "AND product_name LIKE '%".$search_key."%' ";
  		} else {
  			$search_condition  = "";
  		}

		if($price_filter!="") {
			$from = $price_filter['from'];
			$to   = $price_filter['to'];
			$price_filter = "AND selling_price  BETWEEN '".$from."' AND '".$to."'";
			$condition = "AND selling_price  BETWEEN '".$from."' AND '".$to."'";
		}


		if ($brands!="") {
  			$condition = "AND brand_id IN ('".$brands."') $price_filter";
  		}
  		if($category=='main') {
			$get_subcategories = $this->getSubCategoryList($cat_id);
			if(count($get_subcategories)==0)
			{
				$get_subcategories[]=0;
			}
  			$condition = "AND main_category_id='".$cat_id."' OR sub_category_id IN (" . implode(',', array_map('intval',$get_subcategories)). ") $price_filter ";
  		}
  		if($category=='sub') {
  			$condition = "AND sub_category_id='".$cat_id."' $price_filter";
  		}

		$sql = "SELECT id FROM ".PRODUCT_TBL."  WHERE 1 AND delete_status='0' $search_condition $condition AND is_draft='0' AND status='1' ORDER BY id DESC ";
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$num_rec_per_page = (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount );
		$total_pages = ceil($total_records / $num_rec_per_page);
		return $total_pages;
	}

	//Product Pagination

	function productsPagination($current="",$id,$view_page,$view_page_link,$price_filter,$page_amount,$brands,$search_key="")
	{
		$layout ="";
		$condition = "";

		if ($search_key!="") {
  			$search_condition  = "AND product_name LIKE '%".$search_key."%' ";
  		} else {
  			$search_condition  = "";
  		}

		if($price_filter!="") {
			$from = $price_filter['from'];
			$to   = $price_filter['to'];
			$price_filter = "AND selling_price  BETWEEN '".$from."' AND '".$to."'";
			$condition = "AND selling_price  BETWEEN '".$from."' AND '".$to."'";
		}

		if ($brands!="") {
  			$brand_check = "AND brand_id IN ('".$brands."') $price_filter";
  		} else {
  			$brand_check = "";
  		}

		
		if ($view_page=="brand") {
  			$condition = "AND brand_id='".$id."' $price_filter ";
  		} elseif($view_page=="category") {
			$get_subcategories = $this->getSubCategoryList($id);
			if(count($get_subcategories)==0)
			{
				$get_subcategories[]=0;
			}
  			$condition = "AND (main_category_id='".$id."'  OR sub_category_id IN (" . implode(',', array_map('intval',$get_subcategories)). ")) $price_filter ";
  		} elseif($view_page=="subcategory") {
  			$condition = "AND sub_category_id='".$id."' $price_filter ";
  		}

		$sql = "SELECT id FROM ".PRODUCT_TBL."  WHERE 1 AND delete_status='0' $condition $search_condition $brand_check AND is_draft='0' AND status='1' ORDER BY id DESC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / (($page_amount=="show_all")? mysqli_num_rows($rs_result) : $page_amount ));
		$page = $current;
		$limit= 6;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";
			if ($page >  ($limit/2)){
				$layout .= "<li><a href='".$view_page_link."p=1' >1</a></li>";
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit) {
	            	$layout .= "<li class='".$current_page."'><a href=".$view_page_link."p=".$i.">".$i."</a></li>";
	            }
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".$view_page_link."p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	function getProductsNames()
	{
		$product_names = array();

		$query = "SELECT id,product_name FROM ".PRODUCT_TBL." WHERE 1 AND delete_status='0' AND is_draft='0' AND status='1' ";
		$exe   = $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			while ($list = mysqli_fetch_assoc($exe)) {
				$product_names[$list['id']] = $list['product_name'];
			}
		}

		return $product_names;

	}



	function customSearch($keyword, $arrayToSearch){
		$matches =  array();
		foreach($arrayToSearch as $key => $arrayItem){
		    if( strrpos( $arrayItem, $keyword ) ){
		        $matches[] =  $key;
		    }
		}
		return implode(",", $matches);
	}


	// Product List

	function manageProductlist($page="",$brands="",$category="",$cat_id="",$sort_by="",$price_sort="",$from="",$to="",$page_amount="",$search_key)
	{		
		$layout = "";
  		$condition 		= "";
  		$total_products = "";
		
		if ($brands!="") {
  			$brand_check = "AND P.brand_id IN (".$brands.")";
  		} else {
  			$brand_check = "";
  		}

  		if ($search_key!="") {
  			$product_with_searchkey = $this->check_query(PRODUCT_TBL,"product_name"," product_name LIKE '%".$search_key."%' AND delete_status='0' AND is_draft='0' AND status='1'");

  			if($product_with_searchkey) {
  				$search_condition  = "AND P.product_name LIKE '%".$search_key."%' ";
  			} else {
  				$split_key         = str_split($search_key);
  				$search_condition  = "AND P.product_name LIKE '".$search_key[0]."%' ";
  			}

  		} else {
  			$search_condition  = "";
  		}

  		if($category=='main') {
			$get_subcategories = $this->getSubCategoryList($cat_id);
			if(count($get_subcategories)==0)
			{
				$get_subcategories[]=0;
			}
  			$condition 		   = "AND (P.main_category_id='".$cat_id."' OR P.sub_category_id IN (" . implode(',', array_map('intval',$get_subcategories)). ")) ";
  		}
  		if($category=='sub') {
  			$condition 		= "AND P.sub_category_id='".$cat_id."'";
  		}
		$q = "SELECT P.id,P.page_url,P.sku,P.has_variants,P.product_name,P.category_type,P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft,P.status,T.tax_class as taxClass ,C.category,C.page_url as cat_url ,W.fav_status,P.selling_price,P.actual_price,SC.subcategory,SC.page_url as sub_cat_url ,P.display_tag,P.display_tag_end_date,DT.display_tag as display_tag_title,DT.status as tag_status,PV.id as variant_id,(SELECT selling_price FROM ".VENDOR_PRODUCTS_TBL." WHERE product_id=P.id AND PV.id='1'  ORDER BY id ASC LIMIT 1) as product_vendor_price,(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image,
			(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id DESC LIMIT 1) as secondary_img 
			FROM ".PRODUCT_TBL." P 
					LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id) 
					LEFT JOIN ".SUB_CATEGORY_TBL." SC ON (SC.id=P.sub_category_id)   
					LEFT JOIN ".MAIN_CATEGORY_TBL." C ON (C.id=SC.category_id) 
					LEFT JOIN ".PRODUCT_VARIANTS." PV ON (P.id=PV.product_id )   
					LEFT JOIN ".PRODUCT_DISPLAY_TAG." DT ON (P.display_tag=DT.id)
					LEFT JOIN ".WISHLIST_TBL." W ON(W.product_id=P.id AND W.user_id='".@$_SESSION['user_session_id']."') 
			WHERE 1 AND P.delete_status='0' AND P.is_draft='0' $condition $search_condition $brand_check  AND P.status='1' AND SC.status='1' AND C.status='1'  " ;

		if($price_sort=='price'){
            $q .= " AND P.selling_price  BETWEEN $from AND $to ";
    		$t_p = $q;
        }
        if ($sort_by=='lowtohigh') {
            $q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) ASC";
    		$t_p = $q;
        }elseif($sort_by=='hightolow'){
        	$q .= "GROUP BY P.id ORDER BY CAST(P.selling_price AS DECIMAL(10,2)) DESC";
    		$t_p = $q;
    	}elseif($sort_by=='asc'){
        	$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
    		$t_p = $q;
    	}elseif($sort_by=='desc'){
        	$q .= "GROUP BY P.id ORDER BY P.product_name DESC";
    		$t_p = $q;
    	} else {
    		$q .= "GROUP BY P.id ORDER BY P.product_name ASC";
    		$t_p = $q;
    	}

    	if($page_amount!="show_all") {
    		$start_from = ($page-1)*$page_amount;
  			$page_count = $page_amount;
       		$q .= "  LIMIT $start_from, $page_count";
    	} else {
    		$start_from = 0;
    	}

        $q_t_p = $this->exeQuery($t_p);
        $total_products = mysqli_num_rows($q_t_p);
	 	$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	    	while($row = mysqli_fetch_array($exe)){

		    		$list  = $this->editPagePublish($row);

	            $wishlist_text 	 = (($list['fav_status']=="") ? "Add to wishlist" : "Remove from wishlist");
				$status 		 = (($list['fav_status']!="") ? "favourite_item" : "");
				

				// Get Product Price

	            $variant    = "";
	            $variant_id = "";

	            if($list['has_variants']==1) {
					$variant  = $this->getDetails(PRODUCT_VARIANTS,"*"," product_id='".$list['id']."'  ORDER BY id ASC LIMIT 1 ");
					$variant_id = $variant['id'];
				}

				$product_price = $this->getProductPrice($list['id'],$variant);

				if(isset($product_price['vendor_id']))
				{
					$vendor_id = $product_price['vendor_id'];
				} else {
					$vendor_id = "Sapiens";
				}



				if(isset($_SESSION['user_session_id'])) {
					$wishlist = "<li class='wishlist '><a href='".BASEPATH."login' class='addToWishList $status'   data-option='".$this->encryptData($list['id'])."' data-id='".$this->encryptData($list['id'])."' data-vendor_id='".$vendor_id."' data-variant_id='".$variant_id."' title='".$wishlist_text."'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";

				}else{
					$wishlist = "<li class='wishlist'><a href='".BASEPATH."login' title='Login to add Wishlist'><span class='far fa-heart fill-heart'></span><i class='fas fa-heart without-fill d-none'></i></a></li>";
				}


	            $cartInfo = $this->cartInfo();
	            $cart_products = $cartInfo['cart_product_ids'];

	            if(in_array($list['id'], $cart_products)) {
	            	$add_to_cart ="Already in cart";
	            } else {
	            	$add_to_cart ="Add to Cart";
	            }

	            if($list['display_tag']!=0 && $list['display_tag_end_date'] && $list['tag_status']==1) {
					$today    = date("Y-m-d");
					$end_date = date("Y-m-d",strtotime($list['display_tag_end_date']));
					if($end_date >= $today) {
						$display_tag = "<div class='label_product display_tag'>
								".$list['display_tag_title']."
							</div>";
					} else {
						$display_tag = "";
					}
				} else {
					$display_tag = "";
				}

	            $product_image = $list['product_image']!='' ? SRCIMG.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;


				$secondary_image   = $list['secondary_img']!='' ? SRCIMG.$list['secondary_img'] : ASSETS_PATH."no_img.jpg" ;

            	$product_category = $list['category_type']=="main" ? "<a href='".BASEPATH."product/category/".$list['cat_url']."'>".$list['category']."</a>" : "<a href='".BASEPATH."product/subcategory/".$list['sub_cat_url']."'>".$list['subcategory']."</a>" ;
		    		$layout .= "
		    		 	<div class='col-lg-4 col-xl-3 col-md-6 col-sm-12 col-12'>
                        <div class='single_product single_product_list_width_hight' >
                            <div class='product_name grid_name'>
                                <h3><a href='".BASEPATH."product/details/".$list['page_url']."' title='".$list['product_name']."'>".$list['product_name']."</a></h3>
                                <p class='manufacture_product'>$product_category</p>
                            </div>
                            <div class='product_thumb'>
                                <a class='primary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$product_image."' alt='".$list['product_name']."' title='".$list['product_name']."'></a>
                            	<a class='secondary_img product_wish_list_img' href='".BASEPATH."product/details/".$list['page_url']."'><img src='".$secondary_image."' alt='".$list['product_name']."' title='".$list['product_name']."'></a>
                                ".$display_tag."
                            </div>	                                    
							<div class='action_links'>
								<ul>
									".$wishlist."
								</ul>
							</div>
                            <div class='product_content grid_content'>
                                <div class='content_inner'>
                                    <div class='product_footer d-flex align-items-center'>
                                        <div class='price_box'>
                                            <span class='current_price'>Rs.".number_format($product_price['selling_price'])."</span>
											<span class='old_price'>Rs.".number_format($list['actual_price'])."</span>
                                        </div>
                                        <div class='add_to_cart'>
                                            <a href='".BASEPATH."product/details/".$list['page_url']."' class='addToCart_pending' data-quantity='1'   data-option='".$this->encryptData($list['id'])."' title='".$add_to_cart."'><span class='lnr lnr-cart'></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
                $i++;
	    	}
		    } else {
			$layout = "<div class='cart_content '>No Records Found !!</div>";
		}
	 	$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = (mysqli_num_rows($exe)!=0)? $start_from + 1 : 0;
		$result['start_to']   = $start_from + mysqli_num_rows($exe);
		$result['totals']     = $total_products ;
		return $result;
	}

	// Main Category List For filter bar
	function manageCategorylist()
	{
		$layout = "";
		$q = "SELECT * FROM ".MAIN_CATEGORY_TBL."  WHERE 1 AND is_draft='0' AND delete_status='0' ORDER BY category ASC";
		$exe = $this->exeQuery($q);
	 		if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($row = mysqli_fetch_array($exe)){
		    		$list  = $this->editPagePublish($row);
					$sub_categories         = $this->getSubCategoryList($list['id']);
					$category_products      = $this->check_query(PRODUCT_TBL,"*"," main_category_id='".$list['id']."' AND category_type='main' AND delete_status='0' AND status='1' AND is_draft='0'  ");
					$sub_category_products  = $this->check_query(PRODUCT_TBL,"*"," sub_category_id IN (" . implode(',', array_map('intval',$sub_categories)). ") AND category_type='sub' AND delete_status='0' AND status='1' AND is_draft='0'   ");

					if($category_products || $sub_category_products ) {
						$layout .= "
	 		    		 			<li >
                                        <a href='".BASEPATH."product/category/".$list['page_url']."' class='category_list category_list_padding' data-id='".$list['id']."' >".$list['category']."</a>
                                        ".$this->manageSubCategorylist($list['id'])."
                                    </li>
	 		    		";
					}
		    	}
 		    }
	 	return $layout;
	}

	function manageSubCategorylist($category_id)
	{
		$layout = "";
		$q = "SELECT * FROM ".SUB_CATEGORY_TBL."  WHERE category_id='".$category_id."' AND is_draft='0' AND delete_status='0' ORDER BY subcategory ASC";
		$exe = $this->exeQuery($q);
	 		if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($row = mysqli_fetch_array($exe)){
    				$list  = $this->editPagePublish($row);
					$sub_category_products      = $this->check_query(PRODUCT_TBL,"*"," sub_category_id='".$list['id']."' AND category_type='sub' AND delete_status='0' AND status='1' AND is_draft='0'  ");
					if ($sub_category_products) {
						$layout .= "
 		    		 			<li class='subcat_list_$category_id subcat_list_sc ' data-option='".$category_id."'>
                                    <a href='".BASEPATH."product/subcategory/".$list['page_url']."' class='category_list_padding' >".$list['subcategory']."</a>
                                </li>
 		    			";
					}
		    	}
 		    }
	 	return $layout;
	}

	//Brand List For filter bar
	function manageBrandlist()
	{
		$layout = "";
		$q = "SELECT * FROM ".BRAND_TBL."  WHERE 1 AND is_draft='0' AND delete_status='0' ORDER BY brand_name ASC";
		$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	while($row = mysqli_fetch_array($exe)){
	    				$list  = $this->editPagePublish($row);
	    				$is_empty  = $this->getDetails(PRODUCT_TBL,'id'," brand_id='".$list['id']."' AND delete_status='0' AND status='1' AND is_draft='0'  ");
	 		    		if($is_empty) {

	 		    			if(isset($_GET['brands'])) {
								$selected_ids = explode(",", $_GET['brands']);
								$checked     = (in_array($list['id'], $selected_ids))? "checked" : "" ;
							} else {
								$checked = "";
							}

	 		    			$layout .= "
	 		    		 			<div class='brand_lists'>
			 		    			<input type='checkbox' id='brand_id_".$list['id']."' data-option='".$list['id']."' class='brand_filter_".$list['id']." brand_list_checkbox brand_filter' name='brand_ids[]' value='".$list['id']."'  $checked><label class='brand_list_label' for='brand_id_".$list['id']."'>".$list['brand_name']."</label>
			 		    			</div>
	 		    			";
	 		    		}
			    	}
	 		    }
	 	return $layout;

	}

	function getSearchItemBrandsList($search_key)
	{	
		$ids = array();
		$query = "SELECT brand_id FROM ".PRODUCT_TBL." WHERE brand_id!='0' AND product_name LIKE '%".$search_key."%'  AND is_draft='0' AND status='1' AND delete_status='0'   ";
		$exe   = $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0)
		{
			while ($list = mysqli_fetch_assoc($exe)) {
				$ids[] = $list['brand_id'];
			}
		}

		$ids = implode(",", $ids);

		if($ids!="")
		{
			$brand_filter = "AND id IN (".$ids.")";
		} else {
			$brand_filter = "";	
		}

		$layout = "";
		$q = "SELECT * FROM ".BRAND_TBL."  WHERE 1 AND is_draft='0' ".$brand_filter." AND delete_status='0' ORDER BY brand_name ASC";
		$exe = $this->exeQuery($q);
	 	if(mysqli_num_rows($exe)>0){
	 		    	$i=1;
	 		    	$selected = ((mysqli_num_rows($exe)==1)? "checked" : "" );
	 		    	while($row = mysqli_fetch_array($exe)){
	    				$list  = $this->editPagePublish($row);
	    				$is_empty  = $this->getDetails(PRODUCT_TBL,'id'," brand_id='".$list['id']."' AND delete_status='0' AND status='1' AND is_draft='0'  ");

	    				if(isset($_GET['brands'])) {
							$selected_ids = explode(",", $_GET['brands']);
							$checked     = (in_array($list['id'], $selected_ids))? "checked" : "" ;
						} else {
							$checked = "";
						}

	 		    		if($is_empty) {
	 		    			$layout .= "
	 		    			<div class='brand_lists'>
	 		    			<input type='checkbox' id='brand_id_".$list['id']."' data-option='".$list['id']."' class='brand_filter_".$list['id']." brand_list_checkbox brand_filter' name='brand_ids[]' value='".$list['id']."' $selected $checked><label class='brand_list_label' for='brand_id_".$list['id']."'>".$list['brand_name']."</label>
	 		    			</div>
	 		    			";
	 		    		}
			    	}
	 		    }
	 	return $layout;

	}


	function getSubCategoryList($category="")
	{
		$layout = "";

		$q = "SELECT id FROM ".SUB_CATEGORY_TBL." WHERE category_id='".$category."' AND delete_status='0' AND status='1' AND is_draft='0' ";
		$exe = $this->exeQuery($q);
		$ids = array();
		while($list = mysqli_fetch_array($exe)){
			$ids[] =	$list['id'];
		}
		$get_related_products = "";
		
		return $ids;
	}


	/*----------------------------------------------
				Manage classified 
	----------------------------------------------*/

	function contractorProfilePaginationCount()
	{
		$num_rec_per_page = 10;
		$sql = "SELECT id FROM ".CONTRACTOR_PROFILE_TBL." WHERE delete_status='0' AND status='1' ORDER BY id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page);
		return $total_pages;
	}

	function contractorProfilePagination($current)
	{
		$layout 	= "";
		$page_link 	= "hire";
		$num_rec_per_page = 10;
		$sql = "SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE delete_status='0' AND status='1' ORDER BY id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page);
		$page = $current;
		$limit= 4;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";
			if ($page > ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."".$page_link."?p=1' >1</a></li>".$dot_link;
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit)
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."".$page_link."?p=".$i."'>".$i."</a></li>";
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."".$page_link."?p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	

	function getClassifiedBtns($token="")
	{	
		$layout = "";
  		$q 		= "SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE status='1' AND delete_status='0' " ;
  		$query = $this->exeQuery($q);

  		$all_profiles = $this->getDetails(CONTRACTOR_TBL,"group_concat(profile_type) as rowsOfIds","invite_status='1' ");
  		$profile_ids  = explode(",", $all_profiles['rowsOfIds']);

	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){

	    		$active = (($list['token']==$token)? "active" : "");

	    		$check_if_empty = in_array($list['id'], $profile_ids);
				if($check_if_empty) {
					$layout.= "<a  href='".BASEPATH."hire/hirelist/".$list['token']."' ><button class='btn filter-classified $active' data-classified='".$list['token']."' > ".$list['profile']."</button></a>";
				}
                $i++;
	    	}
	    }
	    return $layout;
	}

	function getClassifiedList($token="",$page="",$search_key="")
	{	
		$layout 	= "";
		if( $search_key!=""){
  			$condition = "AND name LIKE '%".$search_key."%' OR company_name LIKE '%".$search_key."%'  ";
  		} else {
			$condition 	= " ";
  		}
  		$total_data = $this->check_query(CONTRACTOR_PROFILE_TBL,"*"," delete_status='0' AND status='1' ");
		$start_from = ($page-1)*2;
  		$page_count = 10;
		$q ="SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE delete_status='0' AND status='1'  ORDER BY id ASC LIMIT $start_from , $page_count ";
		$query = $this->exeQuery($q);
		if(mysqli_num_rows($query)>0) {
			while ($details = mysqli_fetch_array($query)) {
				$list  	 = $this->editPagePublish($details);
	    		$pic 	 = $list['file_name']=="" ? ASSETS_PATH."no_img.jpg" : SRCIMG.$list['file_name'];
	    		$min_one_hire_check   = $this->hireProfileListCheck($list['id']);
	    		if($min_one_hire_check) {
	    			$layout .= "
						<div class='col-lg-4 col-xl-3 col-md-6 col-12 classified_content ".$list['token']." '>
                            <div class='single_product rounded-0 hire-box'>
								<a href='".BASEPATH."hire/hirelist/".$list['token']."'>
									<img src='".$pic."' alt='' class='classified_img'>
									<p>".$list['short_description']."</p>
									<h4 class='text-center my-3'>".$list['profile']."</h4>
								</a>
                            </div>
                        </div>
							";
	    		}
			}
		} else {
			$layout = "<div class='cart_content '>No Records Found !!</div>";
		}
		$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = $start_from + 1;
		$result['start_to']   = $start_from + mysqli_num_rows($query);
		$result['totals']     = $total_data;
		return $result;
	}

	function hireProfileListCheck($hire_profile_id)
	{
		$q   = "SELECT profile_type FROM ".CONTRACTOR_TBL." WHERE delete_status='0' AND status='1' AND invite_status='1' ";
		$exe = $this->exeQuery($q);
		if(mysqli_num_rows($exe) > 0) {
			$result = array();
			while ($list = mysqli_fetch_assoc($exe)) {
				$profile_type_array = explode(",", $list['profile_type']);
				$result[] = in_array($hire_profile_id, $profile_type_array);
			}
			return array_sum($result);
		} else {
			return false;
		}
	}


	

	function contractorPaginationCount($token="",$search_key="")
	{
		$layout ="";

		if($token!="") {
			$cp_info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
 			$contractor_profile = " AND C.profile_type='".$cp_info['id']."'";
 		} else {
 			$contractor_profile = "";
 		}

 		$condition = "";

  		if( $search_key!=""){
 			if(isset($search_key['experience'])) {
 				$condition .= " AND (C.experience = ".$search_key['experience']." OR C.experience > ".$search_key['experience'].") ";
 			}
			if(isset($search_key['location'])) {
				$condition .= " AND C.city LIKE '%".$search_key['location']."%' ";
			}

  		} else {
			$condition 	= " ";
  		}

		$num_rec_per_page = 4;
		$sql = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft,CP.profile,CP.file_name as cp_img FROM ".CONTRACTOR_TBL." C LEFT JOIN ".CONTRACTOR_PROFILE_TBL." CP ON (CP.id=C.profile_type) WHERE C.delete_status='0' AND C.status='1' $condition AND C.invite_status='1'  AND profile_type!='' ORDER BY C.id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records

		if(mysqli_num_rows($rs_result)  > 0 && $token!="" ) {
			$total_records = 0;
			while ($list = mysqli_fetch_assoc($rs_result)) {
			$profile_type_array = explode(",", $list['profile_type']);
			$cp_info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
			$check_profile = in_array($cp_info['id'], $profile_type_array);
			$hire_profile  =  $cp_info['profile']; 

			if($check_profile)
				$total_records++;
			}
		}

		$total_pages = ceil($total_records / $num_rec_per_page);

		return $total_pages;
	}

	function contractorPagination($current,$token="",$search_key="",$search_key_link="",$url_token)
	{
		$layout 	= "";

		if($search_key_link=="") {
			$page_link 	= "hire/hirelist".$url_token."?";
		} else {
			//$page_link 	= "hire/search".$search_key_link."&";
			$page_link = "hire/search".$search_key_link."&hirelist".$url_token."?p=".($page+1);
		}

		$num_rec_per_page = 4;

		$condition = "";

		if( $search_key!=""){
 			if(isset($search_key['experience'])) {
 				$condition .= " AND (C.experience = ".$search_key['experience']." OR C.experience > ".$search_key['experience'].") ";
 			}
			if(isset($search_key['location'])) {
				$condition .= " AND C.city LIKE '%".$search_key['location']."%' ";
			}

  		} else {
			$condition 	= " ";
  		}

  		$sql = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft,CP.profile,CP.file_name as cp_img FROM ".CONTRACTOR_TBL." C LEFT JOIN ".CONTRACTOR_PROFILE_TBL." CP ON (CP.id=C.profile_type) WHERE C.delete_status='0' AND C.status='1' $condition AND C.invite_status='1' AND profile_type!=''  ORDER BY C.id ASC";
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records

		if(mysqli_num_rows($rs_result)  > 0 && $token!="" ) {
			$total_records = 0;
			while ($list = mysqli_fetch_assoc($rs_result)) {
			$profile_type_array = explode(",", $list['profile_type']);
			$cp_info            = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
			$check_profile      = in_array($cp_info['id'], $profile_type_array);
			$hire_profile       = $cp_info['profile']; 

			if($check_profile)
				$total_records++;
			}
		}


		$total_pages = ceil($total_records / $num_rec_per_page);
		$page = $current;
		$limit= 4;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";
			if ($page > ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."".$page_link."p=1' >1</a></li>".$dot_link;
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit)
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."".$page_link."p=".$i."'>".$i."</a></li>";
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."".$page_link."p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	function getHireList($token="",$page="",$search_key="")
	{	
		$layout 	= "";
		$condition 	= "";

		if( $search_key!=""){
 			if(isset($search_key['experience'])) {
 				$condition .= " AND (C.experience = ".$search_key['experience']." OR C.experience > ".$search_key['experience'].") ";
 			}
			if(isset($search_key['location'])) {
				$condition .= " AND C.city LIKE '%".$search_key['location']."%' ";
			}

  		} else {
			$condition 	= " ";
  		}

  		if($token!="") {
			$hire_ids     = array(); 
			$profile_info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");

			$q 	 = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft,CP.profile,CP.file_name as cp_img FROM ".CONTRACTOR_TBL." C LEFT JOIN ".CONTRACTOR_PROFILE_TBL." CP ON (CP.id=C.profile_type) WHERE C.delete_status='0' AND C.status='1' $condition AND C.invite_status='1' AND profile_type!=''  ORDER BY C.id ASC ";

			$exe = $this->exeQuery($q);

			if (mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$profile_type_array   = explode(",", $list['profile_type']);
					if(in_array($profile_info['id'], $profile_type_array)) {
						$hire_ids[] = $list['id'];
					}
				}

				if(count($hire_ids) > 0) {
					$profile_filter = " AND C.id IN (".implode(",", $hire_ids).")";
				} else {
					$profile_filter = "";
				}
				
			} else {
				$profile_filter = "";
			}
		} else {
			$profile_filter = "";
		}
		

  		$total_data = $this->check_query(CONTRACTOR_PROFILE_TBL,"*"," delete_status='0' AND invite_status='1' AND status='1' ");
		$start_from = ($page-1)*4;
  		$page_count = 4;
  		$q 	   ="SELECT C.id,C.token,C.profile_type,C.profile_verified,C.name,C.mobile,C.email,C.experience,C.experience_duration,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft,CP.profile,CP.file_name as cp_img FROM ".CONTRACTOR_TBL." C LEFT JOIN ".CONTRACTOR_PROFILE_TBL." CP ON (CP.id=C.profile_type) WHERE C.delete_status='0' AND C.status='1' $condition AND C.invite_status='1' AND profile_type!='' $profile_filter ORDER BY C.id ASC LIMIT $start_from, $page_count ";
		$query = $this->exeQuery($q);


		$query = $this->exeQuery($q);
		if(mysqli_num_rows($query)>0) {
			$i=1;
			while ($details = mysqli_fetch_array($query)) {
				$list  	 = $this->editPagePublish($details);
	    		$pic 	 = $list['file_name']=="" ? ASSETS_PATH."no_img.jpg" : SRCIMG.$list['file_name'];
	    		$cp_pic  = $list['cp_img']=="" ? ASSETS_PATH."no_img.jpg" : SRCIMG.$list['cp_img'];

		    	$profile = explode(",",$list['profile_type']);
	  			$hire_profile = "";

	  			foreach ($profile as $key => $value) {
	    			$profile_date   = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","id='".$value."' ");
	    			$Comma          = (($key==0)? "" : ",");
	  				$hire_profile .= $Comma." ".$this->unHyphenize($profile_date['token']);
	  			}

	  			$experience = (($list['experience']!="")? "<p class='float-start mb-0'>Experience : ".$list['experience']." ".$list['experience_duration']."</p>" : "");

	  			 if(isset($_GET['id'])) {  
                       if($_GET['id']==$this->encryptData($list['id'])) {   
                        $contact_details =
                        	"<p style='position:relative;top:5px;' class='float-end mb-0'>+91 - ".$list['mobile']."<br>".$list['email']."</p>
							 ";
                     } else { 
                        $contact_details =" <h3 class='float-end'><div style='position:relative;top:5px;'><button type='button' class='btn btn-sm theme-btn-dark float-end rounded-pill ps-3 pe-3' data-bs-toggle='modal' id='viewContatDetailsBtn' data-encrypted_hire_id='".$this->encryptData($list['id'])."' data-bs-target='#view_contact'>View Contact Details</button></div></h3>";
                     } 
                 } else {
                    $contact_details =" <h3 class='float-end'><div style='position:relative;top:5px;'><button type='button' class='btn btn-sm theme-btn-dark float-end  rounded-pill ps-3 pe-3' data-bs-toggle='modal' id='viewContatDetailsBtn' data-encrypted_hire_id='".$this->encryptData($list['id'])."' data-bs-target='#view_contact'>View Contact Details</button></div>			
					</h3>";
                 } 

                $projects = $this->gethireProjectsCaroselList($list['id']);

                if($projects!="") {
                	$project_carosel = "<div class='classified-pt-dts-img d-flex'>
                							".$projects."
											<div class='col-lg-3 hirelg_thumbanail_img'>
												<a class='' href='".BASEPATH."hire/details/".$list['token']."'>View More</a>
											</div>
                						</div>";
                } else {
            	    $project_carosel = " ";
                }


                $profile_verified_status = (($list['profile_verified']==1)? "<span class='ion-android-checkmark-circle' style='color:green;font-size: 12px;'> Verified</span>" : "" );
             

    			$layout .= "
					<div class='classified-lists border-0 bg-white p-3 w-100 mb-1 clearfix'>
						<div class='classified-users-img'>
	                        <a href='".BASEPATH."hire/details/".$list['token']."' class='clearfix clear'>
	                            <img src='".$pic."' alt='' class=''>
	                        </a>
                        </div>
						".$contact_details."
						<div style='position:relative;top:5px;' class='share_link_card_box float-end w-0'>
							<h4 class='float-end btn-group'><button type='button' data-share_hire_id='".$list['id']."' class='btn text-black-50 btn dropdown' data-bs-toggle='dropdown' aria-expanded='false'>
							<span class='fa fa-share-alt'></span>
							</button>
								<ul class='dropdown-menu dropdown-menu-end shareproductdetails share_links'>
									<li class='bg_whatsapp'><a href='https://api.whatsapp.com/send?text=Hey, Checkout this site content here   ".BASEPATH." - We provide content here.   ".BASEPATH."'hire/details/".$this->encryptData($list['id'])."' class='share_icon' rel='tooltip' title='Whatsapp'>
									<i class='fab fa-whatsapp'></i>
									</a></li>
								</ul>
							</h4>
						</div>
						<h4 class='float-start w-100'>
                        <div class='listing-classified-title'>
                            <h3 class='w-100'><a href='".BASEPATH."hire/details/".$list['token']."' class='clearfix clear '>".ucfirst($list['name'])." ".$profile_verified_status." </a></h3>
                    	
                            <h4 class='float-start w-100'><p class='float-start'>".$hire_profile."</p></h4>								
                            ".$experience."
                            <p class='float-start w-100'>".ucfirst($list['city'])."</p>
                        </div></h4>

                        <div class='classified-works clear d-block pt-3 row'>
	                        ".$project_carosel."
                        </div>                            	
                    </div>";
				
	    		$i++;
			}
		} else {
			$layout = "<div class='cart_content '>No Records Found !!</div>";
		}

		$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = $start_from + 1;
		$result['start_to']   = $start_from + mysqli_num_rows($query);
		$result['totals']     = $total_data;
		return $result;
	}

	function gethireProjectsCaroselList($hire_id="",$project_id="")
  	{	
  		
  		$layout = "";
  		$q = "SELECT id,file_name,project_id FROM ".CONTRACTOR_PROJECT_IMG_TBL." WHERE contractor_id='".$hire_id."' AND status='1' AND delete_status='0' Limit 3 " ;
  		$query = $this->exeQuery($q);
	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){
	    		$count 			= mysqli_num_rows($query);
				$project_image  = $list['file_name']!='' ? HIRE_UPLOADS.$list['file_name'] : ASSETS_PATH."no_img.png" ;
				$hide_img       = (($i==0)? : "display_none" );
				$layout.= " 
							<div class='col-lg-3 hirelg_thumbanail'>
                                <img src='".$project_image."' alt='' class=''>
                            </div>
                             
                            ";
                $i++;
	    	}
	    } else {
	    	$layout = "";
	    }
	    return $layout;
  	}

	function getServiceTags($service_tags="")
  	{	
  		
  		$layout = "";
        $get_tags = ($service_tags!="") ? "AND id IN (".$service_tags.") " : "" ;

  		$q = "SELECT id,token,service_tag FROM ".SERVICE_TAGS_TBL." WHERE  status='1' AND delete_status='0' ".$get_tags." " ;
  		$query = $this->exeQuery($q);
	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($details = mysqli_fetch_array($query)){
	 		    $list  = $this->editPagePublish($details);
	    		$layout.= "
							<a href='#'>".$list['service_tag']."</a>
							";
	            $i++;
		    }
	    } else {

	    }
	    return $layout;
  	}

  	function getProjectSliderImgs($hire_id="",$project_id="")
  	{	
  		
  		$layout = "";
  		$q = "SELECT id,file_name,project_id FROM ".CONTRACTOR_PROJECT_IMG_TBL." WHERE contractor_id='".$hire_id."' AND status='1' AND delete_status='0' " ;
  		$query = $this->exeQuery($q);
	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){
	    		$count 			= mysqli_num_rows($query);
				$project_image  = $list['file_name']!='' ? HIRE_UPLOADS.$list['file_name'] : ASSETS_PATH."no_img.png" ;
				$hide_img       = (($i==0)? : "display_none" );
				$layout.= " 
							<div class='carousel__slide'>
                                <img src='".$project_image."' alt=''>
                            </div>
                             
                            ";
                $i++;
	    	}
	    } else {
	    	$layout = " ";
	    }
	    return $layout;
  	}

  	// Get Classified project list


	function getClassifiedProjects($contractor_id="")
	{

		$layout = "";
		$q = "SELECT * FROM ".CONTRACTOR_PROJECT_TBL." WHERE delete_status='0' AND status='1' AND contractor_id='".$contractor_id."'  ORDER BY id DESC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 		      = $this->editPagePublish($details);
				$verified_status  = (($list['status']==1) ? "<span class='ion-ribbon-a'> </span><span class='location_customer text-success'>Verified</span>" : ""); 
				$layout.= " <div class='col-sm-6'>
                                <div class='text-center bg-white dt-page-worksamples owl-carousel owl-theme' style='max-height: 260px;'>
                                   ".$this->getProjectImgs($list['id'])."
                                </div>
                            </div>";

	    		
	    		$i++;
	    	}
	    }
	    	return $layout;
	}

	function getProjectImgs($project_id="")
  	{	
  		
  		$layout = "";
  		$q = "SELECT IM.id,IM.file_name,IM.project_id,CP.status,CP.verified_status FROM ".CONTRACTOR_PROJECT_IMG_TBL." IM LEFT JOIN ".CONTRACTOR_PROJECT_TBL." CP ON (CP.id=IM.project_id) WHERE IM.project_id='".$project_id."' AND IM.status='1' AND IM.delete_status='0' " ;
  		$query = $this->exeQuery($q);
	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){
	    		$count 			= mysqli_num_rows($query);
	    		$verified       = (($list['verified_status']==1)? "<span class='ion-android-checkmark-circle' style='color:green;'></span> Verified" : " " );
				$project_image  = $list['file_name']!='' ? HIRE_UPLOADS.$list['file_name'] : ASSETS_PATH."no_img.png" ;
				$hide_img       = (($i==0)? : "display_none" );
				$layout.= " <a class='item project_modal' data-project_id='".$list['project_id']."' href='".$project_image."' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                                <img src='".$project_image."' alt='' >
                                <p class='p-3'>".$verified."</p>
                            </a>
                          ";
                $i++;
	    	}
	    } else {
			$project_image  = ASSETS_PATH."no_img.png" ;
			$verified       = (($list['verified_status']==1)? "<span class='ion-android-checkmark-circle' style='color:green;'></span> Verified" : " " );
			$layout.= " <a class='item project_modal' data-project_id='".$list['project_id']."' href='".$project_image."' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                            <img src='".$project_image."' alt='' >
                            <p class='p-3'>".$verified."</p>
                        </a>";
	    }
	    return $layout;
  	}


  	function classifiedEnquiry($data)
  	{
  		$curr 		= date("Y-m-d H:i:s");
		$id 		= $this->decryptData($data['session_token']);
	    $query = "INSERT INTO ".CONTRACTOR_ENQUIRY_TBL." SET 
	    	name 			= '".$this->cleanString($data['name'])."',
	        email 			= '".$this->cleanString($data['email'])."',
	        mobile 			= '".$this->cleanString($data['mobile'])."',
	        message 		= '".$this->cleanString($data['message'])."',
	    	contractor_id	= '".$id."',
	        status 			= '1',
	        created_at 		= '".$curr."',
	        updated_at 		= '".$curr."'";
	    $result 	= $this->lastInserID($query);
	    if ($result) {
	    	$c_info = $this->getDetails(CONTRACTOR_TBL,"*","id='".$id."' ");

	    	$sender 		= COMPANY_NAME;
			$sender_mail 	= NO_REPLY;
			$subject 		= COMPANY_NAME." ".$data['name']."'s contract Enquiry has been submitted on ".date("d.m.Y");
			$receiver_mail 	= $this->cleanString($c_info['email']);
			$message  		= $this->contractEnquiry($c_info,$data);
			$send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);

		    /*	$query = "SELECT * FROM ".NOTIFICATION_EMAIL_TBL." WHERE 1";
	            $e_info = $this->exeQuery($query);
	            if(mysqli_num_rows($e_info) > 0){
	                $i = 1;
	                while($list = mysqli_fetch_array($e_info)){
						$sender 		= COMPANY_NAME;
						$sender_mail 	= NO_REPLY;
						$subject 		= COMPANY_NAME." ".$data['name']."'s contract Enquiry has been submitted on ".date("d.m.Y");
						$receiver_mail 	= $this->cleanString($list['email']);
						$message  		= $this->contractEnquiry($data);
						$send_mail 		= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
	                }
	            } 
	        */

	        return $send_mail;
	    } else {
	        return "Sorry!! Unexpected Error Occurred. Please try again.";
	    }

  	}


  	// classified Contact details viewd form

    function viewdClassifiedDetails($input)
    {	
    	$curr 		= date("Y-m-d H:i:s");
		$data 		= $this->cleanStringData($input);
		$id 		= $this->decryptData($data['session_token']);		
		$cus_name 	= $this->hyphenize($data['name']);
		$cus_info   = $this->getDetails(CONTRACTOR_DETAILS_VIEWED_TBL,"*"," contractor_id='".$id."' ");

		$cus_info 	= $this->check_query(CONTRACTOR_DETAILS_VIEWED_TBL,"id","contractor_id='".$id."' AND mobile='".$data['mobile']."' ");
		if($cus_info==0){
			$query = "INSERT INTO ".CONTRACTOR_DETAILS_VIEWED_TBL." SET 
						name 			= '".$cus_name."',
						mobile 			= '".$data['mobile']."',
						contractor_id	= '".$id."',
						status			= '1',
						created_at 		= '".$curr."',
						updated_at 		= '".$curr."' ";
			$exe = $this->exeQuery($query);
			if($exe){
				return $data['session_token'];
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}	
		}else{
			return $data['session_token'];
		}	
    }

    // Get Classified Profile  Details

	function projectInfoold($data)
	{		
		$layout = "";
		$query  = "SELECT * FROM  ".CONTRACTOR_PROJECT_TBL."  WHERE id='$data' ";
		$exe 	= $this->exeQuery($query);
		$result = mysqli_fetch_assoc($exe);

  		

	    $result['images'] = $images;

		return  json_encode($result);
	}

	function projectInfo($data='')
    {
        $project_id   = $data;
        $project_info = $this->getDetails(CONTRACTOR_PROJECT_TBL,"*","id='".$project_id."' ");
        $verified     = (($project_info['verified_status']==1)? "Verfied" : "");
        $layout = '
                <div class="col-sm-6 text-center">
                  <h3>'.$project_info['project_title'].'</h3>
                  <p>'.$project_info['description'].'</p>
                  <span>'.$verified.'</span>
                </div>
                <div class="col-sm-6">
                    <div class="popup-hire owl-carousel owl-theme">
                        '.$this->projectImgList($project_id).'
                    </div>
                </div>
                <script>
                    $(".popup-hire").owlCarousel({
                        loop: true,
                        nav: true,
                        dots: false,
                        autoplayHoverPause: true,
                        autoplay: true,
                        mouseDrag: true,
                        responsive: {
                            0: {
                                items: 1,
                            },
                            576: {
                                items: 1,
                            },
                            768: {
                                items: 1,
                            },
                            1200: {
                                items: 1,
                            },
                        }
                    });
                </script>
                ';
        return json_encode($layout);
    }
    // Awards Img List
    function projectImgList($data='')
    {
        $images = "";
  		$q = "SELECT id,file_name,project_id FROM ".CONTRACTOR_PROJECT_IMG_TBL." WHERE project_id='".$data."' AND status='1' AND delete_status='0' " ;
  		$query = $this->exeQuery($q);
	    if(@mysqli_num_rows($query)>0){
	    	$i=0;
	    	while($list = mysqli_fetch_array($query)){
	    		$count 			= mysqli_num_rows($query);
				$project_image  = $list['file_name']!='' ? HIRE_UPLOADS.$list['file_name'] : ASSETS_PATH."no_img.png" ;
				$hide_img       = (($i==0)? : "display_none" );
				$images.= " 
                            <a class='item' href='#'>
                                <img src='".$project_image."' alt=''>
             				</a>
                            ";
                $i++;
	    	}
	    } else {
	    	$images = "No Records Found";
	    }
        return $images;
    }

	// Profile Search Items 

	function profileSearchItems($data)
	{	
		// Results 
		$profiles 		= array();
		$layout         = "";

		$strng = strtolower( $data );
		$strng = trim(preg_replace('/\s+/',' ', $strng));
		$s_key = trim(preg_replace('/\s+/','-', $strng));
		
		if($data!="") {
			$p_query  	= "SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE token LIKE   '%".$s_key."%' GROUP BY profile ";
			$p_exe 		= $this->exeQuery($p_query);
		    if(mysqli_num_rows($p_exe)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($p_exe)) {
		    		$list  = $this->editPagePublish($row);
		    		$profiles[$list['token']] = $list['profile'];
		    		$i++;
		    	}
		    }
		}

		if(count($profiles)>0) {
			$layout .="<div class='menu_title'><strong>Profiles</strong></div>";
				foreach ($profiles as $key => $value) {
					$layout.=" <a class='profileSelect' data-profile='".$value."' > <div class='menu_items' >".$value."</div> </a>";
				}
		}

		
		if($layout=="") {
			$layout .= "<div class='menu_items'>No Items</div>";
		}

	    return  $layout;
	}

	// Location search items

	function locationSearchItems($data)
	{	
		// Results 
		$locations 		= array();
		$layout         = "";

		$s_key = trim(preg_replace('/\s+/',' ', $data));
		
		if($data!="") {
			$p_query  	= "SELECT * FROM ".CONTRACTOR_TBL." WHERE city LIKE '%".$s_key."%' GROUP BY city ";
			$p_exe 		= $this->exeQuery($p_query);
		    if(mysqli_num_rows($p_exe)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($p_exe)) {
		    		$list  = $this->editPagePublish($row);
		    		$locations[$list['city']] = $list['city'];
		    		$i++;
		    	}
		    }
		}

		if(count($locations)>0) {
			$layout .="<div class='menu_title'><strong>City</strong></div>";
				foreach ($locations as $key => $value) {
					$layout.=" <a class='locationSelect' data-profile='".ucfirst($value)."' > <div class='menu_items' >".ucfirst($value)."</div> </a>";
				}
		}
		
		if($layout=="") {
			$layout .= "<div class='menu_items'>No Items</div>";
		}

	    return  $layout;
	}


  	/*----------------------------------------------
				Manage architect 
	----------------------------------------------*/

	function architectPaginationCount()
	{
		$layout ="";

		$num_rec_per_page = 12;
		$sql = "SELECT id FROM ".CONTRACTOR_TBL." WHERE is_draft='0' AND profile_type='2' AND invite_status='1' ORDER BY id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page);
		return $total_pages;
	}

	function architectPagination($current="")
	{
		$layout ="";
		
		$page_link = "architect";
		$num_rec_per_page = 12;
		$sql = "SELECT * FROM ".CONTRACTOR_TBL." WHERE is_draft='0' AND profile_type='2' AND invite_status='1' ORDER BY id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records
		$total_pages = ceil($total_records / $num_rec_per_page);
		$page = $current;
		$limit= 12;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";
			if ($page > ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."".$page_link."?p=1' >1</a></li>".$dot_link;
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit)
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."".$page_link."?p=".$i."'>".$i."</a></li>";
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."".$page_link."?p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	function getArchitectList($page="",$search_key="")
	{	
		$layout = "";

		$condition = " ";
		if( $search_key!=""){
  			$condition = "AND name LIKE '%".$search_key."%' OR company_name LIKE '%".$search_key."%'  ";
  		}

  		$q1 ="SELECT * FROM ".CONTRACTOR_TBL." WHERE is_draft='0' $condition AND profile_type='2' AND invite_status='1'  ORDER BY id ASC ";
		$query1 = $this->exeQuery($q1);
  		
		$layout = "";
		$start_from = ($page-1)*12;
  		$page_count = 12;
		$q ="SELECT * FROM ".CONTRACTOR_TBL." WHERE is_draft='0' $condition AND profile_type='2' AND invite_status='1'  ORDER BY id ASC LIMIT $start_from , $page_count ";
		$query = $this->exeQuery($q);
		if(mysqli_num_rows($query)>0) {
			$i=1;
			while ($details = mysqli_fetch_array($query)) {
				$list  = $this->editPagePublish($details);
	    		$pic 		= $list['file_name']=="" ? ASSETS_PATH."logo.png" : SRCIMG.$list['file_name'];

				$layout .= "
						<div class='col-lg-4 col-xl-3 col-md-6 col-12 '>
                            <div class='single_product'>
                                <div class='product_thumb'>
                                    <a href='".BASEPATH."architect/details/".$list['token']."'><img src='".$pic."' alt='' class='architect_list_img'></a>
                                </div>
                                <div class='product_name grid_name'>
                                    <h3><a  href='".BASEPATH."architect/details/".$list['token']."' title='".$list['name']."'>".$list['name']."</a></h3>
                                </div>
                                <div class='product_content grid_content'>
                                    <div class='content_inner'>
                                        <div class='product_footer d-flex align-items-center'>
                                            <div class='price_box'>
                                                <span class='ion-ribbon-a'></span>
                                                <span class='location_customer'>".$list['company_name']."</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span>".$list['total_projects']." + Projects Done</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
							";
	    		$i++;
			}
		} else {
			$layout = "<div class='cart_content '>No Records Found !!</div>";
		}

		$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = $start_from + 1;
		$result['start_to']   = $start_from + mysqli_num_rows($query);
		$result['totals']     = mysqli_num_rows($query1);
		return $result;
	}

	/*-------------------------------------------
                Claculator Functions
	-------------------------------------------*/

	// Plastering Claulations

	function plasterCalculation($input) 
	{	
		$result = array();

		$unit 			   = $input['unit'];
		$plasterType 	   = $input['plasterType'];
		$lengthMeterOrFeet = $input['lengthMeterOrFeet'];
		$lengthCmOrInch    = (($input['lengthCmOrInch']=="")? 0 : $input['lengthCmOrInch'] );
		$widthMeterOrFeet  = $input['widthMeterOrFeet'];
		$widthCmOrInch 	   = (($input['widthCmOrInch']=="")? 0 : $input['widthCmOrInch']);
		$gradeOfFooting    = explode(",",$input['gradeOfFooting']);

		$plastere_thickness = $plasterType/1000;
		
		$lengthCmOrInch_numlength  =  mb_strlen($lengthCmOrInch);
		$widthCmOrInch_numlength   =  mb_strlen($widthCmOrInch);
		$lengthCmOrInch_decimal    = (($lengthCmOrInch_numlength==2)? ($lengthCmOrInch/100) : ($lengthCmOrInch/10) ) ;
		$widthCmOrInch_decimal 	   = (($widthCmOrInch_numlength==2)? ($widthCmOrInch/100) : ($widthCmOrInch/10) ) ;

		$length 		    = $input['lengthMeterOrFeet'] + $lengthCmOrInch_decimal;
		$width  			= $input['widthMeterOrFeet']  + $widthCmOrInch_decimal;
		$cement_ratio       = $gradeOfFooting[0];
		$sand_ratio         = $gradeOfFooting[1];
		$sum_of_ratio       = $cement_ratio +  $sand_ratio;


		// values divide by 10.764 for coverting feet to meter
		
		if($unit=="meter_or_cm") {
			$plaster_area = $length * $width;			
		} else {
			$plaster_area = ($length * $width)/10.764 ;			
		}

		$valoume_of_mortar = $plaster_area * $plastere_thickness;

		// Add 30% to fill up join & Cover surface

		$valoume_of_mortar_cover = $valoume_of_mortar +($valoume_of_mortar * 0.3 );

		// Increases by 25% of the total dry volume

		$valoume_of_mortar_dry = $valoume_of_mortar_cover +($valoume_of_mortar_cover * 0.25 );

		// Amount of Cement Require (Note : 1 Bag of cement = 0.035 m3. 1 Cement bag contains = 50 kg cement)

		$cement_bag_required = ((($valoume_of_mortar_dry * $cement_ratio) / $sum_of_ratio ) / 0.035);

		$cement_required_in_kg = round(($cement_bag_required * 50),2);

		// Quantity of Sand Require (Note: By considering dry density of sand = 1550 kg/m3. 1000 kg = 1 Ton)

		$sand_required_in_kg = ((($valoume_of_mortar_dry * $sand_ratio) / $sum_of_ratio ) * 1550);

		$sand_required_in_ton = sprintf('%0.2f', ($sand_required_in_kg/1000) );  //round(($sand_required_in_kg / 1000),2);

		$layout = "<tr><td>1</td><td>Cement</td><td>".ceil($cement_bag_required)." Bags</td></tr><tr><td>2</td><td>Sand</td><td>".$sand_required_in_ton." Ton</td></tr>";

		$result['layout']   = $layout;
		$result['cement']   = ceil($cement_bag_required);
		$result['sand']     = $sand_required_in_ton;
		$result['sand_cft'] = $sand_required_in_ton * 22;

		return json_encode($result);
	}

	// Brick Work Calculation

	function brickWorkClaculation($input)
	{
		$result            = array();
		$unit              = $input['unit'];
		$lengthMeterOrFeet = $input['lengthMeterOrFeet'];
		$lengthCmOrInch    = (($input['lengthCmOrInch']=="")? 0 : $input['lengthCmOrInch'] );
		$widthMeterOrFeet  = $input['widthMeterOrFeet'];
		$widthCmOrInch     = (($input['widthCmOrInch']=="")? 0 : $input['widthCmOrInch']);
		$wallThickness     = $input['wallThickness'];
		$lengthOfBrick     = $input['lengthOfBrick'];
		$widthOfBrick      = $input['widthOfBrick'];
		$heightOfBrick     = $input['heightOfBrick'];
		$ratio             = explode(",",$input['ratio']);


		if($unit=="feet_or_inch") {
			$lengthOfBrick = $input['lengthOfBrick'] * 2.54;
			$widthOfBrick  = $input['widthOfBrick'] * 2.54;
			$heightOfBrick = $input['heightOfBrick'] * 2.54;

		}


		if($wallThickness=="others_partition") {
			if ($unit=="meter_or_cm") {
				$wallThickness = $input['otherPartition']/100;
			} else {
				$wallThickness = $input['otherPartition']/39.37;
			}
		} else {
			$wallThickness = $input['wallThickness']/100;
		}

		// cm value divide by 100 for convert cm to mm & inch value divide by 12 for convert inch to meter

		$lengthCmOrInch_decimal    = (($unit=="meter_or_cm")? ($lengthCmOrInch/100) : ($lengthCmOrInch/12) ) ;
		$widthCmOrInch_decimal 	   = (($unit=="meter_or_cm")? ($widthCmOrInch/100) : ($widthCmOrInch/12) ) ;

		$length 		    = $input['lengthMeterOrFeet'] + $lengthCmOrInch_decimal;
		$width  			= $input['widthMeterOrFeet']  + $widthCmOrInch_decimal;

		$cement_ratio       = $ratio[0];
		$sand_ratio         = $ratio[1];
		$sum_of_ratio       = $cement_ratio +  $sand_ratio;

		// values divide by 3.281 for coverting feet to meter
		
		if($unit=="meter_or_cm") {
			$length = $length;			
			$width  = $width;			
		} else {
			$length = $length/3.281;			
			$width  = $width/3.281;			
		}

		$volume_of_brick_masonry = round(($length * $width * $wallThickness),4,PHP_ROUND_HALF_UP);
		// $volume_of_brick_masonry = $length * $width * $wallThickness;

		$lengthOfBrick_in_mm = $lengthOfBrick/100;
		$widthOfBrick_in_mm  = $widthOfBrick/100;
		$heightOfBrick_in_mm = $heightOfBrick/100;

		$brick_size = ($lengthOfBrick_in_mm) * ($widthOfBrick_in_mm) * ($heightOfBrick_in_mm) ; 

		$size_of_brick_with_mortar = ($lengthOfBrick_in_mm + 0.01) * ($widthOfBrick_in_mm + 0.01) * ($heightOfBrick_in_mm + 0.01) ; 

		$no_of_bricks = $volume_of_brick_masonry/$size_of_brick_with_mortar;

		$actual_volume_of_brick_mortar = $no_of_bricks * $brick_size;

		$quantity_of_mortar = $volume_of_brick_masonry - $actual_volume_of_brick_mortar;


		// Add 15% more for wastage, Non - uniform thickness of mortar joins

		$quantity_of_mortar_wastage = $quantity_of_mortar + ( $quantity_of_mortar * 0.15 );

		// Add 25% more for Dry Volume

		$quantity_of_mortar_dry = $quantity_of_mortar_wastage + ( $quantity_of_mortar_wastage * 0.25 );

		// Amount of Cement

		$cement = ($cement_ratio/$sum_of_ratio) * $quantity_of_mortar_dry;

		// 1 Bag of Cement = 0.035 m3

		$required_cement_in_kg = ($cement/0.035)*50;

		$required_cement_bag   = ceil($required_cement_in_kg/50);

		// Amount of Sand Required

		$sand = ($sand_ratio/$sum_of_ratio) * $quantity_of_mortar_dry;

		// Quantity of Sand Require (Note: By considering dry density of sand = 1550 kg/m3. 1000 kg = 1 Ton)

		$required_sand_in_kg    = $sand * 1500;

		$required_sand_in_ton   = sprintf('%0.2f', ($required_sand_in_kg/1000) ); // round(($required_sand_in_kg/1000),2);

		$layout = "<tr><td>1</td><td>Brick</td><td>".ceil($no_of_bricks)." Bricks</td></tr><tr><tr><td>2</td><td>Cement</td><td>".$required_cement_bag." Bags</td></tr><tr><td>3</td><td>Sand</td><td>".$required_sand_in_ton." Ton</td></tr>";

		$result['layout']       = $layout;
		$result['bricks']       = ceil($no_of_bricks);
		$result['cement']       = $required_cement_bag;
		$result['sand']         = $required_sand_in_ton;
		$result['sand_cft']     = $required_sand_in_ton * 22;
		$result['cement_graph'] = $required_cement_bag * 50;
		$result['sand_graph']   = $required_sand_in_ton * 1000;

		return json_encode($result);
		 
	}

	// Concrete Calculation

	function concreteCalculation($input)
	{	
		$result = array();

		$unit 			   = $input['unit'];
		$lengthMeterOrFeet = $input['lengthMeterOrFeet'];
		$lengthCmOrInch    = (($input['lengthCmOrInch']=="")? 0 : $input['lengthCmOrInch'] );
		$widthMeterOrFeet  = $input['widthMeterOrFeet'];
		$widthCmOrInch     = (($input['widthCmOrInch']=="")? 0 : $input['widthCmOrInch']);
		$depthMeterOrFeet  = $input['depthMeterOrFeet'];
		$depthCmOrInch     = (($input['depthCmOrInch']=="")? 0 : $input['depthCmOrInch']);
		$gradeOfConcrete   = explode(",",$input['gradeOfConcrete']);


		// cm value divide by 100 for convert cm to mm & inch value divide by 12 for convert inch to meter

		$lengthCmOrInch_decimal = (($unit=="meter_or_cm")? ($lengthCmOrInch/100) : ($lengthCmOrInch/12) ) ;
		$widthCmOrInch_decimal  = (($unit=="meter_or_cm")? ($widthCmOrInch/100) : ($widthCmOrInch/12) ) ;
		$depthCmOrInch_decimal  = (($unit=="meter_or_cm")? ($depthCmOrInch/100) : ($depthCmOrInch/12) ) ;

		$length = $input['lengthMeterOrFeet'] + $lengthCmOrInch_decimal;
		$width  = $input['widthMeterOrFeet']  + $widthCmOrInch_decimal;
		$depth  = $input['depthMeterOrFeet']  + $depthCmOrInch_decimal;

		$cement_ratio       = $gradeOfConcrete[0];
		$sand_ratio         = $gradeOfConcrete[1];
		$aggregate_ratio    = $gradeOfConcrete[2];
		$sum_of_ratio       = $cement_ratio + $sand_ratio + $aggregate_ratio ;

		// values divide by 3.281 for coverting feet to meter

		if($unit=="meter_or_cm") {
			$length = $length;			
			$width  = $width;			
			$depth  = $depth;			
		} else {
			$length = $length/3.281;			
			$width  = $width/3.281;			
			$depth  = $depth/3.281;			
		}

		$cement_concrete_volume = $length * $width * $depth;

		// Wet volume of mix is 52.4 % higher than dry volume

		$wet_volume_of_mix = $cement_concrete_volume + ( ( $cement_concrete_volume * (52.4/100) ) );

		// Cement Volume (Note: 1 Bag of cement = 0.035 m3 & 1 Cement bag contains = 50 kg cement)

		$cement_volume         = ($cement_ratio/$sum_of_ratio) * $wet_volume_of_mix ;

		$required_cement_bags  = $cement_volume / 0.035;

		$required_cement_in_kg = $required_cement_bags * 50 ;

		// Sand Volume (Note: 1 Bag of sand = 0.035 m3 & 1 sand bag contains = 50 kg sand)

		$sand_volume         = ($sand_ratio/$sum_of_ratio) * $wet_volume_of_mix ;

		$required_sand_kg     = $sand_volume * 1550;

		$required_sand_in_ton = sprintf('%0.2f', ($required_sand_kg/1000) ); // $required_sand_kg / 1000 ;

		// Aggregate Volume (Note: By considering dry loose bulk density of aggregate = 1350 kg/m3.1000 kg = 1 Ton)

		$aggregate_volume   = ($aggregate_ratio/$sum_of_ratio) * $wet_volume_of_mix ;

		$required_aggregate_kg     = $aggregate_volume * 1350;

		$required_aggregate_in_ton = sprintf('%0.2f', ($required_aggregate_kg/1000) ); // $required_aggregate_kg / 1000 ;

		$layout = "<tr><td>1</td><td>Cement</td><td>".ceil($required_cement_bags)." Bags</td></tr><tr><tr><td>2</td><td>Sand</td><td>".round($required_sand_in_ton,2)." Ton</td></tr><tr><td>3</td><td>Aggregate</td><td>".round($required_aggregate_in_ton,2)." Ton</td></tr>";

		$result['layout'] 	      = $layout;
		$result['cement'] 	      = ceil($required_cement_bags);
		$result['sand'] 	      = round($required_sand_in_ton,2);
		$result['sand_cft']  	  = round($required_sand_in_ton * 22,2);
		$result['aggregate']      = round($required_aggregate_in_ton,2);
		$result['aggregate_cft']  = round($required_aggregate_in_ton * 23.24,2);

		return json_encode($result);
	}

	// Tile WOrk Calculation

	function tileWorkCalculation($input)
	{	
		$result = array();

		$unit 			   =  $input['unit'];
		$lengthMeterOrFeet =  $input['lengthMeterOrFeet'];
		$lengthCmOrInch    =  (($input['lengthCmOrInch']=="")? 0 : $input['lengthCmOrInch'] );
		$widthMeterOrFeet  =  $input['widthMeterOrFeet'];
		$widthCmOrInch     =  (($input['widthCmOrInch']=="")? 0 : $input['widthCmOrInch']);
		$tileLength        =  $input['tileLength'];
		$tileWidth         =  $input['tileWidth'];

		$lengthCmOrInch_numlength  =  mb_strlen($lengthCmOrInch);
		$widthCmOrInch_numlength   =  mb_strlen($widthCmOrInch);
		$lengthCmOrInch_decimal    = (($lengthCmOrInch_numlength==2)? ($lengthCmOrInch/100) : ($lengthCmOrInch/10) ) ;
		$widthCmOrInch_decimal 	   = (($widthCmOrInch_numlength==2)? ($widthCmOrInch/100) : ($widthCmOrInch/10) ) ;

		$length 		    = $input['lengthMeterOrFeet'] + $lengthCmOrInch_decimal;
		$width  			= $input['widthMeterOrFeet']  + $widthCmOrInch_decimal;

		if($unit=="meter_or_cm") {
			$tile_flooring_area = $length * $width;			
		} else {
			$tile_flooring_area = ($length * $width)/10.764 ;			
		}

		// Tile required

		$area_of_tiles = $tileLength * $tileWidth;

		$required_tiles = ($tile_flooring_area * 10.764) / $area_of_tiles;

		// Amount Of Cement (Note: Assuming Thickness of Bedding is 0.07m & Ratio of Mortar 1:6 

		$volume_with_bedding   = $tile_flooring_area *  0.07 ;

		// 1 Bag of cement = 0.035 m3 & 1 Cement bag contains = 50 kg cement

		$cement_bag_required   = ( (($volume_with_bedding*1)/7) / 0.035 );

		$cement_required_in_kg = $cement_bag_required * 50 ; 


		// Amount Of Sand (Note: Assuming Thickness of Bedding is 0.07m Ratio of Mortar 1:6)

		$sand_required_in_kg    = ( (($volume_with_bedding*6)/7) * 1550 );
		
		$sand_required_in_ton   =  sprintf('%0.2f', ($sand_required_in_kg/1000) ); //$sand_required_in_kg / 1000 ; 

		$layout = "<tr><td>1</td><td>Tiles</td><td>".ceil($required_tiles)." No.of Tiles</td></tr><tr><td>2</td><td>Cement</td><td>".ceil($cement_bag_required)." Bags</td></tr><tr><td>3</td><td>Sand</td><td>".$sand_required_in_ton." Ton</td></tr>";

		$result['layout']   = $layout;
		$result['tile']     = ceil($required_tiles);
		$result['cement']   = ceil($cement_bag_required);
		$result['sand']     = $sand_required_in_ton;
		$result['sand_cft'] = ceil($sand_required_in_ton * 22 );

		return json_encode($result);

	}

	// Wall finish Calculation 

	function wallFinishCalculation($input)
	{	
		$result = array();

		$unit          = $input['unit'];
		$carpet_area   = $input['carpet_area'];
		$no_of_doors   = $input['no_of_doors'];
		$door_width    = (($unit=='feet_or_inch')? $input['wod_door_width'] : $input['door_width']) ;
		$door_hight    = (($unit=='feet_or_inch')? $input['wod_door_hight'] : $input['door_hight']) ;
		$no_of_windows = $input['no_of_windows'];
		$window_width  = (($unit=='feet_or_inch')? $input['wod_window_width'] : $input['window_width']) ;
		$window_hight  = (($unit=='feet_or_inch')? $input['wod_window_hight'] : $input['window_hight']) ;

		// Paint Area (Note: Approx Paint Area Including Wall And Ceiling)

		$paint_area = $carpet_area * 3.5 ;

		// Door and window area

		$door_area = $door_width * $door_hight * $no_of_doors;

		// window and window area

		$window_area = $window_width * $window_hight * $no_of_windows;

		// Actual Paint Area

		$actual_paint_area = $paint_area -  $door_area - $window_area;

		// Paint (Note: 1 liter of paint cover upto100 sq.ft of Area)

		$paint = $actual_paint_area / 100;

		// Primer (Note: 1 liter of primer cover upto100 sq.ft of Area)

		$primer = $actual_paint_area / 100; 

		// Putty (Note: 2.5 kg of putty cover upto100 sq.ft of Area)

		$putty = $actual_paint_area / 40; 

		$layout = "<tr><td>1</td><td>Paint</td><td>".sprintf('%0.2f',$paint)." liters</td></tr><tr><td>2</td><td>Primer</td><td>".sprintf('%0.2f',$primer)." liters</td></tr><tr><td>3</td><td>Putty</td><td>".$putty." kgs</td></tr>";

		$result['layout'] = $layout;
		$result['paint']  = $paint;
		$result['primer'] = $primer;
		$result['sand']   = $putty;

		return json_encode($result);
	}


	function contractorSearchPaginationCount($token="",$search_key="")
	{
		$layout ="";

		$condition = "";

  		if( $search_key!=""){
  			if(isset($search_key['hire'])){
				$condition 	= "AND C.name LIKE '%".$search_key['hire']."%' OR C.company_name LIKE '%".$search_key['hire']."%'  ";
			}
 			if(isset($search_key['experience'])) {
 				$condition .= " AND (C.experience = ".$search_key['experience']." OR C.experience > ".$search_key['experience'].") ";
 			}
			if(isset($search_key['location'])) {
				$condition .= " AND C.city LIKE '%".$search_key['location']."%' ";
			}

  		} else {
			$condition 	= " ";
  		}

		if($token!="") {
			$cp_info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
 			$contractor_profile = " AND C.profile_type='".$cp_info['id']."'";
 		
 		} elseif(isset($search_key['hire'])) {

			$hire_ids = array();
			$hire_profile_ids = array();
			
			$query = "SELECT id FROM ".CONTRACTOR_PROFILE_TBL." WHERE token='".$search_key['hire']."' OR token LIKE '%".$search_key['hire']."' ";

			$exe   = $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$hire_profile_ids[] = $list['id'];
				}
			}


			foreach ($hire_profile_ids as $key => $value) {
				$q 	 = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft FROM ".CONTRACTOR_TBL." C WHERE C.delete_status='0' AND C.is_draft='0' AND C.status='1' $condition AND C.invite_status='1' AND C.profile_type!='' ORDER BY C.id ASC ";


				$exe = $this->exeQuery($q);

				if (mysqli_num_rows($exe) > 0) {
					while ($list = mysqli_fetch_assoc($exe)) {
						
						$profile_type_array   = explode(",", $list['profile_type']);

						if(in_array($value, $profile_type_array)) {
							$hire_ids[] = $list['id'];
						}

					}

				} 
			}

			$hire_ids_unique = array_unique($hire_ids);
			if(count($hire_ids_unique) > 0) {
				$contractor_profile = " AND C.id IN (".implode(",", $hire_ids_unique).")";
			} else {
				$contractor_profile = "";
			}

		} else {
 			$contractor_profile = "";
 		}

 		

		$num_rec_per_page = 4;
		$sql = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft FROM ".CONTRACTOR_TBL." C WHERE C.delete_status='0' AND C.status='1' $condition AND C.invite_status='1' AND C.is_draft='0' AND C.profile_type!='' $contractor_profile  ORDER BY C.id ASC "; 
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records

		// if(mysqli_num_rows($rs_result)  > 0 && $token!="" ) {
		// 	$total_records = 0;
		// 	while ($list = mysqli_fetch_assoc($rs_result)) {
		// 	$profile_type_array = explode(",", $list['profile_type']);
		// 	$cp_info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
		// 	$check_profile = in_array($cp_info['id'], $profile_type_array);
		// 	$hire_profile  =  $cp_info['profile']; 

		// 	if($check_profile)
		// 		$total_records++;
		// 	}
		// }

		$total_pages = ceil($total_records / $num_rec_per_page);

		return $total_pages;
	}

	function contractorSearchPagination($current,$token="",$search_key="",$search_key_link="")
	{
		$layout 	= "";

		$condition = "";

		if( $search_key!=""){
			if(isset($search_key['hire'])){
				$condition 	= "AND C.name LIKE '%".$search_key['hire']."%' OR C.company_name LIKE '%".$search_key['hire']."%'  ";
			}
 			if(isset($search_key['experience'])) {
 				$condition .= " AND (C.experience = ".$search_key['experience']." OR C.experience > ".$search_key['experience'].") ";
 			}
			if(isset($search_key['location'])) {
				$condition .= " AND C.city LIKE '%".$search_key['location']."%' ";
			}

  		} else {
			$condition 	= " ";
  		}

		if($token!="") {
			$cp_info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
 			$contractor_profile = " AND C.profile_type='".$cp_info['id']."'";
 		
 		} elseif(isset($search_key['hire'])) {

			$hire_ids = array();
			$hire_profile_ids = array();
			
			$query = "SELECT id FROM ".CONTRACTOR_PROFILE_TBL." WHERE token='".$search_key['hire']."' OR token LIKE '%".$search_key['hire']."' ";

			$exe   = $this->exeQuery($query);

			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$hire_profile_ids[] = $list['id'];
				}
			}


			foreach ($hire_profile_ids as $key => $value) {
				$q 	 = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft FROM ".CONTRACTOR_TBL." C WHERE C.delete_status='0' AND C.status='1' $condition AND C.invite_status='1' AND C.profile_type!='' ORDER BY C.id ASC ";


				$exe = $this->exeQuery($q);

				if (mysqli_num_rows($exe) > 0) {
					while ($list = mysqli_fetch_assoc($exe)) {
						
						$profile_type_array   = explode(",", $list['profile_type']);

						if(in_array($value, $profile_type_array)) {
							$hire_ids[] = $list['id'];
						}

					}

				} 
			}

			$hire_ids_unique = array_unique($hire_ids);
			if(count($hire_ids_unique) > 0) {
				$contractor_profile = " AND C.id IN (".implode(",", $hire_ids_unique).")";
			} else {
				$contractor_profile = "";
			}

		} else {
 			$contractor_profile = "";
 		}
		
		

  		$sql = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft FROM ".CONTRACTOR_TBL." C WHERE C.delete_status='0' AND C.is_draft='0' AND C.status='1' $condition AND C.invite_status='1' AND C.profile_type!='' $contractor_profile  ORDER BY C.id ASC";
		$rs_result = $this->exeQuery($sql); //run the query
		$total_records = mysqli_num_rows($rs_result);  //count number of records

		// if(mysqli_num_rows($rs_result)  > 0 && $token!="" ) {
		// 	$total_records = 0;
		// 	while ($list = mysqli_fetch_assoc($rs_result)) {
		// 	$profile_type_array = explode(",", $list['profile_type']);
		// 	$cp_info            = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");
		// 	$check_profile      = in_array($cp_info['id'], $profile_type_array);
		// 	$hire_profile       = $cp_info['profile']; 

		// 	if($check_profile)
		// 		$total_records++;
		// 	}
		// }


		if($search_key_link=="") {
			$page_link 	= "hire/hirelist?";
		} else {
			$page_link 	= "hire/search".$search_key_link."&";
		}

		$num_rec_per_page = 4;


		$total_pages = ceil($total_records / $num_rec_per_page);
		$page = $current;
		$limit= 4;
		if ($total_pages >=1 && $page <= $total_pages){
			$counter = 1;
			$link = "";
			$dot_link = "<li><a href='javascript:void();' >...</a></li>";
			if ($page > ($limit/2)){
				$layout .= "<li><a href='".BASEPATH."".$page_link."p=1' >1</a></li>".$dot_link;
			}
			for ($i=$page; $i<=$total_pages;$i++){
				$current_page = (($i==$current) ? "current" : "");
	            if($counter < $limit)
	            	$layout .= "<li class='".$current_page."'><a href='".BASEPATH."".$page_link."p=".$i."'>".$i."</a></li>";
	            $counter++;
	        }
	        if ($page < $total_pages - ($limit/2)){
	        	$current_page = (($i==$current) ? "current" : "");
	        	$layout .= $dot_link."<li class='".$current_page."'><a href='".BASEPATH."".$page_link."p=".($i-1)."'>".($i-1)."</a></li>"; 
	        }
		}
		return $layout;
	}

	function getHireSearchList($token="",$page="",$search_key="")
	{	
		$layout 	= "";

		$condition = "";

		if( $search_key!=""){
			if(isset($search_key['hire'])){
				$condition 	= "";//"AND C.name LIKE '%".$search_key['hire']."%' OR C.company_name LIKE '%".$search_key['hire']."%'  ";
			}
 			if(isset($search_key['experience'])) {
 				$condition .= " AND (C.experience = ".$search_key['experience']." OR C.experience > ".$search_key['experience'].") ";
 			}
			if(isset($search_key['location'])) {
				$condition .= " AND C.city LIKE '%".$search_key['location']."%' ";
			}

  		} else {
			$condition 	= " ";
  		}

		$hire_ids     = array(); 
  		
  		if($token!="") {
			$profile_info = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","token='".$token."'");

			$q 	 = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft,CP.profile,CP.file_name as cp_img FROM ".CONTRACTOR_TBL." C LEFT JOIN ".CONTRACTOR_PROFILE_TBL." CP ON (CP.id=C.profile_type) WHERE C.delete_status='0' AND C.is_draft='0' AND C.status='1' $condition AND C.invite_status='1' AND C.profile_type!=''  ORDER BY C.id ASC ";

			$exe = $this->exeQuery($q);

			if (mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					
					$profile_type_array   = explode(",", $list['profile_type']);

					if(in_array($profile_info['id'], $profile_type_array)) {
						$hire_ids[] = $list['id'];
					}

				}

				if(count($hire_ids) > 0) {
					$profile_filter = " AND C.id IN (".implode(",", $hire_ids).")";
				} else {
					$profile_filter = "";
				}
				
			} else {
				$profile_filter = "";
			}
		
		} elseif(isset($search_key['hire'])) {

			$hire_profile_ids = array();
			
			$query = "SELECT id FROM ".CONTRACTOR_PROFILE_TBL." WHERE token='".$search_key['hire']."' OR token LIKE '%".$search_key['hire']."' ";

			$exe   = $this->exeQuery($query);


			if(mysqli_num_rows($exe) > 0) {
				while ($list = mysqli_fetch_assoc($exe)) {
					$hire_profile_ids[] = $list['id'];
				}
			}


			foreach ($hire_profile_ids as $key => $value) {
				
				$q 	 = "SELECT C.id,C.token,C.profile_type,C.name,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft FROM ".CONTRACTOR_TBL." C WHERE C.delete_status='0' AND C.is_draft='0' AND C.status='1' $condition AND C.invite_status='1' AND C.profile_type!=''  ORDER BY C.id ASC ";


				$exe = $this->exeQuery($q);

				if (mysqli_num_rows($exe) > 0) {
					while ($list = mysqli_fetch_assoc($exe)) {
						
						$profile_type_array   = explode(",", $list['profile_type']);

						if(in_array($value, $profile_type_array)) {
							$hire_ids[] = $list['id'];
						}

					}

				} 
			}

			$hire_ids_unique = array_unique($hire_ids);

			if(count($hire_ids_unique) > 0) {
				$profile_filter = " AND C.id IN (".implode(",", $hire_ids_unique).")";
			} else {
				$profile_filter = "";
			}


		} else {
			$profile_filter = "";
		}


  		$total_data = $this->check_query(CONTRACTOR_PROFILE_TBL,"*"," delete_status='0' AND invite_status='1' AND status='1' ");
		$start_from = ($page-1)*4;
  		$page_count = 4;
  		$q 	   ="SELECT C.id,C.token,C.profile_type,C.name,C.experience,C.experience_duration,C.city,C.file_name,C.invite_status,C.approval_status,C.delete_status,C.status,C.is_draft,CP.profile,CP.file_name as cp_img FROM ".CONTRACTOR_TBL." C LEFT JOIN ".CONTRACTOR_PROFILE_TBL." CP ON (CP.id=C.profile_type) WHERE C.delete_status='0' AND C.is_draft='0' AND C.status='1' $condition AND C.invite_status='1' AND C.profile_type!='' $profile_filter  ORDER BY C.id ASC LIMIT $start_from, $page_count ";
		$query = $this->exeQuery($q);


		$query = $this->exeQuery($q);
		if(mysqli_num_rows($query)>0) {
			$i=1;
			while ($details = mysqli_fetch_array($query)) {
				$list  	 = $this->editPagePublish($details);
	    		$pic 	 = $list['file_name']=="" ? ASSETS_PATH."no_img.jpg" : SRCIMG.$list['file_name'];
	    		$cp_pic  = $list['cp_img']=="" ? ASSETS_PATH."no_img.jpg" : SRCIMG.$list['cp_img'];

		    	$profile = explode(",",$list['profile_type']);
	  			$hire_profile = "";

	  			foreach ($profile as $key => $value) {
	    			$profile_date   = $this->getDetails(CONTRACTOR_PROFILE_TBL,"*","id='".$value."' ");
	    			$Comma          = (($key==0)? "" : ",");
	  				$hire_profile .= $Comma." ".$this->unHyphenize($profile_date['token']);
	  			}

	  			$experience = (($list['experience']!="")? "<span class='float-start'>Experience : ".$list['experience']." ".$list['experience_duration']."</span>" : "");

				  if(isset($_GET['id'])) {  
					if($_GET['id']==$this->encryptData($list['id'])) {   
					 $contact_details =
						 "<p style='position:relative;top:5px;' class='float-end mb-0'>+91 - ".$list['mobile']."<br>".$list['email']."</p>
						  ";
				  } else { 
					 $contact_details =" <h3 class='float-end'><div style='position:relative;top:5px;'><button type='button' class='btn btn-sm theme-btn-dark float-end rounded-pill ps-3 pe-3' data-bs-toggle='modal' id='viewContatDetailsBtn' data-encrypted_hire_id='".$this->encryptData($list['id'])."' data-bs-target='#view_contact'>View Contact Details</button></div></h3>";
				  } 
			  } else {
				 $contact_details =" <h3 class='float-end'><div style='position:relative;top:5px;'><button type='button' class='btn btn-sm theme-btn-dark float-end  rounded-pill ps-3 pe-3' data-bs-toggle='modal' id='viewContatDetailsBtn' data-encrypted_hire_id='".$this->encryptData($list['id'])."' data-bs-target='#view_contact'>View Contact Details</button></div>			
				 </h3>";
			  } 

				  $projects = $this->gethireProjectsCaroselList($list['id']);

				  if($projects!="") {
					  $project_carosel = "<div class='classified-pt-dts-img d-flex'>
											  ".$projects."
											  <div class='col-lg-3 hirelg_thumbanail_img'>
												  <a class='' href='".BASEPATH."hire/details/".$list['token']."'>View More</a>
											  </div>
										  </div>";
				  } else {
					  $project_carosel = " ";
				  }

    			$layout .= "
					<div class='classified-lists border-0 bg-white p-3 w-100 mb-3 clearfix'>

					<div class='classified-users-img'>
					<a href='".BASEPATH."hire/details/".$list['token']."' class='clearfix clear'>
						<img src='".$pic."' alt='' class=''>
					</a>
				    </div>

							".$contact_details."

							<div style='position:relative;top:5px;' class='share_link_card_box float-end w-0'>
					<h4 class='float-end btn-group'><button type='button' data-share_hire_id='".$list['id']."' class='btn text-black-50 btn dropdown' data-bs-toggle='dropdown' aria-expanded='false'>
					<span class='fa fa-share-alt'></span>
					</button>
						<ul class='dropdown-menu dropdown-menu-end shareproductdetails share_links'>
							<li class='bg_whatsapp'><a href='https://api.whatsapp.com/send?text=Hey, Checkout this site content here   ".BASEPATH." - We provide content here.   ".BASEPATH."'hire/details/".$this->encryptData($list['id'])."' class='share_icon' rel='tooltip' title='Whatsapp'>
							<i class='fab fa-whatsapp'></i>
							</a></li>
						</ul>
					</h4>
				</div>

				<h4 class='float-start w-100'>
				<div class='listing-classified-title'>
					<h3 class='w-100'><a href='".BASEPATH."hire/details/".$list['token']."' class='clearfix clear '>".ucfirst($list['name'])."</a></h3>
				
					<h4 class='float-start w-100'><p class='float-start'>".$hire_profile."</p></h4>								
					".$experience."
					<p class='float-start w-100'>".ucfirst($list['city'])."</p>
				</div></h4>

				<div class='classified-works clear d-block pt-3 row'>
					".$project_carosel."
				</div> 
                    </div>
						";
				
	    		$i++;
			}
		} else {
			$layout = "<div class='cart_content '>No Records Found !!</div>";
		}

		$result = array();
		$result['layout'] 	  = $layout;
		$result['start_from'] = $start_from + 1;
		$result['start_to']   = $start_from + mysqli_num_rows($query);
		$result['totals']     = $total_data;
		return $result;
	}


		

/*-----------Dont'delete---------*/
}?>
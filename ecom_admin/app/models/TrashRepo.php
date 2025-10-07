<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class TrashRepo extends Model {


		//------------------Trash Management---------------//

		// Get Blog Trash Items

		function blogTrashItems()
		{
			$layout="";
			$q= "SELECT B.id,B.title,B.category_id,B.file_name,B.delete_status,B.status,B.updated_at,C.category FROM ".BLOG_TBL." B LEFT JOIN ".BLOG_CATEGORY_TBL." C ON (C.id=B.category_id) WHERE 1 AND B.delete_status='1' ORDER BY B.id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
	                            <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Blog </span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Category </span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col'>
                                ".$list['category']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='blog'   data-api_case='restore'  data-redirect_link='blog' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Blog Category trash Ites

		function blogCategoryTrashItems()
		{
			$layout="";
			$q = "SELECT * FROM ".BLOG_CATEGORY_TBL." WHERE delete_status='1'  ORDER BY id DESC" ;
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	<tr class='nk-tb-item nk-tb-head'>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Blog Category</span></th>
                                <th class='nk-tb-col tb-col-md'><span class='sub-text'>Status</span></th>
                                <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                                
                            </tr>
                    	</thead>";
			if(mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$status = (($list['status']==1) ? "Active" : "Inactive"); 
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['category'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            <label for='check_$i' class='$status_class'>
	                            	$status
	                            </label>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-danger btn-sm restoreItem'  data-controller_name='blog' data-api_case='restoreCategory' data-redirect_link='blogcategory'  data-item_id='".$this->encryptData($list['id'])."' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Home Slider Trash Items

		function  homesliderTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".HOME_BANNER_TBL." WHERE 1 AND delete_status='1' ";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                        	 <tr class='nk-tb-item nk-tb-head'>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
                               	<th class='nk-tb-col'><span class='sub-text'>Image</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Title </span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Message </span></th>
                                <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
                                <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                            </tr>
                    	</thead>";
			if(mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$status = (($list['status']==1) ? "Active" : "Inactive"); 
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col'>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col'>
                                ".$list['message']."
                            </td>
                             <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem' data-controller_name='homeslider' data-api_case='restore' data-redirect_link='homeslider' data-item_id='".$this->encryptData($list['id'])."'  data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Home Slider Trash Items

		function  offerBannerTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".OFFER_BANNER_TBL." WHERE 1 AND delete_status='1' ";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                        	 <tr class='nk-tb-item nk-tb-head'>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>Image</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Title </span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Message </span></th>
                                <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
                                <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                            </tr>
                    	</thead>";
			if(mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$status = (($list['status']==1) ? "Active" : "Inactive"); 
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col'>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
	                        <td class='nk-tb-col'>
                                ".$list['message']."
                            </td>
                             <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem' data-controller_name='offerbanner' data-api_case='restore' data-redirect_link='offerbanner' data-item_id='".$this->encryptData($list['id'])."'  data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get User Trash Items

		function userTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".USERS_TBL." WHERE 1 AND delete_status='1' ";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                        	<tr class='nk-tb-item nk-tb-head'>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
	                            <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Name</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Mobile</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Email</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>User Type</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                       	 	</tr>
                    	</thead>";
			if(mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$status = (($list['status']==1) ? "Active" : "Inactive"); 
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");
					$user_type = (($list['is_super_admin']==1) ? "Super Admin" : "Employee");
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
                            </td>
                            <td class='nk-tb-col tb-col-mb'>
                                ".$list['mobile']."
                            </td>
                            <td class='nk-tb-col tb-col-mb'>
                                ".$list['email']."
                            </td>
                           	<td class='nk-tb-col tb-col-mb'>
                                ".$user_type."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='users' data-redirect_link='users'  data-api_case='restore' data-item_id='".$this->encryptData($list['id'])."' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Brand Trash Items

		function brandTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".BRAND_TBL."  WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    		<tr class='nk-tb-item nk-tb-head'>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>Image</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Brand Name</span></th>
                                <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
                                <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                            </tr>	
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col'>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['brand_name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='brand'   data-api_case='restore'  data-redirect_link='brand' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}
		// Get Legal Pages Trash Items

		function legalpagesTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".LEGAL_PAGE_TBL."  WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    		<tr class='nk-tb-item nk-tb-head'>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>Image</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Page Title</span></th>
                                <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
                                <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                            </tr>	
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col'>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['title'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='legalpages'   data-api_case='restore'  data-redirect_link='legalpages' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}
		// Get Legal Pages Trash Items

		function seoTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".SEO_TBL."  WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    		<tr class='nk-tb-item nk-tb-head'>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>UID</span></th>
                                <th class='nk-tb-col'><span class='sub-text'>Image</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Page Title</span></th>
                                <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
                                <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                            </tr>	
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$image = (($list['file_name']=='') ? ASSETS_PATH.'file_upload.jpg'  : UPLOADS.$list['file_name']);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col'>
                               <img src='".$image."' width='50' class='img-thumbnail'/>
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['page_title'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='seo'   data-api_case='restore'  data-redirect_link='seo' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Testimonial Trash Items

		function testimonialTrashItems()
		{
			$layout="";
			echo $q= "SELECT * FROM ".TESTIMONIALS_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Name </span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='testimonials'   data-api_case='restore'  data-redirect_link='testimonials' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get City Trash Items

		function locationcityTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".LOCATIONGROUP_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>City Name </span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['group_name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='locationcity'   data-api_case='restore'  data-redirect_link='locationcity' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}


		// Get Location Group Trash Items

		function locationgroupTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".GROUP_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Group Name </span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['group_name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='locationgroup'   data-api_case='restore'  data-redirect_link='locationgroup' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}
		

		// Get Location Group Trash Items

		function locationTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".LOCATION_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Location </span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['location'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='location'   data-api_case='locationrestore'  data-redirect_link='location' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}


		// Get Page Banner Trash Items

		function pageBannerTrashItems()
		{
			$layout="";
			$q= "SELECT * FROM ".PAGE_BANNER_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
	                            <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Page Name </span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['page_name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='pagebanner'   data-api_case='restore'  data-redirect_link='pagebanner' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get State Trash Items

		function pageStateItems()
		{
			$layout="";
			$q= "SELECT * FROM ".STATE_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>State Name</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='state'   data-api_case='restore'  data-redirect_link='state' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}
		

		// Get Branch Trash Items

		function pageBranchItems()
		{
			$layout="";
			$q= "SELECT * FROM ".BRANCH_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Branch Name</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['branch_name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='company'   data-api_case='restore'  data-redirect_link='branch' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Vendor Trash Items

		function pageVendorItems()
		{
			$layout="";
			$q= "SELECT * FROM ".VENDOR_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Vendor Name</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Company</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['company'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='vendor'   data-api_case='restore'  data-redirect_link='vendor' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Category Trash Items

		function pageCategoryItems()
		{
			$layout="";
			$q= "SELECT * FROM ".MAIN_CATEGORY_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Category</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['category'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='category'   data-api_case='restore'  data-redirect_link='category' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Subcategory Trash Items

		function pageSubcategoryItems()
		{
			$layout="";
			$q= "SELECT * FROM ".SUB_CATEGORY_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Subcategory</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Category ID</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['subcategory'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['category_id'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='subcategory'   data-api_case='restore'  data-redirect_link='subcategory' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Coupon Trash Items

		function pageCouponItems()
		{
			$layout="";
			$q= "SELECT * FROM ".COUPON_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Coupon</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['coupon_code'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='coupon'   data-api_case='restore'  data-redirect_link='coupon' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Attribute Trash Items

		function pageAttributeItems()
		{
			$layout="";
			$q= "SELECT L.id,L.attri_group_id,L.attribute_name,L.sort_order,L.status,L.updated_at,G.attribute_group as groupname FROM ".ATTRIBUTE_TBL." L LEFT JOIN ".ATTRIBUTE_GROUP_TBL." G ON (G.id=L.attri_group_id) WHERE L.delete_status='1' ORDER BY L.id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Attribute Name</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Attribute Group</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['attribute_name'])."</span>
	                        </td>
	                         <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['groupname'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='attribute'   data-api_case='restore'  data-redirect_link='attribute' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Attribute Group Trash Items

		function pageAttributeGroupItems()
		{
			$layout="";
			$q= "SELECT * FROM ".ATTRIBUTE_GROUP_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Attribute Group Name</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['attribute_group'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='coupon'   data-api_case='restore'  data-redirect_link='coupon' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}
		// Get Product Trash Items

		function pageProductItems()
		{
			$layout="";
			$q= "SELECT * FROM ".PRODUCT_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Product UID</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Product Name</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['product_uid'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['product_name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='products'   data-api_case='restore'  data-redirect_link='product' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Notification Email Items

		function pageNotiEmailItems()
		{
			$layout="";
			$q= "SELECT * FROM ".NOTIFICATION_EMAIL_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Notification Email</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['email'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='notificationemail'   data-api_case='restore'  data-redirect_link='notificationemail' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Product Review Items

		function pagePrdouctReviewItems()
		{
			$layout="";
			$q= "SELECT * FROM ".REVIEW_TBL." WHERE 1 AND del_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Product Name</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Comment</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$product = $this->getDetails(PRODUCT_TBL,"product_name"," id='".$list['product_id']."'  ");
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($product['product_name'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span >".$this->publishContent($list['comment'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='review'   data-api_case='restoreProductReview'  data-redirect_link='productreview' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Newsletter trash Items

		function newsletterItems()
		{
			$layout="";
			$q= "SELECT * FROM ".SUBSCRIBE_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Email</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['email'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='enquiry'   data-api_case='restoreNewsletter'  data-redirect_link='newsletter' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Return reason Items

		function returnreasonItems()
		{
			$layout="";
			$q= "SELECT * FROM ".RETURN_REASON_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Return Reason</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['return_reason'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='returnreason'   data-api_case='restoreReturnReason'  data-redirect_link='returnreason' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Return reason Items

		function returnsettingItems()
		{
			$layout="";
			$q= "SELECT * FROM ".RETURN_SETTINGS_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                            <tr class='nk-tb-item nk-tb-head'>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Return Duration Type</span></th>
                                <th class='nk-tb-col tb-col-mb'><span class='sub-text'>Duration</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
                                <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                            </tr>
                        </thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($list = mysqli_fetch_array($exe)) {
					
					$return_setting = (($list['return_setting']=='days') ? "Day" : "Hour");
					
					if($list['return_setting']=='days') {
						$return_duration = $list['days']. " Day";
					} else {
						if($list['minutes']=="" || $list['minutes']==0  ) {
							$return_duration = $list['hours']." Hour";	
						} elseif($list['hours']==0) {
							$return_duration = $list['minutes']." Minutes";
						} else {
							$return_duration = $list['hours']." Hour ".$list['minutes']." Minutes";
						}
					}


		    		$layout .= "
		    				<tr class='nk-tb-item'>
		    					<td class='nk-tb-col'>
	                                ".$i."
	                            </td>
	                            <td class='nk-tb-col tb-col-md'>
	                                <span class='text-primary'>".$this->publishContent($return_setting)."</span>
 	                            </td>
 	                            <td class='nk-tb-col tb-col-md'>
	                                <span class='text-primary'>".$this->publishContent($return_duration)."</span>
 	                            </td>
 	                         	 <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
	                            </td>
	                            <td class='nk-tb-col tb-col-md'>
	                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='returnsettings'   data-api_case='restoreReturnSetting'  data-redirect_link='returnsettings' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
	                            </td>
 	                           
	                        </tr>";
		    		$i++;
				}
			}
			return $layout;
		}

		// Get Classified Profile Items

		function classifiedprofileItems()
		{
			$layout="";
			$q= "SELECT * FROM ".CONTRACTOR_PROFILE_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Contractor Profile</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['profile'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='classifiedprofile'   data-api_case='restoreClassifiedProfile'  data-redirect_link='classifiedprofile' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Classified Profile Items

		function classifiedprojectItems()
		{
			$layout="";
			$q= "SELECT * FROM ".CONTRACTOR_PROJECT_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Classified Project</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['project_title'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='hire'   data-api_case='restoreClassifiedProject'  data-redirect_link='classifiedproject' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Work With Us Request Items

		function workWithUsRequestItems()
		{
			$layout="";
			$q= "SELECT * FROM ".WORK_WITH_US_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Company Name</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Contact Person</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['company_name'])."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='enquiry'   data-api_case='restoreWorkWithUsRequest'  data-redirect_link='workwithusreuest' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Product Request Status Items

		function productRequestStatus()
		{
			$layout="";
			$q= "SELECT * FROM ".PRODUCT_REQUEST_STATUS_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Product Request Status</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['request_status'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='products'   data-api_case='restoreProductRequestStatus'  data-redirect_link='productrequeststatus' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}

		// Get Order Response Status Items

		function orderResponseStatusItems()
		{
			$layout="";
			$q= "SELECT * FROM ".ORDER_RESPONSE_STATUS_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Order Response Status</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['response_status'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='orderresponsestatus'   data-api_case='restore'  data-redirect_link='orderresponsestatus' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}


		// Get Product Display Tag Items

		function productDisplayTagtems ()
		{
			$layout="";
			$q= "SELECT * FROM ".PRODUCT_DISPLAY_TAG." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Display Tag</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['display_tag'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='products'   data-api_case='restoreDisplayTag'  data-redirect_link='productdisplaytag' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}


		// Get Product Unit Items

		function productUnitItems()
		{
			$layout="";
			$q= "SELECT * FROM ".PRODUCT_UNIT_TBL." WHERE 1 AND delete_status='1' ORDER BY id ASC";
			$exe = $this->exeQuery($q);
			$layout .= "
					  	<thead>
                    	 <tr class='nk-tb-item nk-tb-head'>
                    	  		<th class='nk-tb-col tb-col-mb'><span class='sub-text'>#</span></th>
                              	<th class='nk-tb-col tb-col-mb'><span class='sub-text'>Product Unit</span></th>
	                            <th class='nk-tb-col tb-col-md'><span class='sub-text'>Trash Date</span></th>
	                            <th class='nk-tb-col tb-col-lg'><span class='sub-text'>Action</span></th>
                    		</tr>
                    	</thead>";
			if(@mysqli_num_rows($exe) > 0) {
				$i = 1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list = $this->editPagePublish($rows);
					$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['product_unit'])."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
	                            ".date("d/m/Y h:i A", strtotime($list['updated_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm restoreItem'  data-controller_name='products'   data-api_case='restoreProductUnit'  data-redirect_link='productunit' data-item_id='".$this->encryptData($list['id'])."' data-toggle='tooltip' data-placement='top' title='Restore Item' ><em class='icon ni ni-unarchive'></em></button>
                            </td>
                        </tr>";
                        $i++;
				}
			}
			return $layout;
		}






	/*-----------Dont'delete---------*/

	}


?>
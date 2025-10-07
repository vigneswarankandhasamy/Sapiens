<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Projects extends Model
	{


	/*----------------------------------------------
				Projects Management
	----------------------------------------------*/

	function addProjectImages($input='',$files)
	{	
		$validate_csrf = $this->validateCSRF($input);
		if($validate_csrf=='success') {
			$data 			= $this->cleanStringData($input);
			$admin_id 		= $_SESSION["ecom_contractor_id"];
			$curr 			= date("Y-m-d H:i:s");
			$exe            = 0;
			$token 			= $this->hyphenize($data['project_title']);
			$check 			= $this->check_query(CONTRACTOR_PROJECT_TBL,"token"," token='".$token."' ");

			if($check==0) {

					$q1 = "INSERT INTO ".CONTRACTOR_PROJECT_TBL." SET
						   contractor_id = '".$admin_id."', 
						   token 		 = '".$token."',
						   project_title = '".$data['project_title']."',
						   description   = '".$data['description']."',
						   status 		 = '1',
						   created_at    = '".$curr."',
						   updated_at    = '".$curr."'
					";

					$last_id = $this->lastInserID($q1);

					if ($files['is_uploaded'] && $last_id) {
					
						foreach ($files['images'] as $key => $value) {
							$q = "INSERT INTO ".CONTRACTOR_PROJECT_IMG_TBL." SET 
								  project_id    = '".$last_id."',
								  contractor_id = '".$admin_id."',
								  file_name 	= '".$value['file_name']."',
								  file_type 	= '".$value['file_type']."',
								  file_size 	= '".$value['file_size']."',
								  status 		= '1',
								  delete_status = '0',
								  created_at 	= '".$curr."',
								  updated_at 	= '".$curr."'
								" ;
							$exe = $this->exeQuery($q);
						}
					}
					
					if($files['is_uploaded']){
						if($exe){
							$upload = $this->uploadMediaFiles($files['images'],"Contractor Projects",$admin_id);

							$project_detail = $this->getDetails(CONTRACTOR_PROJECT_TBL,"id,COUNT(id) as count,contractor_id","delete_status='0' AND status='1' AND contractor_id = '".$admin_id."'  ");
							$query  = "UPDATE ".CONTRACTOR_TBL." SET 
								   total_projects = ".$project_detail['count']."
								   WHERE id = '".$project_detail['contractor_id']."' 
								   ";
							$exe = $this->exeQuery($query);
							return 1;
						} else {
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					}else{
						// return "Images can not be empty. or upload images in jpg and png format only.";
						return 1;
					}
			} else {
				return "Entered Project title already exists";
			}
			
		}else{
			return $validate_csrf;
		}
	}


	function getProjectImage($project_id,$contractor_id)
	{
		$layout = "";
		$q = "SELECT * FROM ".CONTRACTOR_PROJECT_IMG_TBL." WHERE project_id = ".$project_id." AND contractor_id = ".$contractor_id." ORDER BY id ASC ";
		 $exe    = $this->exeQuery($q);
		 if(mysqli_num_rows($exe)>0){
			 $i=1;
			 while($rows = mysqli_fetch_array($exe)){
				 $list = $this->editPagePublish($rows);
			
				 $layout .= ' <div class="image_list" id="image_item_'.$list['id'].'" style=\'background-image: url("'.HIRE_UPLOADS.$list['file_name'].'"); background-repeat: no-repeat; background-position: center top; background-size: cover;\' >
				 <div class="image_actions">
					 <p>
					 <a href="javascript:void();" id="image_action_'.$list['id'].'" data-option="'.$this->encryptData($list['id']).'"  data-filename="'.$list['file_name'].'" data-element="'.$list['id'].'"  data-alt="'.$list['file_alt_name'].'"  data-image="'.HIRE_UPLOADS.$list['file_name'].'">
					 <em class="icon ni ni-expand"></em>
					 </a>
					 </p>
				 </div>
				
			 </div>';

				$i++;
			}
		 }
		 return $layout;
	}

	// Edit - Product Image info

	function projectImageInfo($data)
	{
		$image_id = $this->decryptData($data);
		$image_info  = $this->getDetails(CONTRACTOR_PROJECT_IMG_TBL,"*"," id= '$image_id' ");

		$result = array();
		$result['id'] = $image_info['id'];
		$result['file_name'] = $image_info['file_name'];
		$result['file_alt_name'] = $image_info['file_alt_name'];
		$result['image_path'] = HIRE_UPLOADS.$image_info['file_name'];
		return $result;
	}


		// Edit Product Image

		function editProductImage($data)
		{
			$image_id = $this->decryptData($data['image_id']);

			$image_info  = $this->getDetails(CONTRACTOR_PROJECT_IMG_TBL,"*"," id= '$image_id' ");
			$project_info = $this->getDetails(CONTRACTOR_PROJECT_TBL,"*"," id='".$image_info['project_id']."' ");

			$result = array();
			

			if ($image_info['is_draft']==1 && $data['image_name']!='') {
				$file_name_array = explode(".", $image_info['file_name']);
				$file_extension = end($file_name_array);


				$new_name = $this->hyphenize($data['image_name']).".".$file_extension;
				$thumb_file_name = "thumb-".$new_name;

				if (file_exists("./resource/uploads/hire_uploads/".$new_name)) {
					
					$edited_file_name = $this->hyphenize($data['image_name'])."-".$this->generateRandomString("5").".".$file_extension;
					$edit_thumb_file_name = "thumb-".$edited_file_name;
					rename('./resource/uploads/hire_uploads/'.$image_info['file_name'], './resource/uploads/hire_uploads/'.$edited_file_name);
					rename('./resource/uploads/hire_uploads/thumbnail/thumb-'.$image_info['file_name'], './resource/uploads/hire_uploads/thumbnail/'.$edit_thumb_file_name);

					$file_query = " file_name = '$edited_file_name', ";
					$result['new_name'] = $edited_file_name;
					$result['new_path'] = HIRE_UPLOADS.$edited_file_name;


				}
				else{
					rename('./resource/uploads/hire_uploads/'.$image_info['file_name'], './resource/uploads/hire_uploads/'.$new_name);
					rename('./resource/uploads/hire_uploads/thumbnail/thumb-'.$image_info['file_name'], './resource/uploads/hire_uploads/thumbnail/'.$thumb_file_name);
					$file_query = " file_name = '$new_name',  ";

					$result['new_name'] = $new_name;
					$result['new_path'] = HIRE_UPLOADS.$new_name;

				}
			}
			else
			{
				$file_query = "";
				$result['new_name'] = "";
			}

			$q = "UPDATE ".CONTRACTOR_PROJECT_IMG_TBL." SET
					$file_query
					file_alt_name = '".$data['alt_name']."'
					WHERE id = '$image_id'
				";
			$exe = $this->exeQuery($q);

			$result['status']= "ok";
			return $result;
		}

	function editProjectImages($input='',$files)
	{	
		$validate_csrf = $this->validateCSRF($input);
		if($validate_csrf=='success') {
			$data 			= $this->cleanStringData($input);
			$admin_id 		= $_SESSION["ecom_contractor_id"];
			$curr 			= date("Y-m-d H:i:s");
			$exe            = 0;
			$token 			= $this->hyphenize($data['project_title']);
			$check 			= $this->check_query(CONTRACTOR_PROJECT_TBL,"token"," token='".$token."' AND id!= '".$data['project_id']."'");

			if($check==0) {
				$q1 = "UPDATE ".CONTRACTOR_PROJECT_TBL." SET 
						   token 		 = '".$token."',
						   project_title = '".$data['project_title']."',
						   description   = '".$data['description']."',
						   status 		 = '1',
						   verified_status 	= '0',
						   updated_at    = '".$curr."' WHERE id = '".$data['project_id']."';
					";
					$this->exeQuery($q1);


					if ($files['is_uploaded']) {
					
						foreach ($files['images'] as $key => $value) {
							$q = "INSERT INTO ".CONTRACTOR_PROJECT_IMG_TBL." SET 
								  project_id    = '".$data['project_id']."',
								  contractor_id = '".$admin_id."',
								  file_name 	= '".$value['file_name']."',
								  file_type 	= '".$value['file_type']."',
								  file_size 	= '".$value['file_size']."',
								  status 		= '1',
								  delete_status = '0',
								  created_at 	= '".$curr."',
								  updated_at 	= '".$curr."'
								" ;
							$exe = $this->exeQuery($q);
						}
					}
					return 1;	
			} else {
				return "Entered Project title already exists";
			}
		}else{
			return $validate_csrf;
		}
	}

	function manageProjectsimage() /* Not in use */
	{
		$layout ="";
	    $q = "SELECT * FROM ".CONTRACTOR_PROJECT_IMG_TBL." WHERE contractor_id = ".$_SESSION["ecom_contractor_id"]." ORDER BY id ASC ";
		$query = $this->exeQuery($q);
		if(mysqli_num_rows($query) > 0){
			$i=1;
	      	while($list = mysqli_fetch_array($query)){
	      		$layout .= "
	      		<div class='gallery-block masonry-item' style='margin-right: 10px;'>
				    <div class='inner-box' style='border-ridge'>
						<a  href='javascript:void();' class='removeProjectImage' data-id='".$this->encryptData($list['id'])."' ><span class='badge badge-danger'>X</span></a>

				        <div class='image' style='width: 150px;height: 150px;'>
								<img src='".HIRE_UPLOADS.$list['file_name']."' style='width: 150px;'>

				        </div>
				    </div>
				</div>";	
	            $i++;
	      	}
	    } else {
	    	$layout = "No Record Found ";
	    }
	    return $layout;
	}

	function removeProjectImages($data)
	{	
		$id = $this->decryptData($data);
		$info = $this->getDetails(CONTRACTOR_PROJECT_IMG_TBL,"file_name"," id='$id' ");
		if ($info['file_name']!='') {
			unlink("./resource/uploads/hire_uploads/".$info['file_name']);
		}
		$delete = $this -> deleteRow(CONTRACTOR_PROJECT_IMG_TBL," id ='$id'");
		return $delete;
	}

	// Trash Classified Project


	function trashClassifiedProject($data)
	{
		$data     = $this->decryptData($data);
		$info     = $this->getDetails(CONTRACTOR_PROJECT_TBL,"contractor_id","id='".$data."'");
		$h_info   = $this->getDetails(CONTRACTOR_TBL,"id","id='".$info['contractor_id']."'");

		$delete_project = $this->deleteRow(CONTRACTOR_PROJECT_TBL,"id='".$data."' ");
		$up_exe = $this->exeQuery($delete_project);

		$delete_image 	= $this->deleteRow(CONTRACTOR_PROJECT_IMG_TBL,"project_id='".$data."' ");
		$up_exe_img = $this->exeQuery($delete_image);

		$project_detail = $this->getDetails(CONTRACTOR_PROJECT_TBL,"id,COUNT(id) as count,contractor_id","delete_status='0' AND status='1' AND contractor_id = '".$info['contractor_id']."'  ");
		
		$project_count = (($project_detail['id'])? $project_detail['count'] : 0);

		$query  = "UPDATE ".CONTRACTOR_TBL." SET 
			   total_projects = ".$project_count."
			   WHERE id = '".$h_info['id']."' 
			   ";
		$exe = $this->exeQuery($query);

		return 1;
	}


	/*-----------Dont'delete---------*/

	}


?>





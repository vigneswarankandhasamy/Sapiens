<?php


class FileUploader extends Model
{

	// Upload a file

	function uploadFile($file_data,$data,$path="")
	{	
		$output = array();

		// File Naming

		$rands = $this->generateRandomString("10");
		$date = date("dmYhis");

		$temporary = explode(".", $file_data["file"]["name"]);
		$file_extension = end($temporary);
		if (isset($data['image_name'])) {
			$image_name = $this->hyphenize($data['image_name']);
		}else{
			$image_name = "";
		}
		$temp_file_name = (($image_name=='') ? $rands.'_'.$date.'_'.$this->hyphenize($_FILES["file"]['name']) : $image_name.".".$file_extension  );


		if(!empty($file_data["file"]["type"])){
			$validate = $this->validate($file_data,$data);
			if ($validate==1) {

				if($path=="admin_resource") {
					$store_path = SRCIMG;
				} else {
					$store_path = "./resource/uploads/";
				}

				if (file_exists($store_path.$temp_file_name)) {
					$file_name_array = explode(".", $temp_file_name);
					$new_file_extension = end($file_name_array);
					$file_name = $file_name_array[0]."-".$this->generateRandomString("5").".".$new_file_extension;
				}else{
					$file_name = $temp_file_name;
				}

				// Move Uploaded File
				$sourcePath = $_FILES["file"]['tmp_name'];
				$targetPath = $store_path.$file_name;
				move_uploaded_file($sourcePath,$targetPath);


				// Send output
				$output['file_name'] 	= $file_name;
				$output['file_type'] 	= $file_data["file"]["type"];
				$output['file_size'] 	= $file_data["file"]["size"];
				$output['status'] 		= "ok";
				$output['message'] 		= "Upload Success !!!";
				$output['is_uploaded'] 	= true;

			}else{
				$output['status'] 		= "failed";
				$output['message'] 		= $validate;
				$output['file_name'] 	= "";
				$output['file_type'] 	= "";
				$output['file_size'] 	= "";
				$output['is_uploaded'] 	= true;
			}
		}else{
			$output['is_uploaded'] = false;
		}

		

		return $output;

	}

	// Validate the file

	function validate($file_data,$data)
	{
		$file_type = $data['file_type'];
		$temporary = explode(".", $file_data["file"]["name"]);
		$file_extension = end($temporary);

		switch ($file_type) {

			case 'image':
				$extensions = array("jpeg", "jpg", "png", "JPG", "JPEG", "PNG");
				$mime_types = array(
					  	'image/gif',
					  	'image/jpeg',
					  	'image/png'
					);

				if (in_array($file_extension, $extensions) && in_array($file_data["file"]["type"], $mime_types) ) {
					return 1;
				}else{
					return "Please upload images in JPEG, JPG and PNG formats only. ";
				}
			break;

			case 'pdf':
				$extensions = array("pdf", "PDF");
				$mime_types = array(
					  	'application/pdf',
					);

				if (in_array($file_extension, $extensions) && in_array($file_data["file"]["type"], $mime_types) ) {
					return 1;
				}else{
					return "Please upload files in PDF formats only. ";
				}
			break;

			case 'doc':
				$extensions = array("doc", "docx","DOC","DOCX");
				$mime_types = array(
					  	'application/msword',
	  					'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					);

				if (in_array($file_extension, $extensions) && in_array($file_data["file"]["type"], $mime_types) ) {
					return 1;
				}else{
					return "Please upload files in PDF formats only. ";
				}
			break;

			case 'excel':
				$extensions = array("xlsx", "xls","XLSX","XLS");
				$mime_types = array(
					  	'application/vnd.ms-excel',
	  					'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
					);

				if (in_array($file_extension, $extensions) && in_array($file_data["file"]["type"], $mime_types) ) {
					return 1;
				}else{
					return "Please upload files in Excel formats only. ";
				}
			break;

			case 'anyfiles':
				return 1;
			break;
			
			default:
				return "Please upload images and document files only !!!";
			break;
		}

	}


//------ End of the file ------//

}

?> 
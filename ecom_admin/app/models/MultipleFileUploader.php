<?php
require_once 'Model.php';
require_once 'class.uploader.php';

class MultiFileUploader extends Model
{

	// Upload a file

	function uploadFile($file_data,$title_name)
	{
		$new_name = $this->hyphenize($title_name);
		

		if (!empty($file_data['images']['name'][0])) {
			
			$uploader   = new Uploader();

			$data = $uploader->upload($file_data['images'], array(
		        'limit'       => 10, //Maximum Limit of files. {null, Number}
		        'maxSize'     => 40, //Maximum Size of files {null, Number(in MB's)}
		        'extensions'  => array('jpg', 'png','jpeg'), //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
		        'required'    => false, //Minimum one file is required for upload {Boolean}
		        'uploadDir'   => './resource/uploads/', //Upload directory {String}
		        'title'       => array('auto', 30), //New file name {null, String, Array} *please read documentation in README.md
		        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
		        'perms'       => null, //Uploaded file permisions {null, Number}
		        'onCheck'     => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
		        'onError'     => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
		        'onSuccess'   => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
		        'onUpload'    => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
		        'onComplete'  => null, //A callback function name to be called when upload is complete | ($file) | Callback
		        'onRemove'    => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
		    ));

		    if($data['isComplete']){
		        $files  = $data['data'];
		        $images = $data['data']['files'];
		    }

		    

		    //print_r($data['data']['metas']);

		    $images_output = array();

		    foreach ($data['data']['metas'] as $key => $value) {
		    	$file            = $value['file'];
		    	$img_name        = $value['img_name'];
				$file_extension  = $value['extension'];
				$random          = $this->generateRandomString("5");
		    	$file_name       = $new_name."-".$key."-".$random.".".$file_extension;
		    	$thumb_file_name = "thumb-".$new_name."-".$key."-".$random.".".$file_extension;

		    	// Rename File
		    	rename($value['file'], './resource/uploads/'.$file_name);
		    	// Rename thumbnail name
		    	rename('./resource/uploads/thumbnail/'.$img_name, './resource/uploads/thumbnail/'.$thumb_file_name);

		    	$element              = array();
		    	$element['file_name'] = $file_name;
		    	$element['file_type'] = $value['type'][0]."/".$value['type'][1];
		    	$element['file_size'] = $value['size'];
		    	$images_output[]      = $element;
		    }

		    $output                   = array();
		    $output['images']         = $images_output;
		    $output['is_uploaded']    = true;
			
		}else{
			$output['is_uploaded']    = false;
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
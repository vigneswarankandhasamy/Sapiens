<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class MarketingModel extends Model
	{	

	/*----------------------------------------
			Manage Contact Enquiry
	-----------------------------------------*/

	function manageContactEnquiry()
	{
		$layout = "";
		$q = "SELECT * FROM ".CONTACT_US_TBL." WHERE delete_status='0' AND status='1' ORDER BY id DESC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 		= $this->editPagePublish($details);

	    		$layout .= "
	    				<tr class='nk-tb-item open_enq_model' data-target='#modalForm' data-keyboard='false' data-backdrop='static' data-toggle='modal' data-option='".$this->encryptData($list['id'])."' >
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                             <td class='nk-tb-col'>
                                ".date("d-m-Y", strtotime($list['created_at']))."
                            </td>
                           
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>

	                        <td class='nk-tb-col tb-col-md'>
                                <em class='icon ni ni-mail'></em> <span >". $list['email']."</span><br>
                                <em class='icon ni ni-mobile'></em> <span >". $list['mobile']."</span>
	                        </td>


                            <td class='nk-tb-col'>
                                ".$list['subject']."
                            </td>";

                if($list['location'] != "" && $list['area']!="") {
                    $layout .= "
	                    		<td class='nk-tb-col tb-col-md'>
	                            	<span >". $list['area']."</span>,<br>
	                                <span >". $list['location']."</span>
		                        </td>
	                    		";
		    	} else {
		    		$layout .= "
	                    		<td class='nk-tb-col tb-col-md'>
	                            	
		                        </td>
	                    		";
		    	}
                        
                $layout .= "
	                        <td class='nk-tb-col nk-tb-col-tools'>
	                            <ul class='nk-tb-actions gx-1'>
	                                <li class='nk-tb-action-hidden'>
	                                    <a href='javascript:void();' class='btn btn-trigger btn-icon trashContactEnquiry'  data-link='enquiry/contact' data-toggle='tooltip' data-placement='top' title='Trash Enquiry' data-option='".$this->encryptData($list['id'])."' >
	                                        <em class='icon ni ni-trash-fill'></em>
	                                    </a>
	                                </li>
	                            </ul>
	                        </td>
	                	</tr>";
	    		$i++;
	    	}
	    }
	    	return $layout;
	}

	// Trash Contact Enquiry

	function trashContactEnquiry($data)
	{
		$data   = $this->decryptData($data);
		$query  = "UPDATE ".CONTACT_US_TBL." SET delete_status='1' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}


	// Get Contact Details

	function getContactDetails($data){
		$layout 	= "";
		$contact_id = $this->decryptData($data);
		$info 		= $this->getDetails(CONTACT_US_TBL,"*"," id='$contact_id' ");
		$info 		= $this->cleanStringData($info);
		$curr 		= date("Y-m-d H:i:s");

		$layout .="
					<div class='nk-block'>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                            <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Name</span>
	                                <span class='profile-ud-value enq_name_align'>".$info['name']."</span>
	                            </div>
	                        </div>
	                    </div>
	                     <div class='profile-ud-list'>
	                       <div class='profile-ud-item '>
	                            <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Mobile Number</span>
	                                <span class='profile-ud-value'>".$info['mobile']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Email Address</span>
	                                <span class='profile-ud-value'>".$info['email']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Date</span>
	                                <span class='profile-ud-value enq_name_align'>".date("d-M-Y",strtotime($info['created_at']))."</span>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class='nk-block'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Subject</h6>
	                    </div>
	                    <div class='enq_message'>
	                        <p>".$info['subject']."</p>
	                    </div>
	                </div>
	                <div class='nk-block'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Message</h6>
	                    </div>
	                    <div class='enq_message'>
	                        <p>".$info['message']."</p>
	                    </div>
	                </div>
				";
		return $layout;
	}

	
	/*----------------------------------------
			Manage News Letter
	-----------------------------------------*/

	function manageNewsLetter()
	{
		$layout = "";
		$q = "SELECT * FROM ".SUBSCRIBE_TBL." WHERE delete_status='0' AND status='1' ORDER BY id DESC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 		= $this->editPagePublish($details);
	    		$layout .= "
    				<tr class='nk-tb-item open_newsletter_detail' data-option='".$list['id']."'>
    					<td class='nk-tb-col'>
                            ".$i."
                        </td>
                        <td class='nk-tb-col'>
                            ".$list['email']."
                        </td>
                        <td class='nk-tb-col tb-col-md'>".date("d/m/Y ", strtotime($list['sub_date']))."</td>
                       

                        <td class='nk-tb-col nk-tb-col-tools'>
                            <ul class='nk-tb-actions gx-1'>
                                <li class='nk-tb-action-hidden'>
                                    <a href='javascript:void();' class='btn btn-trigger btn-icon trashNewsletter'  data-link='enquiry/newsletter' data-toggle='tooltip' data-placement='top' title='Trash Newsletter' data-option='".$this->encryptData($list['id'])."' >
                                        <em class='icon ni ni-trash-fill'></em>
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>";
	    		$i++;
	    	}
	    }
	    	return $layout;
	}

	// Trash Newsletter Enquiry

	function trashNewsLetter($data)
	{
		$data   = $this->decryptData($data);
		$query  = "UPDATE ".SUBSCRIBE_TBL." SET delete_status='1' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}

	// Restore Newsletter Enquiry

	function restoreNewsletter($data)
	{	
		$data = $this->decryptData($data);
		$query = "UPDATE ".SUBSCRIBE_TBL." SET delete_status='0' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}

	/*----------------------------------------
			Manage Product Review
	-----------------------------------------*/

	function manageProductReview()
	{
		$layout = "";
		$q = "SELECT R.id,R.product_id,R.name,R.email,R.comment ,R.star_ratings,R.approval_status,R.del_status,R.status,R.added_by,R.created_at,P.product_name,P.page_url FROM ".REVIEW_TBL." R LEFT JOIN ".PRODUCT_TBL." P ON(P.id=R.product_id) WHERE R.del_status='0' ORDER BY R.id DESC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 		= $this->editPagePublish($details);
	    		$status = (($list['status']==1) ? "On" : "Off"); 
	    		$status_c = (($list['status']==1) ? "checked" : " ");
				$status_class = (($list['status']==1) ? "text-success" : "text-danger");


				$app_status = (($list['approval_status']==1) ? "Approved" : "Pending"); 
	    		$app_status_c = (($list['approval_status']==1) ? "checked" : " ");
				$app_status_class = (($list['approval_status']==1) ? "text-success" : "text-warning");

				$td_click = "open_review_detail";
				$td_data  = "data-option='".$this->encryptData($list['id'])."'";

	    		$layout .= "
	    				<tr class='nk-tb-item ' >
    					<td class='nk-tb-col $td_click' $td_data>
                            ".$i."
                        </td>
                         <td class='nk-tb-col $td_click' $td_data>
                            ".date("d-m-Y", strtotime($list['created_at']))."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                            <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                        </td>
                        <td class='nk-tb-col $td_click' $td_data>
                            ".$list['product_name']."
                        </td>
                        <td class='nk-tb-col $td_click' $td_data>
                            ".$list['comment']."
                        </td>
                        <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch custom-control-sm'>
                                <input type='checkbox' class='custom-control-input changeReviewApproval' data-option='".$this->encryptData($list['id'])."'   value='0'  id='app_status_".$i."' name='approval_status' $app_status_c>
                                <label class='custom-control-label ' for='app_status_".$i."'><span class='$app_status_class'>$app_status </span></label>
                            </div>
                        </td>
                       <td class='nk-tb-col tb-col-md'>
                            <div class='custom-control custom-switch custom-control-sm'>
                                <input type='checkbox' class='custom-control-input changeReviewStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='status' $status_c>
                                <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                            </div>
                        </td>

                        <td class='nk-tb-col nk-tb-col-tools'>
                            <ul class='nk-tb-actions gx-1'>

                                <li class='nk-tb-action-hidden'>
                                    <a href='javascript:void();' class='btn btn-trigger btn-icon trashproductReview'  data-link='review' data-toggle='tooltip' data-placement='top' title='Trash Enquiry' data-option='".$this->encryptData($list['id'])."' >
                                        <em class='icon ni ni-trash-fill'></em>
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>";
	    		$i++;
	    	}
	    }
	    	return $layout;
	}

	// Get Reveiw Details

	function getReveiwDetails($data){
		$layout = "";
		$id 	= $this->decryptData($data);
		$info 	= $this->getDetails(REVIEW_TBL,"*"," id='$id' ");
		$p_info	= $this->getDetails(PRODUCT_TBL,"*"," id='".$info['product_id']."' ");
		$v_info	= $this->getDetails(VENDOR_TBL,"*"," id='".$info['vendor_id']."' ");
		$info 	= $this->editPagePublish($info);
		$p_info = $this->editPagePublish($p_info);

		$status = (($info['status']==1) ? "On" : "Off"); 
		$status_class = (($info['status']==1) ? "text-success" : "text-danger");

		$app_status = (($info['approval_status']==1) ? "Approved" : "Pending"); 
		$app_status_class = (($info['approval_status']==1) ? "text-success" : "text-warning");

		$admin_replay = (($info['admin_replay']!="" && $info['admin_replay']!=NULL)? $info['admin_replay'] : "");

		$layout .="	
					<div class='nk-block'>
						<div class='row'>
							<div class='col-md-6'>							
								<div class='profile-ud-list'>
			                        <div class='profile-ud-item'>
			                            <div class='profile-ud wider enq_name_field'>
			                                <span class='profile-ud-label'>Name</span>
		                                	<span class='profile-ud-value enq_name_align'>".ucfirst($info['name'])."</span>
			                            </div>
			                        </div>
			                    </div>
		                    </div>
							<div class='col-md-6'>		                    	
			                    <div class='profile-ud-list'>
			                        <div class='profile-ud-item'>
			                             <div class='profile-ud wider enq_name_field'>
			                                <span class='profile-ud-label'>Approval Status</span>
			                                <span class='profile-ud-value $app_status_class enq_name_align'>".$app_status."</span>
			                            </div>
			                        </div>
			                    </div>
		                    </div>
		                </div>
		                <div class='row'>
							<div class='col-md-6'>							
								 <div class='profile-ud-list'>
			                        <div class='profile-ud-item'>
			                            <div class='profile-ud wider enq_name_field'>
			                                 <span class='profile-ud-label'>Email Address</span>
		                                <span class='profile-ud-value enq_name_align'>".$info['email']."</span>
			                            </div>
			                        </div>
			                    </div>
		                    </div>
							<div class='col-md-6'>		                    	
			                    <div class='profile-ud-list'>
			                        <div class='profile-ud-item'>
			                             <div class='profile-ud wider'>
			                                <span class='profile-ud-label'>Visibility Status</span>
			                                <span class='profile-ud-value $status_class enq_name_align'>".$status."</span>
			                            </div>
			                        </div>
			                    </div>
		                    </div>
		                </div>
		                <div class='row'>
							<div class='col-md-6'>							
								<div class='profile-ud-list'>
		                       <div class='profile-ud-item'>
		                            <div class='profile-ud wider enq_name_field'>
		                               <span class='profile-ud-label'>Product Name</span>
		                                <span class='profile-ud-value enq_name_align'>".$p_info['product_name']."</span>
		                            </div>
		                        </div>
		                    </div>
		                    </div>
							
		                </div>
		                <div class='row'>
							<div class='col-md-6'>							
								 <div class='profile-ud-list'>
			                        <div class='profile-ud-item'>
			                             <div class='profile-ud wider enq_name_field'>
			                                <span class='profile-ud-label'>Date</span>
			                                <span class='profile-ud-value enq_name_align'>".date("d-M-Y",strtotime($info['created_at']))."</span>
			                            </div>
			                        </div>
			                    </div>
		                    </div>
		                </div>
	                </div>
	                <div class='nk-block nk_block_pt'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Message</h6>
	                    </div>
	                    <div class='enq_message'>
	                        <p>".$info['comment']."</p>
	                    </div>
	                </div>
	                <div class='nk-block nk_block_pt'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Replay</h6>
	                    </div>
	                    <div class='enq_message'>
	                        <input type='hidden' name='review_id' value='".$this->encryptData($info['id'])."'>
                            <textarea name='review_replay' id='review_replay' rows='6' class='form-control'  placeholder='Enter Your Message'>$admin_replay</textarea>
	                    </div>
	                    <p class='float-right model_pt'>
                            <button type='submit' class='btn btn-primary ' data-form='viewReview' data-formclass='view_contact_class'> Submit</button>
                            <button type='button' class='btn btn-light close_review_detail' data-form='viewReview' data-formclass='view_contact_class'> Cancel</button>
                        </p>
	                </div>
	                
				";
		return $layout;
	}

	// Change Approval  Status

	function reveiwApprovalStatus($data)
	{
		$data = $this->decryptData($data);
		$info = $this -> getDetails(REVIEW_TBL,"approval_status"," id ='$data' ");
		if($info['approval_status'] ==1){
			$query = "UPDATE ".REVIEW_TBL." SET approval_status='0' WHERE id='$data' ";
		}else{
			$query = "UPDATE ".REVIEW_TBL." SET approval_status='1' WHERE id='$data' ";
		}
		$up_exe = $this->exeQuery($query);
		if($up_exe){
			return 1;
		}
	}

	// Change Visiblity  Status

	function changeReveiwStatus($data)
	{
		$data = $this->decryptData($data);
		$info = $this -> getDetails(REVIEW_TBL,"status"," id ='$data' ");
		if($info['status'] ==1){
			$query = "UPDATE ".REVIEW_TBL." SET status='0' WHERE id='$data' ";
		}else{
			$query = "UPDATE ".REVIEW_TBL." SET status='1' WHERE id='$data' ";
		}
		$up_exe = $this->exeQuery($query);
		if($up_exe){
			return 1;
		}
	}

	// Review Replay

	function reviewReplay($data) 
	{	
		$id = $this->decryptData($data['review_id']);
		$curr  		 	= date("Y-m-d H:i:s");
		$query = "UPDATE ".REVIEW_TBL." SET admin_replay='".$this->cleanString($data['review_replay'])."', replay_at='$curr' WHERE id='$id' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe){
			return 1;
		}
	}

	// Trash Review

	function trashproductReview($data)
	{
		$data   = $this->decryptData($data);
		$query  = "UPDATE ".REVIEW_TBL." SET del_status='1' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}

	// Restore Product Review

	function restoreProductReview($data)
	{	
		$data = $this->decryptData($data);
		$query = "UPDATE ".REVIEW_TBL." SET del_status='0' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}

	/*----------------------------------------
		Manage Work with us request
	-----------------------------------------*/

	function manageWorkWithUsRequest()
	{
		$layout = "";
		$q = "SELECT * FROM ".WORK_WITH_US_TBL." WHERE delete_status='0' AND status='1' ORDER BY id DESC " ;
	    $query = $this->exeQuery($q);	
	    if(mysqli_num_rows($query) > 0){
	    	$i=1;
	    	while($details = mysqli_fetch_array($query)){
	    		$list 		= $this->editPagePublish($details);

	    		$layout .= "
	    				<tr class='nk-tb-item open_enq_model' data-target='#modalForm' data-keyboard='false' data-backdrop='static' data-toggle='modal' data-option='".$this->encryptData($list['id'])."' >
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                             <td class='nk-tb-col'>
                                ".date("d-m-Y", strtotime($list['created_at']))."
                            </td>
                           
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span><br>
                                <em class='icon ni ni-mail'></em> <span >". $list['email']."</span><br>
                                <em class='icon ni ni-mobile'></em> <span >". $list['mobile']."</span><br>

	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['company_name'])."</span><br>
                                <em class='icon ni ni-location'></em> <span >". $list['city']."</span>
	                        </td>

                            <td class='nk-tb-col'>
                                ".$list['message']."
                            </td>";


                        
                $layout .= "
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                
                                    <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon trashWorkWithUsRequest'  data-link='enquiry/workwithus' data-toggle='tooltip' data-placement='top' title='Trash Request' data-option='".$this->encryptData($list['id'])."' >
                                            <em class='icon ni ni-trash-fill'></em>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                    	</tr>";
	    		$i++;
	    	}
	    }
	    	return $layout;
	}

	// Trash Contact Enquiry

	function trashWorkWithUsRequest($data)
	{
		$data   = $this->decryptData($data);
		$query  = "UPDATE ".WORK_WITH_US_TBL." SET delete_status='1' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}

	// Restore Work With Us Request

	function restoreWorkWithUsRequest($data)
	{	
		$data = $this->decryptData($data);
		$query = "UPDATE ".WORK_WITH_US_TBL." SET delete_status='0' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}


	// Get Contact Details

	function getWorkWithUsRequestDetails($data){
		$layout 	= "";
		$contact_id = $this->decryptData($data);
		$info 		= $this->getDetails(WORK_WITH_US_TBL,"*"," id='$contact_id' ");
		$info 		= $this->cleanStringData($info);
		$curr 		= date("Y-m-d H:i:s");

		$layout .="
					<div class='nk-block'>
					<div class='row'>
						<div class='col-md-6'>							
							<div class='profile-ud-list'>
		                        <div class='profile-ud-item'>
		                            <div class='profile-ud wider enq_name_field'>
		                                <span class='profile-ud-label'>Company Name</span>
		                                <span class='profile-ud-value enq_name_align'>".$info['company_name']."</span>
		                            </div>
		                        </div>
		                    </div>
	                    </div>
						<div class='col-md-6'>		                    	
		                   <div class='profile-ud-list'>
		                        <div class='profile-ud-item'>
		                            <div class='profile-ud wider enq_name_field'>
		                                <span class='profile-ud-label'>City </span>
		                                <span class='profile-ud-value enq_name_align'>".$info['city']."</span>
		                            </div>
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class='row'>
						<div class='col-md-6'>							
							 <div class='profile-ud-list'>
		                        <div class='profile-ud-item'>
		                            <div class='profile-ud wider enq_name_field'>
		                                <span class='profile-ud-label'>Contact Person </span>
		                                <span class='profile-ud-value enq_name_align'>".$info['name']."</span>
		                            </div>
		                        </div>
		                    </div>
	                    </div>
						<div class='col-md-6'>		                    	
		                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>State</span>
	                                <span class='profile-ud-value enq_name_align'>".$info['state']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    </div>
	                </div>
	                <div class='row'>
						<div class='col-md-6'>							
							<div class='profile-ud-list'>
	                       <div class='profile-ud-item'>
	                            <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Mobile Number</span>
	                                <span class='profile-ud-value enq_name_align'>".$info['mobile']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    </div>
						<div class='col-md-6'>		                    	
		                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Pincode</span>
	                                <span class='profile-ud-value'>".$info['pincode']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    </div>
	                </div>
	                 <div class='row'>
						<div class='col-md-6'>							
							<div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>Email Address</span>
	                                <span class='profile-ud-value'>".$info['email']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    </div>
						<div class='col-md-6'>		                    	
		                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider'>
	                                <span class='profile-ud-label'>GST IN</span>
	                                <span class='profile-ud-value'>".$info['gst_no']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    </div>
	                </div>
	                <div class='row'>
						<div class='col-md-6'>							
							<div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Date</span>
	                                <span class='profile-ud-value enq_name_align'>".date("d-M-Y",strtotime($info['created_at']))."</span>
	                            </div>
	                        </div>
	                    </div>
	                    </div>
						<div class='col-md-6'>		                    	
		                    <div class='profile-ud-list'>
	                        <div class='profile-ud-item'>
	                             <div class='profile-ud wider enq_name_field'>
	                                <span class='profile-ud-label'>Address</span>
	                                <span class='profile-ud-value enq_name_align'>".$info['address']."</span>
	                            </div>
	                        </div>
	                    </div>
	                    </div>
	                </div>
	                </div>
	                <div class='nk-block'>
	                    <div class='nk-block-head nk-block-head-line'>
	                        <h6 class='title overline-title text-base'>Message</h6>
	                    </div>
	                    <div class='enq_message'>
	                        <p>".$info['message']."</p>
	                    </div>
	                </div>
				";
		return $layout;
	}


	
	/*-----------Dont'delete---------*/

	}


?>





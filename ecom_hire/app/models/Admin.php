<?php
	require_once 'Model.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Admin extends Model
	{

		//----------- Migrations -------------//

		//  Migration List

		function migrationList()
		{
			$layout = "";
			$q = "SELECT * FROM ".MIGRATION_TBL." WHERE 1 ";
			$exe = $this->exeQuery($q);	
		    if(mysqli_num_rows($exe) > 0){
		    	$i=1;
		    	while($rows = mysqli_fetch_array($exe)){
		    		$list 	= $this->editPagePublish($rows);
		    		$layout .= "
		    			<tr class='nk-tb-item'>
                            <td class='nk-tb-col'>
                                ".$list['id']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$list['name']."</span>
                            </td>
                            <td class='nk-tb-col tb-col-mb'>
                                 ".$list['type']."
                            </td>
                            <td class='nk-tb-col tb-col-mb'>
                                 ".$list['sql_query']."
                            </td>
                           	<td class='nk-tb-col tb-col-mb'>
                                 ".$list['remarks']."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                ".date("d/m/Y", strtotime($list['created_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                            	<button class='btn btn-success btn-sm' ><em class='icon ni ni-upload-cloud'></em></button>
                            </td>
                        </tr>";
		    		$i++;
		    	}
		    } 
		    return $layout;
		}

		/*----------------------------------------
				Manage Index Info 
		-----------------------------------------*/

		function getIndexInfo()
		{

			$query = " SELECT name, 
			
				(SELECT COUNT(id) as tots FROM ".CONTRACTOR_ENQUIRY_TBL." WHERE status='1' AND contractor_id='".@$_SESSION['ecom_contractor_id']."' ) as total_enquiries,
				(SELECT COUNT(id) as tots FROM ".CONTRACTOR_ENQUIRY_TBL." WHERE status='1' AND contractor_id='".@$_SESSION['ecom_contractor_id']."' AND date(created_at) = CURDATE() ) as today_enquiries,
				(SELECT COUNT(id) as tots FROM ".CONTRACTOR_PROJECT_TBL." WHERE delete_status='0' AND contractor_id='".@$_SESSION['ecom_contractor_id']."' ) as total_projects
				
			 	FROM ".CONTRACTOR_TBL." WHERE id =  '".$_SESSION['ecom_contractor_id']."' ";
			 $exe = $this->exeQuery($query);
			 $list = mysqli_fetch_assoc($exe);


			return $list;
		}

		 // Datas for chart in index

	    function getEnquiryChartData() 
	    {
	        $result = array();
	        
			$current_date   = date("Y-m-d");
			$from 			= date("Y-m-d", strtotime("-30 days", strtotime($current_date)));
            $to     		= $current_date;
            $date_filter 	= "AND DATE(created_at) BETWEEN '$from' AND '$to' ";

            // For sales vs order chart

            $dates                = array();
            $daily_total          = array();
            $current              = strtotime($from);
            $date2                = strtotime($to);
            $stepVal              = '+1 day';
	                
	            while( $current <= $date2 ) {
	                $dates[] ='"'.date("d-M", $current).'"';
	                $q_date  = date("Y-m-d",$current);
	                $current = strtotime($stepVal, $current);
	                $q       = "SELECT COUNT(id) as dailyTotal FROM ".CONTRACTOR_ENQUIRY_TBL." WHERE DATE(created_at) = '".$q_date."' AND contractor_id='".$_SESSION["ecom_contractor_id"]."'";
	                $exe     =  $this->exeQuery($q);
	                $list    = mysqli_fetch_array($exe);
	                $daily_total[] = (($list['dailyTotal']=="")? 0 : $list['dailyTotal'] );
	            }

          	$x_axis       = "[". implode(",",$dates) ."]" ;
          	$y_axis_sales = "[". implode(",",$daily_total) ."]" ;


	        $q  = "SELECT COUNT(id) as totalEnquires  FROM ".CONTRACTOR_ENQUIRY_TBL."  WHERE contractor_id='".$_SESSION["ecom_contractor_id"]."'  ".$date_filter." ";
	        $exe = $this->exeQuery($q);
	        $list = mysqli_fetch_array($exe);


	        $result['count_data']               = $list; 
	        $result['x_axis']                   = $x_axis; 
	        $result['y_axis_sales']             = $y_axis_sales; 

	        return $result;
	    }



		/*----------------------------------------
				Manage Enquiry 
		-----------------------------------------*/

		function manageEnquiry($contractor,$from="",$to="",$type="")
		{
			$layout = "";

			$date_filter = "";

			if($from!="" && $from!=0 && $from!="today" )
	        {   
	            $from = date("Y-m-d",strtotime($from));
	            $to   = date("Y-m-d",strtotime($to));
	            $date_filter = "AND DATE(created_at) BETWEEN '$from' AND '$to'";
	        } else {
	            $date_filter = "";
	        }

	        if($type=="unread") {
	        	$read_status_filter = "AND read_status='0' ";
	        } elseif ($type=='read') {
	        	$read_status_filter = "AND read_status='1' ";
	        } else {
	        	$read_status_filter = "";
	        }

			if($from=="today")
			{
				$date_filter = "AND DATE(created_at)=CURDATE() ";
			}

			$q = "SELECT * FROM ".CONTRACTOR_ENQUIRY_TBL." WHERE contractor_id='$contractor' ".$date_filter." ".$read_status_filter." AND delete_status='0' AND status='1' ORDER BY id DESC " ;
		    $query = $this->exeQuery($q);	
		    if(mysqli_num_rows($query) > 0){
		    	$i=1;
		    	while($details = mysqli_fetch_array($query)){
		    		$list 		= $this->editPagePublish($details);
		    		$read_status = (($list['read_status']==1)? "enq_readed_msg" : "" );
		    		$read_tootip = (($list['read_status']==1)? "Mark as unread" : "Mark as read" );
		    		$read_icon   = (($list['read_status']==1)? "ni-book-read" : "ni-book" );

		    		$check_box_status = (($list['read_status']==1)? "checked" : "" );

		    		$td_data = "data-dycryprt_id='".$list['id']."' data-toggle='modal'  data-option='".$this->encryptData($list['id'])."' data-target='#modalForm' data-keyboard='false' data-backdrop='static'";

		    		$layout .= "
		    				<tr class='nk-tb-item enq_tr_".$list['id']." $read_status' >
		    				<td class='nk-tb-col'>
		    				 	<div class='custom-control custom-control-sm custom-checkbox notext' data-toggle='tooltip' data-placement='top' title='$read_tootip'>
                                    <input type='checkbox' class='custom-control-input readStatusToogle' id='enq_check_bok_".$list['id']."' data-option='".$this->encryptData($list['id'])."' data-dycryprt_id='".$list['id']."'    $check_box_status>
                                    <label class='custom-control-label'  for='enq_check_bok_".$list['id']."'></label>
                                </div>
		    				</td>
	    					<td class='nk-tb-col open_enq_model' $td_data>
                                ".$i."
                            </td>
                             <td class='nk-tb-col open_enq_model' $td_data>
                                ".date("d-m-Y", strtotime($list['created_at']))."
                            </td>
                            <td class='nk-tb-col tb-col-md open_enq_model' $td_data>
                                <span class='text-primary'>".$this->publishContent($list['name'])."</span>
	                        </td>
	                        <td class='nk-tb-col open_enq_model' $td_data>
                                ".$list['email']."
                            </td>
                            <td class='nk-tb-col open_enq_model' $td_data>
                                ".$list['mobile']."
                            </td>

                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon trashEnquiry'  data-link='enquiry/contact' data-toggle='tooltip' data-placement='top' title='Trash Enquiry' data-option='".$this->encryptData($list['id'])."' >
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

		// Trash Enquiry 

		function trashEnquiry($data)
		{
			$data   = $this->decryptData($data);
			$query  = "UPDATE ".CONTRACTOR_ENQUIRY_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Get Enquiry Details

		function getEnquiryDetails($data){
			$layout = "";
			$enq_id = $this->decryptData($data);
			$info = $this->getDetails(CONTRACTOR_ENQUIRY_TBL,"*"," id='$enq_id' ");
			$info = $this->cleanStringData($info);
			$curr = date("Y-m-d H:i:s");

			$q = "UPDATE ".CONTRACTOR_ENQUIRY_TBL." SET
				  read_status = '1',
				  updated_at  = '".$curr."'
				  WHERE id='".$enq_id."'
				 ";
		    $exe = $this->exeQuery($q);

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
		                        <h6 class='title overline-title text-base'>Message</h6>
		                    </div>
		                    <div class='enq_message'>
		                        <p>".$info['message']."</p>
		                    </div>
		                </div>
					";
			return $layout;
		}

		// Toogle read enquiry status

		function toggleEnquiryReadStatus($input) 
		{
			$enq_id = $this->decryptData($input);
			$curr   = date("Y-m-d H:i:s");
			$info   = $this->getDetails(CONTRACTOR_ENQUIRY_TBL,"*"," id='$enq_id' ");

			if($info['read_status'] ==1){
				$query = "UPDATE ".CONTRACTOR_ENQUIRY_TBL." SET read_status='0' WHERE id='$enq_id' ";
		    	$exe = $this->exeQuery($query);
		    	return 0;

			}else{
				$query = "UPDATE ".CONTRACTOR_ENQUIRY_TBL." SET read_status='1' WHERE id='$enq_id' ";
		    	$exe = $this->exeQuery($query);
		    	return 1;
			}

		}

		/*----------------------------------------
				Project list
		-----------------------------------------*/

		function manageProjectList() 
		{

			$layout = "";
			$contractor_id = $_SESSION['ecom_contractor_id'];
			$q = "SELECT * FROM ".CONTRACTOR_PROJECT_TBL." WHERE contractor_id='$contractor_id' AND delete_status='0' AND status='1' ORDER BY id DESC " ;
		    $query = $this->exeQuery($q);	
		    if(mysqli_num_rows($query) > 0){
		    	$i=1;
		    	while($details = mysqli_fetch_array($query)){
		    		$list 		    = $this->editPagePublish($details);
		    		$image          = $this->getDetails(CONTRACTOR_PROJECT_IMG_TBL,"*","project_id='".$list['id']."' ORDER BY id ASC LIMIT 1 ");
					$project_image  = (($image)?  (($image['file_name']!='')? HIRE_UPLOADS.$image['file_name'] : ASSETS_PATH."no_img.png" ) : ASSETS_PATH."no_img.png" );# code...
		    		$request_status = (($list['verified_status']==0)? "<span class='badge badge-dot badge-dot-xs badge-warning'>Not Verified</span>" : "<span class='badge badge-dot badge-dot-xs badge-success'>Verified</span>" );
		    		

					$layout .= "
		    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                <img src='".$project_image."' width=50 />
                            </td>
                             <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$this->publishContent($list['project_title'])."</span>
	                        </td>
	                        <td class='nk-tb-col'>
                                ".$list['description']."
                            </td>
                            <td class='nk-tb-col'>
                                ".date("d-m-Y", strtotime($list['created_at']))."
                            </td>
                            <td class='nk-tb-col'>
			                    ".$request_status."
			                </td>
			                 <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <a href='ecom_hire/project/edit/".$list['id']."' class='btn btn-trigger btn-icon editClassifiedProject' data-toggle='tooltip' data-placement='top' title='Edit Project' data-option='".$this->encryptData($list['id'])."' >
                                            <em class='icon ni ni-edit-fill'></em>
                                        </a>
                                    </li>
									<li class='nk-tb-action-hidden'>
									<a href='javascript:void();' class='btn btn-trigger btn-icon trashClassifiedProject' data-toggle='tooltip' data-placement='top' title='Trash Project' data-option='".$this->encryptData($list['id'])."' >
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





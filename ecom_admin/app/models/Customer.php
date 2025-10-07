<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Customer extends Model
	{	
		
	/*--------------------------------------------- 
				 Customer
	----------------------------------------------*/

	// Manage Customer

	function manageCustomer() 
	{
		$layout = "";
    	$q      = "SELECT * FROM ".CUSTOMER_TBL." WHERE 1 ORDER BY registration_date DESC " ;
		    $exe    = $this->exeQuery($q);
		    if(mysqli_num_rows($exe)>0){
		    	$i=1;
		    	while($rows = mysqli_fetch_array($exe)){
	    		$list 	= $this->editPagePublish($rows);
	    		$status 	  	= (($list['status']==1) ? "Active" : "Inactive"); 
	    		$status_c     	= (($list['status']==1) ? "checked" : " ");
				$status_class 	= (($list['status']==1) ? "text-success" : "text-danger");


                if ($list['delete_status']==1) {
                	$delete_btn 		=	"<em class='icon ni ni-undo'></em>";
					$delete_class 		= 'restoreCustomer';
					$delete_class_hover = 'Restore Customer';

				}else{
					$delete_btn			=	"<em class='icon ni ni-trash-fill'></em>";
					$delete_class 		= 'trashCustomer';
                	$delete_class_hover = 'Trash Customer';
                    
				}

				if($list['delete_status']==1){
					$customer_status = "<span class='tb-status text-danger'>Suspend</span>";
				}else if($list['email_verify']==0){
					$customer_status = "<span class='tb-status text-warning'>Unverified</span>";
				}else{
					$customer_status = "<span class='tb-status text-success'>Verified</span>";
				}	

				$total_order    = 0;
				$total_value    = 0;

				$order_q = " SELECT * FROM ".ORDER_TBL." WHERE 1 AND user_id=".$list['id']." ";
				$order_query  = $this->exeQuery($order_q);
			    if(@mysqli_num_rows($order_query)>0){
			    	
			    	while($orderlist = mysqli_fetch_array($order_query)){
						if($orderlist['order_status']==2)
						{
							$total_order = $total_order + 1;
							$total_value = $total_value + $orderlist['total_amount'];
						}
			    	}
			    }

	    		$layout .= "
    				<tr class='nk-tb-item open_enq_model' data-option='".$list['id']."'>
    					<td class='nk-tb-col'>
                            ".$i."
                        </td>
                        <td class='nk-tb-col tb-col-md'>".date("d/m/Y ", strtotime($list['registration_date']))."</td>	
                       
                       	 <td class='nk-tb-col tb-col-md'>
                            <div class='user-card'>
                                <div class='user-info'>
                					<a href='".COREPATH."customers/details/".$list['id']."' ><span class='text-primary'>".$this->unHyphenize($list['name'])."</span></a>
                                </div>
                            </div>
                        </td>

                        <td class='nk-tb-col tb-col-md'>
                            <em class='icon ni ni-mail'></em> <span >". $list['email']."</span><br>
                            <em class='icon ni ni-mobile'></em> <span >". $list['mobile']."</span>
                        </td>

    					<td class='nk-tb-col tb-col-md'> <span class='tb-amount'>".$total_order." <span class='currency'>".(($total_order> 0) ? (($total_order > 1 )? 'Orders' : 'Order') : "")."</span></span></td>
    					<td class='nk-tb-col tb-col-md'>
                                        <span class='tb-amount'>₹ ".$this->inrFormat($total_value)." </span>
    					</td> 
                    	<td class='nk-tb-col tb-col-md'>
                    		".$customer_status."
                    	</td>
                    	
                    </tr>";
	    		$i++;
	    	}
		    }
		    return $layout;
	}

	/*--------------------------------------------
		 Manage Order List
	---------------------------------------------*/

	function manageUserOrdersList($cus_id)
	{
		$layout ="";
		$query  ="SELECT O.id,O.total_amount,O.user_id,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.created_at,C.name,C.mobile,C.email,(SELECT vendor_commission_amt FROM ".ORDER_TBL." WHERE id=O.id) as total_commission,(SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as items FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE O.user_id='$cus_id' ORDER BY id  DESC";
		$exe 	= $this->exeQuery($query);
		if(mysqli_num_rows($exe) > 0) {
			$i=1;
				while ($rows = mysqli_fetch_array($exe)) {
					$list 	= $this->editPagePublish($rows);
					$layout .="
								<tr class='nk-tb-item open_order_details_page' data-option='".$list['id']."' >
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href='".COREPATH."orders/orderdetails/".$list['id']."'>".$list['order_uid']."</a></span>
                                        </td>
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".date('d/m/Y',strtotime($list['created_at']))."</span>
                                        </td>
                                        <td class='nk-tb-col'>
                                            <div class='user-card'>
                                                <div class='user-info'>
                                                    <span class='tb-lead'>".$list['name']."<span class='dot dot-success d-md-none ml-1'></span></span>
                                                    <span><em class='icon ni ni-mail'></em> ".$list['email']."</span><br>
                                                    <span><em class='icon ni ni-mobile'></em> ".$list['mobile']."</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>".$list['items']." <span class='currency'>".(($list['items'] > 1 )? 'Items' : 'Item')."</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['total_commission'])." </span>
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$this->inrFormat($list['total_amount'])." </span>
                                        </td>
                                        <td class='nk-tb-col tb-col-md'>
                                            <span class='tb-status text-success'>Active</span>
                                        </td>
                                    </tr>

							  ";
				$i++;
				}
		}
		return $layout;
	}

	// Change Customer Status

	function changeCustomerStatus($data)
	{
		//$data = $this->decryptData($data);
		$info = $this -> getDetails(CUSTOMER_TBL,"status"," id ='$data' ");
		if($info['status'] ==1){
			$query = "UPDATE ".CUSTOMER_TBL." SET status='0' WHERE id='$data' ";
		}else{
			$query = "UPDATE ".CUSTOMER_TBL." SET status='1' WHERE id='$data' ";
		}
		$up_exe = $this->exeQuery($query);
		if($up_exe){
			return 1;
		}
	}

	// Trash Customer

	function trashCustomer($data)
	{
		$data   = $this->decryptData($data);
		$query  = "UPDATE ".CUSTOMER_TBL." SET delete_status='1' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}

	// Restore Customer

	function restoreCustomer($data)
	{	
		$data   = $this->decryptData($data);
		$info   = $this -> getDetails(CUSTOMER_TBL,"delete_status"," id ='$data' ");
		$query  = "UPDATE ".CUSTOMER_TBL." SET delete_status='0' WHERE id='$data' ";
		$up_exe = $this->exeQuery($query);
		if($up_exe) {
			return 1;
		}
	}

	// Customer Order History (Not In Use)
	function customerViewOrders($id)
  	{
  		$layout = "";
	    $q= "SELECT O.id,O.user_id,O.order_uid,O.total_amount,O.order_address,O.payment_type,O.shipping_type,O.order_date,O.order_status,O.deliver_status,O.status FROM ".ORDER_TBL." O WHERE O.user_id='$id' ORDER BY O.order_date DESC " ;
	    $query = $this->exeQuery($q);
	    if(mysqli_num_rows($query)>0){
	    	$i=1;
	    	while($rows = mysqli_fetch_array($query)){
	    		$list 	= $this->editPagePublish($rows);

	    		if($list['deliver_status']=="1"){
	    			$status_order ="Delivered";
	    		}else{
	    			$status_order ="Waiting for Delivery";
	    		}
	    		
	    		$layout .= "
	    		<tr class='nk-tb-item'>
					<td class='nk-tb-col'>$i</td>
					<td class='nk-tb-col'><a href='".COREPATH."orders/details/".$list['id']."'>".$list['order_uid']."</a> </td>
					<td class='nk-tb-col'>".date("d/m/Y ",strtotime($list['order_date']))."</td>
					<td class='nk-tb-col'>Rs. ".$list['total_amount']."</td>
					<td class='nk-tb-col'>".ucwords($list['payment_type'])."</td>
					<td class='nk-tb-col'>".$status_order."</td>
				</tr>
				";
	    		$i++;
	    	}
	    }
	    	return $layout;
  	}  

  	// Customer Review History 
  	function customerViewReviews($id)
  	{
  		$layout = "";
	    $q = "SELECT R.id,R.product_id,R.created_at,R.comment,R.approval_status,R.del_status,R.status,R.updated_at,R.added_by,C.name,P.product_name FROM ".REVIEW_TBL." R LEFT JOIN ".CUSTOMER_TBL." C ON (C.id=R.added_by) LEFT JOIN ".PRODUCT_TBL." P ON (P.id=R.product_id) WHERE 1 AND R.added_by=".$id." ORDER BY updated_at DESC  " ;
	    $query = $this->exeQuery($q);
	    if(mysqli_num_rows($query)>0){
	    	$i=1;
	    	while($rows = mysqli_fetch_array($query)){
		    	$list 	= $this->editPagePublish($rows);
	    		$status = (($list['status']==1) ? "Active" : "Inactive"); 
				$status_c = (($list['status']==1) ? "checked" : " ");
				$status_class = (($list['status']==1) ? "text-success" : "text-danger");
				$checkbox_class = (($list['status']==1) ? "success" : "danger");
				$appstatus = (($list['approval_status']==1) ? "Approval" : "Not Approval"); 
				$appstatus_c = (($list['approval_status']==1) ? "checked" : " ");
				$appstatus_class = (($list['approval_status']==1) ? "text-success" : "text-danger");
				$appcheckbox_class = (($list['approval_status']==1) ? "success" : "danger");
	    		$layout .= "
	    		<tr class='nk-tb-item open_review_list_page'>
	    			<td class='nk-tb-col'>$i</td>
	    			<td class='nk-tb-col'>".date("d/m/Y ",strtotime($list['created_at']))."</td>
	    			<td class='nk-tb-col'>".$this->unHyphenize($list['product_name'])."</td>
	    			<td class='nk-tb-col'>".$this->unHyphenize($list['comment'])."</td>
	    			<td class='nk-tb-col'> 
                        <label for='check_$i' class='$appstatus_class'>
                         $appstatus
                        </label>
					</td>
	    			<td class='nk-tb-col'> 
                        <label for='check_$i' class='$status_class'>
                           $status
                        </label>
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





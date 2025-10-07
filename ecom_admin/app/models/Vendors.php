<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'MultipleFileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class Vendors extends Model
	{	

		/*--------------------------------------------- 
					  Vendor Management
		----------------------------------------------*/

		// Manage Vendor 

		function manageVendor() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".VENDOR_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status              = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c            = (($list['status']==1) ? "checked" : " ");
					$status_class        = (($list['status']==1) ? "text-success" : "text-danger");

					// Active status 
					$active_status       = (($list['active_status']==1)? 1 : 0 );
					$active_status_text  = (($active_status==1) ? "Active" : "Inactive"); 
					$active_status_class = (($active_status==0) ? "text-danger" : "text-success"); 

					// Draft Status
					$draft_status        = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c      = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class  = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row        = (($list['is_draft']==1) ? "draft_item" : ""); 

					$invite_button       = (($list['invite_status']==1) ? "Resend" : "Send"); 
					$invite_disable      = (($list['is_draft']==1) ? "disabled" : ""); 


					if ($list['is_draft']==1) {
						$draft_action        = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishVendor' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class        = 'deleteVendor';
						$delete_class_hover  = 'Delete Vendor';

					}else{
						$draft_action        = "<div class='tb-tnx-status'>
					                            	<span class='badge badge-dot text-success cursor_pointer' > Published </span>
					                            </div>";
	                    $delete_class        = 'trashVendor';
	                    $delete_class_hover  = 'Trash Vendor';
	                    
					}

					if ($list['is_draft']==0 && $list['status']==1) {
						$preview = "<li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='View On Website'>
                                            <em class='icon ni ni-eye-fill'></em>
                                        </a>
                                    </li>";
					}else{
						$preview = "";
					}

					$td_data = "data-data_id='".$list['token']."' data-data_link='vendor/details'";

		    		$layout .= "
	    				<tr class='nk-tb-item $is_draft_row dot_display_none'>
	    					<td class='nk-tb-col td_click' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <a href='".COREPATH."vendor/details/".$list['token']."' ><span class='text-primary'>".$this->publishContent($list['company'])."</span></a>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <em class='icon ni ni-mail'></em> <span >". $list['email']."</span><br>
                                <em class='icon ni ni-mobile'></em> <span >". $list['mobile']."</span>
	                        </td>
	                        <td class='nk-tb-col td_click' $td_data>
                                ".$list['city']."
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                                <div class='tb-tnx-status'>
	                            	<span class='badge badge-dot ".$active_status_class." cursor_pointer' > ".$active_status_text." </span>
	                            </div>
                            </td>
	                        <td class='nk-tb-col td_click' $td_data>
                               	$draft_action
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeVendorStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon $delete_class' data-toggle='tooltip' data-placement='top' title='$delete_class_hover' data-option='".$this->encryptData($list['id'])."' >
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

		// Manage Expired Vendors List 

		function manageExpiredVendorList() 
		{
			$layout = "";
	    	$q = "SELECT * FROM ".VENDOR_TBL." WHERE 1 AND delete_status='0' ORDER BY id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status              = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c            = (($list['status']==1) ? "checked" : " ");
					$status_class        = (($list['status']==1) ? "text-success" : "text-danger");

					// Active status 
					$active_status       = (($list['active_status']==1)? 1 : 0 );
					$active_status_text  = (($active_status==1) ? "Active" : "Inactive"); 
					$active_status_class = (($active_status==0) ? "text-danger" : "text-success"); 

					// Draft Status
					$draft_status        = (($list['is_draft']==1) ? "Draft" : "Published"); 
					$draft_status_c      = (($list['is_draft']==1) ? "checked" : ""); 
					$draft_status_class  = (($list['is_draft']==1) ? "text-warning" : "text-success"); 

					$is_draft_row        = (($list['is_draft']==1) ? "draft_item" : ""); 

					$invite_button       = (($list['invite_status']==1) ? "Resend" : "Send"); 
					$invite_disable      = (($list['is_draft']==1) ? "disabled" : ""); 


					$today               = strtotime(date("Y/m/d"));
					$valid_to            = strtotime(date("Y/m/d",strtotime($list['valid_to'])));
					$timeDiff            = abs($today - $valid_to );
					$numberDays          = $timeDiff/86400;  // 86400 seconds in one day
					// and you might want to convert to integer
					$numberDays          = intval($numberDays);

					// Expired Status
					$days                = (($numberDays == 1)? "Tomorrow" : " within ".$numberDays." Days" );

					if($valid_to <= $today) 
					{	
						if ($valid_to == $today) {
							$expired_status  = "Expires Today";
						} else {
							$expired_status  = "Expired";
						}

					} elseif ($numberDays <= 3 && $numberDays != 0) {
						$expired_status  = "Expires ".$days;
					}



					if ($list['is_draft']==1) {
						$draft_action        = "<button type='button' data-option='".$this->encryptData($list['id'])."' class='btn btn-warning btn-dim btn-sm publishVendor' ><em class='icon ni ni-check-thick'></em> Publish</button>";
						$delete_class        = 'deleteVendor';
						$delete_class_hover  = 'Delete Vendor';

					}else{
						$draft_action        = "<div class='tb-tnx-status'>
					                            	<span class='badge badge-dot text-success cursor_pointer' > Published </span>
					                            </div>";
	                    $delete_class        = 'trashVendor';
	                    $delete_class_hover  = 'Trash Vendor';
	                    
					}

					if ($list['is_draft']==0 && $list['status']==1) {
						$preview = "<li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='View On Website'>
                                            <em class='icon ni ni-eye-fill'></em>
                                        </a>
                                    </li>";
					} else {
						$preview = "";
					}

					$td_data = "data-data_id='".$list['token']."' data-data_link='vendor/details'";

					if($today >=  $valid_to || $numberDays <= 3  ) {
		    			$layout .= "
	    				<tr class='nk-tb-item $is_draft_row dot_display_none'>
	    					<td class='nk-tb-col td_click' $td_data>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <a href='".COREPATH."vendor/details/".$list['token']."' ><span class='text-primary'>".$this->publishContent($list['company'])."</span></a>
	                        </td>
	                        <td class='nk-tb-col tb-col-md td_click' $td_data>
                                <em class='icon ni ni-mail'></em> <span >". $list['email']."</span><br>
                                <em class='icon ni ni-mobile'></em> <span >". $list['mobile']."</span><br>
                                <em class='icon ni ni-location'></em> <span >". $list['city']."</span>
	                        </td>
                            <td class='nk-tb-col td_click' $td_data>
                                ".$expired_status." 
                            </td>
                            <td class='nk-tb-col td_click' $td_data>
                                <div class='tb-tnx-status'>
	                            	<span class='badge badge-dot ".$active_status_class." cursor_pointer' > ".$active_status_text." </span>
	                            </div>
                            </td>
	                        <td class='nk-tb-col tb-col-md'>
	                            <div class='custom-control custom-switch'>
                                    <input type='checkbox' class='custom-control-input changeVendorStatus' data-option='".$this->encryptData($list['id'])."'   value='0'  id='status_".$i."' name='save_as_draft' $status_c>
                                    <label class='custom-control-label ' for='status_".$i."'><span class='$status_class'>$status </span></label>
                                </div>
	                        </td>
                            <td class='nk-tb-col nk-tb-col-tools'>
                                <ul class='nk-tb-actions gx-1'>
                                    <li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon $delete_class' data-toggle='tooltip' data-placement='top' title='$delete_class_hover' data-option='".$this->encryptData($list['id'])."' >
                                            <em class='icon ni ni-trash-fill'></em>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>";
		    			$i++;
		    		}
		    	}
 		    }
 		    return $layout;
		}

		// Order List

		function manageVendorOrdersList()
		{
			$layout ="";
			$query  ="SELECT O.id,O.total_amount,O.shipping_cost,O.coupon_value,O.order_uid,O.payment_type,O.order_status,O.payment_status,O.order_date,O.cancel_status,O.shipping_status,O.deliver_status,O.created_at,C.name,C.mobile,C.email,(SELECT vendor_invoice_number FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id LIMIT 1 ) as vendorInvoiceNumber,(SELECT COUNT(id) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as items,(SELECT SUM(vendor_commission_amt) FROM ".ORDER_ITEM_TBL." WHERE order_id=O.id ) as total_commission FROM ".ORDER_TBL." O LEFT JOIN ".CUSTOMER_TBL." C ON(O.user_id=C.id) WHERE 1 ORDER BY id  DESC";
			$exe 	= $this->exeQuery($query);
			if(mysqli_num_rows($exe) > 0) {
				$i=1;
					while ($rows = mysqli_fetch_array($exe)) {
						$list 	= $this->editPagePublish($rows);

						$data_staus       = (($list['order_status']==0) ? 1 :  2 );
						$status_class 	  = (($list['order_status']==2) ? "text-success" :  "text-warning" ); 
						$status 		  = (($list['order_status']==0) ? "Inprocess" : (($list['order_status']==1) ? "Shipped" : "Delivered") ); 
						$status_btn_title = (($list['order_status']==0) ? "Mark as Shipped" :  (($list['order_status']==1) ? "Mark as Paid" : "Send Invoice") );
						$status_btn 	  = (($list['order_status']==0) ? "<em class='icon ni ni-truck'></em>" :  (($list['order_status']==1) ? "<em class='icon ni 					ni-money'></em>" : "<em class='icon ni ni-report-profit'></em>") ); 
						$layout .="
									<tr class='nk-tb-item open_enq_model' data-option=".$list['id'].">
	                                        <td class='nk-tb-col'>
	                                            <span class='tb-lead'><a href='".COREPATH."orders/vendororderdetails/".$list['id']."'>".$list['vendorInvoiceNumber']."</a></span>
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
	                                            <span class='tb-status $status_class'>$status</span>
	                                        </td>
	                                    </tr>

								  ";
					$i++;
					}
			}
			return $layout;
		}


		/*----------------------------------------
				Manage Product List
		-----------------------------------------*/

		function manageProductList($vendor_id) 
		{
			$check ="";
			$layout = "";
	    	$q = "SELECT  P.id,P.product_name,P.actual_price,P.selling_price,P.category_type, P.sku, P.main_category_id,P.sub_category_id,P.tax_class,P.delete_status,P.is_draft, P.stock, T.tax_class as taxClass , M.category, S.subcategory,VP.id as vendor_assigned_id,VP.has_variants, VP.selling_price as vendor_selling_price,VP.variant_id,VP.status, VP.stock as vendor_stock, VP.status as vendor_product_status,PV.variant_name,PV.selling_price as veriant_selling_price,
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 ORDER BY id ASC LIMIT 1) as product_image, 
	    		(SELECT file_name FROM ".MEDIA_TBL." WHERE item_id=P.id AND item_type='product' AND delete_status=0 AND is_default=1 ORDER BY id ASC LIMIT 1) as default_product_image, 
	    		(SELECT count(id) as total_count FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_count,
	    		(SELECT sum(stock) as total_stock FROM ".PRODUCT_VARIANTS." WHERE product_id=P.id AND delete_status=0 ) as variant_stock

	    	  FROM ".VENDOR_PRODUCTS_TBL." VP LEFT JOIN  ".PRODUCT_TBL." P ON (P.id=VP.product_id)
	    	  LEFT JOIN ".TAX_CLASSES_TBL." T ON(P.tax_class=T.id)
	    	  LEFT JOIN ".MAIN_CATEGORY_TBL." M ON (M.id=P.main_category_id)
	    	  LEFT JOIN ".SUB_CATEGORY_TBL." S ON (S.id=P.sub_category_id)
	    	  LEFT JOIN ".PRODUCT_VARIANTS." PV ON (PV.id=VP.variant_id)
	    	  WHERE VP.vendor_id='$vendor_id' AND VP.selling_price!='0' ORDER BY VP.product_id DESC" ;

 		    $exe = $this->exeQuery($q);
 		     if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	$j=1;
 		    	while($list = mysqli_fetch_array($exe)){
		    		$status = (($list['status']==1) ? "On" : "Off"); 
		    		$status_c = (($list['status']==1) ? "checked" : " ");
					$status_class = (($list['status']==1) ? "text-success" : "text-danger");


					if ($list['status']==1) {
						$preview = "<li class='nk-tb-action-hidden'>
                                        <a href='javascript:void();' class='btn btn-trigger btn-icon' data-toggle='tooltip' data-placement='top' title='View On Website'>
                                            <em class='icon ni ni-eye-fill'></em>
                                        </a>
                                    </li>";
					}else{
						$preview = "";
					}

					// Category
					$category_name = (($list['category_type']=="sub") ? $list['subcategory'] : $list['category'] );

					// Product Image
					if ($list['default_product_image']!="") {
						$product_image = $list['default_product_image']!='' ? UPLOADS.$list['default_product_image'] : ASSETS_PATH."no_img.jpg" ;# code...
					}else{
						$product_image = $list['product_image']!='' ? UPLOADS.$list['product_image'] : ASSETS_PATH."no_img.jpg" ;
					}

					$product_name = ($list['has_variants']==1)? $this->publishContent($list['product_name'])." [ ".$this->publishContent($list['variant_name'])." ]" : $this->publishContent($list['product_name']);

					// Stock
					$stock = $list['has_variants']==1 ? $list['variant_stock']." in stock for  ".$list['variant_count']." variants" : $list['stock']." in stock ";

		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col'>
                                <img src='".$product_image."' width=50 />
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$product_name."</span>
	                        </td>
                            <td class='nk-tb-col tb-col-md'>
                                ".$this->publishContent($category_name)."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ".$list['actual_price']."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ".$list['vendor_selling_price']."
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                               ".$list['vendor_stock']." in stock
	                        </td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}

		function vendorAssignedLocations($vendor_id) 
		{
			$layout = "";
			$vendor_info = $this->getDetails(VENDOR_TBL,"delivery_location_groups","id='".$vendor_id."'");
	  		$q 			 = "SELECT * FROM ".GROUP_TBL." WHERE id IN (".(($vendor_info['delivery_location_groups']!="")? $vendor_info['delivery_location_groups'] : 0 ).") AND status='1' AND delete_status='0' " ;
 		    $exe = $this->exeQuery($q);
 		     if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($list = mysqli_fetch_array($exe)){
 		    		$c_info  = $this->getDetails(LOCATIONGROUP_TBL,"group_name","id='".$list['city_id']."'");
		    		$layout .= "
	    				<tr class='nk-tb-item'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <span class='text-primary'>".$list['group_name']."</span>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <span >".$c_info['group_name']."</span>
	                        </td>
                        </tr>";
		    		$i++;
		    	}
 		    }
 		    return $layout;
		}
		// Get Vendor Location List

		function getVendorLocation($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT * FROM ".LOCATION_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['location']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get State list

		function getStatelist($current="")
	  	{
	  		$layout = "";
	  		$q 		= "SELECT * FROM ".STATE_TBL." WHERE status='1' AND delete_status='0' " ;
	  		$query = $this->exeQuery($q);
		    if(@mysqli_num_rows($query)>0){
		    	$i=0;
		    	while($list = mysqli_fetch_array($query)){
					$selected = (($list['id']==$current) ? 'selected' : '');
					$layout.= "<option value='".$list['id']."' $selected>".$list['name']."</option>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get vendor location group

		function locationsDatas($vendor_id="")
	  	{
	  		$layout = "";
		    $q = "SELECT id,token,group_name,status,delete_status FROM ".LOCATIONGROUP_TBL." WHERE delete_status='0' AND status='1' AND status='1'  GROUP BY id ASC  " ;
		    $query  	      = $this->exeQuery($q);
		    if($vendor_id=="") {
		    	$vendor_locations = array();
		    } else {
		    	$vendor_info      = $this->getDetails(VENDOR_TBL,"delivery_cities", " id='".$vendor_id."' ");
		    	$vendor_locations = explode(",", $vendor_info['delivery_cities']);
		    }
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list    = $this->editPagePublish($row);
		    		$checked = (in_array($list['id'], $vendor_locations)) ? 'checked' :'';
		    		$layout .= "
		    			<div class='location_parent'>
	                		<label class='checkbox-inline checkbox-success heading'>
	                   		<input name='delivery_cities[]' value='".$list['id']."' data-option='".$list['id']."' class='post_permission main_permission' id='main_".$list['id']."' type='checkbox' $checked > ".$list['group_name']." </label>
	                    	<span class='clearfix'></span>
	                    	<div class='menu location_menu' >
	                    		<div class='row'>
	                    			".$this->getlocationGroups($list['id'],$vendor_id)."
	                    		</div>
	                   		</div>
	                    </div>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

	  	// Get location under location group

	  	function getlocationGroups($city_id,$vendor_id)
	  	{	
	  		$layout = "";
		    $q = "SELECT * FROM ".GROUP_TBL." WHERE city_id='".$city_id."' AND status='1' AND delete_status='0' " ;
		    $query 	= $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list 	       = $this->editPagePublish($row);

		    		if($vendor_id=="") {
				    	$vendor_areas = array();
				    } else {
				    	$vendor_info   = $this->getDetails(VENDOR_TBL,"delivery_location_groups", " id='".$vendor_id."' ");
		    			$vendor_areas  = explode(",", $vendor_info['delivery_location_groups']);
				    }
		    		
		    		$checked = (in_array($list['id'], $vendor_areas)) ? 'checked' :'';
		    		$layout .= "
		    			<div class='col-4'>
		    			 	<label class='checkbox-inline checkbox-success sub_menu' >
		    			 	<input name='delivery_location_groups[]' value='".$list['id']."' data-option='".$city_id."' class='post_permission  sub_menu_permission sub_permission_".$city_id."' type='checkbox' $checked>  ".$list['group_name']."</label>
		    			</div>
		    		";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}

		// Add Vendor 

		function addVendor($input)
		{	

			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success') {
				$data = $input;
				$company = $this->hyphenize($data['company']);
				$check_token = $this->check_query(VENDOR_TBL,"id"," token='".$company."' ");
				if ($check_token==0) {
					$token = $company;
				}else{
					$token = $company."-".$this->generateRandomString("5");
				}
				$valid_from 	= date("d-m-Y",strtotime($data['valid_from']));
				$todate 		= date('d-m-Y', strtotime($data['valid_from']. ' + '.$data['validity_days'].' days'));
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft  = isset($data['save_as_draft']) ? 1 : 0;
				$state          = $this->getDetails(STATE_TBL,"name","id=".$data['state_id']."");

				$delivery_cities          = implode(",", $input['delivery_cities']);
				$delivery_location_groups = implode(",", $input['delivery_location_groups']);
				
					$check_mobile = $this -> check_query(VENDOR_TBL,"id","mobile='".$data['mobile']."' ");
					if($check_mobile==0) {
						$check_email = $this -> check_query(VENDOR_TBL,"id","email='".$data['email']."' ");
						if($check_email==0) {
							$query = "INSERT INTO ".VENDOR_TBL." SET 
									token 					 = '".$token."',
									name					 = '".$this->cleanString($data['name'])."',
									company 				 = '".$this->cleanString($data['company'])."',
									mobile 					 = '".$this->cleanString($data['mobile'])."',
									email 					 = '".$this->cleanString($data['email'])."',
									valid_from 				 = '".$this->cleanString($data['valid_from'])."',
									validity_days 			 = '".$this->cleanString($data['validity_days'])."',
									valid_to 				 = '".$todate."',
									state_id 				 = '".$this->cleanString($data['state_id'])."',
									state_name 				 = '".$state['name']."',
									vendor_location 		 = '".$this->cleanString($data['vendor_location'])."',
									address 				 = '".$this->cleanString($data['address'])."',
									city 					 = '".$this->cleanString($data['city'])."',
									pincode 				 = '".$this->cleanString($data['pincode'])."',
									gst_no 					 = '".$this->cleanString($data['gst_no'])."',
									is_draft 				 = '".$save_as_draft."',
									delivery_cities 		 = '".$delivery_cities."',
									delivery_location_groups = '".$delivery_location_groups."',
									status					 = '1',
									added_by 				 = '$admin_id',	
									created_at 				 = '$curr',
									updated_at 				 = '$curr' ";

									$exe 	= $this->exeQuery($query);
									if($exe){
										return 1;
									}else{
										return "Sorry!! Unexpected Error Occurred. Please try again.";
									}
						} else {
							return "Entered email address is already registered.";
						}
					} else {
						return "Entered mobile number is already registered.";
					}
				}else{
					return $validate_csrf;
				}
		}

		// Edit Vendor 

		function editVendor($input)
		{ 	
			$validate_csrf = $this->validateCSRF($input);
			if($validate_csrf=='success'){
				$data = $input;
				$id =	$this->decryptData($data['session_token']);

				if (isset($data['save_as_draft'])==1) {
					$company = $this->hyphenize($data['company']);
					$check_token = $this->check_query(VENDOR_TBL,"id"," token='".$company."' AND id!='$id' ");
					if ($check_token==0) {
						$token = $company;
					}else{
						$token = $company."-".$this->generateRandomString("5");
					}
					$token_q = "token  = '".$token."', ";
				}else{
					$token_q = '';
				}

				$valid_from 	= date("d-m-Y",strtotime($data['valid_from']));
				$todate 		= date('d-m-Y', strtotime($data['valid_from']. ' + '.$data['validity_days'].' days'));
				$admin_id 		= $_SESSION["ecom_admin_id"];
				$curr 			= date("Y-m-d H:i:s");
				$save_as_draft 	= isset($data['save_as_draft']) ? 1 : 0;
				$state          = $this->getDetails(STATE_TBL,"name","id=".$data['state_id']."");

				@$delivery_cities          = implode(",", $input['delivery_cities']);
				@$delivery_location_groups = implode(",", $input['delivery_location_groups']);
				
				$check_mobile = $this -> check_query(VENDOR_TBL,"id","mobile='".$data['mobile']."' AND id!='$id' ");
				if($check_mobile==0) {
			 		$check_email = $this -> check_query(VENDOR_TBL,"id","email='".$data['email']."' AND id!='$id' ");
					if($check_email==0) {
						$query = "UPDATE ".VENDOR_TBL." SET 
						".$token_q."
						name					 = '".$this->cleanString($data['name'])."',
						company 				 = '".$this->cleanString($data['company'])."',
						mobile 					 = '".$this->cleanString($data['mobile'])."',
						email 					 = '".$this->cleanString($data['email'])."',
						valid_from 				 = '".$this->cleanString($data['valid_from'])."',
						validity_days 			 = '".$this->cleanString($data['validity_days'])."',
						valid_to 				 = '".$todate."',
						state_id 				 = '".$this->cleanString($data['state_id'])."',
						state_name 				 = '".$state['name']."',
						vendor_location 		 = '".$this->cleanString($data['vendor_location'])."',
						address 				 = '".$this->cleanString($data['address'])."',
						city 					 = '".$this->cleanString($data['city'])."',
						pincode 				 = '".$this->cleanString($data['pincode'])."',
						gst_no 					 = '".$this->cleanString($data['gst_no'])."',
						is_draft 				 = '".$save_as_draft."',
						delivery_cities 		 = '".$delivery_cities."',
						delivery_location_groups = '".$delivery_location_groups."',
						status					 = '1',
						added_by 				 = '$admin_id',	
						created_at 				 = '$curr',
						updated_at 				 = '$curr' WHERE id='$id' ";
						$exe 	= $this->exeQuery($query);
						if($exe){
							return 1;
						}else{
							return "Sorry!! Unexpected Error Occurred. Please try again.";
						}
					} else {
						return "Entered email address is already registered.";
					}
				} else {
					return "Entered mobile number is already registered..";
				}
			}else{
				return "Sorry Invalid User Entry. Try Re login to the Panel to continue.";
			}
		}

		// Send Invie
		function sendInvite($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(VENDOR_TBL,"*","id ='$data'");

			if($info['invite_status']==0) {
				$passw 		= $this->mobileToken(4);
				$password 	= $this->encryptPassword($passw);
				$password_q = " password 		= '".$password."',
							    password_normal  = '".$passw."', ";
			} else {
				$passw 		= $info['password_normal'];
				$password_q = "";
			}

			$contact_info       = $this->getDetails(COMPANNY_INFO,"*"," id='1' ");
			$curr   			= date("Y-m-d H:i:s");
			$sender_mail 		= NO_REPLY;
			$sender 			= $contact_info['company_name'];
	        $receiver_mail 		= $info['email'];
	        $subject        	= "Login details for"." - ".ucwords($info['name']);
	        $message 			= $this->vendorLoginInfo($info['name'],$info['email'],$passw);
	        $send_mail 			= $this->send_mail($sender_mail,$receiver_mail,$subject,$message);
			if($send_mail){
	            $query  = "UPDATE ".VENDOR_TBL." SET
					invite_status 	= '1',
					".$password_q."
					password_update = '$curr',
					updated_at 		= '$curr'
					WHERE id 		= '$data' ";
				$exe 	= $this->exeQuery($query);
				return 1;
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}

		}

    	// Send vendor Credentials to notification emails
		function sendCredentials($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(VENDOR_TBL,"*","id ='$data'");

			if($info['invite_status']==0) {
				$passw 		= $this->mobileToken(4);
				$password 	= $this->encryptPassword($passw);
				$password_q = " password 		= '".$password."',
							    password_normal  = '".$passw."', ";
			} else {
				$passw 		= $info['password_normal'];
				$password_q = "";
			}

			$contact_info       = $this->getDetails(COMPANNY_INFO,"*"," id='1' ");
			$curr   			= date("Y-m-d H:i:s");
			$sender_mail 		= NO_REPLY;
			$sender 			= $contact_info['company_name'];
	        $subject        	= ucwords($info['name'])."- Vendor Credentials";
	        $message 			= $this->vendorCredentialsInfo($info['name'],$info['email'],$passw);

	        $query = "SELECT * FROM ".NOTIFICATION_EMAIL_TBL." WHERE delete_status='0' AND status='1' ";
	        $exe   = $this->exeQuery($query);
	        
	        if(mysqli_num_rows($exe) > 0)
	        {
	        	while ($list = mysqli_fetch_assoc($exe)) {
	        		$send_mail = $this->send_mail($sender_mail,$list['email'],$subject,$message);
	        	}
	        }

			if($send_mail){
	            $query  = "UPDATE ".VENDOR_TBL." SET
					invite_status 	= '1',
					".$password_q."
					password_update = '$curr',
					updated_at 		= '$curr'
					WHERE id 		= '$data' ";
				$exe 	= $this->exeQuery($query);
				return 1;
			}else{
				return "Sorry!! Unexpected Error Occurred. Please try again.";
			}

		}

		// Change Vendor  Status

		function changeVendorStatus($data)
		{
			$data = $this->decryptData($data);
			$info = $this -> getDetails(VENDOR_TBL,"status"," id ='$data' ");
			if($info['status'] ==1){
				$query = "UPDATE ".VENDOR_TBL." SET status='0' WHERE id='$data' ";
			}else{
				$query = "UPDATE ".VENDOR_TBL." SET status='1' WHERE id='$data' ";
			}
			$up_exe = $this->exeQuery($query);
			if($up_exe){
				return 1;
			}
		}

		// Update Vendor delete status

		function deleteVendor($data)
		{	
			$data = $this->decryptData($data);
			$info = $this -> getDetails(VENDOR_TBL,"file_name"," id ='$data' AND is_draft='1' ");
			// Remove Image
			if ($info['file_name']!='') {
				unlink("./resource/uploads/".$info['file_name']);
			}
			$delete = $this -> deleteRow(VENDOR_TBL," id ='$data' AND is_draft='1' ");
			return $delete;
		}

		// Trash Vendor

		function trashVendor($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".VENDOR_TBL." SET delete_status='1' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// Publish Vendor

		function publishVendor($data)
		{
			$data = $this->decryptData($data);
			$query = "UPDATE ".VENDOR_TBL." SET is_draft='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}	
		}

		// Restore Bolg

		function restoreVendor($data)
		{	
			$data = $this->decryptData($data);
			$query = "UPDATE ".VENDOR_TBL." SET delete_status='0' WHERE id='$data' ";
			$up_exe = $this->exeQuery($query);
			if($up_exe) {
				return 1;
			}
		}

		// get Vendor orders by selected date

		function vendorShort($data) 
		{
			$layout ="";

			if($data['valid_from']!="" && $data['validity_to']!=""  ) {
				$from = date("Y-m-d ",strtotime($data['valid_from']));
				$to   = date("Y-m-d ",strtotime($data['validity_to']));
			}

			$query  = "SELECT VO.id,VO.vendor_id,VO.order_id,VO.order_date,VE.name,VE.mobile,VE.email,
            (SELECT COUNT(id) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id='".$data['vendor_id']."' AND DATE(order_date) BETWEEN '$from' AND '$to' ) as totalOrders,
            (SELECT SUM(total_amount) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id='".$data['vendor_id']."' AND DATE(order_date) BETWEEN '$from' AND '$to') as totalAmount,
            (SELECT SUM(vendor_payment_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id='".$data['vendor_id']."' AND DATE(order_date) BETWEEN '$from' AND '$to' ) as totalPayment,
            (SELECT SUM(vendor_shipping_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id='".$data['vendor_id']."' AND DATE(order_date) BETWEEN '$from' AND '$to' ) as totalShipping,
            (SELECT SUM(vendor_commission_total) FROM ".VENDOR_ORDER_TBL." WHERE vendor_id='".$data['vendor_id']."' AND DATE(order_date) BETWEEN '$from' AND '$to')  as totalCommission
         FROM ".VENDOR_ORDER_TBL." VO LEFT JOIN ".VENDOR_TBL." VE ON (VE.id=VO.vendor_id) WHERE VO.vendor_id='".$data['vendor_id']."' AND  DATE(VO.order_date) BETWEEN '$from' AND '$to' GROUP BY VO.vendor_id "; 
          $exe    = $this->exeQuery($query);
        if(mysqli_num_rows($exe) > 0) {
            $i=1;
                while ($rows = mysqli_fetch_array($exe)) {
                    $list   = $this->editPagePublish($rows);

                    $payable = $list['totalCommission'] + $list['totalPayment'] + $list['totalShipping'];

                    $layout .="
                                <tr class='nk-tb-item'>
                                        <td class='nk-tb-col'>
                                            <span class='tb-lead'><a href=''>".$i."</a></span>
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
                                         <td class='nk-tb-col tb-col-md'>
                                            <span>".$list['totalOrders']."</span>
                                        </td>
                                       
                                        <td class='nk-tb-col tb-col-mb' data-order='35040.34'>
                                            <span class='tb-amount'>₹ ".$list['totalAmount']."<span class='currency'> INR</span></span>
                                        </td>
                                        <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$payable." </span>
                                           
                                        </td>
                                         <td class='nk-tb-col '>
                                            <span class='tb-amount'>₹ ".$list['totalCommission'] ." </span>
                                           
                                        </td>
                                       
                                        <td class='nk-tb-col nk-tb-col-tools'>
                                            <ul class='nk-tb-actions gx-1'>
                                                <li class='nk-tb-action-hidden'><a href='".COREPATH."'orders/vendorpayout' class='btn btn-icon btn-trigger btn-tooltip' title='Pay'><em class='icon ni ni-wallet-out'></em></a>
                                                </li>
                                                <li>
                                                    <div class='dropdown mr-n1'>
                                                        <a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a>
                                                        <div class='dropdown-menu dropdown-menu-right'>
                                                            <ul class='link-list-opt no-bdr'>
                                                                <li><a href='".COREPATH."'orders/vendorpayout'><em class='icon ni ni-wallet-out'></em><span>Pay</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                              ";
                $i++;
                }
        }
        return $layout;

		}

		/*--------------------------------------------- 
					  Vendor Ratings
		----------------------------------------------*/

		// Manage Vendor Ratings

		function vendorRatingList() 
		{
			$layout = "";
	    	$q = "SELECT VR.id,VR.vendor_id,V.company,V.token,
	    			(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 1 AND vendor_id=VR.vendor_id) as onestarcount,
					(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 2 AND vendor_id=VR.vendor_id) as twostarcount,
					(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 3 AND vendor_id=VR.vendor_id) as threestarcount,
					(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 4 AND vendor_id=VR.vendor_id) as fourstarcount,
					(SELECT COUNT(star_ratings) as rating FROM ".VENDOR_RATTING_TBL." WHERE star_ratings = 5 AND vendor_id=VR.vendor_id) as fivestarcount
				 FROM ".VENDOR_RATTING_TBL." VR LEFT JOIN ".VENDOR_TBL." V ON (VR.vendor_id = V.id) WHERE 1 GROUP BY V.id ORDER BY V.id ASC" ;
 		    $exe = $this->exeQuery($q);
 		    if(mysqli_num_rows($exe)>0){
 		    	$i=1;
 		    	while($details = mysqli_fetch_array($exe)) {
 		    		 
 		    		$list    	     = $this->editPagePublish($details);
 		    		$totl_stat_count = (5*$list['fivestarcount'] + 4*$list['fourstarcount'] + 3*$list['threestarcount'] + 2*$list['twostarcount'] + 1*$list['onestarcount']) / ($list['fivestarcount']+$list['fourstarcount']+$list['threestarcount']+$list['twostarcount']+$list['onestarcount']);

		    		$layout .= "
	    				<tr class='nk-tb-item dot_display_none'>
	    					<td class='nk-tb-col'>
                                ".$i."
                            </td>
                            <td class='nk-tb-col tb-col-md'>
                                <a href='".COREPATH."vendor/details/".$list['token']."' ><span class='text-primary'>".$this->publishContent($list['company'])."</span></a>
	                        </td>
	                        <td class='nk-tb-col tb-col-md'>
                                <div class='star_rating'>
						           <span class='my-rating-7 ms-2 star_tbl_list'></span>
						           <input type='hidden' class='rating_data' name='star_ratings' value='".$totl_stat_count."' id='rating_data'>
						        </div>
	                        </td>
	                        <td class='nk-tb-col'>
                               ".number_format($totl_stat_count,1)."
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





<?php
	require_once 'Model.php';
	require_once 'FileUploader.php';
	require_once 'config/config.php';
	require_once 'app/core/classes/PHPMailerAutoload.php';

	class AdminProfile extends Model
	{

		/*--------------------------------------------- 
					  Admin Profile
		----------------------------------------------*/

		// Login Activity

		function manageloginActivity($limit)
	  	{
	  		$layout = "";

		    $q ="SELECT * FROM ".ADMIN_SESSION_TBL." WHERE 1 AND logged_id='".$_SESSION["ecom_admin_id"]."'  ORDER BY id DESC $limit " ;
		    $query = $this->exeQuery($q);
		    if(mysqli_num_rows($query)>0){
		    	$i=1;
		    	while($row = mysqli_fetch_array($query)){
		    		$list = $this->editPagePublish($row);
		    		$layout .= "
		    			<tr>
                            <td class='tb-col-os'>".$list['auth_user_agent']."</td>
                            <td class='tb-col-ip'><span class='sub-text'>".$list['auth_ip_address']."</span></td>
                            <td class='tb-col-time'><span class='sub-text'>".date("M d, Y h:i A  ",strtotime($list['session_in']))."</span></td>
                        </tr>";
	                $i++;
		    	}
		    }
		    return $layout;
	  	}
	/*-----------Dont'delete---------*/

	}


?>





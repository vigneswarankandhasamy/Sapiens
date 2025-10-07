<?php
require_once 'app/excel/PHPExcel.php';

class exportData extends Controller
{
	
	public function customerorderreport($from="",$to="")
	{	
		if($from=="overall") {
			$from = "";
			$to = "";
		}

		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('ExportReports');
			if($from!="" && $to!=""  ) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				$title 		= "Customer Order Report (".$from." to ".$to.") ";
			} else if($from=='') {
				$title 		= "Customer Order Report (".date("d-m-y").") ";
			} 

			// PHPExcel Instance
			$sheet = new PHPExcel();


			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator("Customer Order Report")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle("Customer Order Report")
			               ->setSubject("Customer Order Report")
			               ->setDescription("Customer Order Report")
			               ->setKeywords("Customer Order Report")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();
			// Create Header
			$sheet->setActiveSheetIndex(0)
						->setCellValue('A1', 'S.No')
			           	->setCellValue('B1', 'Invoice No')
			           	->setCellValue('C1', 'Order Date')
			           	->setCellValue('D1', 'Customer name')
			           	->setCellValue('E1', 'Mobile')
			            ->setCellValue('F1', 'Email')
			            ->setCellValue('G1', 'Totla Items')
			            ->setCellValue('H1', 'Commission')
			            ->setCellValue('I1', 'Total')
			           	->setCellValue('J1', 'Status');
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			$activeSheet->getColumnDimension('G')->setAutoSize(true);
			$activeSheet->getColumnDimension('H')->setAutoSize(true);
			$activeSheet->getColumnDimension('I')->setAutoSize(true);
			$activeSheet->getColumnDimension('J')->setAutoSize(true);
			
			//------------- Create Report Body ------------//

			$users = $user->exportCustomerOrderReport($from,$to);

			$row = 2;
			foreach ($users as $key => $value) {
				$col = 0;
			    foreach ($value as $key=> $value) {
			        //echo $row." $col -- ".$key."=".$value."<br/>";
			        $sheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
			        $col++;
			   }
			   $row++;
			}

			//------------- Create Report Body Ends------------//
			/*

			// Rename worksheet
			$sheet_name = "Registered Users";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".xlsx";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename.'');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
			$objWriter->save('php://output');*/
			

			// Rename worksheet
			$sheet_name = "Admission Form";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".csv";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename);
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			//ob_end_clean();
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'CSV');
			$objWriter->save('php://output');

			
		}else{
			$this->view('home/login',
				[
					'meta_title'=> 'Admin Login - '.COMPANY_NAME
				]);
	    }
	}

	public function vendororderreport($from="",$to="")
	{	
		if($from=="overall") {
			$from = "";
			$to = "";
		}

		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('ExportReports');
			if($from!="" && $to!=""  ) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				$title 		= "Vendor Order Report (".$from." to ".$to.") ";
			} else if($from=='') {
				$title 		= "Vendor Order Report (".date("d-m-y").") ";
			} 

			// PHPExcel Instance
			$sheet = new PHPExcel();


			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator("Vendor Order Report")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle("Vendor Order Report")
			               ->setSubject("Vendor Order Report")
			               ->setDescription("Vendor Order Report")
			               ->setKeywords("Vendor Order Report")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();

			// Create Header
				$sheet->setActiveSheetIndex(0)
						->setCellValue('A1', 'S.No')
			           	->setCellValue('B1', 'Invoice No')
			           	->setCellValue('C1', 'Order Date')
			           	->setCellValue('D1', 'Vendor Name')
			           	->setCellValue('E1', 'Mobile')
			            ->setCellValue('F1', 'Email')
			            ->setCellValue('G1', 'Totla Items')
			            ->setCellValue('H1', 'Commission')
			            ->setCellValue('I1', 'Order Value')
			           	->setCellValue('J1', 'Order Status');			
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			$activeSheet->getColumnDimension('G')->setAutoSize(true);
			$activeSheet->getColumnDimension('H')->setAutoSize(true);
			$activeSheet->getColumnDimension('I')->setAutoSize(true);
			$activeSheet->getColumnDimension('J')->setAutoSize(true);
			
			//------------- Create Report Body ------------//

			$users = $user->exportVendorOrderReport($from,$to);

			$row = 2;
			foreach ($users as $key => $value) {
				$col = 0;
			    foreach ($value as $key=> $value) {
			        //echo $row." $col -- ".$key."=".$value."<br/>";
			        $sheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
			        $col++;
			   }
			   $row++;
			}

			//------------- Create Report Body Ends------------//
			/*

			// Rename worksheet
			$sheet_name = "Registered Users";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".xlsx";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename.'');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
			$objWriter->save('php://output');*/
			

			// Rename worksheet
			$sheet_name = "Admission Form";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".csv";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename);
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			//ob_end_clean();
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'CSV');
			$objWriter->save('php://output');

			
		}else{
			$this->view('home/login',
				[
					'meta_title'=> 'Admin Login - '.COMPANY_NAME
				]);
	    }
	}

	public function orderlistreport($from="",$to="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('ExportReports');

			
			$vendor_detail = $user->getDetails(VENDOR_TBL,"company","id='".$_SESSION["ecom_vendor_id"]."'");
			$vendor_name   = $vendor_detail['company'];
			

			if($from!="" && $from!=0 ) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				$title 		= $vendor_name." Order List (".$from." to ".$to.") ";
			} else if($from=='' || $from==0) {
				$title 		= $vendor_name." Order List (".date("d-m-y").") ";
			} 

			// PHPExcel Instance
			$sheet = new PHPExcel();


			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator($vendor_name." Order List")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle($vendor_name." Order List")
			               ->setSubject($vendor_name." Order List")
			               ->setDescription($vendor_name." Order List")
			               ->setKeywords($vendor_name." Order List")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();
			// Create Header
			$sheet->setActiveSheetIndex(0)
						->setCellValue('A1', 'S.No')
			           	->setCellValue('B1', 'Order Id')
			           	->setCellValue('C1', 'Customer name')
			           	->setCellValue('D1', 'Mobile')
			           	->setCellValue('E1', 'Email')
			            ->setCellValue('F1', 'Order Date')
			            ->setCellValue('G1', 'Vendor Name')
			            ->setCellValue('H1', 'Order Value')
			            ->setCellValue('I1', 'Commission')
			           	->setCellValue('J1', 'Payout Status')
			           	->setCellValue('K1', 'Order Status');
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			$activeSheet->getColumnDimension('G')->setAutoSize(true);
			$activeSheet->getColumnDimension('H')->setAutoSize(true);
			$activeSheet->getColumnDimension('I')->setAutoSize(true);
			$activeSheet->getColumnDimension('J')->setAutoSize(true);
			$activeSheet->getColumnDimension('K')->setAutoSize(true);
			
			//------------- Create Report Body ------------//

			$users = $user->exportCustomerOrderList($from,$to);

			$row = 2;
			foreach ($users as $key => $value) {
				$col = 0;
			    foreach ($value as $key=> $value) {
			        //echo $row." $col -- ".$key."=".$value."<br/>";
			        $sheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
			        $col++;
			   }
			   $row++;
			}

			//------------- Create Report Body Ends------------//
			/*

			// Rename worksheet
			$sheet_name = "Registered Users";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".xlsx";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename.'');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
			$objWriter->save('php://output');*/
			

			// Rename worksheet
			$sheet_name = "Admission Form";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".csv";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename);
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			//ob_end_clean();
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'CSV');
			$objWriter->save('php://output');

			
		}else{
			$this->view('home/login',
				[
					'meta_title'=> 'Admin Login - '.COMPANY_NAME
				]);
	    }
	}

	public function rejectedorderlistreport($from="",$to="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('ExportReports');
			
			$vendor_detail = $user->getDetails(VENDOR_TBL,"company","id='".$_SESSION["ecom_vendor_id"]."'");
			$vendor_name   = $vendor_detail['company'];

			if($from!="" && $from!=0 ) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				$title 		= $vendor_name." Rejected Order List (".$from." to ".$to.") ";
			} else if($from=='' || $from==0) {
				$title 		= $vendor_name." Rejected Order List (".date("d-m-y").") ";
			} 

			// PHPExcel Instance
			$sheet = new PHPExcel();


			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator($vendor_name." Order List")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle($vendor_name." Order List")
			               ->setSubject($vendor_name." Order List")
			               ->setDescription($vendor_name." Order List")
			               ->setKeywords($vendor_name." Order List")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();
			// Create Header
			$sheet->setActiveSheetIndex(0)
						->setCellValue('A1', 'S.No')
			           	->setCellValue('B1', 'Order Id')
			           	->setCellValue('C1', 'Customer name')
			           	->setCellValue('D1', 'Mobile')
			           	->setCellValue('E1', 'Email')
			            ->setCellValue('F1', 'Order Date')
			            ->setCellValue('G1', 'Vendor Name')
			            ->setCellValue('H1', 'Order Value')
			            ->setCellValue('I1', 'Commission')
			           	->setCellValue('J1', 'Response')
			           	->setCellValue('K1', 'Reponse Message');
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			$activeSheet->getColumnDimension('G')->setAutoSize(true);
			$activeSheet->getColumnDimension('H')->setAutoSize(true);
			$activeSheet->getColumnDimension('I')->setAutoSize(true);
			$activeSheet->getColumnDimension('J')->setAutoSize(true);
			$activeSheet->getColumnDimension('K')->setAutoSize(true);
			
			//------------- Create Report Body ------------//

			$users = $user->exportCustomerRejectedOrderList($from,$to);

			$row = 2;
			foreach ($users as $key => $value) {
				$col = 0;
			    foreach ($value as $key=> $value) {
			        //echo $row." $col -- ".$key."=".$value."<br/>";
			        $sheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
			        $col++;
			   }
			   $row++;
			}

			//------------- Create Report Body Ends------------//
			/*

			// Rename worksheet
			$sheet_name = "Registered Users";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".xlsx";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename.'');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
			$objWriter->save('php://output');*/
			

			// Rename worksheet
			$sheet_name = "Admission Form";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".csv";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename);
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			//ob_end_clean();
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'CSV');
			$objWriter->save('php://output');

			
		}else{
			$this->view('home/login',
				[
					'meta_title'=> 'Admin Login - '.COMPANY_NAME
				]);
	    }
	}

	public function cancelledorderlistreport($from="",$to="")
	{	
		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('ExportReports');

			if($vendor!="")
			{
				$vendor_detail = $user->getDetails(VENDOR_TBL,"company","id='".$_SESSION["ecom_vendor_id"]."'");
				$vendor_name   = $vendor_detail['company'];
			} else {
				$vendor_name   ="";
			}

			if($from!="" && $from!=0 ) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				$title 		= $vendor_name." Cancel Order List (".$from." to ".$to.") ";
			} else if($from=='' || $from==0 ) {
				$title 		= $vendor_name." Cancel Order List (".date("d-m-y").") ";
			} 

			// PHPExcel Instance
			$sheet = new PHPExcel();


			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator($vendor_name." Cancel Order List")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle($vendor_name." Cancel Order List")
			               ->setSubject($vendor_name." Cancel Order List")
			               ->setDescription($vendor_name." Cancel Order List")
			               ->setKeywords($vendor_name." Cancel Order List")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();
			// Create Header
			$sheet->setActiveSheetIndex(0)
						->setCellValue('A1', 'S.No')
			           	->setCellValue('B1', 'Customer Name')
			           	->setCellValue('C1', 'Order Date')
			           	->setCellValue('D1', 'Deliverd Date')
			           	->setCellValue('E1', 'Vendor Name')
			            ->setCellValue('F1', 'Order Value')
			            ->setCellValue('G1', 'Cancel Reason')
			            ->setCellValue('H1', 'Cancellation date');
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			$activeSheet->getColumnDimension('G')->setAutoSize(true);
			$activeSheet->getColumnDimension('H')->setAutoSize(true);
			
			//------------- Create Report Body ------------//

			$users = $user->exportCustomerReturnedOrderList($from,$to);

			$row = 2;
			foreach ($users as $key => $value) {
				$col = 0;
			    foreach ($value as $key=> $value) {
			        //echo $row." $col -- ".$key."=".$value."<br/>";
			        $sheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
			        $col++;
			   }
			   $row++;
			}

			//------------- Create Report Body Ends------------//
			/*

			// Rename worksheet
			$sheet_name = "Registered Users";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".xlsx";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename.'');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
			$objWriter->save('php://output');*/
			

			// Rename worksheet
			$sheet_name = "Admission Form";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".csv";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename);
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			//ob_end_clean();
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'CSV');
			$objWriter->save('php://output');

			
		}else{
			$this->view('home/login',
				[
					'meta_title'=> 'Admin Login - '.COMPANY_NAME
				]);
	    }
	}


	public function payoutlistreport($from="",$to="")
	{	
		if($from=="overall") {
			$from = "";
			$to = "";
		}

		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('ExportReports');
			if($from!="" && $to!=""  ) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				$title 		= "Payout Reports (".$from." to ".$to.") ";
			} else if($from=='') {
				$title 		= "Payout Reports (".date("d-m-y").") ";
			} 

			// PHPExcel Instance
			$sheet = new PHPExcel();


			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator("Payout Reports")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle("Payout Reports")
			               ->setSubject("Payout Reports")
			               ->setDescription("Payout Reports")
			               ->setKeywords("Payout Reports")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();

			// Create Header
			$sheet->setActiveSheetIndex(0)
					->setCellValue('A1', 'S.No')
		           	->setCellValue('B1', 'Payout Invoice No')
		           	->setCellValue('C1', 'Payout Date')
		           	->setCellValue('D1', 'Total Orders')
		           	->setCellValue('E1', 'Order value')
		            ->setCellValue('F1', 'Commission')
		            ->setCellValue('G1', 'Net Payable')
		            ->setCellValue('H1', 'Status');
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			$activeSheet->getColumnDimension('G')->setAutoSize(true);
			$activeSheet->getColumnDimension('H')->setAutoSize(true);
			
			//------------- Create Report Body ------------//

			$users = $user->exportPayoutListReport($from,$to);

			$row = 2;
			foreach ($users as $key => $value) {
				$col = 0;
			    foreach ($value as $key=> $value) {
			        //echo $row." $col -- ".$key."=".$value."<br/>";
			        $sheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
			        $col++;
			   }
			   $row++;
			}

			//------------- Create Report Body Ends------------//
			/*

			// Rename worksheet
			$sheet_name = "Registered Users";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".xlsx";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename.'');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
			$objWriter->save('php://output');*/
			

			// Rename worksheet
			$sheet_name = "Admission Form";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".csv";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename);
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			//ob_end_clean();
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'CSV');
			$objWriter->save('php://output');

			
		}else{
			$this->view('home/login',
				[
					'meta_title'=> 'Admin Login - '.COMPANY_NAME
				]);
	    }
	}

	public function unpayoutlistreport($from="",$to="")
	{	
		if($from=="overall") {
			$from = "";
			$to = "";
		}

		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('ExportReports');
			if($from!="" && $to!=""  ) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				$title 		= "Unpaid Payout Reports (".$from." to ".$to.") ";
			} else if($from=='') {
				$title 		= "Unpaid Payout Reports (".date("d-m-y").") ";
			} 

			// PHPExcel Instance
			$sheet = new PHPExcel();


			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator("Unpaid Payout Reports")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle("Unpaid Payout Reports")
			               ->setSubject("Unpaid Payout Reports")
			               ->setDescription("Unpaid Payout Reports")
			               ->setKeywords("Unpaid Payout Reports")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();

			// Create Header
			$sheet->setActiveSheetIndex(0)
					->setCellValue('A1', 'S.No')
		           	->setCellValue('B1', 'Order Invoice No')
		           	->setCellValue('C1', 'Order Date')
		           	->setCellValue('D1', 'Customer')
		           	->setCellValue('E1', 'Order value')
		            ->setCellValue('F1', 'Commission')
		            ->setCellValue('G1', 'Net Payable')
		            ->setCellValue('H1', 'Status');
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			$activeSheet->getColumnDimension('G')->setAutoSize(true);
			$activeSheet->getColumnDimension('H')->setAutoSize(true);
			
			//------------- Create Report Body ------------//

			$users = $user->exportUnpayoutListReport($from,$to);

			$row = 2;
			foreach ($users as $key => $value) {
				$col = 0;
			    foreach ($value as $key=> $value) {
			        //echo $row." $col -- ".$key."=".$value."<br/>";
			        $sheet->getActiveSheet()->setCellValueByColumnAndRow($key, $row, $value);
			        $col++;
			   }
			   $row++;
			}

			//------------- Create Report Body Ends------------//
			/*

			// Rename worksheet
			$sheet_name = "Registered Users";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".xlsx";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='.$filename.'');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
			$objWriter->save('php://output');*/
			

			// Rename worksheet
			$sheet_name = "Admission Form";
			$activeSheet ->setTitle($sheet_name);
			$filename = $title.".csv";
			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename='.$filename);
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			//ob_end_clean();
			$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'CSV');
			$objWriter->save('php://output');

			
		}else{
			$this->view('home/login',
				[
					'meta_title'=> 'Admin Login - '.COMPANY_NAME
				]);
	    }
	}

	public function error()
	{
		if(isset($_SESSION["ecom_vendor_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
					'sitesettings'	=>	$user->filtersiteSettings()
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>
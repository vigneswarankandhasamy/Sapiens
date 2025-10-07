<?php
require_once 'app/excel/PHPExcel.php';


class exportData extends Controller
{
	
	public function enquiry($from="",$to="",$type="")
	{	
		if(isset($_SESSION["ecom_contractor_id"])){
			$user = $this->model('AdminProfile');
			if($from!="" && $to!="" && $from!=0 && $to!=0) {
				$from = date("d-M-y ",strtotime($from));
				$to   = date("d-M-y ",strtotime($to));
				
				$title 		= "Enquiries (".$from." to ".$to.") ";
		    } elseif($from=='today') {
				$title 		= "Todays Enquiries List (".date("d-m-y").") ";
		    }  else {
				$title 		= "Overall Enquiries (".date("d-m-y").") ";
		    }

			// PHPExcel Instance
			$sheet = new PHPExcel();

			//------------- Create Header ------------//
			
			// Set document properties
			$sheet->getProperties()->setCreator("Enquiries")
			               ->setLastModifiedBy(COMPANY_NAME)
			               ->setTitle("Enquiries")
			               ->setSubject("Enquiries")
			               ->setDescription("Enquiries")
			               ->setKeywords("Enquiries")
			               ->setCategory("Excel Report");
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$sheet->setActiveSheetIndex(0);
			$activeSheet = $sheet->getActiveSheet();
			// Create Header
			$sheet->setActiveSheetIndex(0)
						->setCellValue('A1', 'S.No')
			           	->setCellValue('B1', 'Name')
			            ->setCellValue('C1', 'Email')
			            ->setCellValue('D1', 'Mobile')
			            ->setCellValue('E1', 'Message')
			            ->setCellValue('F1', 'Date');
			            
			$activeSheet->getColumnDimension('A')->setAutoSize(true);
			$activeSheet->getColumnDimension('B')->setAutoSize(true);
			$activeSheet->getColumnDimension('C')->setAutoSize(true);
			$activeSheet->getColumnDimension('D')->setAutoSize(true);
			$activeSheet->getColumnDimension('E')->setAutoSize(true);
			$activeSheet->getColumnDimension('F')->setAutoSize(true);
			
			//------------- Create Report Body ------------//


			$users = $user->enquiryList($from,$to,$type);

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
		if(isset($_SESSION["ecom_contractor_id"])){
			$user = $this->model('Admin');
			$this->view('home/error', 
				[	
					'active_menu' 	=>  '',
					'page_title'	=>	'404 - Page Not Found',
					'scripts'		=>	'error',
					'user_info'		=>	$user->userInfo(),
				]);
		}else{
			$this->view('home/login',[]);
		}
	}

}


?>
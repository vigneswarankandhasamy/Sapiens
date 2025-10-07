<?php
require_once 'Model.php';
require_once '../global/library/tcpdf/tcpdf.php'; 
require_once '../global/library/tcpdf/tcpdf_autoconfig.php';
require_once '../global/library/tcpdf/lang/eng.php';

class PdfExport extends Model
{


	// Export on the Browser

	function export($content){
		$obj_pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
	    $obj_pdf->SetCreator(COMPANY_NAME);  
	    $obj_pdf->SetTitle(COMPANY_NAME);  
	    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
	    $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
	    $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
	    $obj_pdf->SetDefaultMonospacedFont('helvetica');  
	    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
	    $obj_pdf->SetMargins(-1, 0, -1);  
	    $obj_pdf->setPrintHeader(false);  
	    $obj_pdf->setPrintFooter(false);  
	    $obj_pdf->SetAutoPageBreak(TRUE, 10);  
	    $obj_pdf->SetFont('helvetica', '', 11);  
	    $obj_pdf->AddPage('P','A4');  
      	$obj_pdf->writeHTML($content['layout']);  
      	ob_end_clean();
      	if (SITE_MODE=="developmentt") {
      		return $obj_pdf->Output($content['file_name'].'pdf', 'I');
      	}else{
      		return $obj_pdf->Output($content['file_name'].'pdf', 'D');
      	}
	}

	// Preview on the Browser

	function preview($content)
	{
		$obj_pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
	    $obj_pdf->SetCreator(COMPANY_NAME);  
	    $obj_pdf->SetTitle(COMPANY_NAME);  
	    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
	    $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
	    $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
	    $obj_pdf->SetDefaultMonospacedFont('helvetica');  
	    //$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
		$obj_pdf->setFooterMargin(20);
		$obj_pdf->SetMargins(-1, 0, -1);
		//$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		//$obj_pdf->SetMargins(true, 0, true, true);
		//$obj_pdf->setCellPaddings(10,10,20,10);
	    $obj_pdf->setPrintHeader(false);  
	    $obj_pdf->setPrintFooter(false);  
	    $obj_pdf->SetAutoPageBreak(TRUE, 10);  
	    $obj_pdf->SetFont('helvetica', '', 10);  
	    $obj_pdf->AddPage('P','A4'); 
      	$obj_pdf->writeHTML($content['layout']);
		// footer modify start
		$obj_pdf->SetY(-30);
		$footertext="
		<table>
			<tbody>
				<tr>
					<td>
						<strong>Declaration:</strong><br/>
						We declare that this invoice shows the actual price of the goods
						described above and that all particulars are true and correct.
					</td>
				</tr>
				<tr>
					<td>* This is a computer generated invoice and does not require a physical signature.
					</td>
				</tr>
			</tbody>
		</table>
					";
		//$obj_pdf->writeHTMLCell(0, 0, '', '', $footertext, 0, 0, false, "L", true);
		//$obj_pdf->writeHTMLCell(0, 0, '', '', $footertext, 1, 0, 0, true, '', true);
		
		//$obj_pdf->writeHTML($footertext, false, true, false, true); 
		$obj_pdf->writeHTMLCell(200, 8, 7, -25, $footertext, 0, 1, 0, true, 'L'); 
		//$obj_pdf->Cell(20, 10, 'Page ', 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// footer modify end 
      	ob_end_clean();
     	return $obj_pdf->Output($content['file_name'].'pdf', 'I');   
	}
//------ End of the file ------//

}?> 
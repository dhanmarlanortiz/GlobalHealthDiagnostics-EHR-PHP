<?php
class GynecologicReportPDF {
    function generateGynecologicReport($conn, $id, $pdf = null) {
        $apeDetails = fetchApeDetailsById($conn, $id);
        $orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);
        $gynecologicReport = getGynecologicReport($id);

        $pathologist = getProfessional($conn, $orgDetails['pathologist_fk']);
        $pathologist_id = $pathologist['prof_id'] ?? 0;
        $pathologist_name = $pathologist['prof_name'] ?? '';
        $pathologist_role = $pathologist['prof_role'] ?? '';
        $pathologist_license = $pathologist['prof_license'] ?? '';
        $pathologist_signature = base_url(false) . '/images/healthcare-professional-' . $pathologist_id  . '-signature.png';

        if(null !== $gynecologicReport) {
            if(null == $pdf) {
                $pdf = new GynecologicReportFPDF();
            }

            $vw = $pdf->GetPageWidth();
            $border = "T L R";
            $lineHeight = 8;
            $toRight = 0;
            $toBegin = 1;
            $toBelow = 2;
            $space = " ";

            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B', 10);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->SetDrawColor(241, 245, 249);
            $pdf->Cell(0,4.5,'GYNECOLOGIC REPORT', 0, 1, 'L');

            $pdf->ln(5);

            // Name
            $pdf->thFontStyle();
            $pdf->Cell(20, $lineHeight,' Name: ', $border, $toRight, 'L', true);
            $pdf->tdFontStyle();
            $pdf->Cell(85, $lineHeight, $space.$apeDetails['lastName'] . ", " . $apeDetails['firstName'].$space, $border);

            // Sex
            $pdf->thFontStyle();
            $pdf->Cell(10, $lineHeight,' Sex: ', $border, $toRight, 'L', true);
            $pdf->tdFontStyle();
            $pdf->Cell(15, $lineHeight, $space.substr($apeDetails['sex'], 0,1).$space , $border);

            // Age
            $pdf->thFontStyle();
            $pdf->Cell(10, $lineHeight,' Age: ', $border, $toRight, 'L', true);
            $pdf->tdFontStyle();
            $pdf->Cell(15, $lineHeight, $space.$apeDetails['age'].$space , $border);

            // Date
            $pdf->thFontStyle();
            $pdf->Cell(10, $lineHeight,' Date: ', $border, $toRight, 'L', true);
            $pdf->tdFontStyle();
            $pdf->Cell(25, $lineHeight, $space.date('d-M-y', strtotime($gynecologicReport['gynerep_date'])).$space, $border, $toBegin);

            // Company
            $pdf->thFontStyle();
            $pdf->Cell(20, $lineHeight,' Company: ', $border, $toRight, 'L', true);
            $pdf->tdFontStyle();
            $pdf->MultiCell(170, $lineHeight, $space.$orgDetails['name'].$space, $border);

            $pdf->Cell(0, 1,'', 'T', $toBegin);

            $pdf->ln(5);
            
            $pdf->row('Based on the Bethesda System for Reporting Cervical Cytology.', '', '');

            $pdf->ln(12);
            
            // Specimen Type
            $pdf->SetFont('Arial','', 8);
            $pdf->SetTextColor(83,99,113);
            $pdf->Cell(35, 4, ' Specimen Type: ' , 0, 0, 'L');

            $pdf->SetFont('Arial','B', 8);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->MultiCell(0, 4, ($gynecologicReport['gynerep_specimen_type'] ?? null), 0, 'L');
            $pdf->ln(3);
            
            // Specimen Adequacy
            $pdf->SetFont('Arial','', 8);
            $pdf->SetTextColor(83,99,113);
            $pdf->Cell(35, 4, ' Specimen Adequacy: ' , 0, 0, 'L');

            $pdf->SetFont('Arial','B', 8);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->MultiCell(0, 4, ($gynecologicReport['gynerep_specimen_adequacy'] ?? null), 0, 'L');
            $pdf->ln(3);
            
            // Interpretation/Result
            $pdf->SetFont('Arial','', 8);
            $pdf->SetTextColor(83,99,113);
            $pdf->Cell(35, 4, ' Interpretation/Result: ' , 0, 0, 'L');

            $pdf->SetFont('Arial','B', 8);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->MultiCell(0, 4, ($gynecologicReport['gynerep_interpretation_result'] ?? null), 0, 'L');
            $pdf->ln(3);
            
            // Recommendation
            $pdf->SetFont('Arial','', 8);
            $pdf->SetTextColor(83,99,113);
            $pdf->Cell(35, 4, ' Recommendation: ' , 0, 0, 'L');

            $pdf->SetFont('Arial','B', 8);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->MultiCell(0, 4, ($gynecologicReport['gynerep_recommendation'] ?? null), 0, 'L');
            $pdf->ln();
 
            $pdf->Cell(0, 1,'', '', $toBegin);
            
            $pdf->ln(25);

            if (isValidImageUrl($pathologist_signature)) {
                $pdf->Image($pathologist_signature, 25, $pdf->GetY() - 15);
            }
            
            $pdf->SetFont('Arial','B', 8);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->SetDrawColor(83,99,113);
    
            $pdf->Cell(10, 8, '' , '', 0, 'L');
            $pdf->Cell(50, 8, $pathologist_name , 'B', 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial','', 8);
            $pdf->Cell(10, 8, '' , '', 0, 'L');
            $pdf->Cell(50, 8, $pathologist_role , '', 0, 'C');
            $pdf->ln(5);
            $pdf->Cell(10, 8, '' , '', 0, 'C');
    
            if($pathologist_license != '') {
                $pdf->Cell(50, 8, 'License No.: ' . $pathologist_license , '', 0, 'C');
                $pdf->ln(5);
                $pdf->Cell(10, 8, '' , '', 0, 'C');
            }

            $pdf->ln(25);
            
            $pdf->row('Computer-generated report.', '', '');

            return $pdf;
        }
    }
}

class GynecologicReportFPDF extends FPDF {
    
    function Header() {
        $id = $_GET["id"];
        $location = getLocationDetailsByApe($id);
        $address1 = $location['loc_address1'];
        $address2 = $location['loc_address2'];
        $telephone = $location['loc_telephone'];

        $this->Image(base_url(false) . '/images/ghd-logo-with-text.png', 10, 10, 60);
        $this->SetFont('Arial','', 8);
        $this->SetTextColor(83,99,113);
        $this->ln(1);
        $this->Cell(0,4.5, $address1, 0, 1, 'R');
        
        if($address2) {
            $this->Cell(0,4.5, $address2, 0, 1, 'R');
        }
        
        $this->Cell(0,4.5,'Tel/Fax No. ' . $telephone, 0, 1, 'R');
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function thFontStyle() {
        $this->SetFont('Arial','B', 8);
        $this->SetTextColor(83,99,113);
        $this->SetFillColor(249,250,251);
    }

    function tdFontStyle() {
        $this->SetFont('Arial','', 8);   
        $this->SetTextColor(17, 24, 39);
    }

    function row($title, $value, $default = null) {
        $border = 0;
        $lineHeight = 5;
        $toRight = 0;
        $space = " ";

        $this->SetFont('Arial','', 8);   
        $this->SetTextColor(83,99,113);
        $this->Cell(30, $lineHeight, $space.$title.$space , $border, $toRight, 'L', false);
        
        $this->SetFont('Arial','B', 8);   
        $this->SetTextColor(17, 24, 39);
        $this->Cell(25, $lineHeight, $space.$value.$space, $border, $toRight);

        $this->SetFont('Arial','', 8); 
        $this->SetTextColor(83,99,113); 
        $this->Cell(35, $lineHeight, $space.$default.$space, $border, $toRight);
        $this->Cell(10, $lineHeight, $space, $border, $toRight);
    }

    function rowCol4($title, $value) {
        $border = 0;
        $lineHeight = 5;
        $toRight = 0;
        $space = " ";

        $this->SetFont('Arial','', 8);   
        $this->SetTextColor(83,99,113);
        $this->Cell(55, $lineHeight, $space.$title.$space , $border, $toRight, 'L', false);
        
        $this->SetFont('Arial','B', 8);   
        $this->SetTextColor(17, 24, 39);
        $this->Cell(35, $lineHeight, $space.$value.$space, $border, $toRight);
        $this->Cell(10, $lineHeight, $space, $border, $toRight);
    }

}
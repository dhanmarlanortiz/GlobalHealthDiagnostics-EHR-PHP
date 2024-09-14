<?php
class EcgPDF {
    function generateEcgDiagnosis($conn, $id, $pdf = null) {
        $apeDetails = fetchApeDetailsById($conn, $id);
        $ecgDetails = getEcgDiagnosis($id);

        $orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);

        $physician = getProfessional($conn, $orgDetails['physician_fk']);
        $physician_id = $physician['prof_id'] ?? 0;
        $physician_name = $physician['prof_name'] ?? '';
        $physician_role = $physician['prof_role'] ?? '';
        $physician_license = $physician['prof_license'] ?? '';
        $physician_signature = base_url(false) . '/images/healthcare-professional-' . $physician_id  . '-signature.png';

        // Instanciation of inherited class
        if(null == $pdf) {
            $pdf = new EcgFPDF();
        }

        $vw = $pdf->GetPageWidth();
        $border = "T L R";
        $lineHeight = 8;
        $toRight = 0;
        $toBegin = 1;
        $toBelow = 2;
        
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B', 10);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetDrawColor(241, 245, 249);
        $pdf->Cell(0,4.5,'ECG Diagnosis', 0, 1, 'L');
        
        $pdf->ln(5);
        
        // Name
        $pdf->thFontStyle();
        $pdf->Cell(25, $lineHeight,' Patien\'s Name: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(70, $lineHeight, $apeDetails['firstName'] . " " . $apeDetails['middleName'] . " " . $apeDetails['lastName'], $border);
        
        // Date
        $pdf->thFontStyle();
        $pdf->Cell(17.5, $lineHeight,' Date: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(30, $lineHeight, $ecgDetails['ecgdiag_date'] , $border, $toRight, 'L');
        
        // Case No
        $pdf->thFontStyle();
        $pdf->Cell(17.5, $lineHeight,' Case No.: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(30, $lineHeight, $ecgDetails['ecgdiag_casenumber'] , $border, $toBegin, 'L');

        // Civil Status
        $pdf->thFontStyle();
        $pdf->Cell(25, $lineHeight,' Civil Status: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();        
        $pdf->Cell(70, $lineHeight, $apeDetails['civilStatus'], $border);

        // Sex
        $pdf->thFontStyle();
        $pdf->Cell(17.5, $lineHeight,' Sex: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(30, $lineHeight, $apeDetails['sex'], $border);
        
        // Age
        $pdf->thFontStyle();
        $pdf->Cell(17.5, $lineHeight,' Age: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(30, $lineHeight, $apeDetails['age'] , $border, $toBegin);
        
        // Company
        $pdf->thFontStyle();
        $pdf->Cell(25, $lineHeight,' Company: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->MultiCell(0, $lineHeight, $orgDetails['name'], $border);

        // Company
        $pdf->thFontStyle();
        $pdf->Cell(25, $lineHeight,' Clinical Data: ', $border, $toRight, 1, true);
        $pdf->tdFontStyle();
        $pdf->MultiCell(0, $lineHeight, $ecgDetails['ecgdiag_clinicaldata'], 1, $toBegin);
        
        $pdf->ln(10);

        $pdf->SetFont('Arial','B', 8);
        $pdf->SetTextColor(83,99,113);
        $pdf->Cell(25, $lineHeight, ' ECG Diagnosis: ' , 0, 0, 'L');

        $pdf->SetFont('Arial','B', 8);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->MultiCell(0, $lineHeight, ($ecgDetails['ecgdiag_ecgdiagnosis'] ?? null), 0, 'L');

        $pdf->ln(5);

        $pdf->SetFont('Arial','B', 8);
        $pdf->SetTextColor(83,99,113);
        $pdf->Cell(25, $lineHeight, ' Note: ' , 0, 0, 'L');

        $pdf->SetFont('Arial','', 8);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->MultiCell(0, $lineHeight, 'This report is based entirely on electrocardiographic tracing and should be correlated clinically with laboratory findings.', 0, 'L');

        $pdf->ln(10);
        
        
        if (isValidImageUrl($physician_signature)) {
            $pdf->Image($physician_signature, 20);
        }
        
        $pdf->SetFont('Arial','B', 8);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetDrawColor(83,99,113);

        $pdf->Cell(10, 8, '' , '', 0, 'L');
        $pdf->Cell(50, 8, $physician_name , 'B', 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial','', 8);
        $pdf->Cell(10, 8, '' , '', 0, 'L');
        $pdf->Cell(50, 8, $physician_role , '', 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(10, 8, '' , '', 0, 'C');

        if($physician_license != '') {
            $pdf->Cell(50, 8, 'License No.: ' . $physician_license , '', 0, 'C');
            $pdf->ln(5);
            $pdf->Cell(10, 8, '' , '', 0, 'C');
        }
        // $pdf->SetFont('Arial','B', 8);
        // $pdf->Cell(12, 8, '' , '', 0, 'C');
        // $pdf->SetFont('Arial','', 8);
        // $pdf->Cell(5, 8, '' , '', 0, 'L',);
        // $pdf->Cell(9, 8, 'Date:' , '', 0, 'L');
        // $pdf->SetFont('Arial','B', 8);
        
        $pdf->Cell(50, 8, 'Date: ' . ($ecgDetails['ecgdiag_date'] ?? null), '', 0, 'C');
        
        // $pdf->Output();
        // $conn->close();

        $pdf->ln(20);
        $pdf->row('Computer-generated report.', '', '');
        return $pdf;
    }
}

class EcgFPDF extends FPDF {

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

    function row($title, $value) {
        $border = 0;
        $lineHeight = 5;
        $toRight = 0;

        $this->SetFont('Arial','', 8);
        $this->SetTextColor(83,99,113);
        $this->Cell(47.5, $lineHeight, $title , $border, $toRight, 'L');

        $this->SetFont('Arial','B', 8);
        $this->SetTextColor(17, 24, 39);
        $this->Cell(47.5, $lineHeight, $value, $border, $toRight, 'L');
    }

    // function rowCol4($title, $title_width, $value, $value_w = 0) {
    //     $border = 0;
    //     $lineHeight = 5;
    //     $toRight = 0;
    //     $align = 'L';
    //     $this->SetFont('Arial','', 8);
    //     $this->SetTextColor(83,99,113);
    //     $this->Cell($title_width, 5, $title , 0, 0, 'L');

    //     $this->SetFont('Arial','B', 8);
    //     $this->SetTextColor(17, 24, 39);
    //     $this->MultiCell($value_w, 5, $value, 0, 'L');
    //     $this->ln(1);
    // }

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

    function processRow($pdf, $title, $key, $medExamReports) {
        $value = ($medExamReports[$key] ?? null);
        
        if ($value == 'Yes') {
            $pdf->row($title, 'Normal');
        } else if ($value == 'No') {
            $pdf->row($title, ($medExamReports[$key . '_note'] ?? null));
        } else {
            $pdf->row($title, '');
        }
    }

}


<?php 
class radioFPDF extends FPDF {

    function Header() {
        $this->Image(base_url(false) . '/images/ghd-logo-with-text.png', 10, 10, 60);
        $this->SetFont('Arial','', 8);
        $this->SetTextColor(83,99,113);
        $this->ln(1);
        $this->Cell(0,4.5,'3/F LMB Bldg., 158 San Antonio Ave., San Antonio Valley I,', 0, 1, 'R');
        $this->Cell(0,4.5,'Paranaque City, Metro Manila', 0, 1, 'R');
        $this->Cell(0,4.5,'Tel/Fax No. 8825-9964', 0, 1, 'R');
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

class RadiologyPDF {
    function generateRadiologyReport($conn, $id, $pdf = null) {
        $apeDetails = fetchApeDetailsById($conn, $id);
        $orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);
        $rr_row = fetchRadReportDetailsByAPEfk($conn, $id);
        
        $xraytech = getProfessional($conn, $orgDetails['xraytech_fk']);
        $xraytech_id = $xraytech['prof_id'] ?? 0;
        $xraytech_name = $xraytech['prof_name'] ?? '';
        $xraytech_role = $xraytech['prof_role'] ?? '';
        $xraytech_license = $xraytech['prof_license'] ?? '';
        $xraytech_signature = base_url(false) . '/images/healthcare-professional-' . $xraytech_id  . '-signature.png';

        $radiologist = getProfessional($conn, $orgDetails['radiologist_fk']);
        $radiologist_id = $radiologist['prof_id'] ?? 0;
        $radiologist_name = $radiologist['prof_name'] ?? '';
        $radiologist_role = $radiologist['prof_role'] ?? '';
        $radiologist_license = $radiologist['prof_license'] ?? '';
        $radiologist_signature = base_url(false) . '/images/healthcare-professional-' . $radiologist_id  . '-signature.png';
     
        if (null !== $rr_row) {
            $rr_MedicalExamination_FK  = $rr_row["MedicalExamination_FK"];
            $medExamDetails = fetchMedExamDetailsById($conn, $rr_MedicalExamination_FK);

            if(null == $pdf) {
                $pdf = new radioFPDF();
            }
            
            $pdf->AliasNbPages();
            $pdf->AddPage();

            $pdf->SetFont('Arial','B', 10);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->SetDrawColor(241, 245, 249);
            $pdf->Cell(0,4.5,'RADIOLOGY REPORT', 0, 1, 'L');

            if (isValidImageUrl($xraytech_signature)) {
                $pdf->Image($xraytech_signature, 15, 140, 50);
            }

            if (isValidImageUrl($radiologist_signature)) {
                $pdf->Image($radiologist_signature, 135, 140, 50);
            }

            $pdf->SetFont('Arial', '', 12);

            $pdf->Ln(10);

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(95, 5, 'Case Number', 0);
            $pdf->Cell(95, 5, 'Date', 0);
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(95, 8, $rr_row["caseNumber"] ?? 0, 0);
            $pdf->Cell(95, 8, $rr_row["dateCreated"] ?? "", 0);
            $pdf->Ln(12);

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(47.5, 5, 'Family Name', 0);
            $pdf->Cell(47.5, 5, 'First Name', 0);
            $pdf->Cell(31.67, 5, 'Middle Initial', 0);
            $pdf->Cell(31.67, 5, 'Age', 0);
            $pdf->Cell(31.67, 5, 'Sex', 0);
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(47.5, 8, $apeDetails['lastName'] ?? "", 0);
            $pdf->Cell(47.5, 8, $apeDetails['firstName'] ?? "", 0);
            $pdf->Cell(31.67, 8, $apeDetails['middleName'] ?? "", 0);
            $pdf->Cell(31.67, 8, $apeDetails['age'] ?? "", 0);
            $pdf->Cell(31.67, 8, $apeDetails['sex'] ?? "", 0);
            $pdf->Ln(12);



            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(95, 5, 'Company Name', 0);
            $pdf->Cell(95, 5, 'Type of Examination', 0);
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(95, 8, html_entity_decode($orgDetails['name'] ?? ""), 0);
            $pdf->Cell(95, 8, $medExamDetails['name'] ?? "", 0);
            $pdf->Ln(12);

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(190, 8, "Chest PA", 0);
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell(0, 5, html_entity_decode($rr_row["chestPA"] ?? ""));
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(190, 8, "Impression", 0);    
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell(0, 5, html_entity_decode($rr_row["impression"] ?? ""));
            $pdf->Ln(20);

            $pdf->SetFont('Arial','B', 9);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->SetDrawColor(83,99,113);

            
            
            $pdf->Cell(10, 8, '' , '', 0, 'L');
            $pdf->Cell(40, 8, $xraytech_name, 'B', 0, 'C');
            $pdf->Cell(70, 8, '' , '', 0, 'L');
            $pdf->Cell(60, 8, $radiologist_name, 'B', 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial','', 8);
            $pdf->Cell(10, 8, '' , '', 0, 'L');
            $pdf->Cell(40, 8, $xraytech_role , '', 0, 'C');
            $pdf->Cell(70, 8, '' , '', 0, 'L');
            $pdf->Cell(60, 8, $radiologist_role , '', 0, 'C');
            $pdf->ln(5);
            $pdf->Cell(10, 8, '' , '', 0, 'C');
            
            if($xraytech_license != '') {
                $pdf->Cell(40, 8, 'License No.: ' . $xraytech_license , '', 0, 'C');
            }
            
            $pdf->Cell(70, 8, '' , '', 0, 'C');

            if($radiologist_license != '') {
                $pdf->Cell(60, 8, 'License No.: ' . $radiologist_license , '', 0, 'C');
            }
            
            $pdf->Ln(20);
            $pdf->SetTextColor(83,99,113);
            $pdf->Cell(40, 8,'Computer-generated report.', '', 0, 'L');

            return $pdf;
        }
    }
}
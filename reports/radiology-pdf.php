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
    function generateRadiologyReport($conn, $id) {

        /* Radiology Report Data - START */
        $rr_sql = "SELECT * FROM RadiologyReport WHERE APEFK = ?";
        $rr_stmt = $conn->prepare($rr_sql);
        $rr_stmt->bind_param('i', $id);
        $rr_stmt->execute();
        $rr_sqlresult = $rr_stmt->get_result();

        $rr_APEFK = $rr_caseNumber = $rr_MedicalExamination_FK = 0;
        $rr_dateCreated = $rr_chestPA = $rr_impression = "";

        if ($rr_sqlresult->num_rows > 0) {
            $rr_row = $rr_sqlresult->fetch_assoc();

            $rr_APEFK = $rr_row["APEFK"];
            $rr_caseNumber = $rr_row["caseNumber"];
            $rr_dateCreated = $rr_row["dateCreated"];
            $rr_chestPA = $rr_row["chestPA"];
            $rr_impression = $rr_row["impression"];
            $rr_MedicalExamination_FK  = $rr_row["MedicalExamination_FK"];
        }
        /* Radiology Report Data - END */

        /* APE Data - START */
        $ape_sql = "SELECT * FROM APE WHERE id = ?";
        $ape_stmt = $conn->prepare($ape_sql);
        $ape_stmt->bind_param('i', $id);
        $ape_stmt->execute();
        $ape_sqlresult = $ape_stmt->get_result();

        $ape_age = $ape_organizationId = 0;
        $ape_firstName = $ape_middleName = $ape_lastName = $ape_sex = "";

        if ($ape_sqlresult->num_rows > 0) {
            $ape_row = $ape_sqlresult->fetch_assoc();

            $ape_firstName = $ape_row["firstName"];
            $ape_middleName = $ape_row["middleName"];
            $ape_lastName = $ape_row["lastName"];
            $ape_age = $ape_row["age"];
            $ape_sex = $ape_row["sex"];
            $ape_organizationId = $ape_row["organizationId"];
        }
        /* APE Data - END */

        /* Organization Data - START */
        $org_sql = "SELECT * FROM Organization WHERE id = ?";
        $org_stmt = $conn->prepare($org_sql);
        $org_stmt->bind_param('i', $ape_organizationId);
        $org_stmt->execute();
        $org_sqlresult = $org_stmt->get_result();

        $org_name = "";

        if ($org_sqlresult->num_rows > 0) {
            $org_row = $org_sqlresult->fetch_assoc();

            $org_name = $org_row["name"];
        }
        /* Organization - END */

        /* Med Exam Data - START */
        $me_sql = "SELECT * FROM MedicalExamination WHERE id = ?";
        $me_stmt = $conn->prepare($me_sql);
        $me_stmt->bind_param('i', $rr_MedicalExamination_FK);
        $me_stmt->execute();
        $me_sqlresult = $me_stmt->get_result();

        $me_name = "";

        if ($me_sqlresult->num_rows > 0) {
            $me_row = $me_sqlresult->fetch_assoc();

            $me_name = $me_row["name"];
        }
        /* Med Exam - END */

        $pdf = new radioFPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // $pdf->Image(base_url(false) . '/images/ghd-logo-with-text.png', 10, 10, 60);
        // $pdf->SetFont('Arial','', 8);
        // $pdf->SetTextColor(83,99,113);
        // $pdf->ln(1);
        // $pdf->Cell(0,4.5,'3/F LMB Bldg., 158 San Antonio Ave., San Antonio Valley I,', 0, 1, 'R');
        // $pdf->Cell(0,4.5,'Paranaque City, Metro Manila', 0, 1, 'R');
        // $pdf->Cell(0,4.5,'Tel/Fax No. 8825-9964', 0, 1, 'R');
        // $pdf->Ln(10);

        $pdf->SetFont('Arial','B', 10);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetDrawColor(241, 245, 249);
        $pdf->Cell(0,4.5,'RADIOLOGY REPORT', 0, 1, 'L');

        // list($imageWidth, $imageHeight) = getimagesize(base_url(false) . 'images/radiology-report.jpg');

        // $aspectRatio = $imageWidth / $imageHeight;
        // $newWidth = $pdf->GetPageWidth();
        // $newHeight = $newWidth / $aspectRatio;

        // $pdf->Image(base_url(false) . 'images/radiology-report.jpg', 0, 0, $newWidth, $newHeight);

        $pdf->SetFont('Arial', '', 12);

        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(95, 5, 'Case Number', 0);
        $pdf->Cell(95, 5, 'Date', 0);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 8, $rr_caseNumber, 0);
        $pdf->Cell(95, 8, $rr_dateCreated, 0);
        $pdf->Ln(12);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(47.5, 5, 'Family Name', 0);
        $pdf->Cell(47.5, 5, 'First Name', 0);
        $pdf->Cell(31.67, 5, 'Middle Initial', 0);
        $pdf->Cell(31.67, 5, 'Age', 0);
        $pdf->Cell(31.67, 5, 'Sex', 0);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(47.5, 8, $ape_lastName, 0);
        $pdf->Cell(47.5, 8, $ape_firstName, 0);
        $pdf->Cell(31.67, 8, $ape_middleName, 0);
        $pdf->Cell(31.67, 8, $ape_age, 0);
        $pdf->Cell(31.67, 8, $ape_sex, 0);
        $pdf->Ln(12);



        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(95, 5, 'Company Name', 0);
        $pdf->Cell(95, 5, 'Type of Examination', 0);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 8, html_entity_decode($org_name), 0);
        $pdf->Cell(95, 8, $me_name, 0);
        $pdf->Ln(12);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(190, 8, "Chest PA", 0);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, html_entity_decode($rr_chestPA));
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(190, 8, "Impression", 0);    
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, html_entity_decode($rr_impression));
        $pdf->Ln(20);

        $pdf->SetFont('Arial','B', 9);
        $pdf->SetTextColor(17, 24, 39);
        $pdf->SetDrawColor(83,99,113);
        $pdf->Image(base_url(false) . '/images/alvin-d-rosario-art.png', 35, 150, 8);
        $pdf->Image(base_url(false) . '/images/ernie-caliboso.png', 150, 146, 20);
        $pdf->Cell(10, 8, '' , '', 0, 'L');
        $pdf->Cell(40, 8, 'ALVIN D. ROSARIO RXT' , 'B', 0, 'C');
        $pdf->Cell(70, 8, '' , '', 0, 'L');
        $pdf->Cell(60, 8, 'ERNIE CALIBOSO, M.D. F.P.C.R.F.U.S.P.' , 'B', 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial','', 8);
        $pdf->Cell(10, 8, '' , '', 0, 'L');
        $pdf->Cell(40, 8, 'X-RAY TECHNOLOGIST' , '', 0, 'C');
        $pdf->Cell(70, 8, '' , '', 0, 'L');
        $pdf->Cell(60, 8, 'RADIOLOGIST' , '', 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(10, 8, '' , '', 0, 'L');
        $pdf->Cell(40, 8, 'License No.: 0002508' , '', 0, 'C');
        $pdf->Ln(20);
        $pdf->SetTextColor(83,99,113);
        $pdf->Cell(40, 8,'Computer-generated report.', '', 0, 'L');

        // $pdf->Output($fpdfOutput);
        // $stmt->close();
        // $conn->close();

        return $pdf;
    }
}
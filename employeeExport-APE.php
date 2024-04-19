<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();

$live = 'app.globalhealth-diagnostics.com';
$liveURL = "https://app.globalhealth-diagnostics.com";
$devURL = "http://localhost/globalhealth-php";
$baseURL = ($_SERVER['HTTP_HOST'] == $live) ? $liveURL : $devURL;

// Check if logged in
if(!isset($_SESSION["valid"])){
    header("location:" . $baseURL . "/login.php");
    exit();
}

// Check if ID is set
if (!isset($_GET['id'])) {
    header("location:" . $baseURL . "/page-not-found.php");
    exit();
}

include("connection.php");
include("globals.php");
require('fpdf/fpdf.php');
include("reports/radiology-pdf.php");
include("reports/laboratory-pdf.php");
include("reports/medical-exam-pdf.php");

$id = $_GET['id'];
$apeDetails = fetchApeDetailsById($conn, $id);
// $orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);

// If not admin, check if user org and patient org is the same
if( $_SESSION['role'] != 1 ) {
    if( $_SESSION['organizationId'] != $apeDetails['organizationId'] ) {
        header("location:" . $baseURL . "/page-not-found.php");
        exit();
    }
}

class reportsFPDF extends FPDF {

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

    function row2($title, $value, $default = null) {
        $border = 0;
        $lineHeight = 5;
        $toRight = 0;
        $space = " ";

        $this->SetFont('Arial','', 8);
        $this->SetTextColor(83,99,113);
        $this->Cell(30, $lineHeight, $space.$title.$space , $border, $toRight, 'L', false);

        $this->SetFont('Arial','', 8);
        $this->SetTextColor(17, 24, 39);
        $this->Cell(25, $lineHeight, $space.$value.$space, $border, $toRight);

        $this->SetFont('Arial','B', 8);
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

$pdf = new reportsFPDF();


// RADIOLOGY REPORT
$radiologyPDF = new RadiologyPDF();
$radiologyPDF->generateRadiologyReport($conn, $id, $pdf);

// LABORATORY RESULT
$laboratoryPDF = new LaboratoryPDF();
$laboratoryPDF->generateLaboratoryReport($conn, $id, $pdf);    

// MEDICAL EXAM REPORT
$medicalExamPDF = new MedicalExamPDF();
$medicalExamPDF->generateMedicalExamReport($conn, $id, $pdf);

$finalFilename = $apeDetails['controlNumber'] . " - " . $apeDetails['lastName'] . " " . $apeDetails['firstName'] . ".pdf";
$pdf->Output('D', $finalFilename); 
$pdf->Close(); 

$conn->close();

// $url = base_url(false) . "/employee-APE.php?id=" . $id;
// header("Location: " . $url);
exit();
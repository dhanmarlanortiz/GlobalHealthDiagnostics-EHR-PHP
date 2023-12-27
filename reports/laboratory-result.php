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

include("../connection.php");
include("../globals.php");
require('../fpdf/fpdf.php');

$id = $_GET['id'];
$apeDetails = fetchApeDetailsById($conn, $id);
$orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);
$labResults = fetchLabResultByApeId($conn, $id);

// If not admin, check if user org and patient org is the same
if( $_SESSION['role'] != 1 ) {
    if( $_SESSION['organizationId'] != $apeDetails['organizationId'] ) {
        header("location:" . $baseURL . "/page-not-found.php");
        exit();
    }
}

// list($imageWidth, $imageHeight) = getimagesize('../images/radiology-report.jpg');
class PDF extends FPDF {
    
    function Header() {
        $this->Image('../images/ghd-logo-with-text.png', 10, 10, 60);
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

// Instanciation of inherited class
$pdf = new PDF();
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
$pdf->Cell(0,4.5,'LABORATORY RESULT', 0, 1, 'L');

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
$pdf->Cell(25, $lineHeight, $space.date('d-M-y', strtotime($labResults['labRes_date'])).$space, $border, $toBegin);

// Company
$pdf->thFontStyle();
$pdf->Cell(20, $lineHeight,' Company: ', $border, $toRight, 'L', true);
$pdf->tdFontStyle();
$pdf->MultiCell(170, $lineHeight, $space.$orgDetails['name'].$space, $border);

$pdf->Cell(0, 1,'', 'T', $toBegin);

$pdf->ln(5);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' REQUEST: ', 0);
$pdf->rowCol4('HEPATITIS B SCREENING:', html_entity_decode($labResults['labRes_hepa_b']));
$pdf->rowCol4('', '');
$pdf->ln(7);
$pdf->rowCol4('DRUG TEST:', '');
$pdf->rowCol4('', '');
$pdf->ln(6);
$pdf->rowCol4('Methamphetamine (shabu):', $labResults['labRes_drug_shabu']);
$pdf->rowCol4('', '');
$pdf->ln();
$pdf->rowCol4('Tetrahydrocannabinol (marijuana):', $labResults['labRes_drug_marijuana']);
$pdf->rowCol4('', '');
$pdf->Cell(0, 1,'', 'T', $toBegin);
$pdf->ln(9);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' HEMATOLOGY: ', 0);
$pdf->row('', '', 'Normal Values:');
$pdf->row('', '', 'Normal Values:');
$pdf->ln();
$pdf->row('Hemoglobin:', $labResults['labRes_hema_hemoglobin'], 'Female: 12-16 gsm/L');
$pdf->row('Segmenters:', $labResults['labRes_hema_segmenters'], '50-70%');
$pdf->ln();
$pdf->row('', '', 'Male: 14-18 gsm/L');
$pdf->row('Lymphocytes:', $labResults['labRes_hema_lymphocytes'], '20-40%');
$pdf->ln();
$pdf->row('Hematocrit:', $labResults['labRes_hema_hematocrit'], 'Female: 37-47 vol%');
$pdf->row('Monocytes:', $labResults['labRes_hema_monocytes'], '2-8%');
$pdf->ln();
$pdf->row('', '', 'Male: 40-54 vol%');
$pdf->row('Eosinophils:', $labResults['labRes_hema_eosinophils'], '1-3%');
$pdf->ln();
$pdf->row('White Blood Cell:', $labResults['labRes_hema_whiteblood'], '5,000-10,000/cu.mm.');
$pdf->row('Basophils:', $labResults['labRes_hema_basophils'], '0-2%');
$pdf->ln();
$pdf->row('Red Blood Cell:', $labResults['labRes_hema_rbc'], '3.80 - 5.80 x 10^12/L');
$pdf->Cell(0, 1,'', 'T', $toBegin);
$pdf->ln(5);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' URINALYSIS: ', 0);
$pdf->row('Color:', $labResults['labRes_urin_color']);
$pdf->row('WBC:', $labResults['labRes_urin_wbc']);
$pdf->ln();
$pdf->row('Transparency:', $labResults['labRes_urin_transparency']);
$pdf->row('RBC:', $labResults['labRes_urin_rbc']);
$pdf->ln();
$pdf->row('Reaction:', $labResults['labRes_urin_reaction']);
$pdf->row('Mucous Threads:', $labResults['labRes_urin_mucous']);
$pdf->ln();
$pdf->row('Specific gravity:', $labResults['labRes_urin_gravity']);
$pdf->row('Epithelial Cells:', $labResults['labRes_urin_epithelial']);
$pdf->ln();
$pdf->row('Protein:', $labResults['labRes_urin_protein']);
$pdf->row('Amorphous Urates:', $labResults['labRes_urin_amorphous']);
$pdf->ln();
$pdf->row('Glucose:', $labResults['labRes_urin_glucose']);
$pdf->row('Bacteria:', $labResults['labRes_urin_bacteria']);
$pdf->ln();
$pdf->row('', '', '');
$pdf->row('Cast:', $labResults['labRes_urin_cast']);
$pdf->ln();
$pdf->row('', '', '');
$pdf->row('Crystals:', $labResults['labRes_urin_crystals']);
$pdf->Cell(0, 1,'', 'T', $toBegin);
$pdf->ln(5);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' PARASITOLOGY: ', 0);
$pdf->row('Color:', $labResults['labRes_para_color']);
$pdf->row('', '', '');
$pdf->ln();
$pdf->row('Consistency:', $labResults['labRes_para_consistency']);
$pdf->row('', '', '');
$pdf->ln();
$pdf->row('Result:', $labResults['labRes_para_result']);
$pdf->row('', '', '');
$pdf->Cell(0, 1,'', 'T', $toBegin);
$pdf->ln(57);

$pdf->Image('../images/angel-baquiran.png', 11.5, 230, 40);
$pdf->Image('../images/nova-angela-buyagan.png', 75, 230, 50);
$pdf->Image('../images/noel-c-santos.png', 140, 230, 60);

$pdf->row('Computer-generated report.', '', '');

$pdf->Output();
$conn->close();
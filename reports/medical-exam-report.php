<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
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
$medExamReports = getMedExamReport($conn, $id);

// If not admin, check if user org and patient org is the same
if( $_SESSION['role'] != 1 ) {
    if( $_SESSION['organizationId'] != $apeDetails['organizationId'] ) {
        header("location:" . $baseURL . "/page-not-found.php");
        exit();
    }
}

// list($imageWidth, $imageHeight) = getimagesize('../images/radiology-report.jpg');
// print_pre($medExamReports);
// die;


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
$pdf->Cell(0,4.5,'MEDICAL EXAMINATION REPORT', 0, 1, 'L');

$rating = strtolower($medExamReports['medExamReport_recommendation_ratings']);
if($rating == 'fit') {
    $pdf->Image('../images/badge-fit.jpg', 165, 30, 35);
} else if($rating == 'unfit') {
    $pdf->Image('../images/badge-unfit.jpg', 165, 30, 35);
} else if($rating == 'pending') {
    $pdf->Image('../images/badge-pending.jpg', 165, 30, 35);
}

$pdf->ln(5);

// Name
$pdf->thFontStyle();
$pdf->Cell(25, $lineHeight,' Name: ', $border, $toRight, 'L', true);
$pdf->tdFontStyle();
$pdf->Cell(115, $lineHeight, $space.$apeDetails['lastName'] . ", " . $apeDetails['firstName'].$space, $border);

// Sex
$pdf->thFontStyle();
$pdf->Cell(10, $lineHeight,' Sex: ', $border, $toRight, 'L', true);
$pdf->tdFontStyle();
$pdf->Cell(15, $lineHeight, $space.substr($apeDetails['sex'], 0,1).$space , $border);

// Age
$pdf->thFontStyle();
$pdf->Cell(10, $lineHeight,' Age: ', $border, $toRight, 'L', true);
$pdf->tdFontStyle();
$pdf->Cell(15, $lineHeight, $space.$apeDetails['age'].$space , $border, $toBegin);

// Civil Status
$pdf->thFontStyle();
$pdf->Cell(25, $lineHeight,' Civil Status: ', $border, $toRight, 'L', true);
$pdf->tdFontStyle();
// $pdf->Cell(25, $lineHeight, $space.date('d-M-y', strtotime($medExamReports['medExamReport_recommendation_date'])).$space, $border, $toBegin);
$pdf->Cell(30, $lineHeight, $space.$apeDetails['civilStatus'].$space, $border);

// Home Address
$pdf->thFontStyle();
$pdf->Cell(25, $lineHeight,' Home Address: ', $border, $toRight, 'L', true);
$pdf->tdFontStyle();
$pdf->MultiCell(0, $lineHeight, $space.$apeDetails['homeAddress'].$space, $border);



$pdf->Cell(0, 1,'', 'T', $toBegin);

$pdf->ln(5);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' I. HISTORY: ', 0);
$pdf->row('Childhood Illness:', $medExamReports['medExamReport_history_childhoodIllness']);
$pdf->row('Past Illness:', $medExamReports['medExamReport_history_pastIllness']);
$pdf->ln();
$pdf->row('Present Illness:', $medExamReports['medExamReport_history_presentIllness']);
$pdf->row('Supplements:', $medExamReports['medExamReport_history_supplements']);
$pdf->ln();
$pdf->row('Surgeries:', $medExamReports['medExamReport_history_surgeries']);
$pdf->row('Hospitalizations:', $medExamReports['medExamReport_history_hospitalizations']);
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' II. PERSONAL & SOCIAL HISTORY: ', 0);
$pdf->row('Smoking History:', $medExamReports['medExamReport_personalSocial_smokingHistory'] . '     Pack per: ' . $medExamReports['medExamReport_personalSocial_smokingHistory_pack'],);
$pdf->row('Alcohol Intake:', $medExamReports['medExamReport_personalSocial_alcoholIntake'] . '     Bottle per: ' . $medExamReports['medExamReport_personalSocial_alcoholIntake_bottle']);
$pdf->ln();
$pdf->row('Drug Use:', $medExamReports['medExamReport_personalSocial_drugUse']);
$pdf->row('Allergy:', $medExamReports['medExamReport_personalSocial_allergy']);
$pdf->ln();
$pdf->ln();
$pdf->row('For Women:', '');
$pdf->ln();
$pdf->row('Last Menstrual Period:', $medExamReports['medExamReport_personalSocial_forWomen_period'] . '     Lasting: ' . $medExamReports['medExamReport_personalSocial_lasting'] . " Days");
$pdf->ln();
$pdf->row('Are you pregnant or any', '');
$pdf->ln();
$pdf->row('chance you may be:', $medExamReports['medExamReport_personalSocial_pregnant'] . '     ' . $medExamReports['medExamReport_personalSocial_pregnant_note'] );
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' III. FAMILY HISTORY: ', 0);
$pdf->row('Hypertension:', $medExamReports['medExamReport_family_hypertension'] . '     ' . $medExamReports['medExamReport_family_hypertension_note']);
$pdf->row('HeartDisease:', $medExamReports['medExamReport_family_heartDisease'] . '     ' . $medExamReports['medExamReport_family_heartDisease_note']);
$pdf->ln();
$pdf->row('KidneyDisease:', $medExamReports['medExamReport_family_kidneyDisease'] . '     ' . $medExamReports['medExamReport_family_kidneyDisease_note']);
$pdf->row('DiabetesMellitus:', $medExamReports['medExamReport_family_diabetesMellitus'] . '     ' . $medExamReports['medExamReport_family_diabetesMellitus_note']);
$pdf->ln();
$pdf->row('Others:', $medExamReports['medExamReport_family_others'] . '     ' . $medExamReports['medExamReport_family_others_note']);
$pdf->ln();
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' IV. REVIEW OF SYSTEMS: ', 0);
$pdf->row('Eyes:', $medExamReports['medExamReport_system_eyes'] . '     ' . $medExamReports['medExamReport_system_eyes_note']);
$pdf->row('ENT/Mouth:', $medExamReports['medExamReport_system_entMouth'] . '     ' . $medExamReports['medExamReport_system_entMouth_note']);
$pdf->ln();
$pdf->row('Cardiovascular:', $medExamReports['medExamReport_system_cardiovascular'] . '     ' . $medExamReports['medExamReport_system_cardiovascular_note']);
$pdf->row('Respiratory:', $medExamReports['medExamReport_system_respiratory'] . '     ' . $medExamReports['medExamReport_system_respiratory_note']);
$pdf->ln();
$pdf->row('Gastrointestinal:', $medExamReports['medExamReport_system_gastrointestinal'] . '     ' . $medExamReports['medExamReport_system_gastrointestinal_note']);
$pdf->row('Genitourinary:', $medExamReports['medExamReport_system_genitourinary'] . '     ' . $medExamReports['medExamReport_system_genitourinary_note']);
$pdf->ln();
$pdf->row('Musculoskeletal:', $medExamReports['medExamReport_system_musculoskeletal'] . '     ' . $medExamReports['medExamReport_system_musculoskeletal_note']);
$pdf->row('Skin/Breast:', $medExamReports['medExamReport_system_skinOrBreast'] . '     ' . $medExamReports['medExamReport_system_skinOrBreast_note']);
$pdf->ln();
$pdf->row('Neurological:', $medExamReports['medExamReport_system_neurological'] . '     ' . $medExamReports['medExamReport_system_neurological_note']);
$pdf->row('Endocrine:', $medExamReports['medExamReport_system_endocrine'] . '     ' . $medExamReports['medExamReport_system_endocrine_note']);
$pdf->ln();
$pdf->row('Hematological:', $medExamReports['medExamReport_system_hematological'] . '     ' . $medExamReports['medExamReport_system_hematological_note']);
$pdf->row('Others:', $medExamReports['medExamReport_system_others'] . '     ' . $medExamReports['medExamReport_system_others_note']);
$pdf->ln(10);


$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' V. PHYSICAL EXAMINATION: ', 0);

$pdf->row('Height (cm):', $medExamReports['medExamReport_physical_height']);
$pdf->row('Weight (kg):', $medExamReports['medExamReport_physical_weight']);
$pdf->ln();
$pdf->row('Body Mass Index:', $medExamReports['medExamReport_physical_bmi']);
$pdf->row('Blood Pressure:', $medExamReports['medExamReport_physical_bp']);
$pdf->ln();
$pdf->row('PR:', $medExamReports['medExamReport_physical_pr']);
$pdf->row('RR:', $medExamReports['medExamReport_physical_rr']);
$pdf->ln();
$pdf->row('Visual Acuity R/L Right:', $medExamReports['medExamReport_physical_visual']);
$pdf->row('Hearing:', $medExamReports['medExamReport_physical_hearing']);
$pdf->ln();
$pdf->row('Clarity of Speech:', $medExamReports['medExamReport_physical_speech']);
$pdf->ln();
$pdf->ln();
$pdf->ln();
$pdf->ln();
$pdf->ln();
$pdf->ln();

function processRow($pdf, $title, $key, $medExamReports) {
    $value = $medExamReports[$key];
    
    if ($value == 'Yes') {
        $pdf->row($title, 'Normal');
    } else if ($value == 'No') {
        $pdf->row($title, $medExamReports[$key . '_note']);
    } else {
        $pdf->row($title, '');
    }
}

processRow($pdf, 'General Appearance:', 'medExamReport_physical_generalAppearance', $medExamReports);
processRow($pdf, 'Skin:', 'medExamReport_physical_skin', $medExamReports);
$pdf->ln();
processRow($pdf, 'Head & Neck:', 'medExamReport_physical_headNeck', $medExamReports);
processRow($pdf, 'Ears, Eyes, Nose:', 'medExamReport_physical_earsEyesNose', $medExamReports);
$pdf->ln();
processRow($pdf, 'Mouth/Throat:', 'medExamReport_physical_mouthThroat', $medExamReports);
processRow($pdf, 'Chest Lungs:', 'medExamReport_physical_chestLungs', $medExamReports);
$pdf->ln();
processRow($pdf, 'Back:', 'medExamReport_physical_back', $medExamReports);
processRow($pdf, 'Heart:', 'medExamReport_physical_heart', $medExamReports);
$pdf->ln();
processRow($pdf, 'Abdomen:', 'medExamReport_physical_abdomen', $medExamReports);
processRow($pdf, 'Extremities:', 'medExamReport_physical_extremities', $medExamReports);
$pdf->ln();
processRow($pdf, 'Neurological:', 'medExamReport_physical_neurological', $medExamReports);
processRow($pdf, 'Rectal:', 'medExamReport_physical_rectal', $medExamReports);
$pdf->ln();
processRow($pdf, 'Breast:', 'medExamReport_physical_breast', $medExamReports);
processRow($pdf, 'Genitalia:', 'medExamReport_physical_genitalia', $medExamReports);




$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' VI. X-RAY, ECG AND LABORATORY EXAMINATION REPORT: ', 0);
$pdf->rowCol4('A. Chest X-ray: ' , $medExamReports['medExamReport_xrayEcgLab_chestXray'] . '     ' . $medExamReports['medExamReport_xrayEcgLab_chestXray_sub'] . '     ' . $medExamReports['medExamReport_xrayEcgLab_chestXray_other']);
$pdf->ln();
$pdf->rowCol4('B. ECG: ' , $medExamReports['medExamReport_xrayEcgLab_ecg']);
$pdf->ln();
$pdf->rowCol4('C. Complete Blood Count:' , $medExamReports['medExamReport_xrayEcgLab_bloodCount'] . '     ' . $medExamReports['medExamReport_xrayEcgLab_bloodCount_findings']);
$pdf->ln();
$pdf->rowCol4('D. Urinalysis: Findings: WBC:', ($medExamReports['medExamReport_xrayEcgLab_urinalysis_wbc'] == '' ? $medExamReports['medExamReport_xrayEcgLab_urinalysis'] : $medExamReports['medExamReport_xrayEcgLab_urinalysis_wbc'].'/hpf'));
$pdf->ln();
$pdf->rowCol4('E. Stool Examination: Findings: Positive:', ($medExamReports['medExamReport_xrayEcgLab_stoolSample_positive'] == '' ? $medExamReports['medExamReport_xrayEcgLab_stoolSample'] : $medExamReports['medExamReport_xrayEcgLab_stoolSample_positive'] . '/hpf'));
$pdf->ln();
$pdf->rowCol4('F. Serological Test (VDRL): ', $medExamReports['medExamReport_xrayEcgLab_serologicalTest']);
$pdf->ln();
$pdf->rowCol4('G. Hepatitis - A Screening: ', $medExamReports['medExamReport_xrayEcgLab_hepatitisAScreening']);
$pdf->ln();
$pdf->rowCol4('H. Hepatitis - B Surface Antigen (Hbsag): ', $medExamReports['medExamReport_xrayEcgLab_hepatitisBSurfaceAntigen']);
$pdf->ln();
$pdf->rowCol4('I. Pregnancy Test: ' , $medExamReports['medExamReport_xrayEcgLab_pregnancyTest']);
$pdf->ln();
$pdf->rowCol4('J. Blood Type:' , $medExamReports['medExamReport_xrayEcgLab_bloodType']);
$pdf->ln();
$pdf->rowCol4('K. Drug Test' , '');
$pdf->ln();
$pdf->rowCol4('    Marijuana (tetrahydrocannabinol):', $medExamReports['medExamReport_xrayEcgLab_marijuana']);
$pdf->ln();
$pdf->rowCol4('    Shabu (Methamphetamine):', $medExamReports['medExamReport_xrayEcgLab_marijuana']);
$pdf->ln();
$pdf->rowCol4('L. Others:' , $medExamReports['medExamReport_xrayEcgLab_other']);
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' RECOMMENDATIONS: ', 0);


$pdf->row('Ratings: ', $medExamReports['medExamReport_recommendation_ratings_note'] . '     ' . $medExamReports['medExamReport_recommendation_ratings']);
$pdf->ln();
$pdf->row('Remarks: ', $medExamReports['medExamReport_recommendation_remarks']);
$pdf->ln(30);
// $pdf->row('Examining Physician:', $medExamReports['medExamReport_recommendation_physicianName']);




$pdf->SetFont('Arial','B', 8);
$pdf->SetTextColor(17, 24, 39);
$pdf->SetDrawColor(83,99,113);
$pdf->Image('../images/jacqueline-esguerra-md-signature.png', 20, 175, 40);
$pdf->Cell(10, 8, '' , '', 0, 'L');
$pdf->Cell(50, 8, 'JACQUELINE ESGUERRA, MD' , 'B', 0, 'C');
$pdf->ln();
$pdf->SetFont('Arial','', 8);
$pdf->Cell(10, 8, '' , '', 0, 'L');
$pdf->Cell(50, 8, 'Examining Physician' , '', 0, 'C');
$pdf->ln();
$pdf->Cell(10, 8, '' , '', 0, 'L');
$pdf->Cell(18, 8, 'License No.:' , '', 0, 'L');
$pdf->SetFont('Arial','B', 8);
$pdf->Cell(12, 8, '119451' , '', 0, 'C');
$pdf->SetFont('Arial','', 8);
$pdf->Cell(5, 8, '' , '', 0, 'L',);
$pdf->Cell(9, 8, 'Date:' , '', 0, 'L');
$pdf->SetFont('Arial','B', 8);
$pdf->Cell(17, 8, $medExamReports['medExamReport_recommendation_date'], '', 0, 'C');
$pdf->Output();
$conn->close();
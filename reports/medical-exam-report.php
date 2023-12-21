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
$pdf->ln();
$pdf->row('Past Illness:', $medExamReports['medExamReport_history_pastIllness']);
$pdf->ln();
$pdf->row('Present Illness:', $medExamReports['medExamReport_history_presentIllness']);
$pdf->ln();
$pdf->row('Supplements:', $medExamReports['medExamReport_history_supplements']);
$pdf->ln();
$pdf->row('Surgeries:', $medExamReports['medExamReport_history_surgeries']);
$pdf->ln();
$pdf->row('Hospitalizations:', $medExamReports['medExamReport_history_hospitalizations']);
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' II. PERSONAL & SOCIAL HISTORY: ', 0);
$pdf->row('Smoking History:', $medExamReports['medExamReport_personalSocial_smokingHistory']);
$pdf->ln();
$pdf->row('smokingHistory:', $medExamReports['medExamReport_personalSocial_smokingHistory']);
$pdf->ln();
$pdf->row('smokingHistory_pack:', $medExamReports['medExamReport_personalSocial_smokingHistory_pack']);
$pdf->ln();
$pdf->row('alcoholIntake:', $medExamReports['medExamReport_personalSocial_alcoholIntake']);
$pdf->ln();
$pdf->row('alcoholIntake_bottle:', $medExamReports['medExamReport_personalSocial_alcoholIntake_bottle']);
$pdf->ln();
$pdf->row('drugUse:', $medExamReports['medExamReport_personalSocial_drugUse']);
$pdf->ln();
$pdf->row('allergy:', $medExamReports['medExamReport_personalSocial_allergy']);
$pdf->ln();
$pdf->row('forWomen_period:', $medExamReports['medExamReport_personalSocial_forWomen_period']);
$pdf->ln();
$pdf->row('lasting:', $medExamReports['medExamReport_personalSocial_lasting']);
$pdf->ln();
$pdf->row('pregnant:', $medExamReports['medExamReport_personalSocial_pregnant']);
$pdf->ln();
$pdf->row('pregnant_note:', $medExamReports['medExamReport_personalSocial_pregnant_note']);
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' III. FAMILY HISTORY: ', 0);
$pdf->row('Hypertension:', $medExamReports['medExamReport_family_hypertension']);
$pdf->ln();
$pdf->row('Hypertension_note:', $medExamReports['medExamReport_family_hypertension_note']);
$pdf->ln();
$pdf->row('HeartDisease:', $medExamReports['medExamReport_family_heartDisease']);
$pdf->ln();
$pdf->row('HeartDisease_note:', $medExamReports['medExamReport_family_heartDisease_note']);
$pdf->ln();
$pdf->row('KidneyDisease:', $medExamReports['medExamReport_family_kidneyDisease']);
$pdf->ln();
$pdf->row('KidneyDisease_note:', $medExamReports['medExamReport_family_kidneyDisease_note']);
$pdf->ln();
$pdf->row('DiabetesMellitus:', $medExamReports['medExamReport_family_diabetesMellitus']);
$pdf->ln();
$pdf->row('DiabetesMellitus_note:', $medExamReports['medExamReport_family_diabetesMellitus_note']);
$pdf->ln();
$pdf->row('Others:', $medExamReports['medExamReport_family_others']);
$pdf->ln();
$pdf->row('Others_note:', $medExamReports['medExamReport_family_others_note']);
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' IV. REVIEW OF SYSTEMS: ', 0);
$pdf->row('Eyes:', $medExamReports['medExamReport_system_eyes']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_eyes_note']);
$pdf->ln();
$pdf->row('ENT/Mouth:', $medExamReports['medExamReport_system_entMouth']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_entMouth_note']);
$pdf->ln();
$pdf->row('Cardiovascular:', $medExamReports['medExamReport_system_cardiovascular']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_cardiovascular_note']);
$pdf->ln();
$pdf->row('Respiratory:', $medExamReports['medExamReport_system_respiratory']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_respiratory_note']);
$pdf->ln();
$pdf->row('Gastrointestinal:', $medExamReports['medExamReport_system_gastrointestinal']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_gastrointestinal_note']);
$pdf->ln();
$pdf->row('Genitourinary:', $medExamReports['medExamReport_system_genitourinary']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_genitourinary_note']);
$pdf->ln();
$pdf->row('Musculoskeletal:', $medExamReports['medExamReport_system_musculoskeletal']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_musculoskeletal_note']);
$pdf->ln();
$pdf->row('Skin/Breast:', $medExamReports['medExamReport_system_skinOrBreast']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_skinOrBreast_note']);
$pdf->ln();
$pdf->row('Neurological:', $medExamReports['medExamReport_system_neurological']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_neurological_note']);
$pdf->ln();
$pdf->row('Endocrine:', $medExamReports['medExamReport_system_endocrine']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_endocrine_note']);
$pdf->ln();
$pdf->row('Hematological:', $medExamReports['medExamReport_system_hematological']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_hematological_note']);
$pdf->ln();
$pdf->row('Others:', $medExamReports['medExamReport_system_others']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_system_others_note']);
$pdf->ln(10);


$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' V. PHYSICAL EXAMINATION: ', 0);

$pdf->row('Height (cm):', $medExamReports['medExamReport_physical_height']);
$pdf->ln();
$pdf->row('Weight (kg):', $medExamReports['medExamReport_physical_weight']);
$pdf->ln();
$pdf->row('Body Mass Index:', $medExamReports['medExamReport_physical_bmi']);
$pdf->ln();
$pdf->row('Blood Pressure:', $medExamReports['medExamReport_physical_bp']);
$pdf->ln();
$pdf->row('PR:', $medExamReports['medExamReport_physical_pr']);
$pdf->ln();
$pdf->row('RR:', $medExamReports['medExamReport_physical_rr']);
$pdf->ln();
$pdf->row('Visual Acuity R/L Right:', $medExamReports['medExamReport_physical_visual']);
$pdf->ln();
$pdf->row('Hearing:', $medExamReports['medExamReport_physical_hearing']);
$pdf->ln();
$pdf->row('Clarity of Speech:', $medExamReports['medExamReport_physical_speech']);
$pdf->ln();
$pdf->row('General Appearance:', $medExamReports['medExamReport_physical_generalAppearance']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_generalAppearance_note']);
$pdf->ln();
$pdf->row('Skin:', $medExamReports['medExamReport_physical_skin']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_skin_note']);
$pdf->ln();
$pdf->row('Head & Neck:', $medExamReports['medExamReport_physical_headNeck']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_headNeck_note']);
$pdf->ln();
$pdf->row('Ears, Eyes, Nose:', $medExamReports['medExamReport_physical_earsEyesNose']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_earsEyesNose_note']);
$pdf->ln();
$pdf->row('Mouth/Throat:', $medExamReports['medExamReport_physical_mouthThroat']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_mouthThroat_note']);
$pdf->ln();
$pdf->row('Chest Lungs:', $medExamReports['medExamReport_physical_chestLungs']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_chestLungs_note']);
$pdf->ln();
$pdf->row('Back:', $medExamReports['medExamReport_physical_back']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_back_note']);
$pdf->ln();
$pdf->row('Heart:', $medExamReports['medExamReport_physical_heart']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_heart_note']);
$pdf->ln();
$pdf->row('Abdomen:', $medExamReports['medExamReport_physical_abdomen']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_abdomen_note']);
$pdf->ln();
$pdf->row('Extremities:', $medExamReports['medExamReport_physical_extremities']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_extremities_note']);
$pdf->ln();
$pdf->row('Neurological:', $medExamReports['medExamReport_physical_neurological']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_neurological_note']);
$pdf->ln();
$pdf->row('Rectal:', $medExamReports['medExamReport_physical_rectal']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_rectal_note']);
$pdf->ln();
$pdf->row('Breast:', $medExamReports['medExamReport_physical_breast']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_breast_note']);
$pdf->ln();
$pdf->row('Genitalia:', $medExamReports['medExamReport_physical_genitalia']);
$pdf->ln();
$pdf->row('Remarks:', $medExamReports['medExamReport_physical_genitalia_note']);
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' VI. X-RAY, ECG AND LABORATORY EXAMINATION REPORT: ', 0);
$pdf->row('chestXray:' , $medExamReports['medExamReport_xrayEcgLab_chestXray']);
$pdf->ln();
$pdf->row('chestXray_other:' , $medExamReports['medExamReport_xrayEcgLab_chestXray_other']);
$pdf->ln();
$pdf->row('ecg:' , $medExamReports['medExamReport_xrayEcgLab_ecg']);
$pdf->ln();
$pdf->row('bloodCount:' , $medExamReports['medExamReport_xrayEcgLab_bloodCount']);
$pdf->ln();
$pdf->row('bloodCount_findings:' , $medExamReports['medExamReport_xrayEcgLab_bloodCount_findings']);
$pdf->ln();
$pdf->row('urinalysis_wbc:' , $medExamReports['medExamReport_xrayEcgLab_urinalysis_wbc']);
$pdf->ln();
$pdf->row('urinalysis:' , $medExamReports['medExamReport_xrayEcgLab_urinalysis']);
$pdf->ln();
$pdf->row('stoolSample_positive:' , $medExamReports['medExamReport_xrayEcgLab_stoolSample_positive']);
$pdf->ln();
$pdf->row('stoolSample:' , $medExamReports['medExamReport_xrayEcgLab_stoolSample']);
$pdf->ln();
$pdf->row('serologicalTest:' , $medExamReports['medExamReport_xrayEcgLab_serologicalTest']);
$pdf->ln();
$pdf->row('hepatitisAScreening:' , $medExamReports['medExamReport_xrayEcgLab_hepatitisAScreening']);
$pdf->ln();
$pdf->row('hepatitisBSurfaceAntigen:' , $medExamReports['medExamReport_xrayEcgLab_hepatitisBSurfaceAntigen']);
$pdf->ln();
$pdf->row('pregnancyTest:' , $medExamReports['medExamReport_xrayEcgLab_pregnancyTest']);
$pdf->ln();
$pdf->row('bloodType:' , $medExamReports['medExamReport_xrayEcgLab_bloodType']);
$pdf->ln();
$pdf->row('marijuana:' , $medExamReports['medExamReport_xrayEcgLab_marijuana']);
$pdf->ln();
$pdf->row('shabu:' , $medExamReports['medExamReport_xrayEcgLab_shabu']);
$pdf->ln();
$pdf->row('other:' , $medExamReports['medExamReport_xrayEcgLab_other']);
$pdf->ln(10);

$pdf->thFontStyle();
$pdf->MultiCell(190, $lineHeight, ' RECOMMENDATIONS: ', 0);


$pdf->row('ratings_note', $medExamReports['medExamReport_recommendation_ratings_note']);
$pdf->ln();
$pdf->row('ratings', $medExamReports['medExamReport_recommendation_ratings']);
$pdf->ln();
$pdf->row('remarks', $medExamReports['medExamReport_recommendation_remarks']);
$pdf->ln();
$pdf->row('physicianName', $medExamReports['medExamReport_recommendation_physicianName']);
$pdf->ln();
$pdf->row('physicianLicense', $medExamReports['medExamReport_recommendation_physicianLicense']);
$pdf->ln();
$pdf->row('date', $medExamReports['medExamReport_recommendation_date']);
$pdf->ln(10);

// $pdf->Image('../images/angel-baquiran.png', 10, 240, 40);

// $pdf->Image('../images/nova-angela-buyagan.png', 75, 240, 50);
// $pdf->Image('../images/noel-c-santos.png', 140, 240, 60);

$pdf->Output();
$conn->close();
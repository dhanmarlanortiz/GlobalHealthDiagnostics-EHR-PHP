<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include("../connection.php");
require('../fpdf/fpdf.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    /* Radiology Report Data - START */
    $labRes_sql = "SELECT * FROM LaboratoryResult WHERE labRes_APE_FK = ?";
    $labRes_stmt = $conn->prepare($labRes_sql);
    $labRes_stmt->bind_param('i', $id);
    $labRes_stmt->execute();
    $labRes_sqlresult = $labRes_stmt->get_result();    

    $labRes_APE_FK = $labRes_user_FK = $labRes_date = $labRes_hepa_b = $labRes_drug_shabu = $labRes_drug_marijuana = $labRes_hema_hemoglobin = $labRes_hema_hematocrit = $labRes_hema_whiteblood = $labRes_hema_segmenters = $labRes_hema_lymphocytes = $labRes_hema_monocytes = $labRes_hema_eosinophils = $labRes_hema_basophils = $labRes_hema_stab = $labRes_urin_color = $labRes_urin_transparency = $labRes_urin_reaction = $labRes_urin_gravity = $labRes_urin_protein = $labRes_urin_glucose = $labRes_urin_wbc = $labRes_urin_rbc = $labRes_urin_mucous = $labRes_urin_epithelial = $labRes_urin_amorphous = $labRes_urin_bacteria = $labRes_urin_cast = $labRes_urin_crystals = $labRes_para_color = $labRes_para_consistency = $labRes_para_result = "";

    if ($labRes_sqlresult->num_rows > 0) {
        $labRes_row = $labRes_sqlresult->fetch_assoc();

        $labRes_APE_FK = $labRes_row["$labRes_APE_FK"];
        $labRes_user_FK = $labRes_row["$labRes_user_FK"];
        $labRes_date = $labRes_row["$labRes_date"];
        $labRes_hepa_b = $labRes_row["$labRes_hepa_b"];
        $labRes_drug_shabu = $labRes_row["$labRes_drug_shabu"];
        $labRes_drug_marijuana = $labRes_row["$labRes_drug_marijuana"];
        $labRes_hema_hemoglobin = $labRes_row["$labRes_hema_hemoglobin"];
        $labRes_hema_hematocrit = $labRes_row["$labRes_hema_hematocrit"];
        $labRes_hema_whiteblood = $labRes_row["$labRes_hema_whiteblood"];
        $labRes_hema_segmenters = $labRes_row["$labRes_hema_segmenters"];
        $labRes_hema_lymphocytes = $labRes_row["$labRes_hema_lymphocytes"];
        $labRes_hema_monocytes = $labRes_row["$labRes_hema_monocytes"];
        $labRes_hema_eosinophils = $labRes_row["$labRes_hema_eosinophils"];
        $labRes_hema_basophils = $labRes_row["$labRes_hema_basophils"];
        $labRes_hema_stab = $labRes_row["$labRes_hema_stab"];
        $labRes_urin_color = $labRes_row["$labRes_urin_color"];
        $labRes_urin_transparency = $labRes_row["$labRes_urin_transparency"];
        $labRes_urin_reaction = $labRes_row["$labRes_urin_reaction"];
        $labRes_urin_gravity = $labRes_row["$labRes_urin_gravity"];
        $labRes_urin_protein = $labRes_row["$labRes_urin_protein"];
        $labRes_urin_glucose = $labRes_row["$labRes_urin_glucose"];
        $labRes_urin_wbc = $labRes_row["$labRes_urin_wbc"];
        $labRes_urin_rbc = $labRes_row["$labRes_urin_rbc"];
        $labRes_urin_mucous = $labRes_row["$labRes_urin_mucous"];
        $labRes_urin_epithelial = $labRes_row["$labRes_urin_epithelial"];
        $labRes_urin_amorphous = $labRes_row["$labRes_urin_amorphous"];
        $labRes_urin_bacteria = $labRes_row["$labRes_urin_bacteria"];
        $labRes_urin_cast = $labRes_row["$labRes_urin_cast"];
        $labRes_urin_crystals = $labRes_row["$labRes_urin_crystals"];
        $labRes_para_color = $labRes_row["$labRes_para_color"];
        $labRes_para_consistency = $labRes_row["$labRes_para_consistency"];
        $labRes_para_result = $labRes_row["$labRes_para_result"];
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

    $pdf = new FPDF();
    $pdf->AddPage();
    
    // list($imageWidth, $imageHeight) = getimagesize('../images/radiology-report.jpg');

    // $aspectRatio = $imageWidth / $imageHeight;
    // $newWidth = $pdf->GetPageWidth();
    // $newHeight = $newWidth / $aspectRatio;

    // $pdf->Image('../images/radiology-report.jpg', 0, 0, $newWidth, $newHeight);

    $pdf->SetFont('Arial', '', 12);
    
    $pdf->Ln(38);

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(95, 5, 'Case Number', 0);
    $pdf->Cell(95, 5, 'Date', 0);
    $pdf->Ln();
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(95, 8, $labRes_caseNumber, 0);
    $pdf->Cell(95, 8, $labRes_dateCreated, 0);
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
    $pdf->Cell(95, 8, $org_name, 0);
    $pdf->Cell(95, 8, $me_name, 0);
    $pdf->Ln(12);

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 8, "Chest PA", 0);
    $pdf->Ln();
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(0, 5, $labRes_chestPA);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 8, "Impression", 0);
    $pdf->Ln();
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(0, 5, $labRes_impression);
    $pdf->Ln(12);

    $pdf->Output('I');
    $stmt->close();
    $conn->close();
} else {
    echo "ID not provided.";
}
?>
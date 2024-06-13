<?php
class MedicalExamPDF {
    function generateMedicalExamReport($conn, $id, $pdf = null) {
        $apeDetails = fetchApeDetailsById($conn, $id);
        $medExamReports = getMedExamReport($conn, $id);

        $orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);

        $physician = getProfessional($conn, $orgDetails['physician_fk']);
        $physician_id = $physician['prof_id'] ?? 0;
        $physician_name = $physician['prof_name'] ?? '';
        $physician_role = $physician['prof_role'] ?? '';
        $physician_license = $physician['prof_license'] ?? '';
        $physician_signature = base_url(false) . '/images/healthcare-professional-' . $physician_id  . '-signature.png';

        // Instanciation of inherited class
        if(null == $pdf) {
            $pdf = new MedFPDF();
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
        $pdf->Cell(0,4.5,'MEDICAL EXAMINATION REPORT', 0, 1, 'L');
        
        $rating = strtolower(($medExamReports['medExamReport_recommendation_ratings'] ?? ''));
        if($rating == 'fit') {
            $pdf->Image(base_url(false) . '/images/badge-fit.jpg', 165, 30, 35);
        } else if($rating == 'unfit') {
            $pdf->Image(base_url(false) . '/images/badge-unfit.jpg', 165, 30, 35);
        } else if($rating == 'pending') {
            $pdf->Image(base_url(false) . '/images/badge-pending.jpg', 165, 30, 35);
        }

        $pdf->ln(5);
        
        // Name
        $pdf->thFontStyle();
        $pdf->Cell(25, $lineHeight,' Name: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(115, $lineHeight, $apeDetails['lastName'] . ", " . $apeDetails['firstName'], $border);
        
        // Sex
        $pdf->thFontStyle();
        $pdf->Cell(10, $lineHeight,' Sex: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(15, $lineHeight, substr($apeDetails['sex'], 0,1) , $border);
        
        // Age
        $pdf->thFontStyle();
        $pdf->Cell(10, $lineHeight,' Age: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->Cell(15, $lineHeight, $apeDetails['age'] , $border, $toBegin);
        
        // Civil Status
        $pdf->thFontStyle();
        $pdf->Cell(25, $lineHeight,' Civil Status: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        // $pdf->Cell(25, $lineHeight, date('d-M-y', strtotime($medExamReports['medExamReport_recommendation_date'])), $border, $toBegin);
        $pdf->Cell(30, $lineHeight, $apeDetails['civilStatus'], $border);
        
        // Home Address
        $pdf->thFontStyle();
        $pdf->Cell(25, $lineHeight,' Home Address: ', $border, $toRight, 'L', true);
        $pdf->tdFontStyle();
        $pdf->MultiCell(0, $lineHeight, $apeDetails['homeAddress'], $border);
        
        $pdf->Cell(0, 1,'', 'T', $toBegin);
        
        $pdf->ln(5);
        
        $pdf->thFontStyle();
        $pdf->MultiCell(190, $lineHeight, 'I. HISTORY: ', 0);
        $pdf->row('Childhood Illness:', $medExamReports['medExamReport_history_childhoodIllness'] ?? null);
        $pdf->row('Past Illness:', $medExamReports['medExamReport_history_pastIllness'] ?? null);
        $pdf->ln();
        $pdf->row('Present Illness:', $medExamReports['medExamReport_history_presentIllness'] ?? null);
        $pdf->row('Supplements:', $medExamReports['medExamReport_history_supplements'] ?? null);
        $pdf->ln();
        $pdf->row('Surgeries:', $medExamReports['medExamReport_history_surgeries'] ?? null);
        $pdf->row('Hospitalizations:', $medExamReports['medExamReport_history_hospitalizations'] ?? null);
        $pdf->ln(10);
        
        $pdf->thFontStyle();
        $pdf->MultiCell(190, $lineHeight, 'II. PERSONAL & SOCIAL HISTORY: ', 0);
        $pdf->row('Smoking History:', ($medExamReports['medExamReport_personalSocial_smokingHistory'] ?? null) . '     Pack per: ' . ($medExamReports['medExamReport_personalSocial_smokingHistory_pack'] ?? null));
        $pdf->row('Alcohol Intake:', ($medExamReports['medExamReport_personalSocial_alcoholIntake'] ?? null) . '     Bottle per: ' . ($medExamReports['medExamReport_personalSocial_alcoholIntake_bottle'] ?? null));
        $pdf->ln();
        $pdf->row('Drug Use:', $medExamReports['medExamReport_personalSocial_drugUse'] ?? null);
        $pdf->row('Allergy:', $medExamReports['medExamReport_personalSocial_allergy'] ?? null);
        $pdf->ln();
        $pdf->ln();
        $pdf->row('For Women:', '');
        $pdf->ln();
        $pdf->row('Last Menstrual Period:', ($medExamReports['medExamReport_personalSocial_forWomen_period'] ?? null) . '     Lasting: ' . ($medExamReports['medExamReport_personalSocial_lasting'] ?? null) . " Days");
        $pdf->ln();
        $pdf->row('Are you pregnant or any', '');
        $pdf->ln();
        $pdf->row('chance you may be:', ($medExamReports['medExamReport_personalSocial_pregnant'] ?? null) . '     ' . ($medExamReports['medExamReport_personalSocial_pregnant_note'] ?? null) );
        $pdf->ln(10);
        
        $pdf->thFontStyle();
        $pdf->MultiCell(190, $lineHeight, 'III. FAMILY HISTORY: ', 0);
        $pdf->row('Hypertension:', $medExamReports['medExamReport_family_hypertension'] ?? null . '     ' . ($medExamReports['medExamReport_family_hypertension_note'] ?? null));
        $pdf->row('HeartDisease:', $medExamReports['medExamReport_family_heartDisease'] ?? null . '     ' . ($medExamReports['medExamReport_family_heartDisease_note'] ?? null));
        $pdf->ln();
        $pdf->row('KidneyDisease:', $medExamReports['medExamReport_family_kidneyDisease'] ?? null . '     ' . ($medExamReports['medExamReport_family_kidneyDisease_note'] ?? null));
        $pdf->row('DiabetesMellitus:', $medExamReports['medExamReport_family_diabetesMellitus'] ?? null . '     ' . ($medExamReports['medExamReport_family_diabetesMellitus_note'] ?? null));
        $pdf->ln();
        $pdf->row('Others:', $medExamReports['medExamReport_family_others'] ?? null . '     ' . ($medExamReports['medExamReport_family_others_note'] ?? null));
        $pdf->ln();
        $pdf->ln(10);
        
        $pdf->thFontStyle();
        $pdf->MultiCell(190, $lineHeight, 'IV. REVIEW OF SYSTEMS: ', 0);
        $pdf->row('Eyes:', ($medExamReports['medExamReport_system_eyes'] ?? null) . '     ' . ($medExamReports['medExamReport_system_eyes_note'] ?? null));
        $pdf->row('ENT/Mouth:', ($medExamReports['medExamReport_system_entMouth'] ?? null) . '     ' . ($medExamReports['medExamReport_system_entMouth_note'] ?? null));
        $pdf->ln();
        $pdf->row('Cardiovascular:', ($medExamReports['medExamReport_system_cardiovascular'] ?? null) . '     ' . ($medExamReports['medExamReport_system_cardiovascular_note'] ?? null));
        $pdf->row('Respiratory:', ($medExamReports['medExamReport_system_respiratory'] ?? null) . '     ' . ($medExamReports['medExamReport_system_respiratory_note'] ?? null));
        $pdf->ln();
        $pdf->row('Gastrointestinal:', ($medExamReports['medExamReport_system_gastrointestinal'] ?? null) . '     ' . ($medExamReports['medExamReport_system_gastrointestinal_note'] ?? null));
        $pdf->row('Genitourinary:', ($medExamReports['medExamReport_system_genitourinary'] ?? null) . '     ' . ($medExamReports['medExamReport_system_genitourinary_note'] ?? null));
        $pdf->ln();
        $pdf->row('Musculoskeletal:', ($medExamReports['medExamReport_system_musculoskeletal'] ?? null) . '     ' . ($medExamReports['medExamReport_system_musculoskeletal_note'] ?? null));
        $pdf->row('Skin/Breast:', ($medExamReports['medExamReport_system_skinOrBreast'] ?? null) . '     ' . ($medExamReports['medExamReport_system_skinOrBreast_note'] ?? null));
        $pdf->ln();
        $pdf->row('Neurological:', ($medExamReports['medExamReport_system_neurological'] ?? null) . '     ' . ($medExamReports['medExamReport_system_neurological_note'] ?? null));
        $pdf->row('Endocrine:', ($medExamReports['medExamReport_system_endocrine'] ?? null) . '     ' . ($medExamReports['medExamReport_system_endocrine_note'] ?? null));
        $pdf->ln();
        $pdf->row('Hematological:', ($medExamReports['medExamReport_system_hematological'] ?? null) . '     ' . ($medExamReports['medExamReport_system_hematological_note'] ?? null));
        $pdf->row('Others:', ($medExamReports['medExamReport_system_others'] ?? null) . '     ' . ($medExamReports['medExamReport_system_others_note'] ?? null));
        $pdf->ln(10);
        
        $pdf->thFontStyle();
        $pdf->MultiCell(190, $lineHeight, 'V. PHYSICAL EXAMINATION: ', 0);
        
        $pdf->row('Height (cm):', $medExamReports['medExamReport_physical_height'] ?? null);
        $pdf->row('Weight (kg):', $medExamReports['medExamReport_physical_weight'] ?? null);
        $pdf->ln();
        $pdf->row('Body Mass Index:', $medExamReports['medExamReport_physical_bmi'] ?? null);
        $pdf->row('Blood Pressure:', $medExamReports['medExamReport_physical_bp'] ?? null);
        $pdf->ln();
        $pdf->row('PR:', $medExamReports['medExamReport_physical_pr'] ?? null);
        $pdf->row('RR:', $medExamReports['medExamReport_physical_rr'] ?? null);
        $pdf->ln();
        $pdf->row('Visual Acuity R/L Right:', $medExamReports['medExamReport_physical_visual'] ?? null);
        $pdf->row('Hearing:', $medExamReports['medExamReport_physical_hearing'] ?? null);
        $pdf->ln();
        $pdf->row('Clarity of Speech:', $medExamReports['medExamReport_physical_speech'] ?? null);
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        
        $pdf->processRow($pdf, 'General Appearance:', 'medExamReport_physical_generalAppearance', $medExamReports);
        $pdf->processRow($pdf, 'Skin:', 'medExamReport_physical_skin', $medExamReports);
        $pdf->ln();
        $pdf->processRow($pdf, 'Head & Neck:', 'medExamReport_physical_headNeck', $medExamReports);
        $pdf->processRow($pdf, 'Ears, Eyes, Nose:', 'medExamReport_physical_earsEyesNose', $medExamReports);
        $pdf->ln();
        $pdf->processRow($pdf, 'Mouth/Throat:', 'medExamReport_physical_mouthThroat', $medExamReports);
        $pdf->processRow($pdf, 'Chest Lungs:', 'medExamReport_physical_chestLungs', $medExamReports);
        $pdf->ln();
        $pdf->processRow($pdf, 'Back:', 'medExamReport_physical_back', $medExamReports);
        $pdf->processRow($pdf, 'Heart:', 'medExamReport_physical_heart', $medExamReports);
        $pdf->ln();
        $pdf->processRow($pdf, 'Abdomen:', 'medExamReport_physical_abdomen', $medExamReports);
        $pdf->processRow($pdf, 'Extremities:', 'medExamReport_physical_extremities', $medExamReports);
        $pdf->ln();
        $pdf->processRow($pdf, 'Neurological:', 'medExamReport_physical_neurological', $medExamReports);
        $pdf->processRow($pdf, 'Rectal:', 'medExamReport_physical_rectal', $medExamReports);
        $pdf->ln();
        $pdf->processRow($pdf, 'Breast:', 'medExamReport_physical_breast', $medExamReports);
        $pdf->processRow($pdf, 'Genitalia:', 'medExamReport_physical_genitalia', $medExamReports);
        
        $pdf->ln(10);

        if (isValidImageUrl($physician_signature)) {
            $pdf->Image($physician_signature, 20, 195, 50);
        }
        
        $pdf->thFontStyle();
        $pdf->MultiCell(190, $lineHeight, 'VI. X-RAY, ECG AND LABORATORY EXAMINATION REPORT: ', 0);
        $pdf->ln(0);
        $pdf->rowCol4('A. Chest X-ray: ', '55', ($medExamReports['medExamReport_xrayEcgLab_chestXray'] ?? null) . '     ' . ($medExamReports['medExamReport_xrayEcgLab_chestXray_sub'] ?? null) . '     ' . ($medExamReports['medExamReport_xrayEcgLab_chestXray_other'] ?? null));
        $pdf->rowCol4('B. ECG: ' , '55', ($medExamReports['medExamReport_xrayEcgLab_ecg'] ?? null));
        $pdf->rowCol4('C. Complete Blood Count:' , '55', ($medExamReports['medExamReport_xrayEcgLab_bloodCount'] ?? null) . '     ' . ($medExamReports['medExamReport_xrayEcgLab_bloodCount_findings'] ?? null));
        $pdf->rowCol4('D. Urinalysis: Findings: WBC:', '55', ($medExamReports['medExamReport_xrayEcgLab_urinalysis_wbc'] ?? null) == '' ? ($medExamReports['medExamReport_xrayEcgLab_urinalysis'] ?? null) : ($medExamReports['medExamReport_xrayEcgLab_urinalysis_wbc'] ?? null) .'/hpf');
        $pdf->rowCol4('E. Stool Examination: Findings: Positive:', '55',($medExamReports['medExamReport_xrayEcgLab_stoolSample_positive'] ?? null) == '' ? ($medExamReports['medExamReport_xrayEcgLab_stoolSample'] ?? null) : ($medExamReports['medExamReport_xrayEcgLab_stoolSample_positive'] . '/hpf'));
        $pdf->rowCol4('F. Serological Test (VDRL): ', '55',($medExamReports['medExamReport_xrayEcgLab_serologicalTest'] ?? null));
        $pdf->rowCol4('G. Hepatitis - A Screening: ', '55',($medExamReports['medExamReport_xrayEcgLab_hepatitisAScreening'] ?? null));
        $pdf->rowCol4('H. Hepatitis - B Surface Antigen (Hbsag): ','55', ($medExamReports['medExamReport_xrayEcgLab_hepatitisBSurfaceAntigen'] ?? null));
        $pdf->rowCol4('I. Pregnancy Test: ' ,'55',($medExamReports['medExamReport_xrayEcgLab_pregnancyTest'] ?? null));
        $pdf->rowCol4('J. Blood Type:' , '55',($medExamReports['medExamReport_xrayEcgLab_bloodType'] ?? null));
        $pdf->rowCol4('K. Drug Test' ,'55', '');
        $pdf->rowCol4('    Marijuana (tetrahydrocannabinol):', '55',($medExamReports['medExamReport_xrayEcgLab_marijuana'] ?? null));
        $pdf->rowCol4('    Shabu (Methamphetamine):', '55',($medExamReports['medExamReport_xrayEcgLab_marijuana'] ?? null));
        $pdf->rowCol4('L. Others:' , '55',($medExamReports['medExamReport_xrayEcgLab_other'] ?? null));
        $pdf->ln(5);

        $pdf->thFontStyle();
        $pdf->MultiCell(190, $lineHeight, 'RECOMMENDATIONS: ', 0);        
        $pdf->ln(0);
        $pdf->rowCol4('Ratings: ','30', ($medExamReports['medExamReport_recommendation_ratings_note'] ?? null) . '     ' . ($medExamReports['medExamReport_recommendation_ratings'] ?? null));
        $pdf->rowCol4('Remarks: ', '30',($medExamReports['medExamReport_recommendation_remarks'] ?? null));
        
        $pdf->ln(28);
        // $pdf->row('Examining Physician:', $medExamReports['medExamReport_recommendation_physicianName']);
        
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
        
        $pdf->Cell(50, 8, 'Date: ' . ($medExamReports['medExamReport_recommendation_date'] ?? null), '', 0, 'C');
        
        // $pdf->Output();
        // $conn->close();

        $pdf->ln(20);
        $pdf->row('Computer-generated report.', '', '');
        return $pdf;
    }
}

class MedFPDF extends FPDF {

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

    function rowCol4($title, $title_width, $value, $value_w = 0) {
        $border = 0;
        $lineHeight = 5;
        $toRight = 0;
        $align = 'L';
        $this->SetFont('Arial','', 8);
        $this->SetTextColor(83,99,113);
        $this->Cell($title_width, 5, $title , $border, $toRight, $align);

        $this->SetFont('Arial','B', 8);
        $this->SetTextColor(17, 24, 39);
        $this->MultiCell($value_w, $lineHeight, $value, $border, $align);
        $this->ln(1);
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


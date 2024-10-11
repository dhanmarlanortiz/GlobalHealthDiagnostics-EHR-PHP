<?php
class ClinicalChemistryPDF {
    function generateClinicalChemistry($conn, $id, $pdf = null) {
        $apeDetails = fetchApeDetailsById($conn, $id);
        $orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);
        $clinicalChemistry = getClinicalChemistry($id);

        $medtech1 = getProfessional($conn, $orgDetails['medtech1_fk']);
        $medtech1_id = $medtech1['prof_id'] ?? 0;
        $medtech1_name = $medtech1['prof_name'] ?? '';
        $medtech1_role = $medtech1['prof_role'] ?? '';
        $medtech1_license = $medtech1['prof_license'] ?? '';
        $medtech1_signature = base_url(false) . '/images/healthcare-professional-' . $medtech1_id  . '-signature.png';

        $pathologist = getProfessional($conn, $orgDetails['pathologist_fk']);
        $pathologist_id = $pathologist['prof_id'] ?? 0;
        $pathologist_name = $pathologist['prof_name'] ?? '';
        $pathologist_role = $pathologist['prof_role'] ?? '';
        $pathologist_license = $pathologist['prof_license'] ?? '';
        $pathologist_signature = base_url(false) . '/images/healthcare-professional-' . $pathologist_id  . '-signature.png';

        if(null !== $clinicalChemistry) {
            if(null == $pdf) {
                $pdf = new ClinicalChemistryFPDF();
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
            $pdf->Cell(0,4.5,'CLINICAL CHEMISTRY', 0, 1, 'L');

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
            $pdf->Cell(25, $lineHeight, $space.date('d-M-y', strtotime($clinicalChemistry['clinicchem_date'])).$space, $border, $toBegin);

            // Company
            $pdf->thFontStyle();
            $pdf->Cell(20, $lineHeight,' Company: ', $border, $toRight, 'L', true);
            $pdf->tdFontStyle();
            $pdf->MultiCell(170, $lineHeight, $space.$orgDetails['name'].$space, $border);

            $pdf->Cell(0, 1,'', 'T', $toBegin);

            $pdf->ln(5);
            

            $pdf->thFontStyle();
            $pdf->row('', '', 'Normal Values:');
            $pdf->ln();

            $pdf->row('FBS	:', $clinicalChemistry['clinicchem_fbs'], '60.7-111.3 mg/dl');
            $pdf->ln();
            $pdf->row('RBS:', $clinicalChemistry['clinicchem_rbs'], '80.0-140.0 mg/dl');
            $pdf->ln();
            $pdf->row('Blood Urea Nitrogen:', $clinicalChemistry['clinicchem_blood_urea_nitrogen'], '7.84-20.17 mg/dl');
            $pdf->ln();
            $pdf->row('Creatinine:', $clinicalChemistry['clinicchem_creatinine'], 'Male: 0.90-1.50 mg/dl');
            $pdf->ln();
            $pdf->row('', '', 'Female: 0.70-1.37 mg/dl');
            $pdf->ln();
            $pdf->row('Blood Uric Acid:', $clinicalChemistry['clinicchem_blood_uric_acid'], 'Male: 3.41-7.00 mg/dl');
            $pdf->ln();
            $pdf->row('', '', 'Female: 2.41-5.69 mg/dl');
            $pdf->ln();
            $pdf->row('Total Cholesterol:', $clinicalChemistry['clinicchem_total_cholesterol'], '139.6-238.8 mg/dl');
            $pdf->ln();
            $pdf->row('Triglycerides:', $clinicalChemistry['clinicchem_triglycerides'], '36.3-164.6 mg/dl');
            $pdf->ln();
            $pdf->row('HDL:', $clinicalChemistry['clinicchem_hdl'], '40.0-60.0 mg/dl');
            $pdf->ln();
            $pdf->row('LDL:', $clinicalChemistry['clinicchem_ldl'], '92.3-146.2 mg/dl');
            $pdf->ln();
            $pdf->row('VLDL:', $clinicalChemistry['clinicchem_vldl'], '7.2-32.5 mg/dl');
            $pdf->ln();
            $pdf->row('SGOT/AST:', $clinicalChemistry['clinicchem_sgot_ast'], '0-40 U/L');
            $pdf->ln();
            $pdf->row('SGPT/ALT:', $clinicalChemistry['clinicchem_sgpt_alt'], '0-38 U/L');
            $pdf->ln();
            
            $pdf->row('Hba1c:', $clinicalChemistry['clinicchem_hba1c'], '4.50-6.50 %');
            $pdf->ln();

            $pdf->row('PSA:', $clinicalChemistry['clinicchem_psa'], '0-4.00 ng/mL');
            $pdf->ln();
            $pdf->row('Other:', $clinicalChemistry['clinicchem_others'], '');

            $pdf->ln();
 
            $pdf->Cell(0, 1,'', '', $toBegin);
            
            $pdf->ln(25);

            if (isValidImageUrl($medtech1_signature)) {
                $pdf->Image($medtech1_signature, 25, $pdf->GetY() - 15);
            }
            
            $pdf->SetFont('Arial','B', 8);
            $pdf->SetTextColor(17, 24, 39);
            $pdf->SetDrawColor(83,99,113);
    
            $pdf->Cell(10, 8, '' , '', 0, 'L');
            $pdf->Cell(50, 8, $medtech1_name , 'B', 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial','', 8);
            $pdf->Cell(10, 8, '' , '', 0, 'L');
            $pdf->Cell(50, 8, $medtech1_role , '', 0, 'C');
            $pdf->ln(5);
            $pdf->Cell(10, 8, '' , '', 0, 'C');
    
            if($medtech1_license != '') {
                $pdf->Cell(50, 8, 'License No.: ' . $medtech1_license , '', 0, 'C');
                $pdf->ln(5);
                $pdf->Cell(10, 8, '' , '', 0, 'C');
            }


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

class ClinicalChemistryFPDF extends FPDF {
    
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
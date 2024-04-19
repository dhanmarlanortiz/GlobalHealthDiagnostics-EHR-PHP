<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

require_once('../connection.php');
require_once('../globals.php');
require('../fpdf/fpdf.php');
require('radiology-pdf.php');
require('medical-exam-pdf.php');
require('laboratory-pdf.php');

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
$radiologyPDF->generateRadiologyReport($conn, '210', $pdf);


// LABORATORY RESULT
$laboratoryPDF = new LaboratoryPDF();
$laboratoryPDF->generateLaboratoryReport($conn, '210', $pdf);    


// MEDICAL EXAM REPORT
$medicalExamPDF = new MedicalExamPDF();
$medicalExamPDF->generateMedicalExamReport($conn, '210', $pdf);

$pdf->Output(); 







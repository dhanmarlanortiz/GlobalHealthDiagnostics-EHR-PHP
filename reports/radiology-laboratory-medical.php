<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("report-config.php");
require("radiology-pdf.php");
include("laboratory-pdf.php");

$radiologyPDF = new RadiologyPDF();
$laboratoryPDF = new LaboratoryPDF();

$radiology = $radiologyPDF->generateRadiologyReport($conn, $id);
// $radiology->Output();
$laboratory = $laboratoryPDF->generateLaboratoryReport($conn, $id);
// $laboratory->Output();


// foreach($radiology as $k => $v) 
// $laboratory->$k = $v;
// $obj_merged = (object) array_merge((array) $radiology, (array) $laboratory);
// print_r($obj_merged);
// $obj_merged->Output();


print_r(array_merge($radiology,$laboratory));

// $pdf = $radiology . $laboratory;
// $radiology .= $laboratory;

// $pdf->Output();

// class Radiology_laboratory_medical extends FPDF {
//     public $pdf_str;
//     function __construct($pdf_str) {
//         $this->pdf_str = $pdf_str;
//     }

//     function pdf() {

//         $this->pdf_str->Output();
//         echo $this->pdf_str;

//     }
    
// }

// $rlm = new Radiology_laboratory_medical($radiology);
// $rlm->pdf();


// $fpdf = new FPDF();
// $fpdf->AddPage();
// $fpdf->SetFont('Arial','B',3);
// $fpdf->Cell(40,10,$radiologyPDF);
// $fpdf->Output();




// $pdfMerge = $pdf1 + $pdf2;

// $pdfMerge->Output();

// $merge = new FPDF_Merge();
// $merge->add($radiology);
// $merge->add($laboratory);
// $merge->Output();
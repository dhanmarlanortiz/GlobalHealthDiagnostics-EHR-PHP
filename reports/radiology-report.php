<?php

require('../fpdf/fpdf.php');

class RadiologyReportPDF extends FPDF
{
    function header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Radiology Report', 0, 1, 'C');
    }

    function chapterTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, $title, 0, 1, 'L');
        $this->Ln(4);
    }

    function chapterBody($body)
    {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 10, $body);
        $this->Ln();
    }

    function addTable()
    {
        // Add table with 3 columns
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(50, 10, 'Column 1', 1);
        $this->Cell(50, 10, 'Column 2', 1);
        $this->Ln();

        // Add 1st row data
        $this->SetFont('Arial', '', 12);
        $this->Cell(50, 10, 'Data 1', 1);
        $this->Cell(50, 10, 'Data 2', 1);
        $this->Ln();

        // Add 2nd row with 5 columns
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(25, 10, 'Col 1', 1);
        $this->Cell(25, 10, 'Col 2', 1);
        $this->Cell(25, 10, 'Col 3', 1);
        $this->Cell(25, 10, 'Col 4', 1);
        $this->Cell(50, 10, 'Col 5', 1);
        $this->Ln();

        // Add 2nd row data
        $this->SetFont('Arial', '', 12);
        $this->Cell(25, 10, 'Data 3', 1);
        $this->Cell(25, 10, 'Data 4', 1);
        $this->Cell(25, 10, 'Data 5', 1);
        $this->Cell(25, 10, 'Data 6', 1);
        $this->Cell(50, 10, 'Data 7', 1);
        $this->Ln();

        // Add 3rd row with 1 column
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(150, 10, 'Column 4', 1);
        $this->Ln();

        // Add 3rd row data
        $this->SetFont('Arial', '', 12);
        $this->Cell(150, 10, 'Data 8', 1);
        $this->Ln();

         // Add 4rd row with 1 column
         $this->SetFont('Arial', 'B', 12);
         $this->Cell(150, 10, 'Column 4', 1);
         $this->Ln();
 
         // Add 4rd row data
         $this->SetFont('Arial', '', 12);
         $this->Cell(150, 10, 'Data 8', 1);
         $this->Ln();
    }
}

// Sample data for the report
$patientName = "John Doe";
$studyDate = "2023-11-28";
$impression = "No significant abnormalities detected.";

// Create a PDF document
$pdf = new RadiologyReportPDF();
$pdf->AddPage();

// Add content to the PDF
$pdf->chapterTitle("Patient: $patientName");
$pdf->chapterBody("Study Date: $studyDate");
$pdf->chapterBody("Impression: $impression");

// Add a table
$pdf->addTable();

// Output the PDF directly to the browser
$pdf->Output();
?>

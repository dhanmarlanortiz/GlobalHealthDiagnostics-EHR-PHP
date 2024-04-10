<?php
include("report-config.php");
include("medical-exam-pdf.php");

$medicalPDF = new MedicalExamPDF();
$pdf = $medicalPDF->generateMedicalExamReport($conn, $id);

$pdf->Output();
$conn->close();
<?php
include("report-config.php");
include("ecg-pdf.php");

$ecgPDF = new EcgPDF();
$pdf = $ecgPDF->generateEcgDiagnosis($conn, $id);

$pdf->Output();
$conn->close();
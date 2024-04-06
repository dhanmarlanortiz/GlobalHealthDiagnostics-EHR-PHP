<?php
include("report-config.php");
include("radiology-pdf.php");

$radiologyPDF = new RadiologyPDF();
$pdf = $radiologyPDF->generateRadiologyReport($conn, $id);

$pdf->Output();
$conn->close();
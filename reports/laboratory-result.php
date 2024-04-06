<?php
include("report-config.php");
include("laboratory-pdf.php");

$laboratoryPDF = new LaboratoryPDF();
$pdf = $laboratoryPDF->generateLaboratoryReport($conn, $id);

$pdf->Output();
$conn->close();
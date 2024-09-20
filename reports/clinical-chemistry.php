<?php
include("report-config.php");
include("clinical-chemistry-pdf.php");

$clinicalChemistryPDF = new ClinicalChemistryPDF();
$pdf = $clinicalChemistryPDF->generateClinicalChemistry($conn, $id);

$pdf->Output();
$conn->close();
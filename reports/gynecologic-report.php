<?php
include("report-config.php");
include("gynecologic-report-pdf.php");

$gynecologicReportPDF = new GynecologicReportPDF();
$pdf = $gynecologicReportPDF->generateGynecologicReport($conn, $id);

$pdf->Output();
$conn->close();
<?php

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
ini_set('max_execution_time', '0'); // for infinite time of execution 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess();
// include('navbar.php');
require('fpdf/fpdf.php');
require('fpdf/fpdf_merge.php');
include("reports/radiology-pdf.php");
include("reports/laboratory-pdf.php");
include("reports/medical-exam-pdf.php");

$o = $y = $id = 0 ;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $o = clean(isset($_GET['o']) ? $_GET['o'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));
    $id = clean(isset($_GET['id']) ? $_GET['id'] : 0);
}

$orgDetailsResult = fetchOrgDetailsById($conn, $o);
$organizationName = (null !== $orgDetailsResult) ? $orgDetailsResult["name"] : "Not Found";

createMainHeader($organizationName, array("Home", "Organizations", $organizationName, "Annual Physical Examination", "Export Results"));

if($id != 0){
    $apeQuery = "SELECT * FROM APE WHERE organizationId = '$o' AND id = '$id'";
} else {
    $apeQuery = "SELECT * FROM APE WHERE organizationId = '$o'";
}

$apeResult = $conn->query($apeQuery);

if ($apeResult !== false && $apeResult->num_rows > 0) {
    
    // if result is many
    if($apeResult->num_rows > 1) {
        $zip = new ZipArchive();
        $zipFilename = "reports/combine-temp/" . $organizationName . " _ APE " . $y . ".zip";
        
        unlink($zipFilename);

        if ($zip->open($zipFilename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$zipFilename>\n");
        }
    }
    
    while($apeDetails = $apeResult->fetch_assoc()) {
        $id = $apeDetails['id'];
        $radiologyReport = getRadiologyReport($id);
        $laboratoryResult = getLaboratoryResult($id);
        $medExamReport = getMedExamReport($conn, $id);

        $merge = new FPDF_Merge();
        $folder = 'reports/combine-temp/';
        $prefix = $y . '-' . $o . '-' . $id;
        $radioFilename = $folder . $prefix . '-temp-radiology.pdf';
        $labFilename = $folder . $prefix . '-temp-laboratory.pdf';
        $medFilename = $folder . $prefix . '-temp-medical.pdf';
        $combinedFilename = $prefix . "-APE";
        $finalFilename = $apeDetails['controlNumber'] . " - " . $apeDetails['lastName'] . " " . $apeDetails['firstName'] . ".pdf";
        
        // get radiology report and add to merge
        if(null !== $radiologyReport) {
            $radiologyPDF = new RadiologyPDF();
            $radio = $radiologyPDF->generateRadiologyReport($conn, $id);
            $combinedFilename .= '-radiology';
            $radio->Output('F', $radioFilename);
            $merge->add($radioFilename);
        }

        // get laboratory report and add to merge
        if( null !== $laboratoryResult) {
            $laboratoryPDF = new LaboratoryPDF();
            $lab = $laboratoryPDF->generateLaboratoryReport($conn, $id);
            $combinedFilename .= '-laboratory';
            $lab->Output('F', $labFilename);
            $merge->add($labFilename);
        }

        // get medical report and add to merge
        if(null !== $medExamReport) {
            $medicalExamPDF = new MedicalExamPDF();
            $med = $medicalExamPDF->generateMedicalExamReport($conn, $id);
            $combinedFilename .= '-medical';
            $med->Output('F', $medFilename);
            $merge->add($medFilename);
        }

        // process merge and zip if many
        if($apeResult->num_rows > 1) {
            $merge->output($folder . $combinedFilename . '.pdf');

            $zip->addFile($folder . $combinedFilename . '.pdf');
            $zip->renameName($folder . $combinedFilename . '.pdf', $finalFilename);
        } else {
            // unlink($folder . $finalFilename); // remove old file
            $merge->output($folder . $finalFilename); // create new file
            
            $downloadPath = base_url(false) . "/" . $folder . $finalFilename;
            $redirectPath = base_url(false) . "/employee-APE.php?id=" . $id;
            echo "<script>
                var file_path = '$downloadPath';
                var a = document.createElement('A');
                a.href = file_path;
                a.download = file_path.substr(file_path.lastIndexOf('/') + 1);
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                
                window.location.href = '$redirectPath';

                </script>
            ";
        }

        // delete temp files
        array_map('unlink', glob($folder . $prefix . '-temp-*.pdf'));
    }
    
    if($apeResult->num_rows > 1) {
        $zip->close();
        array_map('unlink', glob($folder . $y . '-' . $o  . '-*.pdf'));
    
        header("Location: " . base_url(false) . "/" . $zipFilename);
    }
    
    exit;
}
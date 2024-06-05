<?php
$start_time = microtime(true);
ini_set('max_execution_time', '0'); // for infinite time of execution 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess();
include('navbar.php');
require('fpdf/fpdf.php');
// require('fpdf/fpdf_merge.php');
include("reports/radiology-pdf.php");
include("reports/laboratory-pdf.php");
include("reports/medical-exam-pdf.php");

$o = $y = $id = 0 ;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $o = clean(isset($_GET['o']) ? $_GET['o'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));
    $id = clean(isset($_GET['id']) ? $_GET['id'] : 0);
} else if($_SERVER["REQUEST_METHOD"] == "POST") {
    $o = clean(isset($_POST['o']) ? $_POST['o'] : 0);
    $y = clean(isset($_POST['y']) ? $_POST['y'] : date("Y"));
    $id = clean(isset($_POST['id']) ? $_POST['id'] : 0);
}

$orgDetailsResult = fetchOrgDetailsById($conn, $o);
$organizationName = (null !== $orgDetailsResult) ? $orgDetailsResult["name"] : "Not Found";

createMainHeader($organizationName, array("Home", "Organizations", $organizationName, "Annual Physical Examination", "Export Results (PDF)"), "Export Results (PDF)");

if($id != 0){
    $apeQuery = "SELECT * FROM APE WHERE organizationId = '$o' AND id = '$id'";
} else {
    $apeQuery = "SELECT * FROM APE WHERE organizationId = '$o'";
}


/* === */

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


/* === */

if(isset($_POST['generate'])) {
    $apeResult = $conn->query($apeQuery);

    if ($apeResult !== false && $apeResult->num_rows > 0) {
        $folder = 'reports/combine-temp/';
        $zipFilename = strtolower($organizationName);
        $zipFilename = str_replace("&amp;", "and", $zipFilename);
        $zipFilename = html_entity_decode($zipFilename);
        $zipFilename = preg_replace('/[^a-zA-Z0-9\s]/', '', $zipFilename);
        $zipFilename = str_replace(' ', '-', $zipFilename);
        $zipFilename = $folder . $zipFilename . "-APE-" . $y . ".zip";

        if(file_exists($zipFilename)){
            unlink($zipFilename);
        }

        $zip = new ZipArchive();
        
        if ($zip->open($zipFilename, ZipArchive::CREATE) === TRUE) {

            while($apeDetails = $apeResult->fetch_assoc()) {
                $id = $apeDetails['id'];
                
                $prefix = $y . '-' . $o . '-' . $id;
                $combinedFilename = $prefix . "-APE.pdf";
                
                $employeeNumber = "";
                if($apeDetails['employeeNumber'] !== '') {
                    $employeeNumber = " - " . $apeDetails['employeeNumber'];
                }
                
                $finalFilename = $apeDetails['controlNumber'] . " - " . $apeDetails['lastName'] . " " . $apeDetails['firstName'] .  $employeeNumber .  ".pdf";
        
                $pdf = new reportsFPDF();
    
                // // RADIOLOGY REPORT
                $radiologyPDF = new RadiologyPDF();
                $radiologyPDF->generateRadiologyReport($conn, $id, $pdf);
    
                // // LABORATORY RESULT
                $laboratoryPDF = new LaboratoryPDF();
                $laboratoryPDF->generateLaboratoryReport($conn, $id, $pdf);    
    
                // // MEDICAL EXAM REPORT
                $medicalExamPDF = new MedicalExamPDF();
                $medicalExamPDF->generateMedicalExamReport($conn, $id, $pdf);
    
                $pdf->Output('F', $folder . $combinedFilename); 
    
                $zip->addFile($folder . $combinedFilename);
                $zip->renameName($folder . $combinedFilename, $finalFilename);
        
            }

            $zip->close();
            array_map('unlink', glob($folder . $y . '-' . $o  . '-*.pdf'));
        }

        $url = base_url(false) . "/employeesExport-APE.php?o=" . $o . "&y=" . $y;
        header("Location: " . $url ."");
        exit();
    }
}

/* ZIP VARIABLES */
$folder = 'reports/combine-temp/';
$zipFilename = strtolower($organizationName);
$zipFilename = str_replace("&amp;", "and", $zipFilename);
$zipFilename = html_entity_decode($zipFilename);
$zipFilename = preg_replace('/[^a-zA-Z0-9\s]/', '', $zipFilename);
$zipFilename = str_replace(' ', '-', $zipFilename);
$zipFilename = $zipFilename. "-APE-" . $y . ".zip";
$zipPath = $folder. $zipFilename;

?>

<main class='<?php echo $classMainContainer; ?>'>
    <div class="bg-white p-2 md:p-4">
        <div class='p-1 overflow-auto'>
            
        </div>
        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="#6b7280" viewBox="0 0 384 512">
                        <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM160 240c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v48h48c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H224v48c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V352H112c-8.8 0-16-7.2-16-16V304c0-8.8 7.2-16 16-16h48V240z"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500">
                        <span class="font-semibold">
                            <?php
                                if(file_exists($zipPath)){
                                    echo $zipFilename;
                                    echo "<span class='text-xs text-gray-500 font-medium block text-center mt-2'>Generated: " . date ("F d Y H:i:s", filemtime($zipPath)) . "</span>";
                                } else {
                                    echo "Not Available";
                                }
                            ?>
                        </span>
                    </p>
                </div>
            </label>
        </div> 
    </div>

    <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
        <div class="flex sm:justify-between flex-col sm:flex-row">
            <div class="dataTables_year p-1 flex items-center">
                <p class="text-xs text-gray-500">
                    <?php 
                        // $diff = number_format(microtime(true) - $start_time, 2);
                        // echo floor($diff/60) . ":" . ($diff % 60) . "s"; 
                    ?>
                </p>
            </div>
            <div class="p-1">
                <a href="<?php echo base_url(false) . "/employees-APE.php?o=" . $o . "&y=" . $y;?>" class="btn btn-default btn-sm text-xs rounded normal-case h-9 w-full sm:w-auto mb-2 sm:mb-0">Back</a>

                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?o=" . $o . "&y=" . $y ;?>" class=" inline-block w-full sm:w-auto /prompt-confirm">
                    <button id="generate-result-button" type="submit" name='generate' class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto mb-2 sm:mb-0">Generate</button>
                    <input type="hidden" name="o" value="<?php echo $o; ?>">
                    <input type="hidden" name="y" value="<?php echo $y; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                <form>

                

                <?php 
                    if(file_exists($zipPath)){
                        echo "<a href='$zipPath' class='btn btn-primary btn-sm text-xs rounded normal-case h-9 w-full sm:w-auto mb-2 sm:mb-0' download>Download</a>";
                    }
                ?>
            </div>
        </div>
    </div>    
</main>

<script>
    $("#generate-result-button").on("click", function() {
    //     $(".prompt-button-yes").on("click", function() {
            $('.page-loader').show();
            $('.page-loader p').html("<br><br><br>Records are currently being generated...<br>The duration of this process will vary based on the records involved.<br><span id='ellapsed-time'>0</span>s");

            var ctr = 1;
            setInterval(() => {
                $('#ellapsed-time').text(ctr++);
            }, 1000);
    //         $('#prompConfirmModal').removeAttr('open');
    //     })
    });
    
</script>


<?php
  include('footer.php');
?>
<?php
ob_start();
session_start();

require_once('connection.php');
require_once 'FileUploader.php';
require_once 'FileDeleter.php';
include('header.php');
preventAccess();

/* ORGANIZATION MYSQL - START */
$orgQuery = "SELECT * FROM Organization";
$orgResult = $conn->query($orgQuery);
/* ORGANIZATION MYSQL - END  */

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$id = 0;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    $apeDetailsQuery = "SELECT * FROM APE WHERE id = $id";
    $apeDetailsResult = $conn->query($apeDetailsQuery);
    if ($apeDetailsResult !== false && $apeDetailsResult->num_rows > 0) {
        while($apeDetails = $apeDetailsResult->fetch_assoc()) {
            $_POST = $apeDetails;
        }
    }
}

/* HANDLES UPLOAD AND DELETE FILE - START  */
$uploadDir = 'uploads/';
$fileUploader = new FileUploader($uploadDir, $conn);
$fileDeleter = new FileDeleter($uploadDir, $conn);
/* HANDLES UPLOAD AND DELETE FILE - END  */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_GET['id'];
    $headCount = test_input( $_POST['headCount'] );
    $controlNumber = test_input( $_POST['controlNumber'] );
    // $controlDate = test_input( $_POST['controlDate'] );
    $firstName = test_input( $_POST['firstName'] );
    $middleName = test_input( $_POST['middleName'] );
    $lastName = test_input( $_POST['lastName'] );
    $age = test_input( $_POST['age'] );
    $sex = test_input( $_POST['sex'] );
    $civilStatus = test_input( $_POST['civilStatus'] );
    $homeAddress = test_input( $_POST['homeAddress'] );
    $organizationId = test_input( $_POST['organizationId'] );
    $employeeNumber = test_input( $_POST['employeeNumber'] );
    $remarks = test_input( $_POST['remarks'] );
    $dateRegistered = test_input( $_POST['dateRegistered'] );

    if (isset($_POST['updateDetailsForm'])) {
        $apeUpdateQuery =  "UPDATE APE SET
                            headCount = $headCount,
                            " . (($_POST['controlNumber'] > 0) ? "controlNumber = '$_POST[controlNumber]'," : '') . "
                            firstName = '$firstName',
                            middleName = '$middleName',
                            lastName = '$lastName',
                            age = $age,
                            sex = '$sex',
                            civilStatus = '$civilStatus',
                            homeAddress = '$homeAddress',
                            organizationId = $organizationId,
                            employeeNumber = '$employeeNumber',
                            remarks = '$remarks'
                            WHERE id = $id";

        if ($conn->query($apeUpdateQuery) === TRUE) {
            create_flash_message('update-success', '<strong>Update Successful!</strong> User has been successfully updated.', FLASH_SUCCESS);

            $url = base_url(false) . "/employee-APE.php?id=" . $id;

            header("Location: " . $url ."");
            exit();
        } else {
            create_flash_message('update-error', '<strong>Update Failed!</strong> Please review and try again.', FLASH_ERROR);
            echo $conn->error;
        }
    }

    if (isset($_POST['uploadFileForm'])) {
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'csv');
        $medicalExaminationFK = $_POST['medicalExaminationFK'];

        $result = $fileUploader->uploadFile('uploadedFile', $medicalExaminationFK, $allowedTypes);

        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['deleteRecord'])) {
        try {
            $deleteQuery = "DELETE FROM APE WHERE id = $id";
    
            if ($conn->query($deleteQuery) === TRUE) {
                create_flash_message('delete-success', $flashMessage['delete-success'], FLASH_SUCCESS);
    
                $url = base_url(false) . "/employees-APE.php?o=" . $organizationId . "&y=" . date("Y", strtotime($dateRegistered));
                header("Location: " . $url);
                exit();
            } else {
                throw new Exception($conn->error);
            }
        } catch (Exception $e) {
            create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);
            if ($conn->errno == 1451) {
                create_flash_message('delete-failed', $flashMessage['delete-failed-linked'], FLASH_ERROR);
            }
    
            $url = base_url(false) . "/employee-APE.php?id=" . $id;
            header("Location: " . $url);
            exit();
        }
    }   

    if (isset($_POST['generateControlNumber'])) {
        getControlNumberAPE($id, $organizationId, $dateRegistered);

        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['deleteRadiologyReport'])) {
        $stmt = $conn->prepare("DELETE FROM RadiologyReport WHERE APEFK = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            create_flash_message('delete-success', $flashMessage['delete-success'], FLASH_SUCCESS);

        } else {
            create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);

            if($conn->errno == 1451) {
                create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
            }
        }

        $url = base_url(false) . "/employee-APE.php?id=" . $id;
        header("Location: " . $url ."");

        $stmt->close();
        exit();
    }

    if (isset($_POST['deleteLaboratoryResult'])) {
        $stmt = $conn->prepare("DELETE FROM LaboratoryResult WHERE labRes_APE_FK = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            create_flash_message('delete-success', $flashMessage['delete-success'], FLASH_SUCCESS);

        } else {
            create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);

            if($conn->errno == 1451) {
                create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
            }
        }

        $url = base_url(false) . "/employee-APE.php?id=" . $id;
        header("Location: " . $url ."");

        $stmt->close();
        exit();
    }

    if (isset($_POST['deleteMedExamReport'])) {
        $medExamReport_family_stmt = $conn->prepare("DELETE FROM medExamReport_family WHERE medExamReport_family_ape_fk = ?");
        $medExamReport_family_stmt->bind_param("i", $id);
        $medExamReport_family_stmt->execute();
        $medExamReport_family_stmt->close();

        $medExamReport_history_stmt = $conn->prepare("DELETE FROM medExamReport_history WHERE medExamReport_history_ape_fk = ?");
        $medExamReport_history_stmt->bind_param("i", $id);
        $medExamReport_history_stmt->execute();
        $medExamReport_history_stmt->close();

        $medExamReport_personalSocial_stmt = $conn->prepare("DELETE FROM medExamReport_personalSocial WHERE medExamReport_personalSocial_ape_fk = ?");
        $medExamReport_personalSocial_stmt->bind_param("i", $id);
        $medExamReport_personalSocial_stmt->execute();
        $medExamReport_personalSocial_stmt->close();

        $medExamReport_physical_stmt = $conn->prepare("DELETE FROM medExamReport_physical WHERE medExamReport_physical_ape_fk = ?");
        $medExamReport_physical_stmt->bind_param("i", $id);
        $medExamReport_physical_stmt->execute();
        $medExamReport_physical_stmt->close();

        $medExamReport_recommendation_stmt = $conn->prepare("DELETE FROM medExamReport_recommendation WHERE medExamReport_recommendation_ape_fk = ?");
        $medExamReport_recommendation_stmt->bind_param("i", $id);
        $medExamReport_recommendation_stmt->execute();
        $medExamReport_recommendation_stmt->close();

        $medExamReport_system_stmt = $conn->prepare("DELETE FROM medExamReport_system WHERE medExamReport_system_ape_fk = ?");
        $medExamReport_system_stmt->bind_param("i", $id);
        $medExamReport_system_stmt->execute();
        $medExamReport_system_stmt->close();

        $medExamReport_xrayEcgLab_stmt = $conn->prepare("DELETE FROM medExamReport_xrayEcgLab WHERE medExamReport_xrayEcgLab_ape_fk = ?");
        $medExamReport_xrayEcgLab_stmt->bind_param("i", $id);
        $medExamReport_xrayEcgLab_stmt->execute();
        $medExamReport_xrayEcgLab_stmt->close();

        $url = base_url(false) . "/employee-APE.php?id=" . $id;
        header("Location: " . $url ."");

        $stmt->close();
        exit();
    }

    if (isset($_POST['deleteEcgDiagnosis'])) {
        $stmt = $conn->prepare("DELETE FROM ecgdiagnosis WHERE ecgdiag_APE_FK = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            create_flash_message('delete-success', $flashMessage['delete-success'], FLASH_SUCCESS);

        } else {
            create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);

            if($conn->errno == 1451) {
                create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
            }
        }

        $url = base_url(false) . "/employee-APE.php?id=" . $id;
        header("Location: " . $url ."");

        $stmt->close();
        exit();
    }

    if (isset($_POST['deleteClinicalChemistry'])) {
        $stmt = $conn->prepare("DELETE FROM clinicalchemistry WHERE clinicchem_APE_FK = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            create_flash_message('delete-success', $flashMessage['delete-success'], FLASH_SUCCESS);

        } else {
            create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);

            if($conn->errno == 1451) {
                create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
            }
        }

        $url = base_url(false) . "/employee-APE.php?id=" . $id;
        header("Location: " . $url ."");

        $stmt->close();
        exit();
    }


    
}

$organizationDetail = getOrganization($_POST['organizationId']);
$o = $_POST['organizationId'];
$y = date('Y', strtotime($_POST['dateRegistered']));

$radiologyReport = getRadiologyReport($id);
include("components/radiologyReportModal.php");

$laboratoryResult = getLaboratoryResult($id);
include("components/laboratoryResultModal.php");

$medExamReport = getMedExamReport($conn, $id);
include("components/medExamReportModal.php");

$ecgDiagnosis = getEcgDiagnosis($id);
include("components/ecgDiagnosisModal.php");

$clinicalChemistry = getClinicalChemistry($id);
include("components/clinicalChemistryModal.php");

$conn->close();

$role = $_SESSION['role'];
if($role == 1) {
    include('navbar.php');
} else if($role == 2) {
    include('client/navbar.php');
} else if($role == 3) {
    include('manager/navbar.php');
}

if( $role != 1 && $_SESSION['organizationId'] != $o ) {
    $url = base_url(false) . "/page-not-found.php";
    header("Location: " . $url);
}
?>

<form id="employee-APE-form" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <?php
        if($role == 1) {
            createMainHeader($organizationDetail['name'], array("Home", "Organizations", $organizationDetail['name'], "Annual Physical Examination", "Information"));
        } else if($role == 2) {
            createMainHeader($organizationDetail['name'], array("Annual Physical Examination", "Information Sheet"));
        } else if($role == 3) {
            createMainHeader($organizationDetail['name'], array("Annual Physical Examination", "Information Sheet"));
        }        
    ?>

    <main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
        <div class="mx-auto max-w-3xl">
            <?php createFormHeader("Information"); ?>
            <div class="flex items-center justify-end gap-x-6 bg-white px-3 sm:px-6 py-10 border-b mb-4">
                <div class="space-y-12 w-full">
                    <?php if($role == '1' || $role == '3') { ?>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                            
                                <div class="col-span-1">
                                    <input type="number" id="headCount" data-label="Head Count" readonly min="1" step="1"  />
                                </div>
                                <div class="col-span-1">
                                    <input class="tes" type="number" id="controlNumber" data-label="Control Number" readonly min="1" step="1" placeholder="Not Available" />
                                    <?php
                                    if( ! isset($_POST['controlNumber']) ) {
                                        // echo "<p class='text-xs mt-1 text-gray-600 whitespace-nowrap'><span class='font-medium'>Generated: </span><input type='date' id='controlDate' data-label='Control Date' readonly /></p>";
                                        echo '<button type="submit" name="generateControlNumber" class="'.$classBtnDefault.' w-full font-normal" style="height:38px;transform:translateY(-38px);">Generate</button>';
                                    }
                                    ?>
                                </div>                            
                        </div>
                    <?php } ?>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                        <div class="col-span-1">
                            <input type="text" id="firstName" data-label="First Name" required />
                        </div>
                        <div class="col-span-1">
                            <input type="text" id="middleName" data-label="Middle Name" />
                        </div>
                        <div class="col-span-1">
                            <input type="text" id="lastName" data-label="Last Name" required />
                        </div>
                        <div class="col-span-1">
                            <input type="number" id="age" data-label="Age" min="1" step="1" />
                        </div>
                        <div class="col-span-1">
                            <select id="sex" data-label="Sex" required>
                                <option value="" selected disabled>Select</option>
                                <option value="Male"
                                    <?php echo (
                                        (isset($_POST['sex']))
                                        ? ( ($_POST['sex'] == 'Male') ? 'selected' : '')
                                        : ''
                                    ) ?>
                                >Male</option>
                                <option value="Female"
                                    <?php echo (
                                        (isset($_POST['sex']))
                                        ? ( ($_POST['sex'] == 'Female') ? 'selected' : '')
                                        : ''
                                    ) ?>
                                >Female</option>
                            </select>
                        </div>
                        <div class="sm:col-span-1">
                            <input type="text" id="civilStatus" data-label="Civil Status" />
                        </div>
                        <div class="sm:col-span-2">
                            <input type="text" id="homeAddress" data-label="Home Address" />
                        </div>
                        <div class="sm:col-span-1">
                            <input type="text" id="employeeNumber" data-label="Employee Number" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                        <div class="sm:col-span-3">
                            <input type="text" id="remarks" data-label="Remarks" />
                        </div>
                    </div>
                </div>
            </div>

            <?php createFormHeader("Results"); ?>
            <div class="flex items-center justify-end gap-x-6 bg-white px-3 pb-4 sm:px-6 border-b mb-4">
                <div class="space-y-12 w-full">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8">
                        <div class="col-span-1">
                        <?php
                            echo '<ul role="list" class="divide-y divide-gray-100">';

                            @include 'components/radiologyReportMenu.php'; // Radiology Report
                            @include 'components/laboratoryResultMenu.php'; // Laboratory Result
                            @include 'components/medExamReportMenu.php'; // Medical Examination Report
                            @include 'components/ecgDiagnosisMenu.php'; // ECG Diagnosis
                            @include 'components/clinicalChemistryMenu.php'; // Clinical Chemistry
                            @include 'components/uploadedResults.php'; // Uploaded

                            echo "</ul>";
                        ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto rounded-b-box rounded-b-box">
                <div class="flex items-center justify-end flex-wrap flex-col sm:flex-row bg-white mt-0 px-2 sm:px-5 py-2 sm:py-5">
                    <input type="hidden" id="organizationId">
                    <input type="hidden" id="dateRegistered">
                    <?php if($role == 1 || $role == 3) { ?>
                        <div class="w-full sm:w-1/2 p-1">
                            <a href="<?php echo base_url(false) . '/employees-APE.php?o=' . $o . '&y=' . $y; ?>" class="w-full <?php echo $classBtnDefault; ?>">Back</a>
                        </div>
                        <div class="w-full sm:w-1/2 p-1">
                            <button type="submit" name="updateDetailsForm" class="w-full <?php echo $classBtnPrimary; ?>">Save Changes</button>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/4 p-1">
                            <button type="button" id="print-patient-button" class="w-full btn btn-sm text-xs rounded normal-case h-9 bg-purple-500 hover:bg-purple-600 border-purple-500 hover:border-purple-600 text-white">Print Patient Info</button>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/4 p-1">
                            <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="w-full <?php echo $classBtnAlternate; ?>" type="button">Upload Result</button>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/4 p-1">
                            <a href="<?php echo base_url(false) . '/employeeExport-APE.php?id=' . $id; ?>" class="w-full <?php echo $classBtnSecondary; ?>" type="button">Export Results</a>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/4 p-1">
                            <input type="submit" name="deleteRecord" value="Delete Record" class="w-full <?php echo $classBtnDanger; ?>">
                        </div>
                    <?php } else if($role == 2) {  ?>
                        <div class="w-full sm:w-auto p-1">
                            <a href="<?php echo base_url(false) . '/client'; ?>" class="w-full <?php echo $classBtnDefault; ?>">Back</a>
                        </div>
                        <div class="w-full sm:w-auto p-1">
                            <a href="<?php echo base_url(false) . '/employeeExport-APE.php?id=' . $id; ?>" class="w-full <?php echo $classBtnSecondary; ?>" type="button">Export Results</a>
                        </div>
                    <?php } ?>
                </div>

                <?php
                    flash('generate-control-number-success');
                    flash('generate-control-number-error');
                    flash('update-success');
                    flash('update-error');
                    flash('upload-success');
                    flash('upload-error');
                    flash('delete-success');
                    flash('delete-failed');
                    flash('create-success');
                    flash('create-failed');
                ?>
            </div>
        </div>
    </main>

    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 flex items-center justify-center overflow-y-auto overflow-x-hidden" style="background: rgba(0,0,0,0.5);">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white shadow">
                <div class="flex items-center justify-between p-4 border-b-2 border-sky-500">
                    <h3 class="font-medium text-sky-500 text-sm">Upload Result</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <div>
                        <select id="medicalExaminationFK" data-label="Examination">
                            <?php
                                $medicalExamination = getMedicalExamination();
                                foreach ($medicalExamination as $me) {
                                    echo "<option value='" . $me['id'] . "'>" . $me['name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Select File</label>
                        <label for="uploadedFile" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p id="file-name" class="text-xs text-gray-500"></p>
                            </div>
                            <input id="uploadedFile" type="file" name="uploadedFile" class="hidden" onchange="displayConfirmation()" />
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-sky-500">
                    <button data-modal-hide="default-modal" type="button" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Close</button>
                    <input type="submit" name="uploadFileForm" value="Upload File" class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto">
                </div>
            </div>
        </div>
    </div>

    <script>
        const modalToggleButtons = document.querySelectorAll('[data-modal-toggle]');
        const modalHideButtons = document.querySelectorAll('[data-modal-hide]');
        const modalOverlay = document.getElementById('default-modal');

        modalToggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                modalOverlay.classList.toggle('hidden');
            });
        });

        modalHideButtons.forEach(button => {
            button.addEventListener('click', () => {
                modalOverlay.classList.add('hidden');
            });
        });
    </script>

</form>

<style>
@media screen {
    body #print-patient-info {
        display: none;
    }
}

@media print {
    body {
        width: 800px;
        height: 1000px;
        margin: 0 auto; 
    }

    body > * {
        display: none !important;
    }

    body #print-patient-info {
        display: inline-block !important;
        width: 400px;
        float: left;
    }
    
    body #print-patient-info p {
        line-height: 1em;
    }

    body #print-patient-info p span {
        color: rgb(17, 24, 39);
        font-size: 14px;
        font-weight: 600;
    }
    body #print-patient-info p strong {
        display: block;
        font-size: 10px;
        font-weight: 500;
        color: rgb(107, 114, 128);
        margin-bottom: 5px;
    }
}
</style>

<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST); ?>;
        var role = <?php echo $role; ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=date], select:not([name^=DataTables])').each( function() {
            if( $(this).attr('html-transform') != 'false' ) {
                let id = $(this).attr('id');

                $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
                $(this).wrap(`<div class='mt-2'></div>`);
                $(this).attr('class', styleInput);
                $(this).attr('name', id);
            }

            if(role == 2) {
                $(this).prop("disabled", true)
            }
        });

        if(Object.keys(post).length !== 0) {
            $('input').each( function(key) {
                let id = $(this).attr('id');
                let type = $(this).attr('type');

                if(type != 'radio') {
                    $(this).attr('value', post[id]);
                    $(this).attr('name', id);
                }
            });
        }
    });

    function displayConfirmation() {
        var input = document.getElementById('uploadedFile');

        document.getElementById('file-name').innerHTML = ((input.files.length > 0)
            ? input.files[0].name
            : ''
        );
    }

    function printSection(sectionId) {
    var printContents = document.getElementById(sectionId).innerHTML;
    var originalContents = document.body.innerHTML;

    // document.body.innerHTML = printContents;
    document.body.classList.add('print-active');

    window.print();

    // document.body.innerHTML = originalContents;
    document.body.classList.remove('print-active');

    // Reload the page to restore the original state
    // location.reload();
}

    // Listen for changes in the print media state
    var mediaQueryList = window.matchMedia('print');

    mediaQueryList.addListener(function(mql) {
        if (mql.matches) {
            // The print dialog is open
            document.body.classList.add('print-active');
        } else {
            // The print dialog is closed
            document.body.classList.remove('print-active');
        }
    });

    document.getElementById('print-patient-button').addEventListener('click', function() {
        printSection('print-patient-info');
    });

    $(document).ready( function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = mm + '/' + dd + '/' + yyyy;
        $("#ppi-controlNumber").text( $("#controlNumber" ).val() );
        $("#ppi-date").text( today );
        $("#ppi-firstName").text( $("#firstName" ).val() );
        $("#ppi-middleName").text( $("#middleName" ).val() );
        $("#ppi-lastName").text( $("#lastName" ).val() );
        $("#ppi-age").text( $("#age" ).val() );
        $("#ppi-sex").text( $("#sex" ).val() );
        $("#ppi-civilStatus").text( $("#civilStatus" ).val() );
        $("#ppi-homeAddress").text( $("#homeAddress" ).val() );
        $("#ppi-company").text( $("h1 span" ).text() );
        $("#ppi-employeeNumber").text( $("#employeeNumber" ).val() );
    });
    
</script>

<?php
include('footer.php')
?>

<!-- PATIENT INFO PRINT SLIP - START -->
<div id="print-patient-info" class="p-1">
    <div class="flex border border-b-0 p-1">
        <p class="w-1/2">
            <strong>Control Number:</strong>
            <span id="ppi-controlNumber"></span>
        </p>
        <p class="w-1/2">
            <strong>Date:</strong>
            <span id="ppi-date"></span>
        </p>
    </div>
    <div class="flex border border-b-0 p-1 flex-grow flex-wrap gap-x-5 gap-y-2">
        <p class="">
            <strong>First Name</strong>
            <span id="ppi-firstName"></span>
        </p>
        <p class="">
            <strong>Middle Name</strong>
            <span id="ppi-middleName"></span>
        </p>
        <p class="">
            <strong>Last Name</strong>
            <span id="ppi-lastName"></span>
        </p>
    </div>
    <div class="flex border border-b-0 p-1">
        <p class="w-1/3 pr-5">
            <strong>Age</strong>
            <span id="ppi-age"></span>
        </p>
        <p class="w-1/3 pr-5">
            <strong>Sex</strong>
            <span id="ppi-sex"></span>
        </p>
        <p class="w-1/3">
            <strong>Civil Status</strong>
            <span id="ppi-civilStatus"></span>
        </p>
    </div>
    <div class="flex border border-b-0 p-1">
        <p class="w-full">
            <strong>Home Address</strong>
            <span id="ppi-homeAddress"></span>
        </p>
    </div>
    <div class="flex border p-1">
        <p class="w-1/2 pr-5">
            <strong>Company Name</strong>
            <span id="ppi-company"></span>
        </p>
        <p class="w-1/2">
            <strong>Employee Number</strong>
            <span id="ppi-employeeNumber"></span>
        </p>
    </div>
</div>
<!-- PATIENT INFO PRINT SLIP - END -->


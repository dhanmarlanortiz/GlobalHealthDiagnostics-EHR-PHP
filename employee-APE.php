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
        $deleteQuery =  "DELETE FROM APE WHERE id = $id";

        if ($conn->query($deleteQuery) === TRUE) {
            create_flash_message('delete-success', $flashMessage['delete-success'] , FLASH_SUCCESS);

            $url = base_url(false) . "/employees-APE.php?o=" . $organizationId . "&y=" . date("Y", strtotime($dateRegistered));
            header("Location: " . $url ."");

            exit();
        } else {
            create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);

            if($conn->errno == 1451) {
                create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
            }

            $url = base_url(false) . "/employee-APE.php?id=" . $id;
            header("Location: " . $url ."");
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

$conn->close();

if($_SESSION['role'] == 1) {
    include('navbar.php');
} else if($_SESSION['role'] == 2) {
    include('client/navbar.php');
}

if( $_SESSION['role'] != 1 && $_SESSION['organizationId'] != $o ) {
    $url = base_url(false) . "/page-not-found.php";
    header("Location: " . $url);
}
?>

<form id="employee-APE-form" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <?php
        if($_SESSION['role'] == 1) {
            createMainHeader($organizationDetail['name'], array("Home", "Organizations", $organizationDetail['name'], "Annual Physical Examination", "Information"));
        } else if($_SESSION['role'] == 2) {
            createMainHeader($organizationDetail['name'], array("Annual Physical Examination", "Information Sheet"));
        }
    ?>

    <main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
        <div class="mx-auto max-w-3xl">
            <?php createFormHeader("Information"); ?>
            <div class="flex items-center justify-end gap-x-6 bg-white px-3 sm:px-6 py-10 border-b mb-4">
                <div class="space-y-12 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                        <?php if($_SESSION['role'] == '1') { ?>
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
                        <?php } ?>
                    </div>
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

                            // Radiology Report
                            echo '<li class="flex flex-col sm:flex-row justify-between gap-x-6 py-5">
                                    <div class="flex min-w-0 gap-x-4">
                                        <div class="min-w-0 flex-auto">
                                            <p class="text-sm font-semibold leading-6 text-gray-900">Radiology Report</p>
                                            <p class="text-xs leading-5 text-gray-500 flex gap-x-4">';
                                                if(($radiologyReport)) {
                                                    echo '<span class="flex gap-x-1 items-center">';
                                                    echo    '<svg xmlns="http://www.w3.org/2000/svg" fill="rgb(107, 114, 128)" height="12" width="12" viewBox="0 0 448 512"><path d="M96 32V64H48C21.5 64 0 85.5 0 112v48H448V112c0-26.5-21.5-48-48-48H352V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V64H160V32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192H0V464c0 26.5 21.5 48 48 48H400c26.5 0 48-21.5 48-48V192z"/></svg>';
                                                    echo    date("M d, Y", strtotime($radiologyReport['dateCreated']));
                                                    echo '</span>';

                                                    echo '<span class="flex gap-x-1 items-center">';
                                                    echo    '<svg xmlns="http://www.w3.org/2000/svg" fill="rgb(107, 114, 128)" height="12" width="12" viewBox="0 0 448 512"><path d="M181.3 32.4c17.4 2.9 29.2 19.4 26.3 36.8L197.8 128h95.1l11.5-69.3c2.9-17.4 19.4-29.2 36.8-26.3s29.2 19.4 26.3 36.8L357.8 128H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H347.1L325.8 320H384c17.7 0 32 14.3 32 32s-14.3 32-32 32H315.1l-11.5 69.3c-2.9 17.4-19.4 29.2-36.8 26.3s-29.2-19.4-26.3-36.8l9.8-58.7H155.1l-11.5 69.3c-2.9 17.4-19.4 29.2-36.8 26.3s-29.2-19.4-26.3-36.8L90.2 384H32c-17.7 0-32-14.3-32-32s14.3-32 32-32h68.9l21.3-128H64c-17.7 0-32-14.3-32-32s14.3-32 32-32h68.9l11.5-69.3c2.9-17.4 19.4-29.2 36.8-26.3zM187.1 192L165.8 320h95.1l21.3-128H187.1z"/></svg>';
                                                    echo    $radiologyReport['caseNumber'];
                                                    echo '</span>';
                                                } else {
                                                    echo 'Not Available';
                                                }
                            echo            '</p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 mt-2 sm:mt-0 sm:flex sm:items-end">';
                                        if(($radiologyReport)) {
                                            $radiologyReportData = [
                                                'href' => '/reports/radiology-report.php?id=' . $id,
                                                'organizationId' => $o,
                                                'firstName' => $_POST['firstName'],
                                                'middleName' => $_POST['middleName'],
                                                'lastName' => $_POST['lastName']
                                            ];
                                            $encodeRadiologyReport = base64_encode(json_encode( $radiologyReportData ));

                                            echo "<a href='" . base_url(false) . "/employeeViewRadiologyReport-APE.php?q=$encodeRadiologyReport' title='View' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z'/></svg>
                                                </a>";
                                            echo "<a href='" . base_url(false) . "/reports/radiology-report.php?id=" . $id . "' title='Download' class='btn btn-sm ml-1 bg-sky-400 hover:bg-sky-500' download>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'/></svg>
                                                </a>";

                                            if($_SESSION['role'] == 1) {
                                                echo "<button type='button' onClick='radiologyReportModal.showModal()' title='Edit' class='btn btn-sm ml-1 bg-amber-500 hover:bg-amber-600 text-white'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z'/></svg>
                                                    </button>";
                                                echo "<button type='submit' name='deleteRadiologyReport' title='delete' class='btn btn-sm ml-1 bg-red-500 hover:bg-red-600 text-white'>
                                                    <span class='sr-only'>Delete Radiology Report</span>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg>
                                                </button>";
                                            }
                                        } else {
                                            if($_SESSION['role'] == 1) {
                                                echo "<button type='button' onClick='radiologyReportModal.showModal()' title='Create' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='14' width='12' viewBox='0 0 448 512'><path d='M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z'/></svg>
                                                    </button>";
                                            }
                                        }
                                echo '</div>';
                            echo "</li>";

                            // Laboratory Result
                            echo '<li class="flex flex-col sm:flex-row justify-between gap-x-6 py-5">
                                    <div class="flex min-w-0 gap-x-4">
                                        <div class="min-w-0 flex-auto">
                                            <p class="text-sm font-semibold leading-6 text-gray-900">Laboratory Result</p>
                                            <p class="text-xs leading-5 text-gray-500 flex gap-x-4">';
                                                if(($laboratoryResult)) {
                                                    echo '<span class="flex gap-x-1 items-center">';
                                                    echo    '<svg xmlns="http://www.w3.org/2000/svg" fill="rgb(107, 114, 128)" height="12" width="12" viewBox="0 0 448 512"><path d="M96 32V64H48C21.5 64 0 85.5 0 112v48H448V112c0-26.5-21.5-48-48-48H352V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V64H160V32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192H0V464c0 26.5 21.5 48 48 48H400c26.5 0 48-21.5 48-48V192z"/></svg>';
                                                    echo    date("M d, Y", strtotime($laboratoryResult['labRes_date']));
                                                    echo '</span>';
                                                } else {
                                                    echo 'Not Available';
                                                }
                            echo            '</p>';
                            echo '      </div>
                                    </div>
                                    <div class="shrink-0 mt-2 sm:mt-0 sm:flex sm:items-end">';
                                        if(($laboratoryResult)) {
                                            $laboratoryResultData = [
                                                'href' => '/reports/laboratory-result.php?id=' . $id,
                                                'organizationId' => $o,
                                                'firstName' => $_POST['firstName'],
                                                'middleName' => $_POST['middleName'],
                                                'lastName' => $_POST['lastName']
                                            ];
                                            $encodeLaboratoryResult = base64_encode(json_encode( $laboratoryResultData ));

                                            echo "<a href='" . base_url(false) . "/employeeViewLaboratoryResult-APE.php?q=$encodeLaboratoryResult' title='View' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z'/></svg>
                                                </a>";
                                            echo "<a href='" . base_url(false) . "/reports/laboratory-result.php?id=" . $id . "' title='Download' class='btn btn-sm ml-1 bg-sky-400 hover:bg-sky-500' download>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'/></svg>
                                                </a>";

                                            if($_SESSION['role'] == 1) {
                                                echo "<button type='button' onClick='laboratoryResultModal.showModal()' title='Edit' class='btn btn-sm ml-1 bg-amber-500 hover:bg-amber-600 text-white'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z'/></svg>
                                                    </button>";
                                                echo "<button type='submit' name='deleteLaboratoryResult' title='delete' class='btn btn-sm ml-1 bg-red-500 hover:bg-red-600 text-white'>
                                                    <span class='sr-only'>Delete Laboratory Result</span>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg>
                                                </button>";
                                            }
                                        } else {
                                            if($_SESSION['role'] == 1) {
                                                echo "<button type='button' onClick='laboratoryResultModal.showModal()' title='Create' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='14' width='12' viewBox='0 0 448 512'><path d='M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z'/></svg>
                                                    </button>";
                                            }
                                        }
                            echo   '</div>';
                            echo "</li>";

                            // Medical Examination Report
                            echo '<li class="flex flex-col sm:flex-row justify-between gap-x-6 py-5">
                                    <div class="flex min-w-0 gap-x-4">
                                        <div class="min-w-0 flex-auto">
                                            <p class="text-sm font-semibold leading-6 text-gray-900">Medical Examination Report</p>
                                            <p class="text-xs leading-5 text-gray-500 flex gap-x-4">';
                                                if(($medExamReport)) {
                                                    echo '<span class="flex gap-x-1 items-center">';
                                                    echo    '<svg xmlns="http://www.w3.org/2000/svg" fill="rgb(107, 114, 128)" height="12" width="12" viewBox="0 0 448 512"><path d="M96 32V64H48C21.5 64 0 85.5 0 112v48H448V112c0-26.5-21.5-48-48-48H352V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V64H160V32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192H0V464c0 26.5 21.5 48 48 48H400c26.5 0 48-21.5 48-48V192z"/></svg>';
                                                    echo    date("M d, Y", strtotime($medExamReport['medExamReport_recommendation_date']));
                                                    echo '</span>';
                                                } else {
                                                    echo 'Not Available';
                                                }
                            echo            '</p>';
                            echo '      </div>
                                    </div>
                                    <div class="shrink-0 mt-2 sm:mt-0 sm:flex sm:items-end">';
                                        if(($medExamReport)) {
                                            $medExamReportData = [
                                                'href' => '/reports/medical-exam-report.php?id=' . $id,
                                                'organizationId' => $o,
                                                'firstName' => $_POST['firstName'],
                                                'middleName' => $_POST['middleName'],
                                                'lastName' => $_POST['lastName']
                                            ];
                                            $encodeMedExamReport = base64_encode(json_encode( $medExamReportData ));

                                            echo "<a href='" . base_url(false) . "/employeeViewMedExamReport-APE.php?q=$encodeMedExamReport' title='View' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z'/></svg>
                                                </a>";
                                            echo "<a href='" . base_url(false) . "/reports/medical-exam-report.php?id=" . $id . "' title='Download' class='btn btn-sm ml-1 bg-sky-400 hover:bg-sky-500' download>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'/></svg>
                                                </a>";

                                            if($_SESSION['role'] == 1) {
                                                echo "<button type='button' onClick='medExamReportModal.showModal()' title='Edit' class='btn btn-sm ml-1 bg-amber-500 hover:bg-amber-600 text-white'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z'/></svg>
                                                    </button>";
                                                echo "<button type='submit' name='deleteMedExamReport' title='delete' class='btn btn-sm ml-1 bg-red-500 hover:bg-red-600 text-white'>
                                                    <span class='sr-only'>Delete Medical Examination Report</span>
                                                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg>
                                                </button>";
                                            }
                                        } else {
                                            if($_SESSION['role'] == 1) {
                                                echo "<button type='button' onClick='medExamReportModal.showModal()' title='Create' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='14' width='12' viewBox='0 0 448 512'><path d='M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z'/></svg>
                                                    </button>";
                                            }
                                        }
                            echo   '</div>';
                            echo "</li>";

                            $resultsAPE = getResultsAPE($id);
                            if (!empty($resultsAPE)) {
                                foreach ($resultsAPE as $resAPE) {
                                    $examName = $resAPE['examName'];
                                    $fileName = $resAPE['fileName'];
                                    $medicalExaminationFK = $resAPE['medicalExaminationFK'];
                                    $src = base_url(false) . "/uploads/" . $fileName;

                                    $resultData = [
                                                    'filePath' => $src,
                                                    'examName' => $examName,
                                                    'organizationId' => $o,
                                                    'firstName' => $_POST['firstName'],
                                                    'middleName' => $_POST['middleName'],
                                                    'lastName' => $_POST['lastName']
                                                ];
                                    $encodeResult = base64_encode(json_encode( $resultData ));
                                    echo '<li class="flex flex-col sm:flex-row justify-between gap-x-6 py-5">
                                            <div class="flex min-w-0 gap-x-4">
                                                <div class="min-w-0 flex-auto">
                                                    <p class="text-sm font-semibold leading-6 text-gray-900">'. $examName . '</p>
                                                    <p class="mt-1 truncate text-xs leading-5 text-gray-500">' . $fileName . '</p>
                                                </div>
                                            </div>
                                            <div class="shrink-0 mt-2 sm:mt-0 sm:flex sm:items-end">';
                                                echo "<a href='" . base_url(false) . "/employeeViewResult-APE.php?pdf=$encodeResult' title='View' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z'/></svg>
                                                    </a>";
                                                echo "<a href='{$src}' class='btn btn-sm ml-1 bg-sky-500 hover:bg-sky-600' title='Download' download>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'/></svg>
                                                    </a>";

                                                if($_SESSION['role'] == 1) {
                                                    echo "<a href='employeeDeleteResult-APE.php?fileName=$fileName&medicalExaminationFK=$medicalExaminationFK&APEFK=$id' title='delete' class='btn btn-sm ml-1 bg-red-500 hover:bg-red-600'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg>
                                                    </a>";
                                                }
                                        echo '</div>';
                                    echo "</li>";
                                }
                            }
                            echo "</ul>";
                        ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto rounded-b-box rounded-b-box">
                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-3 sm:px-6 py-4">
                    <input type="hidden" id="organizationId">
                    <input type="hidden" id="dateRegistered">
                    <?php if($_SESSION['role'] == 1) { ?>
                        <a href="<?php echo base_url(false) . '/employees-APE.php?o=' . $o . '&y=' . $y; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                        <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto mb-2 sm:mb-0" type="button">Upload Result</button>
                        <a href="<?php echo base_url(false) . '/employeeExport-APE.php?id=' . $id; ?>" class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto mb-2 sm:mb-0" type="button">Export Results</a>
                        <button type="submit" name="updateDetailsForm" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">Save Changes</button>
                        <input type="submit" name="deleteRecord" value="Delete Record" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } else if($_SESSION['role'] == 2) {  ?>
                        <a href="<?php echo base_url(false) . '/client'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                        <a href="<?php echo base_url(false) . '/employeeExport-APE.php?id=' . $id; ?>" class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto mb-2 sm:mb-0" type="button">Export Results</a>
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

<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST); ?>;
        var role = <?php echo $_SESSION['role']; ?>;
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

            if(role != 1) {
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
</script>
<?php
include('footer.php')
?>

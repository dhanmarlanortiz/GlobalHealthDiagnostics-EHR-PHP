<?php 
error_reporting(E_ALL); 
ini_set('display_errors', 1);

ob_start();
session_start();

require_once('connection.php');
require_once 'FileUploader.php';
require_once 'FileDeleter.php';
include('header.php');
// preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);

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
    $controlDate = test_input( $_POST['controlDate'] );
    $firstName = test_input( $_POST['firstName'] );
    $middleName = test_input( $_POST['middleName'] );
    $lastName = test_input( $_POST['lastName'] );
    $age = test_input( $_POST['age'] );
    $sex = test_input( $_POST['sex'] );
    $organizationId = test_input( $_POST['organizationId'] );
    $employeeNumber = test_input( $_POST['employeeNumber'] );
    $membership = test_input( $_POST['membership'] );
    $department = test_input( $_POST['department'] );
    $level = test_input( $_POST['level'] );
    $dateRegistered = test_input( $_POST['dateRegistered'] );
    $examination = test_input( $_POST['examination'] );
    $remarks = test_input( $_POST['remarks'] );

    if (isset($_POST['updateDetailsForm'])) {
        $apeUpdateQuery =  "UPDATE APE SET 
                            headCount = $headCount,
                            " . (($_POST['controlNumber'] > 0) ? "controlNumber = '$_POST[controlNumber]'," : '') . "
                            firstName = '$firstName',
                            middleName = '$middleName',
                            lastName = '$lastName',
                            age = $age,
                            sex = '$sex',
                            organizationId = $organizationId,
                            employeeNumber = '$employeeNumber',
                            membership = '$membership',
                            department = '$department',
                            level = '$level',
                            " . (($_POST['dateRegistered'] !== '') ? "dateRegistered = '$_POST[dateRegistered]'," : '') . "
                            " . (($_POST['dateCompleted'] !== '') ? "dateCompleted = '$_POST[dateCompleted]'," : '') . "
                            examination = '$examination',
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
}

$organizationDetail = getOrganization($_POST['organizationId']);
$o = $_POST['organizationId'];
$y = date('Y', strtotime($_POST['dateRegistered']));

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

<form id="employee-APE-form" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>">
    <?php 
        if($_SESSION['role'] == 1) {
            createMainHeader($organizationDetail['name'], array("Home", "Organizations", $organizationDetail['name'], "Annual Physical Examination", "Information"));
        } else if($_SESSION['role'] == 2) { 
            createMainHeader($organizationDetail['name'], array("Annual Physical Examination", "Information Sheet"));
        }
    ?>
    <main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
        <?php createFormHeader("Patient"); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-3 sm:px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-8 gap-x-6 gap-y-8">
                    <div class="col-span-1 lg:col-span-2">
                        <input type="text" id="firstName" data-label="First Name" required />
                    </div>
                    <div class="col-span-1 lg:col-span-2">
                        <input type="text" id="middleName" data-label="Middle Name" />
                    </div>
                    <div class="col-span-1 lg:col-span-2">
                        <input type="text" id="lastName" data-label="Last Name" required />
                    </div>
                    <div class="col-span-">
                        <input type="number" id="age" data-label="Age" min="1" step="1" />
                    </div>
                    <div class="col-span-">
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
                </div>
            </div>
        </div>
        <?php createFormHeader("Organization"); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-3 sm:px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-8">
                    <input type="hidden" id="organizationId">
                    <div class="sm:col-span-1">
                        <input type="text" id="employeeNumber" data-label="Employee Number" />
                    </div>
                    <div class="sm:col-span-1">
                        <input type="text" id="department" data-label="Department" />
                    </div>
                    <div class="sm:col-span-1">
                        <input type="text" id="membership" data-label="Membership" />
                    </div>
                    <div class="sm:col-span-1">
                        <input type="text" id="level" data-label="Level" />
                    </div>
                </div>
            </div>
        </div>
        <?php createFormHeader("Medical"); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-3 sm:px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-8">
                    <?php if($_SESSION['role'] == '1') { ?>
                    <div class="col-span-1">
                        <input type="number" id="headCount" data-label="Head Count" readonly min="1" step="1"  />
                    </div>
                    <div class="col-span-1">
                        <input type="number" id="controlNumber" data-label="Control Number" readonly min="1" step="1" placeholder="Not Available" />
                        <?php 
                        if( isset($_POST['controlDate']) &&  date('Y', strtotime($_POST['controlDate'])) >= 2023) {
                            echo "<p class='text-xs mt-1 text-gray-600 whitespace-nowrap'><span class='font-medium'>Generated: </span><input type='date' id='controlDate' data-label='Control Date' readonly /></p>";
                        } else { ?>
                            <a class="text-xs text-sky-400 whitespace-nowrap" href="<?php echo base_url() . '/components/controlNumberCreate-APE.php?id=' . $id ; ?>" >Generate Control Number</a>
                        <?php 
                        }  ?>
                    </div>
                    <?php } ?>
                    <div class="col-span-1">
                        <input type="date" id="dateRegistered" data-label="Date Registered" required />
                    </div>
                    <div class="col-span-1">
                        <input type="date" id="dateCompleted" data-label="Date Completed" />
                    </div> 
                    <div class="col-span-2">
                        <input type="text" id="examination" data-label="Examination" />
                    </div>
                    
                    <div class="col-span-2">
                        <input type="text" id="remarks" data-label="Remarks" />
                    </div> 
                </div>
            </div>
        </div>
        <?php createFormHeader("Results"); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-3 sm:px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8">
                    <div class="col-span-1">
                    <?php 
                        $resultsAPE = getResultsAPE($id);
                        if (!empty($resultsAPE)) {
                            echo "<div class='overflow-auto p-1'>";
                            echo "<table class='display'>";
                            echo "<thead><tr><th>Examination</th><th>Filename</th><th></th></tr></thead>";
                            echo "<tbody>";
                            foreach ($resultsAPE as $resAPE) {
                                $examName = $resAPE['examName']; 
                                $fileName = $resAPE['fileName'];
                                $medicalExaminationFK = $resAPE['medicalExaminationFK'];
                                $src = base_url(false) . "/uploads/" . $fileName;

                                echo "<tr>";
                                echo "<td>{$examName}</td>";
                                echo "<td>{$fileName}</td>";
                                echo "<td class='text-right'>";
                                echo "<a href='{$src}' class='$classTblBtnPrimary mr-1' download>Download</a>";
                                
                                if($_SESSION['role'] == 1) {
                                    echo "<a href='employeeDeleteResult-APE.php?fileName=$fileName&medicalExaminationFK=$medicalExaminationFK&APEFK=$id' class='$classTblBtnDanger'>Delete</a>";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                        }
                        else {
                            echo "<p class='text-sm'>Results not available.</p>";
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto rounded-b-box rounded-b-box">  
            <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-3 sm:px-6 py-4 border-t-2 border-green-700">
                <?php if($_SESSION['role'] == 1) { ?>
                    <a href="<?php echo base_url(false) . '/employees-APE.php?o=' . $o . '&y=' . $y; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                    <button data-modal-target="default-modal" data-modal-toggle="default-modal" class="<?php echo $classBtnAlternate; ?>" type="button">Upload Result</button>
                    <button type="submit" name="updateDetailsForm" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Save Changes</button>  
                <?php } else if($_SESSION['role'] == 2) {  ?>
                    <a href="<?php echo base_url(false) . '/client'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Close</a>
                <?php } ?>
            </div>

            <?php flash('generate-control-number-success'); ?>
            <?php flash('generate-control-number-error'); ?>
            <?php flash('update-success'); ?>
            <?php flash('update-error'); ?>
            <?php flash('upload-success'); ?>
            <?php flash('upload-error'); ?>
            <?php flash('delete-success'); ?>
            <?php flash('delete-success'); ?>
        </div>        
    </main>

    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 flex items-center justify-center overflow-y-auto overflow-x-hidden" style="background: rgba(0,0,0,0.5);">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white shadow">
                <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                    <h3 class="font-medium text-green-700 text-sm">Upload Result</h3>
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
                        <label for="uploadedFile" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p id="file-name" class="text-xs text-gray-500 dark:text-gray-400"></p>
                            </div>
                            <input id="uploadedFile" type="file" name="uploadedFile" class="hidden" onchange="displayConfirmation()" />
                        </label>
                        </div>
                </div>
                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700">
                    <button data-modal-hide="default-modal" type="button" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Close</button>
                    <input type="submit" name="uploadFileForm" value="Upload File" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">
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
            let id = $(this).attr('id');
            
            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput);  
            $(this).attr('name', id);

            if(role != 1) {
                $(this).prop("disabled", true)
            }
        });

        if(Object.keys(post).length !== 0) {
            $('input').each( function(key) {
                let id = $(this).attr('id');
                $(this).attr('value', post[id]);
                $(this).attr('name', id);
            });
        }
    });

    /*
    $("#birthDate").on("change", function() {
        let birthdate = new Date($(this).val());
        $("#age").val( age(birthdate) );
    });

    function age(birthdate) {
        const today = new Date();
        const age = today.getFullYear() - birthdate.getFullYear() - 
                    (today.getMonth() < birthdate.getMonth() || 
                    (today.getMonth() === birthdate.getMonth() && today.getDate() < birthdate.getDate()));
                    console.log(age)
        return age;
    }
    */

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

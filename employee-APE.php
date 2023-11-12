<?php 
ob_start();
session_start();

require_once('connection.php');
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
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>">
    <?php 
        if($_SESSION['role'] == 1) {
            createMainHeader($organizationDetail['name'], array("Home", "Organizations", $organizationDetail['name'], "Annual Physical Examination", "Information"));
        } else if($_SESSION['role'] == 2) { 
            createMainHeader($organizationDetail['name'], array("Annual Physical Examination", "Information Sheet"));
        }
    ?>
    <main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
        <div class="bg-white max-w-7xl lg:max-w-full lg:flex">
            <div class="">
                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 ">
                    <ul class="flex -mb-px">
                        <li class="w-full bg-white inline-block p-6 text-green-700 border-b-2 border-green-700 active text-left text-sm">
                            Personal Information
                        </li>
                    </ul>
                </div>
                <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
                    <div class="space-y-12 w-full">
                        <div class="">
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
                                <!-- <div class="col-span-">
                                    <input type="date" id="birthDate" data-label="Date of Birth" />
                                </div> -->
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
                </div>
                
                <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b lg:border-b-0">
                    <div class="space-y-12 w-full">
                        <div class="">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                                <input type="hidden" id="organizationId">
                                <!-- <div class="sm:col-span-2">
                                    <select id="organizationId" data-label="Organization" required>
                                        <?php 
                                            if ($orgResult !== false && $orgResult->num_rows > 0) {
                                                echo "<option value='' selected disabled>Select</option>";
                                                while($org = $orgResult->fetch_assoc()) {
                                                    echo "<option value='" . $org['id'] . "' " . 
                                                            ( 
                                                                (isset($_POST['organizationId'])) 
                                                                ? (($_POST['organizationId'] == $org['id']) ? 'selected' : '') 
                                                                : '' 
                                                            )  
                                                        . " >" . $org['name'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value='null' selected disabled>No record</option>";

                                            }
                                        ?>
                                    </select>
                                </div> -->
                                <div class="sm:col-span-1">
                                    <input type="text" id="employeeNumber" data-label="Employee Number" />
                                </div>
                                <div class="sm:col-span-2">
                                    <input type="text" id="department" data-label="Department" />
                                </div>
                                <div class="sm:col-span-1">
                                    <input type="text" id="membership" data-label="Membership" />
                                </div>
                                <div class="sm:col-span-2">
                                    <input type="text" id="level" data-label="Level" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:w-1/2">
                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
                    <ul class="flex -mb-px">
                        <li class="w-full bg-white inline-block p-6 text-green-700 border-b-2 border-green-700 active text-left text-sm">
                            Examination Details
                        </li>
                    </ul>
                </div>

                <?php if($_SESSION['role'] == '1') { ?>
                <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b lg:border-l">
                    <div class="space-y-12 w-full">
                        <div class="">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                                <div class="sm:col-span-1">
                                    <input type="number" name="headCount" id="headCount" data-label="Head Count" readonly min="1" step="1"  />
                                </div>
                                <div class="sm:col-span-1">
                                    <input type="number" name="controlNumber" id="controlNumber" data-label="Control Number" readonly min="1" step="1" placeholder="Not Available" />
                                    <?php 
                                    if( isset($_POST['controlDate']) ) {
                                        echo "<p class='text-xs mt-2 text-gray-600 whitespace-nowrap'><span class='font-medium'>Generated: </span>" . date('M d, Y', strtotime($_POST['controlDate'])) . "</p>";
                                    } else { ?>
                                        <a class="text-xs text-sky-400 whitespace-nowrap" href="<?php echo base_url() . '/components/controlNumberCreate-APE.php?id=' . $_POST['id'] ; ?>" >Generate Control Number</a>
                                    <?php 
                                    }  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                <div class="bg-white border-b flex gap-x-6 h-full lg:border-b-0 lg:border-l px-6 py-10">
                    <div class="space-y-12 w-full">
                        <div class="">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                                <div class="sm:col-span-1">
                                    <input type="date" id="dateRegistered" data-label="Date Registered" required />
                                </div>
                                <div class="sm:col-span-1">
                                    <input type="date" id="dateCompleted" data-label="Date Completed" />
                                </div> 
                            
                                <div class="sm:col-span-3">
                                    <input type="text" id="examination" data-label="Examination" />
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <input type="text" id="remarks" data-label="Remarks" />
                                </div> 
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </div>     
        
        
        <div class="mx-auto rounded-b-box rounded-b-box">  
            <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
                <?php if($_SESSION['role'] == 1) { ?>
                    <a href="<?php echo base_url(false) . '/employees-APE.php?o=' . $o . '&y=' . $y; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                    <a href="<?php echo base_url(false) . '/employeeUploadResult-APE.php?id=' . $id; ?>" class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto mb-2 sm:mb-0">Upload Result</a>
                    <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Save Changes</button>  
                <?php } else if($_SESSION['role'] == 2) {  ?>
                    <a href="<?php echo base_url(false) . '/client'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Close</a>
                <?php } ?>
            </div>

            <?php flash('generate-control-number-success'); ?>
            <?php flash('generate-control-number-error'); ?>
            <?php flash('update-success'); ?>
            <?php flash('update-error'); ?>
        </div>        
    </main>
</form>

<main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
    <div class="mx-auto rounded-b-box rounded-b-box">
        <!-- <h2 class="px-6 py-4 bg-gray-200 font-semibold rounded-t-box shadow-sm">Registration Form</h2> -->
        <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 ">
            <ul class="flex -mb-px">
                <li class="w-full bg-white inline-block p-6 text-green-700 border-b-2 border-green-700 active text-left text-sm">
                    Examination Results
                </li>
            </ul>
        </div>

        <div class="bg-white px-6 py-10">
            <!-- <div class="space-y-12 w-full"> -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                <div class="sm:col-span-1">
                    <select id="name" data-label="Examination" required>
                        <option value="">Blood Pressure Measurement</option>
                        <option value="">Complete Blood Count (CBC)</option>
                        <option value="">Blood Glucose Test</option>
                        <option value="">Lipid Profile</option>
                        <option value="">Electrocardiogram (ECG or EKG)</option>
                        <option value="">Chest X-ray</option>
                        <option value="">MRI (Magnetic Resonance Imaging)</option>
                        <option value="">CT Scan (Computed Tomography)</option>
                        <option value="">Ultrasound</option>
                        <option value="">Colonoscopy</option>
                        <option value="">Mammogram</option>
                        <option value="">Pap Smear</option>
                        <option value="">Prostate-Specific Antigen (PSA) Test</option>
                        <option value="">Bone Density Test (DEXA or DXA)</option>
                        <option value="">Thyroid Function Tests</option>
                        <option value="">Spirometry</option>
                        <option value="">Liver Function Tests</option>
                        <option value="">Kidney Function Tests</option>
                        <option value="">Hemoglobin A1c Test</option>
                        <option value="">Skin Biopsy</option>
                        <option value="">Stool Tests</option>
                        <option value="">Allergy Testing</option>
                        <option value="">Genetic Testing</option>
                        <option value="">Echocardiogram</option>
                        <option value="">Throat Culture</option>
                    </select>
                </div>
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Upload file</label>
                    <div class="mt-2">
                       
<input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file">

                    </div>
                </div>
            </div>
        </div>
    </div>        
</main>

<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST); ?>;
        var role = <?php echo $_SESSION['role']; ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=date], select').each( function() {
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
        var input = document.getElementById('dropzone-file');

        document.getElementById('file-name').innerHTML = ((input.files.length > 0) 
            ? input.files[0].name
            : ''
        );
    }
</script>
<?php
include('footer.php')
?>

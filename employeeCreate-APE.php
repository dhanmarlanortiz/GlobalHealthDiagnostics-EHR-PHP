<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$orgQuery = "SELECT * FROM Organization";
$orgResult = $conn->query($orgQuery);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$o = 0;
$y = date('Y');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // $headCount = test_input( $_POST['headCount'] );
    // $controlNumber = test_input( $_POST['controlNumber'] );
    $firstName = test_input( $_POST['firstName'] );
    $middleName = test_input( $_POST['middleName'] );
    $lastName = test_input( $_POST['lastName'] );
    // $birthDate = test_input( $_POST['birthDate'] );
    $age = test_input( $_POST['age'] );
    $sex = test_input( $_POST['sex'] );
    $civilStatus = test_input( $_POST['civilStatus'] );
    $homeAddress = test_input( $_POST['homeAddress'] );
    $organizationId = test_input( intval($_POST['organizationId']) );
    $employeeNumber = test_input( $_POST['employeeNumber'] );
    // $membership = test_input( $_POST['membership'] );
    // $department = test_input( $_POST['department'] );
    // $level = test_input( $_POST['level'] );
    // $dateRegistered = test_input( $_POST['dateRegistered'] );
    $dateRegistered = date("Y-m-d");
    // $dateCompleted = ( ($_POST['dateCompleted']!== '') ? test_input( $_POST['dateCompleted'] ) : NULL );
    // $examination = test_input( $_POST['examination'] );
    $remarks = test_input( $_POST['remarks'] );
    $userId = test_input( $_SESSION['userId'] );
    

    /* GET HEAD COUNT - START */
    $headCount = 1;
    $headCountQuery = "SELECT MAX(headCount) FROM APE WHERE organizationId = $organizationId AND YEAR(dateRegistered) = '" . date('Y') . "'";
    $headCountResult = $conn->query($headCountQuery);

    if ($headCountResult !== false && $headCountResult->num_rows > 0) {
        while($hc = $headCountResult->fetch_assoc()) {
            $headCount = $hc['MAX(headCount)'] + 1;
        }
    }
    /* GET HEAD COUNT - END */

    $o = $organizationId;
    $y = date('Y', strtotime($dateRegistered));

    $regQuery = "INSERT INTO APE(headCount, firstName, middleName, lastName, age, sex, civilStatus, homeAddress, organizationId, employeeNumber, dateRegistered, remarks, userId) VALUES('$headCount', '$firstName', '$middleName', '$lastName', '$age', '$sex', '$civilStatus', '$homeAddress', '$organizationId', '$employeeNumber', '$dateRegistered', '$remarks', '$userId')";
    
    if ($conn->query($regQuery) === TRUE) {
        create_flash_message('create-success', $flashMessage['create-success'], FLASH_SUCCESS);
        $id = $conn->insert_id;

        getControlNumberAPE($id, $o);
        
        // $url = base_url(false) . "/registeredEmployees.php?o=" . $organizationId . "&y=" . date('Y', strtotime($dateRegistered));
        $url = base_url(false) . "/employee-APE.php?id=" . $id;
        // $url = base_url(false) . "/components/controlNumberCreate-APE.php?id=" . $id;
        header("Location: " . $url ."");
        die;
    } else {
        create_flash_message('create-failed', $flashMessage['create-failed'], FLASH_ERROR);
        echo $conn->error;
    }
} else {
    $o = test_input( $_GET['o']);
}

$organizationDetail = getOrganization($o);

$conn->close();

$styleButtonPrimary = "rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
$styleButtonLink = "text-sm font-semibold leading-6 text-gray-900";
$styleTextError = "mt-2 text-red-400 text-xs";
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center overflow-hidden">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    <span class="pr-3"><?php echo $organizationDetail['name']; ?></span>
                    <span class="font-normal text-xl border-l-2 border-green-700 pl-3">APE Registration</span>
                </h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Organizations</li> 
                        <li><?php echo $organizationDetail['name']; ?></li> 
                        <li>Annual Physical Examination</li>
                        <li>Registration</li> 
                    </ul>
                </div>
            </div>
            <!-- <div>
                <a href="<?php base_url(); ?>/employees-APE.php" class="btn btn-default rounded normal-case">Back</a>
            </div> -->
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="prompt-confirm mx-auto max-w-3xl">
        <input type="hidden" id="organizationId" name="organizationId"  value="<?php echo $organizationDetail['id']; ?>"/>
        <?php createFormHeader('Registration Form'); ?>
        <!-- 
        <h2 class="px-6 py-4 bg-gray-200 font-semibold rounded-t-box shadow-sm">Registration Form</h2>
        <div class="flex items-center justify-end gap-x-6 bg-white p-6 border-b">
            <div class="space-y-12 w-full">
                <div class="">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                        <div class="sm:col-span-1">
                            <input type="number" name="headCount" id="headCount" data-label="Head Count" readonly min="1" step="1"  />
                        </div>
                        <div class="sm:col-span-1">
                            <input type="number" name="controlNumber" id="controlNumber" data-label="Control Number" readonly min="1" step="1"  />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->

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
                        <div class="col-span-1">
                            <input type="number" id="age" data-label="Age" min="1" step="1" required />
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
                        <div class="col-span-1">
                            <input type="text" id="civilStatus" data-label="Civil Status" />
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <input type="text" id="homeAddress" data-label="Home Address" />
                        </div>
                        <div class="col-span-1">
                            <input type="text" id="employeeNumber" data-label="Employee Number" />
                        </div>
                        <!-- <div class="sm:col-span-3">
                            <input type="text" id="examination" data-label="Examination" />
                        </div> -->
                        <div class="sm:col-span-3">
                            <input type="text" id="remarks" data-label="Remarks" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-8">
                        <div class="sm:col-span-2">
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
                        </div> 
                        <div class="sm:col-span-1">
                            <input type="text" id="employeeNumber" data-label="Employee Number" />
                        </div>
                        <div class="sm:col-span-2">
                            <input type="text" id="department" data-label="Department" />
                        </div>
                        <div class="sm:col-span">
                            <input type="text" id="membership" data-label="Membership" />
                        </div>
                        <div class="sm:col-span-2">
                            <input type="text" id="level" data-label="Level" />
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        
        <!-- <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10">
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
        </div> -->

        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
            <a href="<?php echo base_url(false) . '/employees-APE.php?o=' . $o . '&y=' . $y; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
            <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Register</button>
        </div>

        <?php
        flash('create-success');
        flash('create-failed'); 
        ?>
    </form>
</main>

<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST) ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=date], select').each( function() {
            let id = $(this).attr('id');
            
            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput)
            $(this).attr('name', id);
        });

        if(Object.keys(post).length !== 0) {
            $('input').each( function(key) {
                let id = $(this).attr('id');
                $(this).attr('value', (Object.keys(post[id]).length === 0) ? '' : post[id]);
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

</script>

<?php
include('footer.php')
?>

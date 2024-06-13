<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$professionals = getProfessional($conn);

$styleInput = "block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6";
$styleLabel = "block text-sm font-medium leading-6 text-gray-900";
$styleButtonPrimary = "rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
$styleButtonLink = "text-sm font-semibold leading-6 text-gray-900";
$styleTextError = "mt-2 text-red-400 text-xs";

// define variables and set to empty values
$name = $email = $phone = $address = "";

$errVal = $errKey = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean($_POST["name"]);
    $email = clean($_POST["email"]);
    $phone = clean($_POST["phone"]);
    $address = clean($_POST["address"]);
    $location_fk = clean($_POST["location_fk"]);
    $xraytech_fk = clean($_POST["xraytech_fk"]);
    $radiologist_fk = clean($_POST["radiologist_fk"]);
    $medtech1_fk = clean($_POST["medtech1_fk"]);
    $medtech2_fk = clean($_POST["medtech2_fk"]);
    $pathologist_fk = clean($_POST["pathologist_fk"]);
    $physician_fk = clean($_POST["physician_fk"]);
  print_r($_POST);
    $sql = "INSERT INTO Organization (name, email, phone, address, location_fk, xraytech_fk, radiologist_fk, medtech1_fk, medtech2_fk, pathologist_fk, physician_fk)
    VALUES ('$name', '$email', '$phone', '$address', '$location_fk', '$xraytech_fk', '$radiologist_fk', '$medtech1_fk', '$medtech2_fk', '$pathologist_fk', '$physician_fk')";
  
    if ($conn->query($sql) === TRUE) {
        create_flash_message('create-success', '<strong>Success!</strong> New Organization has been created.', FLASH_SUCCESS);

        $id = $conn->insert_id;
        $url = base_url(false) . "/organization.php?id=" . $id;
        header("Location: " . $url ."");
        
        exit();
    } else {
        create_flash_message('create-failed', '<strong>Failed!</strong> Please review and try again.', FLASH_ERROR);

        // duplicate entry error
        if($conn->errno == 1062) {
            $errMsg = explode("'", $conn->error);
            $errVal = $errMsg[1];
            $errKey = $errMsg[3];

            create_flash_message('create-failed', '<strong>Failed!</strong> &quot;' . $errVal . '&quot; already exist.', FLASH_ERROR);
        }
    }
}

$clinics = getLocation($conn);

$conn->close();
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Create Organization</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Organizations</li> 
                        <li>Create Organization</li> 
                    </ul>
                </div>
            </div>
            <div>
            </div>
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="prompt-confirm mx-auto rounded-b-box rounded-b-box max-w-3xl">
        <?php createFormHeader('Organization'); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                    <div class="sm:col-span-2 <?php echo ($errKey == 'name') ? 'input-error' : ''; ?>">
                        <input id="name" type="text" data-label="Organization Name" required />
                        <?php echo ($errKey == 'name') ? '<div class="input-error--message"><p>' . $errVal . ' already exists.</p></div>' : ''; ?>
                    </div>
                    <div class="sm:col-span-1">
                        <input id="email" type="email" data-label="Email Address" required />
                    </div>
                    <div class="sm:col-span-1">
                        <input id="phone" type="number" data-label="Telephone Number" required />
                    </div>
                    <div class="sm:col-span-2">
                        <input id="address" type="text" data-label="Office Address" required />
                    </div>                
                </div>
            </div>
        </div>

        <?php createFormHeader('Clinic'); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                <div class="sm:col-span-2">
                        <select name="location_fk" id="location_fk" data-label="Clinic Address" required>
                            <option value='' selected disabled>Select</option>
                            <?php
                                if (!empty($clinics)) {
                                    foreach ($clinics as $clinic) {
                                        echo "<option value='" . $clinic['loc_id'] . "'" . (isset($_POST['location_fk']) ? 'selected' : '') . ">" . $clinic['loc_address1'] . "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div> 
                </div>
            </div>
        </div>

        <?php createFormHeader('Healthcare Professionals'); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                    <div class="sm:col-span-1">
                        <select id="xraytech_fk" data-filter="X-Ray Technologist" data-label="X-Ray Technologist" required></select>
                        <p class="mt-2 text-gray-500 text-xs">Radiology Report</p>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <select id="radiologist_fk" data-filter="Radiologist" data-label="Radiologist" required></select>
                        <p class="mt-2 text-gray-500 text-xs">Radiology Report</p>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <select id="medtech1_fk" data-filter="Medical Technologist" data-label="Medical Technologist 1" required></select>
                        <p class="mt-2 text-gray-500 text-xs">Laboratory Result (Performer)</p>
                    </div>

                    <div class="sm:col-span-1">
                        <select id="medtech2_fk" data-filter="Medical Technologist" data-label="Medical Technologist 2" required></select>
                        <p class="mt-2 text-gray-500 text-xs">Laboratory Result (Verifier)</p>
                    </div>

                    <div class="sm:col-span-1">
                        <select id="pathologist_fk" data-filter="Pathologist" data-label="Pathologist" required></select>
                        <p class="mt-2 text-gray-500 text-xs">Laboratory Result</p>
                    </div>

                    <div class="sm:col-span-1">
                        <select id="physician_fk" data-filter="Physician" data-label="Physician" required></select>
                        <p class="mt-2 text-gray-500 text-xs">Medical Examination Report (Examiner)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
            <a href="<?php base_url(); ?>/organizations.php" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
            <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create</button>
        </div>

        <?php flash('create-success'); ?>
        <?php flash('create-failed'); ?>
    </form>
</main>

<script src="js/healthcare-professional.js"></script>
<script>
    var listProfessionals = <?php echo json_encode($professionals); ?>;
    
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST) ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=email], input[type=date], select').each( function() {
            let id = $(this).attr('id');
            let placeholder = $(this).attr('data-label');

            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput);  
            $(this).attr('name', id);
            // $(this).attr('placeholder', placeholder);
        });

        if(Object.keys(post).length !== 0) {
            $('input').each( function(key) {
                let id = $(this).attr('id');
                
                // $(this).attr('value', (Object.keys(post[id]).length === 0) ? '' : post[id]);
                $(this).attr('value', post[id]);
                $(this).attr('name', id);
            });
        }

    });

    document.addEventListener("DOMContentLoaded", 
        setRoleSelect( listProfessionals, document.getElementById("xraytech_fk"), "<?php echo isset($_POST['xraytech_fk']) ? $_POST['xraytech_fk'] : ''; ?>", "X-Ray Technologist" )
    );

    document.addEventListener("DOMContentLoaded", 
        setRoleSelect( listProfessionals, document.getElementById("radiologist_fk"), "<?php echo isset($_POST['radiologist_fk']) ? $_POST['radiologist_fk'] : ''; ?>", "Radiologist" )
    );

    document.addEventListener("DOMContentLoaded", 
        setRoleSelect( listProfessionals, document.getElementById("medtech1_fk"), "<?php echo isset($_POST['medtech1_fk']) ? $_POST['medtech1_fk'] : ''; ?>", "Medical Technologist" )
    );

    document.addEventListener("DOMContentLoaded", 
        setRoleSelect( listProfessionals, document.getElementById("medtech2_fk"), "<?php echo isset($_POST['medtech2_fk']) ? $_POST['medtech2_fk'] : ''; ?>", "Medical Technologist" )
    );

    document.addEventListener("DOMContentLoaded", 
        setRoleSelect( listProfessionals, document.getElementById("pathologist_fk"), "<?php echo isset($_POST['pathologist_fk']) ? $_POST['pathologist_fk'] : ''; ?>", "Pathologist" )
    );

    document.addEventListener("DOMContentLoaded", 
        setRoleSelect( listProfessionals, document.getElementById("physician_fk"), "<?php echo isset($_POST['physician_fk']) ? $_POST['physician_fk'] : ''; ?>", "Physician" )
    );

</script>

<?php
  include('footer.php');
?>
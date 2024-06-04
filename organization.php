<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$clinics = getLocation($conn);
$professionals = getProfessional($conn);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$id = 0;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $_POST = getOrganization($id);
}

$errVal = $errKey = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = test_input( $_POST['id'] );
    $name = test_input( $_POST['name'] );
    $email = test_input( $_POST['email'] );
    $phone = test_input( $_POST['phone'] );
    $address = test_input( $_POST['address'] );
    $location_fk = clean($_POST["location_fk"]);
    $xraytech_fk = clean($_POST["xraytech_fk"]);
    $radiologist_fk = clean($_POST["radiologist_fk"]);
    $medtech1_fk = clean($_POST["medtech1_fk"]);
    $medtech2_fk = clean($_POST["medtech2_fk"]);
    $pathologist_fk = clean($_POST["pathologist_fk"]);
    $physician_fk = clean($_POST["physician_fk"]);

    if(isset( $_POST['saveChanges'] )) {

        $orgUpdateQuery =  "UPDATE Organization SET 
                            name = '$name',
                            email = '$email',
                            phone = '$phone',
                            address = '$address',
                            location_fk = '$location_fk',
                            xraytech_fk = '$xraytech_fk',
                            radiologist_fk = '$radiologist_fk',
                            medtech1_fk = '$medtech1_fk',
                            medtech2_fk = '$medtech2_fk',
                            pathologist_fk = '$pathologist_fk',
                            physician_fk = '$physician_fk'
                            WHERE id = $id";

        if ($conn->query($orgUpdateQuery) === TRUE) {
            create_flash_message('update-success', '<strong>Success!</strong> Record has been updated.', FLASH_SUCCESS);
            
            $url = base_url(false) . "/organization.php?id=" . $id;
            header("Location: " . $url ."");
            
            exit();
        } else {
            create_flash_message('update-failed', '<strong>Failed!</strong> Please review and try again.', FLASH_ERROR);
            
            // duplicate entry error
            if($conn->errno == 1062) {
                $errMsg = explode("'", $conn->error);
                $errVal = $errMsg[1];
                $errKey = $errMsg[3];

                create_flash_message('update-failed', '<strong>Failed!</strong> &quot;' . $errVal . '&quot; already exist.', FLASH_ERROR);
            }
        }
    } else if(isset( $_POST['delete'] )) {
        try {
            $deleteQuery =  "DELETE FROM Organization WHERE id = $id";

            if ($conn->query($deleteQuery) === TRUE) {
                create_flash_message('delete-success', $flashMessage['delete-success'] , FLASH_SUCCESS);
                $url = base_url(false) . "/organizations.php";
            } else {
                create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);
            
                if($conn->errno == 1451) {
                    create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
                }

                $url = base_url(false) . "/organization.php?id=" . $id;
            }

            
            header("Location: " . $url ."");
            exit();
        } catch (mysqli_sql_exception $e) {
            create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
            // create_flash_message('delete-failed', "An error occurred: " . $e->getMessage() , FLASH_ERROR);

            $url = base_url(false) . "/organization.php?id=" . $id;
            header("Location: " . $url ."");
                
            exit();
        }
    }
}

$headerText = (null !== getOrganization($id)) ? getOrganization($id)['name'] : "Not found";

$conn->close();
createMainHeader($headerText, array("Home", "Organizations", $headerText));
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

    <main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
        <div class="mx-auto rounded-b-box rounded-b-box max-w-3xl">
            <?php createFormHeader('Organization'); ?>
            <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
                <div class="space-y-12 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                        <div class="sm:col-span-2 <?php echo ($errKey == 'name') ? 'input-error' : ''; ?>">
                            <input type="text" id="name" data-label="Organization Name" required />
                            <?php echo ($errKey == 'name') ? '<div class="input-error--message"><p>' . $errVal . ' already exists.</p></div>' : ''; ?>
                        </div>
                        <div class="sm:col-span-1">
                            <input type="email" id="email" data-label="Email Address" />
                        </div> 
                        <div class="sm:col-span-1">
                            <input type="text" id="phone" data-label="Contact Number" />
                        </div>
                        <div class="sm:col-span-2">
                            <input type="text" id="address" data-label="Office Address" />
                        </div> 
                    </div>
                </div>
            </div>

            <?php createFormHeader('Clinic'); ?>
            <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
                <div class="space-y-12 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                    <div class="sm:col-span-2">
                        <div class="sm:col-span-2">
                            <select name="location_fk" id="location_fk" data-label="Clinic Address" required>
                                <option value='' selected disabled>Select</option>
                                <?php
                                    if (!empty($clinics)) {
                                        foreach ($clinics as $clinic) {
                                            echo "<option value='" . $clinic['loc_id'] . "' " . (($_POST['location_fk'] == $clinic['loc_id']) ? 'selected' : '') . ">" . $clinic['loc_address'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div> 
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
                <a href="<?php echo base_url() . '/organizations.php'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
                <button type="submit" name="saveChanges" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">Save Changes</button>
                <button type="submit" name="delete" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto">Delete Record</button>
            </div>

            <?php flash('update-success'); ?>
            <?php flash('update-failed'); ?>
            <?php flash('create-success'); ?>
            <?php flash('create-failed'); ?>
            <?php flash('delete-failed'); ?>
            <?php flash('delete-success'); ?>
            <?php flash('delete-failed-linked'); ?>
        </div>
    </main>

</form>

<script src="js/healthcare-professional.js"></script>
<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST) ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=date], input[type=email], select').each( function() {
            let id = $(this).attr('id');
            
            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput);  
            $(this).attr('name', id);
        });

        if(Object.keys(post).length !== 0) {
            $('input').each( function(key) {
                let id = $(this).attr('id');
                $(this).attr('value', htmlEntityDecode(post[id]));
                $(this).attr('name', id);
            });
        }
    });

    var listProfessionals = <?php echo json_encode($professionals); ?>;

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
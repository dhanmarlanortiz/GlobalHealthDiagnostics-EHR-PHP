<?php
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$styleInput = "block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6";
$styleLabel = "block text-sm font-medium leading-6 text-gray-900";
$styleButtonPrimary = "rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
$styleButtonLink = "text-sm font-semibold leading-6 text-gray-900";
$styleTextError = "mt-2 text-red-400 text-xs";

// define variables and set to empty values
$name = $address = "";
$errVal = $errKey = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean($_POST["prof_name"]);
    $role = clean($_POST["prof_role"]);
    $license = clean($_POST["prof_license"]);


    $sql = "INSERT INTO HealthcareProfessionals (prof_name, prof_role, prof_license)
    VALUES ('$name', '$role', '$license')";

    if ($conn->query($sql) === TRUE) {
        create_flash_message('create-success', '<strong>Success!</strong> New record has been created.', FLASH_SUCCESS);

        $id = $conn->insert_id;
        $url = base_url(false) . "/HealthcareProfessional.php?id=" . $id;
        header("Location: " . $url ."");

        exit();
    } else {
        create_flash_message('create-failed', '<strong>Failed!</strong> Please review and try again.', FLASH_ERROR);
    }
}

$conn->close();

createMainHeader("Create Healthcare Professionals", array("Home", "Create Healthcare Professionals"));
?>

<main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="prompt-confirm mx-auto rounded-b-box rounded-b-box max-w-3xl">
        <?php createFormHeader('Information'); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                    <div class="sm:col-span-2">
                        <input id="prof_name" type="text" data-label="Full name" maxlength="50" required />
                    </div>                
                    <div class="sm:col-span-1">
                        <input id="prof_role" type="text" data-label="Role" maxlength="50" required />
                    </div>
                    <div class="sm:col-span-1">
                        <input id="prof_license" type="text" data-label="License number" maxlength="20" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
            <a href="<?php base_url(); ?>/healthcareProfessionals.php" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
            <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create</button>
        </div>

        <?php flash('create-success'); ?>
        <?php flash('create-failed'); ?>
    </form>
</main>

<script>
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
</script>

<?php 
include('footer.php');


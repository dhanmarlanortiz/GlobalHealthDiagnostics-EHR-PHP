<?php 
ob_start();
session_start();

require_once('connection.php');
require_once 'FileUploader.php';
require_once 'FileDeleter.php';
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$id = 0;
$headerText = "Healthcare Professional Details";
$errVal = $errKey = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $professional = getProfessional($conn, $id);
    $_POST = $professional;
    $headerText = $professional['prof_name'];
}

if(empty($_POST)){
    $url = base_url(false) . "/page-not-found.php";
    header("Location: " . $url ."");
    exit();
}

/* HANDLES UPLOAD AND DELETE FILE - START  */
$uploadDir = 'images/';
$fileUploader = new FileUploader($uploadDir, $conn);
$fileDeleter = new FileDeleter($uploadDir, $conn);
/* HANDLES UPLOAD AND DELETE FILE - END  */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = clean( $_POST['prof_id'] );
    $name = clean( $_POST['prof_name'] );
    $role = clean( $_POST['prof_role'] );
    $license = clean( $_POST['prof_license'] );

    $professional = getProfessional($conn, $id);
    $headerText = $professional['prof_name'];
    
    if(isset( $_POST['saveChanges'] )) {
        $updateQuery =  "UPDATE HealthcareProfessionals SET 
                            prof_name = '$name',
                            prof_role = '$role',                        
                            prof_license = '$license'                            
                            WHERE prof_id = $id";

        if ($conn->query($updateQuery) === TRUE) {
            create_flash_message('update-success', '<strong>Success!</strong> Record has been updated.', FLASH_SUCCESS);
            
            $url = base_url(false) . "/healthcareProfessional.php?id=" . $id;
            header("Location: " . $url ."");
            
            exit();
        } else {
            create_flash_message('update-failed', '<strong>Failed!</strong> Please review and try again.', FLASH_ERROR);
        }
    } else if(isset( $_POST['delete'] )) {
        try {
            $deleteQuery =  "DELETE FROM HealthcareProfessionals WHERE prof_id = $id";

            if ($conn->query($deleteQuery) === TRUE) {

                $filePath = $uploadDir . "healthcare-professional-" . $id . "-signature.png";
        
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                    
                create_flash_message('delete-success', $flashMessage['delete-success'] , FLASH_SUCCESS);
                $url = base_url(false) . "/healthcareProfessionals.php";
            } else {
                create_flash_message('delete-failed', $flashMessage['delete-failed'], FLASH_ERROR);
            
                if($conn->errno == 1451) {
                    create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
                }

                $url = base_url(false) . "/healthcareProfessional.php?id=" . $id;
            }
            header("Location: " . $url ."");
            exit();

        } catch (mysqli_sql_exception $e) {
            create_flash_message('delete-failed', $flashMessage['delete-failed-linked'] , FLASH_ERROR);
            // create_flash_message('delete-failed', "An error occurred: " . $e->getMessage() , FLASH_ERROR);

            $url = base_url(false) . "/healthcareProfessional.php?id=" . $id;
            header("Location: " . $url ."");
                
            exit();
        }
    } else if (isset($_FILES['uploadedFile'])) {
        $uploadSignature = $fileUploader->uploadSignature('uploadedFile');

        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    } else if (isset($_POST['deleteFileForm'])) {
        $filePath = $uploadDir . "healthcare-professional-" . $id . "-signature.png";
        
        if (file_exists($filePath)) {
            unlink($filePath);

            create_flash_message('delete-success', "<strong>Success!</strong> File has been successfully deleted. ", FLASH_SUCCESS);
        } else {
            create_flash_message('delete-failed', '<strong>Failed!</strong> Error deleting the file.', FLASH_ERROR);
        }

        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
}


$conn->close();

createMainHeader($headerText, array("Home", "Healthcare Professionals", $headerText));

?>

<form id="healthcare-professional-form" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <input type="hidden" id="prof_id" name="prof_id" value="<?php echo $id; ?>">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

    <main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
        <div class="mx-auto rounded-b-box rounded-b-box max-w-3xl">
            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
                <ul class="flex -mb-px">
                    <li class="w-full bg-white inline-block p-6 text-green-700 border-b-2 border-green-700 active text-left text-sm">
                        Information
                    </li>
                </ul>
            </div>

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
                        
                        <div class="sm:col-span-1">
                            <label for="" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Signature</label>
                            
                            <?php 
                            $filePath = $uploadDir . "healthcare-professional-" . $id . "-signature.png";
        
                            if (file_exists($filePath)) {
                            ?>
                            
                                <div class="border border-gray-300 px-2 py-2 rounded relative">
                                    <img class="mx-auto" id="signature-display-image" src="<?php echo base_url() . '/images/healthcare-professional-' . $id . '-signature.png'; ?>" alt="">
                                    <button type='submit' name='deleteFileForm' title='delete' class='btn btn-sm ml-1 border-0 bg-red-500 hover:bg-red-600 text-white absolute top-2 right-2'>
                                        <span class='sr-only'>Delete</span>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg>
                                    </button>
                                </div>

                            <?php 
                            } else {
                            ?>    
                                <div class="border border-gray-300 px-2 py-2 rounded">
                                    <label for="uploadedFile" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 h-40">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><small class="font-semibold">Click to upload</small></p>
                                            <p id="file-name" class="text-xs text-gray-500"></p>
                                        </div>
                                        <input id="uploadedFile" type="file" value="uploadedFile" name="uploadedFile" class="form-input-upload hidden" />
                                    </label>
                                </div>

                                <script>
                                    document.getElementById('uploadedFile').addEventListener('change', function() {
                                        document.getElementById('healthcare-professional-form').submit();
                                    });
                                </script>
                            <?php 
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
                <a href="<?php echo base_url() . '/healthcareProfessionals.php'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
                <button type="submit" name="saveChanges" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">Save Changes</button>
                <!-- <button data-modal-target="upload-modal" data-modal-toggle="upload-modal" class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto mb-2 sm:mb-0" type="button">Upload Signature</button> -->
                <!-- <button type="submit" name="deleteFileForm" class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto mb-2 sm:mb-0">Delete Signature</button> -->
                <button type="submit" name="delete" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto">Delete Record</button>
            </div>
           
            <?php 
                flash('update-success');
                flash('update-failed');
                flash('update-error');
                
                flash('upload-success');
                flash('upload-error');
                
                flash('create-success');
                flash('create-failed');
                
                flash('delete-failed');
                flash('delete-success');
                flash('delete-failed-linked'); 
            ?>
        </div>
    </main>

    <!-- <div id="upload-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 flex items-center justify-center overflow-y-auto overflow-x-hidden" style="background: rgba(0,0,0,0.5);">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white shadow">
                <div class="flex items-center justify-between p-4 border-b-2 border-sky-500">
                    <h3 class="font-medium text-sky-500 text-sm">Upload Signature</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="upload-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Select File</label>
                        <label for="uploadedFile" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p id="file-name" class="text-xs text-gray-500"></p>
                            </div>
                            <input id="uploadedFile" type="file" name="uploadedFile" class="form-input-upload hidden" onchange="displayConfirmation()" />
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-sky-500">
                    <button data-modal-hide="upload-modal" type="button" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Close</button>
                    <input type="submit" name="uploadFileForm" value="Upload File" class="<?php echo $classBtnAlternate; ?> form-input-upload w-full sm:w-auto">
                </div>
            </div>
        </div>
    </div> -->

    <script>
        const modalToggleButtons = document.querySelectorAll('[data-modal-toggle]');
        const modalHideButtons = document.querySelectorAll('[data-modal-hide]');
        const modalOverlay = document.getElementById('upload-modal');

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
            $('input:not(.form-input-upload)').each( function(key) {
                let id = $(this).attr('id');
                $(this).attr('value', htmlEntityDecode(post[id]));
                $(this).attr('name', id);
                
            });
        }
        
        // $("#signature-display-image").attr("src", "/images/healthcare-professional-" + post['prof_id'] + "-signature.png");

    });

    // function displayConfirmation() {
    //     var input = document.getElementById('uploadedFile');

    //     console.log(input.files[0].name);

    //     document.getElementById('file-name').innerHTML = ((input.files.length > 0)
    //         ? input.files[0].name
    //         : ''
    //     );
    // }


</script>

<?php

include('footer.php');


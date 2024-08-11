<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$id = 0;
$headerText = "User Details";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $user = getUser($id);
    $_POST = $user;
    $headerText = $user['username'];
}

$errVal = $errKey = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = clean( $_POST['id'] );
    $user = getUser($id);
    $headerText = $user['username'];

    if(isset( $_POST['saveChanges'] )) {
        $username = clean( $_POST['username'] );
        $email = clean( $_POST['email'] );
        $role = clean( $_POST['role'] );
        $isActive = clean( $_POST['isActive'] );
        $organizationId = clean( $_POST['organizationId'] );
        
        $userUpdateQuery =  "UPDATE User SET 
                            username = '$username',
                            email = '$email',
                            role = '$role',
                            isActive = '$isActive',
                            organizationId = '$organizationId'
                            WHERE id = $id";


        if ($conn->query($userUpdateQuery) === TRUE) {
            create_flash_message('update-success', '<strong>Update Successful!</strong> User has been successfully updated.', FLASH_SUCCESS);
            
            $url = base_url(false) . "/user.php?id=" . $id;
            header("Location: " . $url ."");
            
            exit();
        } else {
            create_flash_message('update-failed', '<strong>Update Failed!</strong> Please review and try again.', FLASH_ERROR);
        
            // duplicate entry error
            if($conn->errno == 1062) {
                $errMsg = explode("'", $conn->error);
                $errVal = $errMsg[1];
                $errKey = $errMsg[3];
            }
        }
    } else if(isset( $_POST['deleteUser'] )) {
        $userDeleteQuery =  "DELETE FROM User WHERE id = $id";

        if ($conn->query($userDeleteQuery) === TRUE) {
            create_flash_message('delete-success', '<strong>Success!</strong> User has been successfully deleted.', FLASH_SUCCESS);
            
            $url = base_url(false) . "/users.php";
            header("Location: " . $url ."");
            
            exit();
        } else {
            create_flash_message('delete-failed', '<strong>Failed!</strong> An error occurred while deleting the user.', FLASH_ERROR);
        
            if($conn->errno == 1451) {
                create_flash_message('delete-failed', '<strong>Failed!</strong> Unable to delete a user linked to other data.', FLASH_ERROR);
            }
        }
    } else if(isset( $_POST['changePassword'] )) {
        $password = clean( $_POST['password'] );
        $url = base_url(false) . "/user.php?id=" . $id;

        $changePasswordQuery =  "UPDATE User SET password = '$password' WHERE id = $id";

        if ($conn->query($changePasswordQuery) === TRUE) {
            create_flash_message('update-success', '<strong>Change password successful!</strong> User has been successfully updated.', FLASH_SUCCESS);
            header("Location: " . $url ."");

            exit();
        } else {
            create_flash_message('update-failed', '<strong>Change password Failed!</strong> Please review and try again.', FLASH_ERROR);

            // duplicate entry error
            if($conn->errno == 1062) {
                $errMsg = explode("'", $conn->error);
                $errVal = $errMsg[1];
                $errKey = $errMsg[3];
            }

            header("Location: " . $url ."");
            exit();
        }
    }
}

/*
$username = $email = $role = $isActive = $organization = "";

$sql = "SELECT U.*, O.name as organization FROM User U LEFT JOIN Organization O ON U.organizationId = O.id WHERE U.id = $id";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    while($user = $result->fetch_assoc()) {
        $username = $user["username"];
        $email = $user["email"];
        $role = $user["role"];
        $organization = $user["organization"];
        $isActive = $user["isActive"];
    }
}
*/


$conn->close();

createMainHeader($headerText, array("Home", "Users", $headerText));
?>

<main class='<?php echo $classMainContainer; ?>'>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="userUpdateForm" class="prompt-confirm max-w-3xl mx-auto">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <?php createFormHeader("Information"); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3 <?php echo ($errKey == 'username') ? 'input-error' : ''; ?>">
                        <input type="text" id="username" data-label="Username" 
                        title="Username must be 3-20 characters and can only contain letters, numbers, underscores, and hyphens" required />

                        <p class="mt-3 text-gray-600 text-xs">Username must be 3-20 characters and can only contain letters, numbers, underscores, and hyphens</p>
                        
                        <?php 
                        echo ($errKey == 'username') 
                            ? '<div class="input-error--message"><p>' . $errVal . ' already exists.</p></div>' 
                            : ''; 
                        ?>
                    </div>
                    <div class="sm:col-span-3">
                        <input id="email" data-label="Email Address" type="text" required />
                    </div>
                    <div class="sm:col-span-6">
                        <select id="organizationId" data-label="Organization" required>   
                        <?php
                           $getOrganization = getOrganization();
                           foreach ($getOrganization as $org) {
                                echo "<option value='" . $org['id'] . "'  " . (($_POST['organizationId'] == $org['id']) ? 'selected' : '') . ">" . $org['name'] . "</option>";
                           }
                        ?>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <select id="role" data-label="User Role" required>   
                            <option value="2" <?php echo (($_POST['role'] == 2 ? "selected" : "")); ?>>Client Administrator</option>
                            <option value="3" <?php echo (($_POST['role'] == 3 ? "selected" : "")); ?>>Manager</option>
                            <option value="1" <?php echo (($_POST['role'] == 1 ? "selected" : "")); ?>>Admin</option>
                        </select>
                    </div>                
                    <div class="sm:col-span-3">
                        <p class="mt-1 text-gray-600 text-xs"><span class="font-semibold">Client Administrator:</span> A representative from a client company with access to their company’s data and the ability to manage their employees’ accounts.</p>
                        <p class="mt-3 text-gray-600 text-xs"><span class="font-semibold">Manager:</span> Manages specific sections of the system, such as client accounts, patients, or certain data sets.</p>
                        <p class="mt-3 text-gray-600 text-xs"><span class="font-semibold">Admin:</span> Full access to the system, including user management, settings, and sensitive data.</p>
                    </div>                
                    <div class="sm:col-span-3">
                        <select id="isActive" data-label="Account Status" required>   
                            <option value="1" <?php echo (($_POST['isActive'] == 1 ? "selected" : "")); ?>>Active</option>
                            <option value="2" <?php echo (($_POST['isActive'] == 2 ? "selected" : "")); ?>>Inactive</option>
                        </select>
                        <p class="mt-3 text-gray-600 text-xs">Inactive account is not allowed to access the system</p>
                    </div>                
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
            <a href="<?php echo base_url() . '/users.php'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
            <!-- <button type="button" class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto">Reset Password</button> -->
            <button type="submit" name="saveChanges" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">Save Changes</button>
            <button type='button' onClick='changePasswordModal.showModal()' title='Change password' class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto mb-2 sm:mb-0">Change Password</button>
            <input type="submit" name="deleteUser" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto mb-2 sm:mb-0" value="Delete Record" />
        </div>

        <?php flash('update-success'); ?>
        <?php flash('update-failed'); ?>
        <?php flash('delete-success'); ?>
        <?php flash('delete-failed'); ?>

    </form>

    <form id="changePasswordForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <dialog id="changePasswordModal" class="modal">
            <div class="modal-box bg-white rounded-none max-w-2xl p-0">
                    <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                        <h3 class="font-medium text-green-700 text-sm">Change Password</h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onClick="radiologyReportModal.close();">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8 p-4 md:p-5">
                        <div class="sm:col-span-1">
                            <input id="password" type="password" data-label="New Password" required />
                        </div>
                        <div class="sm:col-span-1">
                            <input id="password-confirm" type="password" data-label="Confirm New Password" required />
                        </div>
                    </div>
                    <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700">
                        <button type="button" class="btn <?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0" onClick="changePasswordModal.close();">Cancel</button>
                        <input type="submit" name="changePassword" value="Save Changes" class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    </div>
            </div>
        </dialog>
    </form>

    <div class="p-6 border-b mt-6 max-w-3xl mx-auto">
        <div class="px-4 sm:px-0">
            <h3 class="text-sm font-semibold leading-7 text-gray-900">Admin Account Capabilities and Permissions</h3>
            <!-- <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Personal details and application.</p> -->
        </div>
        <div class="mt-4 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-xs font-medium leading-6 text-gray-900">User Management</dt>
                    <dd class="mt-1 text-xs leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <p>View, create, update, and delete users.</p>
                    </dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-xs font-medium leading-6 text-gray-900">Clinic Management</dt>
                    <dd class="mt-1 text-xs leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <p>View, create, update, and delete clinics.</p>
                        <p>Assign clinics to an organization.</p>
                    </dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-xs font-medium leading-6 text-gray-900">Healthcare Professional Management</dt>
                    <dd class="mt-1 text-xs leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <p>Create, update, and delete healthcare professionals.</p>
                        <p>Assign healthcare professionals to an organization.</p>
                    </dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-xs font-medium leading-6 text-gray-900">Organization Management</dt>
                    <dd class="mt-1 text-xs leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <p>View, create, update, and delete organization information.</p>
                    </dd>
                </div>
                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-xs font-medium leading-6 text-gray-900">Patient Data Management</dt>
                    <dd class="mt-1 text-xs leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <p>View, create, update, print, and delete patient information.</p>
                        <p>View, create, download, update, upload, export, print, and delete patient results.</p>
                        <p>Export a list of all patients within an organization to a CSV file.</p>
                        <p>Import a list of all patients into an organization from a CSV file.</p>
                        <p>Export the APE results of all patients within an organization to a PDF file.</p>
                    </dd>
                </div>
            </dl>            
        </div>
    </div>
    <div class="p-6 border-b mt-6 max-w-3xl mx-auto">
        <div class="px-4 sm:px-0">
            <h3 class="text-sm font-semibold leading-7 text-gray-900">Manager Account Capabilities and Permissions</h3>            
        </div>
        <div class="mt-4 border-t border-gray-100"></div>
    </div>
    <div class="p-6 border-b mt-6 max-w-3xl mx-auto">
        <div class="px-4 sm:px-0">
            <h3 class="text-sm font-semibold leading-7 text-gray-900">Client Administrator Account Capabilities and Permissions</h3>            
        </div>
        <div class="mt-4 border-t border-gray-100"></div>
    </div>
</main>

<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST) ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=date], input[type=email], input[type=password], select').each( function() {
            let id = $(this).attr('id');
            
            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput);  
            $(this).attr('name', id);
        });

        if(Object.keys(post).length !== 0) {
            $('input:not([type=password])').each( function(key) {
                let id = $(this).attr('id');
                $(this).attr('value', post[id]);
                $(this).attr('name', id);
            });
        }
    });

    class FormValidation {
        constructor() {
            this.pattern = /^[a-zA-Z0-9_\-]{3,20}$/;
            this.usernameInput = document.getElementById("username");
            this.usernameInput.addEventListener("input", this.validateUsername.bind(this));
            this.form = document.getElementById("userUpdateForm");
            this.form.addEventListener("submit", this.checkForm.bind(this));
            
            this.passwordInput = document.getElementById("password");
            this.passwordInput.addEventListener("input", this.validatePassword.bind(this));
            this.passwordConfirmInput = document.getElementById("password-confirm");
            this.passwordConfirmInput.addEventListener("input", this.validatePassword.bind(this));
            this.changePasswordForm = document.getElementById("changePasswordForm");
            this.changePasswordForm.addEventListener("submit", this.checkForm.bind(this));
        }

        validateUsername() {
            const username = this.usernameInput.value;

            if (this.pattern.test(username)) {
                this.form.classList.remove("form-error");
                this.form.classList.remove("input-error--username");
            } else {
                this.form.classList.add("form-error");
                this.form.classList.add("input-error--username");
            }
        }

        validatePassword() {
            const password = this.passwordInput.value;
            const passwordConfirm = this.passwordConfirmInput.value;

            if (password == passwordConfirm) {
                this.changePasswordForm.classList.remove("form-error");
                this.passwordInput.classList.remove("input-error");
                this.passwordConfirmInput.classList.remove("input-error");
                this.passwordInput.classList.add("input-valid");
                this.passwordConfirmInput.classList.add("input-valid");
            } else {
                this.changePasswordForm.classList.add("form-error");
                this.passwordInput.classList.add("input-error");
                this.passwordConfirmInput.classList.add("input-error");
                this.passwordInput.classList.remove("input-valid");
                this.passwordConfirmInput.classList.remove("input-valid");
            }

        }

        checkForm(event) {
            if (this.form.classList.contains("form-error")) {
                event.preventDefault();
            }
            if (this.changePasswordForm.classList.contains("form-error")) {
                event.preventDefault();
            }
        }
    }

    const formValidation = new FormValidation();
</script>

<?php
  include('footer.php');
?>

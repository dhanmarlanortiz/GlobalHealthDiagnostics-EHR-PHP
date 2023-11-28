<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$id = 0;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $_POST = getUser($id);
}

$errVal = $errKey = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = clean( $_POST['id'] );
    $username = clean( $_POST['username'] );
    $email = clean( $_POST['email'] );
    $role = clean( $_POST['role'] );
    $isActive = clean( $_POST['isActive'] );
    $organizationId = clean( $_POST['organizationId'] );

    if(isset( $_POST['saveChanges'] )) {

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

createMainHeader("User Details", array("Home", "Users", "User Details"));
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
                            <option value="1" <?php echo (($_POST['role'] == 1 ? "selected" : "")); ?>>Admin</option>
                            <option value="2" <?php echo (($_POST['role'] == 2 ? "selected" : "")); ?>>Client</option>
                        </select>
                        <p class="mt-3 text-gray-600 text-xs"><span class="font-semibold">Admin:</span> Global Health Diagnostics employees</p>
                        <p class="mt-1 text-gray-600 text-xs"><span class="font-semibold">Client:</span> Company HR & Admin Officer</p>
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
            <button type="button" class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto">Reset Password</button>
            <button type="submit" name="saveChanges" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Save Changes</button>
            <input type="submit" name="deleteUser" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto" value="Delete User" />
        </div>

        <?php flash('update-success'); ?>
        <?php flash('update-failed'); ?>
        <?php flash('delete-success'); ?>
        <?php flash('delete-failed'); ?>

    </form>
</main>

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
                $(this).attr('value', post[id]);
                $(this).attr('name', id);
            });
        }
    });

    class FormValidation {
        constructor() {
            this.form = document.getElementById("userUpdateForm");
            this.usernameInput = document.getElementById("username");
            this.pattern = /^[a-zA-Z0-9_\-]{3,20}$/;

            this.usernameInput.addEventListener("input", this.validateUsername.bind(this));
            this.form.addEventListener("submit", this.checkForm.bind(this));
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

        checkForm(event) {
            if (this.form.classList.contains("form-error")) {
                event.preventDefault();
            }
        }
    }

    const formValidation = new FormValidation();
</script>

<?php
  include('footer.php');
?>
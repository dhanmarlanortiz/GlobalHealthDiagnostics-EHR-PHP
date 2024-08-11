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

$orgQuery = "SELECT * FROM Organization";
$OrgResult = $conn->query($orgQuery);

// define variables and set to empty values
$username = $email = $role = $organizationId = $password = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errVal = $errKey = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $email = test_input($_POST["email"]);
    $role = intval(test_input($_POST["role"]));
    $organizationId = intval(test_input($_POST["organizationId"]));
    $password = test_input($_POST["password"]);

    $userQuery = "INSERT INTO User (username, email, role, organizationId, password) 
    VALUES ('$username', '$email', $role, $organizationId, '$password')";

    if ($conn->query($userQuery) === TRUE) {
        create_flash_message('create-success', '<strong>Success!</strong> New user has been successfully created.', FLASH_SUCCESS);

        $id = $conn->insert_id;
        $url = base_url(false) . "/user.php?id=" . $id;
        header("Location: " . $url ."");

        exit();
    } else {
        create_flash_message('create-failed', '<strong>Failed!</strong> Please review and try again.', FLASH_ERROR);

        // duplicate entry error
        if($conn->errno == 1062) {
            $errMsg = explode("'", $conn->error);
            $errVal = $errMsg[1];
            $errKey = $errMsg[3];
        }
    }
}

$conn->close();

createMainHeader("Create User", array("Home", "Users", "Create User"));
?>


<main class='<?php echo $classMainContainer; ?>'>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="userCreateForm" class="prompt-confirm max-w-3xl mx-auto">
        <?php createFormHeader('Information'); ?>
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
                        <input id="email" type="email" data-label="Email" required />
                    </div>
                    
                    <div class="sm:col-span-6">
                        <select id="organizationId" data-label="Organization" required>   
                            <option value="" selected disabled>Select</option>
                        <?php
                            if ($OrgResult->num_rows > 0) {
                                while($org = $OrgResult->fetch_assoc()) {
                                    echo "<option value='" . $org['id'] . "'  " . (($organizationId == $org['id']) ? 'selected' : '') . ">" . $org['name'] . "</option>";
                                }
                            }
                        ?>
                        </select>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <select id="role" data-label="User Role" required>
                            <option value="" selected disabled>Select</option>
                            <option <?php echo ($role == "2") ? "selected" : "" ?> value="2">Client Administrator</option>
                            <option <?php echo ($role == "3") ? "selected" : "" ?> value="3">Manager</option>
                            <option <?php echo ($role == "1") ? "selected" : "" ?> value="1">Admin</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <p class="mt-1 text-gray-600 text-xs"><span class="font-semibold">Client Administrator:</span> A representative from a client company with access to their company’s data and the ability to manage their employees’ accounts.</p>
                        <p class="mt-3 text-gray-600 text-xs"><span class="font-semibold">Manager:</span> Manages specific sections of the system, such as client accounts, patients, or certain data sets.</p>
                        <p class="mt-3 text-gray-600 text-xs"><span class="font-semibold">Admin:</span> Full access to the system, including user management, settings, and sensitive data.</p>
                    </div>
                    <div class="sm:col-span-3">
                        <input id="password" type="password" data-label="Password" required />
                    </div>
                    <div class="sm:col-span-3">
                        <input id="password-confirm" type="password" data-label="Confirm Password" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
            <a href="<?php base_url(); ?>/users.php" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
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

        $('input[type=text], input[type=number], input[type=date], input[type=email], input[type=password], select').each( function() {
            let id = $(this).attr('id');
            
            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput);  
            $(this).attr('name', id);
        });

        $('select').addClass('pr-8');


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
            this.form = document.getElementById("userCreateForm");
            this.form.addEventListener("submit", this.checkForm.bind(this));
            this.pattern = /^[a-zA-Z0-9_\-]{3,20}$/;
            this.usernameInput = document.getElementById("username");
            this.usernameInput.addEventListener("input", this.validateUsername.bind(this));
            this.passwordInput = document.getElementById("password");
            this.passwordInput.addEventListener("input", this.validatePassword.bind(this));
            this.passwordConfirmInput = document.getElementById("password-confirm");
            this.passwordConfirmInput.addEventListener("input", this.validatePassword.bind(this));
        }

        validateUsername() {
            const username = this.usernameInput.value;

            if (this.pattern.test(username)) {
                this.form.classList.remove("form-error");
                this.usernameInput.classList.remove("input-error");
                this.usernameInput.classList.add("input-valid");
            } else {
                this.form.classList.add("form-error");
                this.usernameInput.classList.add("input-error");
                this.usernameInput.classList.remove("input-valid");
            }
        }

        validatePassword() {
            const password = this.passwordInput.value;
            const passwordConfirm = this.passwordConfirmInput.value;

            if (password == passwordConfirm) {
                this.form.classList.remove("form-error");
                this.passwordInput.classList.remove("input-error");
                this.passwordConfirmInput.classList.remove("input-error");
                this.passwordInput.classList.add("input-valid");
                this.passwordConfirmInput.classList.add("input-valid");
            } else {
                this.form.classList.add("form-error");
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
        }
    }

    const formValidation = new FormValidation();

</script>


<?php
include('footer.php');
?>
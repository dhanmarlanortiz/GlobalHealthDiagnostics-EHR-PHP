<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

if(!isset($_SESSION["valid"])){
	$url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
    header("location:" . $url . "/login.php");
    exit();
}
/* AUTHENTICATION - END */

require_once('connection.php');
include('header.php');
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
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="userForm" class="max-w-3xl mx-auto">
        <?php createFormHeader(); ?>
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
                    <div class="sm:col-span-3">
                        <select id="role" data-label="Role" required>
                            <option <?php echo ($role == "1") ? "selected" : "" ?> value="1">Admin</option>
                            <option <?php echo ($role == "2") ? "selected" : "" ?> value="2">Client</option>
                        </select>
                        <p class="mt-3 text-gray-600 text-xs"><span class="font-semibold">Admin:</span> Global Health Diagnostics employees</p>
                        <p class="mt-1 text-gray-600 text-xs"><span class="font-semibold">Client:</span> Company HR & Admin Officer</p>
                    </div>
                    <div class="sm:col-span-3">
                        <select id="organizationId" data-label="Organization" required>   
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
                        <input id="password" type="text" data-label="Password" required />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
            <a href="<?php base_url(); ?>/users.php" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
            <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Save</button>
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
            this.form = document.getElementById("userForm");
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
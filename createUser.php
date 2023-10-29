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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $email = test_input($_POST["email"]);
    $role = intval(test_input($_POST["role"]));
    $organizationId = intval(test_input($_POST["organizationId"]));
    $password = test_input($_POST["password"]);

    $userQuery = "INSERT INTO User (username, email, role, organizationId, password) 
    VALUES ('$username', '$email', $role, $organizationId, '$password')";

    if ($conn->query($userQuery) === TRUE) {
        // $id = $conn->insert_id;
        // $url = base_url(false) . "/user.php?id=" . $id;
        // header("Location: " . $url ."");
    } else {
        
    }
}

$conn->close();

?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Create User</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Users</li> 
                        <li>Create User</li> 
                    </ul>
                </div>
            </div>
            <div>
                <a href="<?php base_url(); ?>/users.php" class="btn btn-default rounded normal-case">Back</a>
            </div>
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl mt-4 px-4 pt-6 pb-20 sm:px-6 lg:px-8'>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="max-w-3xl mx-auto">
        <h2 class="px-6 py-4 bg-gray-200 font-semibold rounded-t-box shadow-sm">Form</h2>
        <div class="flex items-center justify-end gap-x-6 bg-white p-6 shadow-sm">
            <div class="space-y-12 w-full">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label htmlFor="username" class="<?php echo $styleLabel; ?>">Username</label>
                            <div class="mt-2">
                                <input name="username" id="username" value="<?php echo $username; ?>" type="text" class="<?php echo $styleInput; ?>" required />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label htmlFor="email" class="<?php echo $styleLabel; ?>">Email</label>
                            <div class="mt-2">
                                <input name="email" id="email" value="<?php echo $email; ?>" type="email" class="<?php echo $styleInput; ?>" required />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label htmlFor="role" class="<?php echo $styleLabel; ?>">Role</label>
                            <div class="mt-2">
                                <select name="role" id="role" class="<?php echo $styleInput; ?>" required>
                                    <option <?php echo ($role == "1") ? "selected" : "" ?> value="1">Admin</option>
                                    <option <?php echo ($role == "2") ? "selected" : "" ?> value="2">Client</option>
                                </select>
                                <p class="mt-3 text-gray-600 text-xs"><span class="font-semibold">Admin:</span> Global Health Diagnostics employees</p>
                                <p class="mt-1 text-gray-600 text-xs"><span class="font-semibold">Client:</span> Company HR & Admin Officer</p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label htmlFor="organizationId" class="<?php echo $styleLabel; ?>">Organization</label>
                            <div class="mt-2">
                                <select name="organizationId" id="organizationId" class="<?php echo $styleInput; ?>" required>   
                                <?php
                                    if ($OrgResult->num_rows > 0) {
                                        while($org = $OrgResult->fetch_assoc()) {
                                            echo "<option value='" . $org['id'] . "'  " . (($organizationId == $org['id']) ? 'selected' : '') . ">" . $org['name'] . "</option>";
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label htmlFor="password" class="<?php echo $styleLabel; ?>">Password</label>
                            <div class="mt-2">
                                <input name="password" id="password" value="<?php echo $password; ?>" type="text" class="<?php echo $styleInput; ?>" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-x-6 bg-white mt-0 pb-6 px-6 rounded-b-box shadow-sm">
            <a href="<?php base_url(); ?>/users.php" class="<?php echo $styleButtonLink; ?>">Cancel</a>
            <button type="submit" class="<?php echo $styleButtonPrimary; ?>">Save</button>
        </div>
    </form>
</main>

<?php
include('footer.php');
?>
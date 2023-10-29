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

$id = 0;
$username = $email = $role = $isActive = $organization = "";
$styleInput = "block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6";
$styleLabel = "block text-sm font-medium leading-6 text-gray-900";
$styleButtonPrimary = "rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
$styleButtonInfo = "rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700";
$styleButtonSuccess = "rounded-md bg-green-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-700";
$styleButtonDanger = "rounded-md bg-rose-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-700";
$styleButtonLink = "text-sm font-semibold leading-6 text-gray-900";
$styleTextError = "mt-2 text-red-400 text-xs";

if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

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
$conn->close();
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">User Details</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Users</li> 
                        <li>User Details</li> 
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
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="max-w-md mx-auto">
        <h2 class="px-6 py-4 bg-gray-200 font-semibold rounded-t-box shadow-sm">Information</h2>
        <div class="flex items-center justify-end gap-x-6 bg-white p-6 shadow-sm">
            <div class="space-y-12 w-full">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label htmlFor="name" class="<?php echo $styleLabel; ?>">Username</label>
                            <div class="mt-2">
                                <input name="username" id="username" value="<?php echo $username; ?>" type="text" class="<?php echo $styleInput; ?>" required readonly />
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label htmlFor="email" class="<?php echo $styleLabel; ?>">Email Address</label>
                            <div class="mt-2">
                                <input name="email" id="email" value="<?php echo $email; ?>" type="text" class="<?php echo $styleInput; ?>" required readonly />
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label htmlFor="Organization" class="<?php echo $styleLabel; ?>">Organization</label>
                            <div class="mt-2">
                                <input name="organization" id="organization" value="<?php echo $organization; ?>" type="text" class="<?php echo $styleInput; ?>" required readonly />
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label htmlFor="role" class="<?php echo $styleLabel; ?>">User Role</label>
                            <div class="mt-2">
                                <input name="role" id="role" value="<?php echo (($role == 1 ? "Admin" : "Client")); ?>" type="text" class="<?php echo $styleInput; ?>" required readonly />
                            </div>
                        </div>                
                        <div class="sm:col-span-6">
                            <label htmlFor="isActive" class="<?php echo $styleLabel; ?>">Account Status</label>
                            <div class="mt-2">
                                <input name="isActive" id="isActive" value="<?php echo (($isActive == 1 ? "Active" : "Inactive")); ?>" type="text" class="<?php echo $styleInput; ?>" required readonly />
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-x-2 bg-white mt-0 pb-6 px-6 rounded-b-box shadow-sm">
            <!-- <a href="<?php base_url(); ?>/Organizations.php" class="<?php echo $styleButtonLink; ?>">Cancel</a> -->
            <button type="button" class="<?php echo $styleButtonInfo; ?>">Edit</button>
            <button type="button" class="<?php echo $styleButtonSuccess; ?>">Reset Password</button>
            <button type="button" class="<?php echo $styleButtonDanger; ?>">Delete</button>
        </div>
    </form>
</main>


<?php
  include('footer.php');
?>
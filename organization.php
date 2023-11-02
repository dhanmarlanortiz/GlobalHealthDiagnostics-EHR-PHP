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
$name = $email = $phone = $address = "";
$styleInput = "block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6";
$styleLabel = "block text-sm font-medium leading-6 text-gray-900";
$styleButtonPrimary = "rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
$styleButtonInfo = "rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700";
$styleButtonDanger = "rounded-md bg-rose-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-700";
$styleButtonLink = "text-sm font-semibold leading-6 text-gray-900";
$styleTextError = "mt-2 text-red-400 text-xs";

if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT * FROM Organization WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($org = $result->fetch_assoc()) {
        $name = $org["name"];
        $email = $org["email"];
        $phone = $org["phone"];
        $address = $org["address"];
    }
}
$conn->close();
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    <?php echo $name; ?>
                </h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Organizations</li> 
                        <li><?php echo $name; ?></li> 
                    </ul>
                </div>
            </div>
            <div>
                <a href="<?php base_url(); ?>/organizations.php" class="btn btn-default rounded normal-case">Back</a>
            </div>
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl mt-4 px-4 pt-6 pb-20 sm:px-6 lg:px-8'>
   
</main>


<?php
  include('footer.php');
?>
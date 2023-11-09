<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();



// if(!isset($_SESSION["valid"])){
// 	$url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
//     header("location:" . $url . "/login.php");
//     exit();
// }
/* AUTHENTICATION - END */

require_once('../connection.php');
include('../header.php');

preventAccess([['role' => 1, 'redirect' => 'home.php']]);

include('navbar.php');

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$o = $_SESSION['organizationId'];
$y = date("Y");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $y = test_input( $_POST['y'] );
}

$empQuery = "SELECT * FROM APE WHERE organizationId = '$o' AND (dateRegistered BETWEEN '$y-01-01' AND '$y-12-31')";
$empResult = $conn->query($empQuery);

$orgQuery = "SELECT * FROM Organization WHERE id = $_SESSION[organizationId]";
$orgResult = $conn->query($orgQuery);

if ($orgResult !== false && $orgResult->num_rows > 0) {
    while($org = $orgResult->fetch_assoc()) {
        $_SESSION['organizationName'] = $org['name'];
    }
}
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    <?php echo $_SESSION['organizationName']; ?>
                </h1>
                <!--                    
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Username: <?php echo $_SESSION['username']; ?></li> 
                    </ul>
                </div>
                -->
            </div>
            <div>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="flex flex-col sm:flex-row sm:items-center max-w-2xl">
                    <label for="d" class="block text-sm font-medium leading-6 text-gray-900 mb-2 sm:mb-0 sm:mr-4 sm:ml-6 sm:text-right">APE&nbsp;Year</label>
                    <input type="number" id="y" name="y" min="1900" max="2099" step="1" value="<?php echo $y; ?>" class="mb-5 sm:mb-0 sm:w-30 block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" required />
                    <button type="submit" class="sm:ml-4 rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold shadow-sm hover:bg-gray-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">Search</button>
                </form>
            </div>
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl mt-4 px-4 pt-6 pb-20 sm:px-6 lg:px-8'>
    

    <div class="bg-white p-6 rounded shadow-sm">
        <?php
        if ($empResult !== false && $empResult->num_rows > 0) {
            echo    
            "<div class='p-1 overflow-auto'>
                <table class='display compact'>
                    <thead>
                        <tr>
                            <th>Head Count</th>
                            <th>Full Name</th>
                            <th>Control Number</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Membership</th>
                            <th>Department</th>
                            <th>Level</th>
                            <th>Examination</th>
                            <th>Date Registered</th>
                            <th>Date Completed</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";
                        while($emp = $empResult->fetch_assoc()) {
                            $dateCompleted = isset($emp["dateCompleted"]) ? date("M d, 2023", strtotime($emp["dateCompleted"])) : "";
                        echo 
                        "<tr>" .
                            "<td class='text-center'>" . $emp["headCount"] . "</td>" .
                            "<td>" . $emp["lastName"] . ", " . $emp["firstName"] . ", " . $emp["middleName"] .  "</td>" .
                            "<td class='text-center'>" . $emp["controlNumber"] . "</td>" .
                            "<td class='text-center'>" . $emp["age"] . "</td>" .
                            "<td>" . $emp["sex"] . "</td>" .
                            "<td>" . $emp["membership"] . "</td>" .
                            "<td>" . $emp["department"] . "</td>" .
                            "<td>" . $emp["level"] . "</td>" .
                            "<td>" . $emp["examination"] . "</td>" .
                            "<td>" . date("M d, 2023", strtotime($emp["dateRegistered"])) . "</td>" .
                            "<td>" . $dateCompleted . "</td>" .
                            "<td>" . $emp["remarks"] . "</td>" .
                            "<td class='text-center'>
                                <a class='text-blue-600' href='#'>
                                    View Result
                                </a>
                            </td>".
                        "</tr>";
                        }
                    echo
                    "</body>" .
                "</table>
            </div>";
        } else {
            echo "Results not found.";
        }
        $conn->close();
        ?>




  </div>
</main>

<?php
  include('../footer.php');
?>
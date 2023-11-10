<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

require_once('../connection.php');
include('../header.php');

preventAccess([['role' => 1, 'redirect' => 'home.php']]);

include('navbar.php');

$o = $_SESSION['organizationId'];
$y = date("Y");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $y = clean( isset($_GET['y']) ? $_GET['y'] : date("Y") );
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $y = clean( $_POST['y'] );
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

createMainHeader($_SESSION['organizationName'], array("Annual Physical Examination"));
?>

<main class='<?php echo $classMainContainer; ?>'>
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex -mb-px">
            <li class="mr-2">
                <a href="" class="inline-block p-2 md:p-4 text-green-700 border-b-2 border-green-700 rounded-t-lg active text-left text-xs md:text-sm" aria-current="page">
                    Annual Physical Examination
                </a>
            </li>
            <li class="mr-2">
                <a href="#" class="inline-block p-2 md:p-4 border-b-2 border-transparent rounded-t-lg text-left text-xs md:text-sm hover:text-gray-600 hover:border-gray-300">
                    Pre-employment Medical Assessment
                </a>
            </li>
        </ul>
    </div>
    <div class="bg-white p-2 md:p-4">
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
                                <a class='" . $classTblBtnPrimary . "' href='" . base_url(false) . "/employee-APE.php?id=" . $emp['id'] . "'>
                                    View
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
    <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
        <div class="flex sm:justify-between flex-col sm:flex-row">
            <div class="dataTables_year p-1">
                <form id="searchYear-APE" method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="flex flex-col sm:flex-row sm:items-center max-w-2xl">
                    <label>
                        Year: <input id="y" value="<?php echo $y; ?>" type="number" id="y" name="y" min="1900" max="2099" step="1"  class="border placeholder-gray-500 ml-2 px-3 py-2 rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required />
                    </label>
                    <input type="hidden" name="o" value="<?php echo $o; ?>">
                </form>
                <script>
                    document.getElementById("y").onchange = function () {
                        document.getElementById("searchYear-APE").submit();
                    };
                </script>
            </div>
        </div>
    </div>
</main>

<?php
  include('../footer.php');
?>
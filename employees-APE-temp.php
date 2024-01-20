<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$o = $y = 0;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $o = test_input( isset($_GET['o']) ? $_GET['o'] : 0 );
    $y = test_input( isset($_GET['y']) ? $_GET['y'] : date("Y") );
    $empQuery = "SELECT * FROM APE WHERE organizationId = '$o' AND YEAR(dateRegistered) = '$y'";
    $empResult = $conn->query($empQuery);
}

$orgQuery = "SELECT * FROM Organization";
$orgResult = $conn->query($orgQuery);

$organizationName = "";
$orgDetailsQuery = "SELECT * FROM Organization WHERE id = '$o'";
$orgDetailsResult = $conn->query($orgDetailsQuery);
if ($orgDetailsResult !== false && $orgDetailsResult->num_rows > 0) {
    while($orgDetails = $orgDetailsResult->fetch_assoc()) {
        $organizationName = $orgDetails['name'];
    }
}

?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center overflow-hidden">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    <span class="pr-3"><?php echo $organizationName; ?></span>
                    <span class="font-normal text-xl border-l-2 border-green-700 pl-3">APE</span>
                </h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Organizations</li> 
                        <li><?php echo $organizationName;?></li>
                        <li>Annual Physical Examination</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<main class='<?php echo $classMainContainer; ?>'>
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex -mb-px">
            <li class="mr-2">
                <a href="" class="inline-block p-2 md:p-4 text-green-700 border-b-2 border-green-700 rounded-t-lg active text-left text-xs md:text-sm" aria-current="page">
                    Annual Physical Examination
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
                            <th style='max-width: 54px; text-align: center;'>Head Count</th>
                            <th>Full Name</th>
                            <th style='max-width: 74px; text-align: center;'>Control Number</th>
                            <th style='max-width: 20px; text-align: center;'>Age</th>
                            <th style='max-width: 40px;'>Gender</th>
                            <th>Remarks</th>
                            <th style='max-width: 54px;'></th>
                        </tr>
                    </thead>
                    <tbody>";
                        while($emp = $empResult->fetch_assoc()) {

                            $dateCompleted = isset($emp["dateCompleted"]) ? date("M d, 2023", strtotime($emp["dateCompleted"])) : "";

                        echo 
                            "<tr>" .
                                "<td class='text-center'>" . $emp["headCount"] . "</td>" .
                                "<td>
                                    <a href='" . base_url(false) . "/employee-APE.php?id=" . $emp['id'] . "' class='whitespace-nowrap uppercase text-green-700'>" . 
                                        $emp["lastName"] . ", " . $emp["firstName"] . ", " . $emp["middleName"] .  
                                    "</a>
                                </td>" .
                                "<td class='text-center'>" . $emp["controlNumber"] . "</td>" .
                                "<td class='text-center'>" . $emp["age"] . "</td>" .
                                "<td class=''>" . $emp["sex"] . "</td>" .
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
            echo "<span class='text-sm'>Results not found.</span>";
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
            <div class="p-1">
                <a href="<?php base_url()?>/organizations.php" class="btn btn-default btn-sm text-xs rounded normal-case h-9 w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                <a href="<?php echo base_url(false) . "/employeesImport-APE.php?o=" . $o . "&y=" . $y; ?>" class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto mb-2 sm:mb-0">Import Data</a>
                <a href="<?php echo base_url(false) . "/employeeCreate-APE.php?o=" . $o . "&y=" . $y; ?>" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">APE Registration</a>
                
                <?php
                $employeeClearDataAPE = ['o' => $o,'y' => $y];
                $encodeEmployeeClearDataAPE = base64_encode(json_encode( $employeeClearDataAPE ));
                ?>
                <form id="employeeClear-APE" method="POST" action="<?php echo htmlspecialchars(base_url(false) . "/employeeClear-APE.php?q='$encodeEmployeeClearDataAPE'");?>" class="prompt-confirm inline-block w-full sm:w-auto">
                    <button type="submit" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto hidden">Delete All Records</button>
                <form>
            </div>
        </div>
    </div>

    <?php 
        flash('delete-success');
        flash('delete-failed');
    ?>
</main>


<?php
  include('footer.php');
?>
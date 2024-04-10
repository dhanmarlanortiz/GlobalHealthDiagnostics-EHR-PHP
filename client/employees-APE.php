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

$empQuery = "SELECT * FROM APE WHERE organizationId = '$o' AND YEAR(dateRegistered) = '$y'";
$empResult = $conn->query($empQuery);

$empResultArray = array();
while ($row = mysqli_fetch_assoc($empResult)) {
    $empResultArray[] = $row;
}

$empJSON = json_encode($empResultArray);

$orgQuery = "SELECT * FROM Organization WHERE id = $_SESSION[organizationId]";
$orgResult = $conn->query($orgQuery);

if ($orgResult !== false && $orgResult->num_rows > 0) {
    while($org = $orgResult->fetch_assoc()) {
        $_SESSION['organizationName'] = $org['name'];
    }
}

createMainHeader($_SESSION['organizationName'], array("Annual Physical Examination"));

print_r($empResult->fetch_assoc());
?>

<main class='<?php echo $classMainContainer; ?>'>
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex -mb-px">
            <li class="mr-2">
                <a href="" class="inline-block p-2 md:p-4 text-green-700 border-b-2 border-green-700 rounded-t-lg active text-left text-xs md:text-sm" aria-current="page">
                    Annual Physical Examination
                </a>
            </li>
            <!-- <li class="mr-2">
                <a href="#" class="inline-block p-2 md:p-4 border-b-2 border-transparent rounded-t-lg text-left text-xs md:text-sm hover:text-gray-600 hover:border-gray-300">
                    Pre-employment Medical Assessment
                </a>
            </li> -->
        </ul>
    </div>
    
    <div class="bg-white p-2 md:p-4">
        <div class='p-1 overflow-auto'>
            <table id="employee-ape-table-json" class="display custom-datatable">
                <thead>
                    <tr>
                        <th style='max-width: 54px;'>Head Count</th>
                        <th>Full Name</th>
                        <th style='max-width: 74px;'>Control Number</th>
                        <th style='max-width: 20px;'>Age</th>
                        <th style='max-width: 40px;'>Gender</th>
                        <th style='max-width: 250px;'>Remarks</th>
                        <th style='max-width: 54px;'></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            var empJSON = <?php echo $empJSON; ?>;
    
            $('#employee-ape-table-json').DataTable({
                "data": empJSON,
                "columns": [
                    {"data": "headCount"},
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return row.lastName + ', ' + row.firstName + ', ' + row.middleName;
                        }
                    },
                    {"data": "controlNumber"},
                    {"data": "age"},
                    {"data": "sex"},
                    {"data": "remarks"},
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return '<a class="<?php echo $classTblBtnPrimary; ?>" href="<?php echo base_url(false); ?>/employee-APE.php?id=' + row.id + '">View</a>';
                        }
                    },
                ],
                "stateSave": true,
                pageLength: 50
            });
        });
    </script>

    
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
                <a href="<?php echo base_url(false) . "/employeesExport-APE.php?o=" . $o . "&y=" . $y; ?>" class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto mb-2 sm:mb-0" id="export-result-button">Export Results</a>
            </div>
        </div>
    </div>
</main>

<?php
  include('../footer.php');
?>
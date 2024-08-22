<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();


require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);

$role = $_SESSION['role'];
if($role == 1) {
    include('navbar.php');    
} else if($role == 3) {
    include('manager/navbar.php');
}

$o = $y = 0;
$empJSON = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && $role == 1) {
    $o = clean( isset($_GET['o']) ? $_GET['o'] : 0 );
    $y = clean( isset($_GET['y']) ? $_GET['y'] : date("Y") );    
}
 
if($role == 3) {
    $o = clean( isset($_SESSION['organizationId']) ? $_SESSION['organizationId'] : 0 );
    $y = clean( isset($_GET['y']) ? $_GET['y'] : date("Y") );
    
    $organizationName = (null !== getOrganization($o)) ? getOrganization($o)['name'] : "";
    createMainHeader($organizationName, array("Annual Physical Examination"));
}

$apeDetails = fetchApeDetailsByOrgAndYear($conn, $o, $y);
$empJSON = json_encode($apeDetails);

if($role == 1) {
    $organizationName = (null !== getOrganization($o)) ? getOrganization($o)['name'] : "";
    createMainHeader($organizationName, array("Home", "Organizations", $organizationName, "Annual Physical Examination"), "APE");    
}

?>
<main class='<?php echo $classMainContainer; ?>'>
    <!-- <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex -mb-px">
            <li class="mr-2">
                <a href="" class="inline-block p-2 md:p-4 text-green-700 border-b-2 border-green-700 rounded-t-lg active text-left text-xs md:text-sm" aria-current="page">
                    Annual Physical Examination
                </a>
            </li>
        </ul>
    </div> -->
    <?php createFormHeader('Annual Physical Exam Roster'); ?>
    <div class="bg-white p-2 md:p-4">
        <div class='p-1 overflow-auto'>
            <table id="employee-ape-table-json" class="display custom-datatable">
                <thead>
                    <tr>
                        <th style='max-width: 54px;'>Head Count</th>
                        <th class='whitespace-nowrap'>Employee No.</th>
                        <th class='whitespace-nowrap'>Full Name</th>
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
                    {"data": "employeeNumber"},
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            var mName = (row.middleName.length > 0 ? (', ' + row.middleName) : '');
                            var fName = (row.firstName.length > 0 ? (', ' + row.firstName) : '');
                            return row.lastName + fName + mName;
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
        <div class="flex md:justify-between flex-col md:flex-row">
            <div class="dataTables_year p-1">
                <form id="searchYear-APE" method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="flex flex-col sm:flex-row sm:items-center max-w-2xl">                    
                    <label>
                        Year: <input id="y" value="<?php echo $y; ?>" type="number" id="y" name="y" min="1900" max="2099" step="1"  class="border placeholder-gray-500 ml-2 px-3 py-2 rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required />
                    </label>
                    <?php 
                    if($role == 1) {
                        echo '<input type="hidden" name="o" value="'. $o .'">';
                    }
                    ?>
                </form>
                <script>
                    document.getElementById("y").onchange = function () {
                        document.getElementById("searchYear-APE").submit();
                    };
                </script>
            </div>
            <div class="p-1 flex flex-wrap">

                <?php 
                $organizations = "";
                $employeesImportAPE = base_url(false) . '/employeesImport-APE.php?o=' . $o . '&y=' . $y;
                $employeesCSVAPE = base_url(false) . '/employeesCSV-APE.php?o=' . $o . '&y=' . $y;
                $employeesExportAPE = base_url(false) . '/employeesExport-APE.php?o=' . $o . '&y=' . $y;
                $employeeCreateAPE = base_url(false) . '/employeeCreate-APE.php?o=' . $o . '&y=' . $y;

                if($role == 1) {
                    $organizations = base_url(false) . '/organizations.php';
                    
                } else if($role == 3) {
                    $organizations = base_url(false) . '/manager';
                }

                echo '<a href="' . $organizations . '" class="btn btn-default btn-sm text-xs rounded normal-case h-9 w-full sm:w-auto grow mb-2 sm:mb-0 mr-0 sm:mr-2">Back</a>';
                echo '<a href="' . $employeesImportAPE . '" class="'. $classBtnAlternate . ' w-full sm:w-auto grow mb-2 sm:mb-0 mr-0 sm:mr-2">Import Data</a>';
                echo '<a href="' . $employeesCSVAPE . '" class="'. $classBtnSuccess . ' w-full sm:w-auto grow mb-2 sm:mb-0 mr-0 sm:mr-2" id="export-csv-button">Export CSV</a>';
                echo '<a href="' . $employeesExportAPE . '" class="'. $classBtnSecondary . ' w-full sm:w-auto grow mb-2 sm:mb-0 mr-0 sm:mr-2" id="export-result-button">Export Results</a>';
                echo '<a href="' . $employeeCreateAPE . '" class="'. $classBtnPrimary . ' w-full sm:w-auto grow mb-2 sm:mb-0">APE Registration</a>';

                
                $employeeClearDataAPE = ['o' => $o,'y' => $y];
                $encodeEmployeeClearDataAPE = base64_encode(json_encode( $employeeClearDataAPE ));
                ?>
                <form id="employeeClear-APE" method="POST" action="<?php echo htmlspecialchars(base_url(false) . "/employeeClear-APE.php?q='$encodeEmployeeClearDataAPE'");?>" class="prompt-confirm inline-block w-full sm:w-auto">
                    <button type="submit" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto hidden" disabled>Delete All Records</button>
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
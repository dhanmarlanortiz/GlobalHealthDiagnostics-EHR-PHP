<?php
ob_start();
session_start();

require_once('connection.php');
include('header.php');

preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);

$role = $_SESSION['role'];

/* SET NAV - START */
if($role == 1) {
    include('navbar.php');    
} else if($role == 3) {
    include('manager/navbar.php');
}
/* SET NAV - END */

$o = $y = $id = 0 ;

if ($_SERVER["REQUEST_METHOD"] == "GET" && $role == 1) {
    $o = clean(isset($_GET['o']) ? $_GET['o'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));
    $id = clean(isset($_GET['id']) ? $_GET['id'] : 0);
} 

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $o = clean(isset($_POST['o']) ? $_POST['o'] : 0);
    $y = clean(isset($_POST['y']) ? $_POST['y'] : date("Y"));
    $id = clean(isset($_POST['id']) ? $_POST['id'] : 0);
}

// $orgDetailsResult = fetchOrgDetailsById($conn, $o);
// $organizationName = (null !== $orgDetailsResult) ? $orgDetailsResult["name"] : "Not Found";

/* SET HEADER BAR - START */
$organizationName  = $breadcrumbsArray = "";
if($role == 1) {
    $orgDetailsResult = fetchOrgDetailsById($conn, $o);
    $organizationName = (null !== $orgDetailsResult) ? $orgDetailsResult["name"] : "Not Found";
    $breadcrumbsArray = array("Home", "Organizations", $organizationName, "Annual Physical Examination", "Export Record (CSV)");
} else if($role == 2) {
    $o = clean(isset($_SESSION['organizationId']) ? $_SESSION['organizationId'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));

    $organizationName = (null !== getOrganization($o)) ? getOrganization($o)['name'] : "";
    $breadcrumbsArray = array("Annual Physical Examination", "Export Record (CSV)");
} else if($role == 3) {
    $o = clean(isset($_SESSION['organizationId']) ? $_SESSION['organizationId'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));

    $organizationName = (null !== getOrganization($o)) ? getOrganization($o)['name'] : "";
    $breadcrumbsArray = array("Annual Physical Examination", "Export Record (CSV)");
}

// createMainHeader($organizationName, array("Home", "Organizations", $organizationName, "Annual Physical Examination", "Export Record (CSV)"), "Export Record (CSV)");
createMainHeader($organizationName, $breadcrumbsArray, "Export Record (CSV)");
/* SET HEADER BAR - END */


/* CSV VARIABLES */
$folder = 'csv/';
$csvFilename = strtolower($organizationName);
$csvFilename = str_replace("&amp;", "and", $csvFilename);
$csvFilename = html_entity_decode($csvFilename);
$csvFilename = preg_replace('/[^a-zA-Z0-9\s]/', '', $csvFilename);
$csvFilename = str_replace(' ', '-', $csvFilename);
$csvFilename = $csvFilename. "-records-" . $y . ".csv";
$csvPath = $folder. $csvFilename;


if(isset($_POST['generate'])) {
    $sql = "SELECT controlNumber AS 'Control No.', employeeNumber AS 'Employee No.', firstName AS 'First Name', middleName AS 'Middle Name', lastName AS 'Last Name', sex AS 'Sex', age AS 'Age', dateRegistered AS 'Date Registered', remarks AS 'Remarks' FROM APE WHERE organizationId = '$o'";
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $file = fopen($csvPath, 'w');

        $column_headers = [];
        while ($column = $result->fetch_field()) {
            $column_headers[] = $column->name;
        }
        fputcsv($file, $column_headers);

        while ($row = $result->fetch_assoc()) {
            fputcsv($file, $row);
        }

        fclose($file);
       
        create_flash_message('create-success', '<strong>Success!</strong> Report has been generated.', FLASH_SUCCESS);

        $url = base_url(false) . "/employeesCSV-APE.php?o=" . $o . "&y=" . $y;
        header("Location: " . $url ."");
        exit();
    } else {
        create_flash_message('create-failed', '<strong>Failed!</strong> Please review and try again.', FLASH_ERROR);
    }

}




?>

<main class='<?php echo $classMainContainer; ?>'>

    <div class="bg-white p-2 md:p-4">
        <div class='p-1 overflow-auto'>
            
        </div>
        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#6b7280">
                        <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V304H176c-35.3 0-64 28.7-64 64V512H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM200 352h16c22.1 0 40 17.9 40 40v8c0 8.8-7.2 16-16 16s-16-7.2-16-16v-8c0-4.4-3.6-8-8-8H200c-4.4 0-8 3.6-8 8v80c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8v-8c0-8.8 7.2-16 16-16s16 7.2 16 16v8c0 22.1-17.9 40-40 40H200c-22.1 0-40-17.9-40-40V392c0-22.1 17.9-40 40-40zm133.1 0H368c8.8 0 16 7.2 16 16s-7.2 16-16 16H333.1c-7.2 0-13.1 5.9-13.1 13.1c0 5.2 3 9.9 7.8 12l37.4 16.6c16.3 7.2 26.8 23.4 26.8 41.2c0 24.9-20.2 45.1-45.1 45.1H304c-8.8 0-16-7.2-16-16s7.2-16 16-16h42.9c7.2 0 13.1-5.9 13.1-13.1c0-5.2-3-9.9-7.8-12l-37.4-16.6c-16.3-7.2-26.8-23.4-26.8-41.2c0-24.9 20.2-45.1 45.1-45.1zm98.9 0c8.8 0 16 7.2 16 16v31.6c0 23 5.5 45.6 16 66c10.5-20.3 16-42.9 16-66V368c0-8.8 7.2-16 16-16s16 7.2 16 16v31.6c0 34.7-10.3 68.7-29.6 97.6l-5.1 7.7c-3 4.5-8 7.1-13.3 7.1s-10.3-2.7-13.3-7.1l-5.1-7.7c-19.3-28.9-29.6-62.9-29.6-97.6V368c0-8.8 7.2-16 16-16z"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500">
                        <span class="font-semibold">
                            <?php
                                if(file_exists($csvPath)){
                                    echo $csvFilename;
                                    echo "<span class='text-xs text-gray-500 font-medium block text-center mt-2'>Generated: " . date ("F d Y H:i:s", filemtime($csvPath)) . "</span>";
                                } else {
                                    echo "Not Available";
                                }
                            ?>
                        </span>
                    </p>
                </div>
            </label>
        </div> 
    </div>

    <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
        <div class="flex sm:justify-between flex-col sm:flex-row">
            <div class="dataTables_year p-1 flex items-center">
                <p class="text-xs text-gray-500"></p>
            </div>
            <div class="p-1">
                <?php 
                    $employeesApeUrl = base_url(false);

                    if($role == 1) {
                        $employeesApeUrl = base_url(false) . "/employees-APE.php?o=" . $o . "&y=" . $y;
                    } else if($role == 2) {
                        $employeesApeUrl = base_url(false) . "/client/employees-APE.php?o=" . $o . "&y=" . $y;
                    } else if($role == 3) {
                        $employeesApeUrl = base_url(false) . "/employees-APE.php?o=" . $o . "&y=" . $y;
                    }
                ?>
                <a href="<?php echo $employeesApeUrl; ?>" class="btn btn-default btn-sm text-xs rounded normal-case h-9 w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?o=" . $o . "&y=" . $y ;?>" class=" inline-block w-full sm:w-auto /prompt-confirm">
                    <button id="generate-result-button" type="submit" name='generate' class="<?php echo $classBtnSecondary; ?> w-full sm:w-auto mb-2 sm:mb-0">Generate</button>
                    <input type="hidden" name="o" value="<?php echo $o; ?>">
                    <input type="hidden" name="y" value="<?php echo $y; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                <form>

                <?php 
                    if(file_exists($csvPath)){
                        echo "<a href='$csvPath' class='btn btn-primary btn-sm text-xs rounded normal-case h-9 w-full sm:w-auto mb-2 sm:mb-0' download>Download</a>";
                    }
                ?>
            </div>
        </div>
    </div>    

    <?php flash('create-success'); ?>
    <?php flash('create-failed'); ?>

</main>

<?php
  include('footer.php');
?>
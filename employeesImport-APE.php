<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$o = $y = 0;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $o = clean( isset($_GET['o']) ? $_GET['o'] : 0 );
    $y = clean( isset($_GET['y']) ? $_GET['y'] : date("Y") );
    // $empQuery = "SELECT * FROM APE WHERE organizationId = '$o' AND (dateRegistered BETWEEN '$y-01-01' AND '$y-12-31')";
    // $empResult = $conn->query($empQuery);
}


$organizationName = "";
$orgDetailsQuery = "SELECT * FROM Organization WHERE id = '$o'";
$orgDetailsResult = $conn->query($orgDetailsQuery);;
if ($orgDetailsResult !== false && $orgDetailsResult->num_rows > 0) {
    while($orgDetails = $orgDetailsResult->fetch_assoc()) {
        $organizationName = $orgDetails['name'];
    }
}


class CSVImporter
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function importCSV($file)
    {
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

        if (!empty($file['name']) && in_array($file['type'], $csvMimes)) {
            if (is_uploaded_file($file['tmp_name'])) {
                $csvFile = fopen($file['tmp_name'], 'r');

                fgetcsv($csvFile);

                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    $this->processCSVLine($line);
                }

                fclose($csvFile);
                return true;
            }
        }

        return false;
    }

    private function processCSVLine($line)
    {
        $name   = $line[0];
        $email  = $line[1];
        $phone  = $line[2];
        $status = $line[3];

        $prevQuery = "SELECT id FROM members WHERE email = '" . $email . "'";
        $prevResult = $this->conn->query($prevQuery);

        if ($prevResult->num_rows > 0) {
            $this->conn->query("UPDATE members SET name = '" . $name . "', phone = '" . $phone . "', status = '" . $status . "', modified = NOW() WHERE email = '" . $email . "'");
        } else {
            $this->conn->query("INSERT INTO members (name, email, phone, created, modified, status) VALUES ('" . $name . "', '" . $email . "', '" . $phone . "', NOW(), NOW(), '" . $status . "')");
        }
    }
}

if (isset($_POST['importSubmit'])) {
    $csvImporter = new CSVImporter($conn);
    if ($csvImporter->importCSV($_FILES['file'])) {
        $qstring = '?status=succ';
    } else {
        $qstring = '?status=err';
    }
} else {
    $qstring = '?status=invalid_file';
}

createMainHeader($organizationName, array("Home", "Organizations", $organizationName, "Annual Physical Examination", "Import Data"));
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
        
        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">CSV</p>
                </div>
                <input id="dropzone-file" type="file" class="hidden" onchange="displayConfirmation()"  />
            </label>
        </div> 

        <script>
            function displayConfirmation() {
                var input = document.getElementById('file-input');
                var fileName = document.getElementById('file-name');
                var label = document.getElementById('file-label');

                // Check if a file is selected
                if (input.files.length > 0) {
                    // Display the file name
                    fileName.innerHTML = 'Selected File: ' + input.files[0].name;
                } else {
                    // If no file is selected, display a default message
                    fileName.innerHTML = 'No file chosen';
                }
            }
        </script>


        
        
        
        <!-- <form action="importData.php" method="post" enctype="multipart/form-data">


            <input type="file" name="file" />
            <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
        </form> -->
    </div>
    <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
        <div class="flex sm:justify-between flex-col sm:flex-row">
            <div class="p-1">
                <a href="<?php echo base_url(false) . "/employees-APE.php?o=" . $o . "&y=" . $y; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                <a href="<?php echo base_url(false) . "/employeesImport-APE.php?o=" . $o . "&y=" . $y; ?>" class="<?php echo $classBtnAlternate; ?> w-full sm:w-auto mb-2 sm:mb-0">Import Data</a>
                <a href="<?php echo base_url(false) . "/employeeCreate-APE.php?o=" . $o . "&y=" . $y; ?>" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">APE Registration</a>
            </div>
        </div>
    </div>
</main>



<?php
  include('footer.php');
?>

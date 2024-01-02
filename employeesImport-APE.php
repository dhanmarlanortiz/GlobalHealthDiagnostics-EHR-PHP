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
    $o = clean(isset($_GET['o']) ? $_GET['o'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));
}

$organizationName = "";
$orgDetailsQuery = "SELECT * FROM Organization WHERE id = ?";
$orgDetailsStmt = $conn->prepare($orgDetailsQuery);
$orgDetailsStmt->bind_param("i", $o);
$orgDetailsStmt->execute();
$orgDetailsResult = $orgDetailsStmt->get_result();

if ($orgDetailsResult !== false && $orgDetailsResult->num_rows > 0) {
    while ($orgDetails = $orgDetailsResult->fetch_assoc()) {
        $organizationName = $orgDetails['name'];
    }
}
$orgDetailsStmt->close();

class CSVImporter
{
    private $conn;
    private $successfulInserts;
    private $failedInserts;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->successfulInserts = [];
        $this->failedInserts = [];
    }

    public function importCSV($file, $o)
    {
        if (!empty($file['name']) && pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {

            if (is_uploaded_file($file['tmp_name'])) {
                $csvFile = fopen($file['tmp_name'], 'r');

                fgetcsv($csvFile);

                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    // Use the processCSVLine result to update successful and failed inserts lists
                    $result = $this->processCSVLine($line, $o);
                    if ($result) {
                        $this->successfulInserts[] = $line;
                    } else {
                        $this->failedInserts[] = $line;
                    }
                }

                fclose($csvFile);
                return true; // Return true only if all lines are successfully processed
            }
        }

        return false;
    }

    private function processCSVLine($line, $o)
{
    $firstName = $line[0];
    $middleName = $line[1];
    $lastName = $line[2];
    $age = $line[3];
    $sex = $line[4];
    $civilStatus = $line[5];
    $homeAddress = $line[6];
    $employeeNumber = $line[7];
    $remarks = $line[8];

    // Check if dateRegistered is null, and set it to the current date
    // if ($dateRegistered === null) {
        $dateRegistered = date("Y-m-d");
    // }

    $prevQuery = "SELECT id FROM APE WHERE 
                    firstName = ? AND 
                    middleName = ? AND 
                    lastName = ? AND
                    organizationId = ? AND
                    dateRegistered = ? ";

    $prevStmt = $this->conn->prepare($prevQuery);
    $prevStmt->bind_param("sssis", $firstName, $middleName, $lastName, $o, $dateRegistered);
    $prevStmt->execute();
    $prevResult = $prevStmt->get_result();

    $PrevId = 0;
    if ($prevResult->num_rows > 0) {
        $prevData = $prevResult->fetch_assoc();
        $PrevId = $prevData['id'];

        $updateQuery = "UPDATE APE SET
                age = ?,
                sex = ?,
                civilStatus = ?,
                homeAddress = ?,
                employeeNumber = ?,
                remarks = ?,
                WHERE id = ?";

        $updateStmt = $this->conn->prepare($updateQuery);
        $updateStmt->bind_param("ssssssi", $age, $sex, $civilStatus, $homeAddress, $employeeNumber, $remarks, $PrevId);
        $result = $updateStmt->execute();
        $updateStmt->close();
    } else {
        $insertQuery = "INSERT INTO APE (
            headCount,
            firstName,
            middleName,
            lastName,
            age,
            sex,
            civilStatus,
            homeAddress,
            employeeNumber,
            remarks,
            dateRegistered,
            organizationId,
            userId
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";
        
        $headCount = getHeadCountAPE($o, date("Y", strtotime($dateRegistered)));
        $insertStmt = $this->conn->prepare($insertQuery);
        $insertStmt->bind_param("issssssssssii", $headCount, $firstName, $middleName, $lastName, $age, $sex, $civilStatus, $homeAddress, $employeeNumber, $remarks, $dateRegistered, $o, $_SESSION['userId']);
        $result = $insertStmt->execute();
        $insertStmt->close();
    }

    // Return true if the query is successful, otherwise false
    return $result;
}


    public function getSuccessfulInserts()
    {
        return $this->successfulInserts;
    }

    public function getFailedInserts()
    {
        return $this->failedInserts;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $o = clean(isset($_POST['o']) ? $_POST['o'] : 0);
    $y = clean(isset($_POST['y']) ? $_POST['y'] : date("Y"));

    $csvImporter = new CSVImporter($conn);
    if ($csvImporter->importCSV($_FILES['file'], $o)) {

        if (!empty($csvImporter->getSuccessfulInserts())) {
            $goodImportsMarkUp = "<p class='mb-2'><strong>Success!</strong> Data has been uploaded.</p>";
            $goodImportsMarkUp .= "<ol class='alert-list alert-list--success'>";
            foreach ($csvImporter->getSuccessfulInserts() as $goodImports) {
                $goodImportsMarkUp .= "<li>" . implode(' ', $goodImports) . "</li>";
            }
            $goodImportsMarkUp .= "</ol>";
            create_flash_message('import-success', $goodImportsMarkUp, FLASH_SUCCESS);
        }

        if (!empty($csvImporter->getFailedInserts())) {
            $barImportsMarkUp = "<p class='mb-2'><strong>Failed! </strong>Please review and try again.</p>";
            $barImportsMarkUp .= "<ol class='alert-list alert-list--error'>";
            foreach ($csvImporter->getFailedInserts() as $badImports) {


                $barImportsMarkUp .= "<li>" . implode(' ', $badImports) . "</li>";
            }
            $barImportsMarkUp .= "</ol>";
            create_flash_message('import-error', $barImportsMarkUp, FLASH_ERROR);
        }

        $url = base_url(false) . "/employeesImport-APE.php?o=" . $o . "&y=" . $y;
        header("Location: " . $url . "");
        exit();
    } else {
        create_flash_message('import-failed', '<strong>Import Failed!</strong> Please review and try again.', FLASH_ERROR);
    }
}

createMainHeader($organizationName, array("Home", "Organizations", $organizationName, "Annual Physical Examination", "Import Data"));
?>
<main class='<?php echo $classMainContainer; ?>'>
    <form action="employeesImport-APE.php?o=<?php echo $o . "&y=" . $y; ?>" method="post" enctype="multipart/form-data">
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
            <ul class="flex -mb-px">
                <li class="mr-2">
                    <a href="<?php echo base_url(false) . "/employees-APE.php?o=" . $o . "&y=" . $y; ?>" class="inline-block p-2 md:p-4 text-green-700 border-b-2 border-green-700 rounded-t-lg active text-left text-xs md:text-sm" aria-current="page">
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
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p id="file-name" class="text-xs text-gray-500 dark:text-gray-400">CSV</p>
                    </div>
                    <input id="dropzone-file" type="file" name="file" class="hidden" onchange="displayConfirmation()" />
                    <input type="hidden" name="o" value="<?php echo $o; ?>" />
                    <input type="hidden" name="y" value="<?php echo $y; ?>" />
                </label>
            </div> 
        </div>
        <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
            <div class="flex justify-end flex-col sm:flex-row">
                <div class="p-1">
                    <a href="<?php echo base_url(false) . "/employees-APE.php?o=" . $o . "&y=" . $y; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Back</a>
                    <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Import</button>
                </div>
            </div>
        </div>

        <?php flash('import-success'); ?>
        <?php flash('import-error'); ?>
    </form>
</main>

<script>
    function displayConfirmation() {
        var input = document.getElementById('dropzone-file');

        document.getElementById('file-name').innerHTML = ((input.files.length > 0) 
            ? input.files[0].name
            : 'CSV'
        );
    }
</script>

<?php
include('footer.php');
?>
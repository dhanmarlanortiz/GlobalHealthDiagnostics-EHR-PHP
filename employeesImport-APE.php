<?php
ob_start();
session_start();

require_once('connection.php');
include('header.php');
include('classes/CSVImporter.php');

preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);

$role = $_SESSION['role'];

/* SET HEADER - START */
if($role == 1) {
    include('navbar.php');    
} else if($role == 3) {
    include('manager/navbar.php');
}
/* SET HEADER - END */

$o = $y = 0;
$organizationName  = $breadcrumbsArray = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && $role == 1) {
    $o = clean(isset($_GET['o']) ? $_GET['o'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));
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

/* SET HEADER - START */
if($role == 1) {
    $organizationName = (null !== getOrganization($o)) ? getOrganization($o)['name'] : "";
    $breadcrumbsArray = array("Home", "Organizations", $organizationName, "Annual Physical Examination", "Import Data");
} else if($role == 3) {
    $o = clean(isset($_SESSION['organizationId']) ? $_SESSION['organizationId'] : 0);
    $y = clean(isset($_GET['y']) ? $_GET['y'] : date("Y"));
    
    $organizationName = (null !== getOrganization($o)) ? getOrganization($o)['name'] : "";
    $breadcrumbsArray = array("Annual Physical Examination", "Import Data");
}
/* SET HEADER - END */

createMainHeader($organizationName, $breadcrumbsArray, "Import Data");

?>
<main class='<?php echo $classMainContainer; ?>'>
    <form action="employeesImport-APE.php?o=<?php echo $o . "&y=" . $y; ?>" method="post" enctype="multipart/form-data">
        <div class="bg-white p-2 md:p-4">
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p id="file-name" class="text-xs text-gray-500">CSV</p>
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
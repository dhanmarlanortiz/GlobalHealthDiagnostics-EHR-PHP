<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (isset($_POST['generateGynecologicReport'])) {

    $gyneRepQuery = "INSERT INTO
                    gynecologicreport(
                        gynerep_APE_FK,
                        gynerep_user_FK,
                        gynerep_date,
                        gynerep_specimen_type,
                        gynerep_specimen_adequacy,
                        gynerep_interpretation_result,
                        gynerep_recommendation)
                    VALUES(
                        '" . clean( $_POST['gynerep_APE_FK'] ) . "',
                        '" . clean( $_POST['gynerep_user_FK'] ) . "',
                        '" . clean( $_POST['gynerep_date'] ) . "',
                        '" . clean( $_POST['gynerep_specimen_type'] ) . "',
                        '" . clean( $_POST['gynerep_specimen_adequacy'] ) . "',
                        '" . clean( $_POST['gynerep_interpretation_result'] ) . "',
                        '" . clean( $_POST['gynerep_recommendation'] ) . "')
                    ";
    if ($conn->query($gyneRepQuery) === TRUE) {
        create_flash_message('create-success', $flashMessage['create-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('create-failed', $flashMessage['create-failed'], FLASH_ERROR);
    }

    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
} else if (isset($_POST['updateGynecologicReport'])) {

    $gyneRepQuery =  "UPDATE gynecologicreport SET 
                        gynerep_user_FK = '" . clean( $_POST['gynerep_user_FK'] ) . "',
                        gynerep_date = '" . clean( $_POST['gynerep_date'] ) . "',
                        gynerep_specimen_type = '" . clean( $_POST['gynerep_specimen_type'] ) . "',
                        gynerep_specimen_adequacy = '" . clean( $_POST['gynerep_specimen_adequacy'] ) . "',
                        gynerep_interpretation_result = '" . clean( $_POST['gynerep_interpretation_result'] ) . "',
                        gynerep_recommendation = '" . clean( $_POST['gynerep_recommendation'] ) . "' 
                    WHERE gynerep_APE_FK = $id";

    if ($conn->query($gyneRepQuery) === TRUE) {
        create_flash_message('update-success', $flashMessage['update-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('update-failed', $flashMessage['update-failed'], FLASH_ERROR);
    }

    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
}
?>
<form id="gynecologicReportForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <dialog id="gynecologicReportModal" class="modal">
        <div class="modal-box rounded-none max-w-5xl p-0 bg-white">
                <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                    <h3 class="font-medium text-green-700 text-sm">Generate Gynecologic Report</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onClick="gynecologicReportModal.close();">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-x-6 gap-y-8 p-4 lg:p-5">
                    <div class="col-span-1 lg:col-span-2">
                        <input type="date" id="gynerep_date" data-label="Date" value="<?php echo date('Y-m-d'); ?>" required />
                    </div>
                    <div class="col-span-1 lg:col-span-2">
                        <input type="text" id="gynerep_name" data-label="Name" value="<?php echo $_POST['lastName'] . ", " . $_POST['firstName'] . ($_POST['middleName'] ? ', ' . substr($_POST['middleName'], 0, 1) . '.' : '') ; ?>" disabled />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="gynerep_sex" data-label="Sex" value="<?php echo $_POST['sex']; ?>" disabled />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="gynerep_age" data-label="Age" value="<?php echo $_POST['age']; ?>" disabled />
                    </div>
                </div>



                <div class="grid grid-cols-1 gap-x-6 gap-y-8 p-4 lg:p-5">
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="gynerep_specimen_type" data-label="Specimen Type" placeholder="Specimen Type" title="Specimen Type" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="gynerep_specimen_adequacy" data-label="Specimen Adequacy" placeholder="Specimen Adequacy" title="Specimen Adequacy" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="255" id="gynerep_interpretation_result" data-label="Interpretation/Result" placeholder="Interpretation/Result" title="Interpretation/Result" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="255" id="gynerep_recommendation" data-label="Recommendation" placeholder="Recommendation" title="Recommendation" />
                    </div>
                </div>



                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700 mt-6">
                    <input type="hidden" name="gynerep_APE_FK" value="<?php echo $id; ?>" required />
                    <input type="hidden" name="gynerep_user_FK" value="<?php echo $_SESSION['userId']; ?>" />

                    <button type="button" class="btn <?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0" onClick="gynecologicReportModal.close();">Cancel</button>

                    <?php if(!empty($gynecologicReport)) { ?>
                        <input type="submit" name="updateGynecologicReport" value="Save Changes" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } else { ?>
                        <input type="submit" name="generateGynecologicReport" value="Generate" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } ?>
                </div>
        </div>
    </dialog>
</form>


<script>
    $(document).ready( function() {
        var gynerep = <?php echo json_encode($gynecologicReport); ?>;

        if(Object.keys(gynerep).length !== 0) {
            $("input[id^='gynerep_']:not(disabled)").each( function() {
                let gynerep_id = $(this).attr('id');

                $(this).attr('value', gynerep[gynerep_id]);
            });
        }
    });
</script>
<?php
if (isset($_POST['rr-generateRadiologyReport'])) {    
    $rrgenQuery = "INSERT INTO RadiologyReport(caseNumber, dateCreated, APEFK, organizationFK, MedicalExamination_FK, chestPA, impression, doctorFK, userFK) 
                 VALUES('".clean($_POST['rr-caseNumber'])."', 
                        '".clean($_POST['rr-dateCreated'])."', 
                        '".clean($_POST['rr-APEFK'])."', 
                        '".clean($_POST['rr-organizationFK'])."', 
                        '".clean($_POST['rr-MedicalExamination_FK'])."', 
                        '".clean($_POST['rr-chestPA'])."', 
                        '".clean($_POST['rr-impression'])."', 
                        '".clean($_POST['rr-doctorFK'])."', 
                        '".clean($_POST['rr-userFK'])."')";

    if ($conn->query($rrgenQuery) === TRUE) {
        create_flash_message('create-success', $flashMessage['create-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('create-failed', $flashMessage['create-failed'], FLASH_ERROR);
    }
    
    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
} else if(isset($_POST['rr-updateRadiologyReport'])) {
    $rrgenQuery =  "UPDATE RadiologyReport
                    SET caseNumber = '".clean($_POST['rr-caseNumber'])."',
                        dateCreated = '".clean($_POST['rr-dateCreated'])."',
                        chestPA = '".clean($_POST['rr-chestPA'])."', 
                        impression = '".clean($_POST['rr-impression'])."', 
                        userFK = '".clean($_POST['rr-userFK'])."'
                    WHERE APEFK = $id";

    if ($conn->query($rrgenQuery) === TRUE) {
        create_flash_message('update-success', $flashMessage['update-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('update-failed', $flashMessage['update-failed'], FLASH_ERROR);
    }
    
    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
}
?>


<form id="radiologyReportForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <dialog id="radiologyReportModal" class="modal">
        <div class="modal-box bg-white rounded-none max-w-2xl p-0">
                <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                    <h3 class="font-medium text-green-700 text-sm">Generate Radiology Report</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onClick="radiologyReportModal.close();">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8 p-4 md:p-5">
                    <div class="col-span-1">
                        <input type="number" id="rr-caseNumber" data-label="Case Number" value="<?php echo (isset($radiologyReport['caseNumber']) ? $radiologyReport['caseNumber'] : '');  ?>" required />
                    </div>
                    <div class="col-span-1">
                        <input type="date" id="rr-dateCreated" data-label="Date" value="<?php echo date('Y-m-d'); ?>" required />
                    </div>
                    <div class="col-span-2">
                        <label for="rrm-chestPA" class="block text-sm font-medium leading-6 text-gray-900">Chest PA</label>
                        <div class="mt-2">
                            <textarea name="rr-chestPA" id="rrm-chestPA" rows="3" class="block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6" required><?php echo (isset($radiologyReport['chestPA']) ? $radiologyReport['chestPA'] : 'Both lung fields are clear.&#10;Heart is not enlarged.&#10;The rest of visualized structures are unremarkable.'); ?></textarea>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label for="rr-impression" class="block text-sm font-medium leading-6 text-gray-900">Impression</label>
                        <div class="mt-2">
                            <textarea name="rr-impression" id="rrm-impression" rows="1" class="block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6" required><?php echo (isset($radiologyReport['impression']) ? $radiologyReport['impression'] : 'ESSENTIALLY NORMAL CHEST'); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700">
                    <input type="hidden" name="rr-APEFK" value="<?php echo $id; ?>" required />
                    <input type="hidden" name="rr-organizationFK" value="<?php echo $o; ?>" required />
                    <input type="hidden" name="rr-MedicalExamination_FK" value="6" />
                    <input type="hidden" name="rr-doctorFK" value="1" />
                    <input type="hidden" name="rr-userFK" value="<?php echo $_SESSION['userId']; ?>" />
                    <button type="button" class="btn <?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0" onClick="radiologyReportModal.close();">Cancel</button>
                    

                    <?php if(!empty($radiologyReport)) { ?>
                        <input type="submit" name="rr-updateRadiologyReport" value="Save Changes" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } else { ?>
                        <input type="submit" name="rr-generateRadiologyReport" value="Generate" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } ?>
                    
                </div>
        </div>
    </dialog>
</form>
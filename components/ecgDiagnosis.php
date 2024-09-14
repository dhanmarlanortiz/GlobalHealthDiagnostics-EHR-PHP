<?php
if (isset($_POST['ecgDiag-generateEcgDiagnosis'])) {    
    $rrgenQuery = "INSERT INTO ecgdiagnosis(ecgdiag_organization_FK, ecgdiag_APE_FK, ecgdiag_user_FK, ecgdiag_date, ecgdiag_casenumber, ecgdiag_ecgdiagnosis, ecgdiag_clinicaldata) 
                 VALUES('".clean($_POST['ecgdiag_organization_FK'])."', 
                        '".clean($_POST['ecgdiag_APE_FK'])."', 
                        '".clean($_POST['ecgdiag_user_FK'])."', 
                        '".clean($_POST['ecgdiag_date'])."', 
                        '".clean($_POST['ecgdiag_casenumber'])."', 
                        '".clean($_POST['ecgdiag_ecgdiagnosis'])."', 
                        '".clean($_POST['ecgdiag_clinicaldata'])."')";

    if ($conn->query($rrgenQuery) === TRUE) {
        create_flash_message('create-success', $flashMessage['create-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('create-failed', $flashMessage['create-failed'], FLASH_ERROR);
    }
    
    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
} else if(isset($_POST['ecgDiag-updateEcgDiagnosis'])) {
    $rrgenQuery =  "UPDATE ecgdiagnosis
                    SET ecgdiag_date = '".clean($_POST['ecgdiag_date'])."',
                        ecgdiag_casenumber = '".clean($_POST['ecgdiag_casenumber'])."',
                        ecgdiag_ecgdiagnosis = '".clean($_POST['ecgdiag_ecgdiagnosis'])."',
                        ecgdiag_clinicaldata = '".clean($_POST['ecgdiag_clinicaldata'])."'
                    WHERE ecgdiag_APE_FK = $id";

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


<form id="ecgDiagnosisForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <dialog id="ecgDiagnosisModal" class="modal">
        <div class="modal-box bg-white rounded-none max-w-2xl p-0">
                <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                    <h3 class="font-medium text-green-700 text-sm">Generate ECG Diagnosis</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onClick="ecgDiagnosisModal.close();">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8 p-4 md:p-5">
                    <div class="col-span-1">
                        <input type="number" id="ecgdiag_casenumber" data-label="Case Number" value="<?php echo (isset($ecgDiagnosis['ecgdiag_casenumber']) ? $ecgDiagnosis['ecgdiag_casenumber'] : '');  ?>" required />
                    </div>
                    <div class="col-span-1">
                        <input type="date" id="ecgdiag_date" data-label="Date" value="<?php echo date('Y-m-d'); ?>" required />
                    </div>
                    <div class="col-span-2">
                        <label for="ecgdiag_clinicaldata" class="block text-sm font-medium leading-6 text-gray-900">Clinical Data</label>
                        <div class="mt-2">
                            <textarea name="ecgdiag_clinicaldata" id="ecgdiag_clinicaldata" rows="3" maxlength="250" class="block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6"><?php echo (isset($ecgDiagnosis['ecgdiag_clinicaldata']) ? $ecgDiagnosis['ecgdiag_clinicaldata'] : ''); ?></textarea>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label for="ecgdiag_ecgdiagnosis" class="block text-sm font-medium leading-6 text-gray-900">ECG Diagnosis</label>
                        <div class="mt-2">
                            <textarea name="ecgdiag_ecgdiagnosis" id="ecgdiag_ecgdiagnosis" rows="3" maxlength="250" class="block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6" required><?php echo (isset($ecgDiagnosis['ecgdiag_ecgdiagnosis']) ? $ecgDiagnosis['ecgdiag_ecgdiagnosis'] : 'WITHIN NORMAL LIMIT'); ?></textarea>
                        </div>
                    </div>
                    
                </div>
                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700">
                    <input type="hidden" name="ecgdiag_APE_FK" value="<?php echo $id; ?>" required />
                    <input type="hidden" name="ecgdiag_organization_FK" value="<?php echo $o; ?>" required />
                    <input type="hidden" name="ecgdiag_user_FK" value="<?php echo $_SESSION['userId']; ?>" />
                    <button type="button" class="btn <?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0" onClick="ecgDiagnosisModal.close();">Cancel</button>

                    <?php if(!empty($ecgDiagnosis)) { ?>
                        <input type="submit" name="ecgDiag-updateEcgDiagnosis" value="Save Changes" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } else { ?>
                        <input type="submit" name="ecgDiag-generateEcgDiagnosis" value="Generate" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } ?>
                    
                </div>
        </div>
    </dialog>
</form>
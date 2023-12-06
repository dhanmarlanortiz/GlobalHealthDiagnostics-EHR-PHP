<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (isset($_POST['generatelaboratoryResult'])) {

    $labResGenQuery =   "INSERT INTO LaboratoryResult(
                        labRes_APE_FK, labRes_user_FK, labRes_date, labRes_hepa_b, labRes_drug_shabu, labRes_drug_marijuana, labRes_hema_hemoglobin, labRes_hema_hematocrit, labRes_hema_whiteblood, labRes_hema_segmenters, labRes_hema_lymphocytes, labRes_hema_monocytes, labRes_hema_eosinophils, labRes_hema_basophils, labRes_hema_stab, labRes_urin_color, labRes_urin_transparency, labRes_urin_reaction, labRes_urin_gravity, labRes_urin_protein, labRes_urin_glucose, labRes_urin_wbc, labRes_urin_rbc, labRes_urin_mucous, labRes_urin_epithelial, labRes_urin_amorphous, labRes_urin_bacteria, labRes_urin_cast, labRes_urin_crystals, labRes_para_color, labRes_para_consistency, labRes_para_result)
                     VALUES('" . clean( $_POST['labRes_APE_FK'] ) . "',
                            '" . clean( $_POST['labRes_user_FK'] ) . "',
                            '" . clean( $_POST['labRes_date'] ) . "',
                            '" . clean( $_POST['labRes_hepa_b'] ) . "',
                            '" . clean( $_POST['labRes_drug_shabu'] ) . "',
                            '" . clean( $_POST['labRes_drug_marijuana'] ) . "',
                            '" . clean( $_POST['labRes_hema_hemoglobin'] ) . "',
                            '" . clean( $_POST['labRes_hema_hematocrit'] ) . "',
                            '" . clean( $_POST['labRes_hema_whiteblood'] ) . "',
                            '" . clean( $_POST['labRes_hema_segmenters'] ) . "',
                            '" . clean( $_POST['labRes_hema_lymphocytes'] ) . "',
                            '" . clean( $_POST['labRes_hema_monocytes'] ) . "',
                            '" . clean( $_POST['labRes_hema_eosinophils'] ) . "',
                            '" . clean( $_POST['labRes_hema_basophils'] ) . "',
                            '" . clean( $_POST['labRes_hema_stab'] ) . "',
                            '" . clean( $_POST['labRes_urin_color'] ) . "',
                            '" . clean( $_POST['labRes_urin_transparency'] ) . "',
                            '" . clean( $_POST['labRes_urin_reaction'] ) . "',
                            '" . clean( $_POST['labRes_urin_gravity'] ) . "',
                            '" . clean( $_POST['labRes_urin_protein'] ) . "',
                            '" . clean( $_POST['labRes_urin_glucose'] ) . "',
                            '" . clean( $_POST['labRes_urin_wbc'] ) . "',
                            '" . clean( $_POST['labRes_urin_rbc'] ) . "',
                            '" . clean( $_POST['labRes_urin_mucous'] ) . "',
                            '" . clean( $_POST['labRes_urin_epithelial'] ) . "',
                            '" . clean( $_POST['labRes_urin_amorphous'] ) . "',
                            '" . clean( $_POST['labRes_urin_bacteria'] ) . "',
                            '" . clean( $_POST['labRes_urin_cast'] ) . "',
                            '" . clean( $_POST['labRes_urin_crystals'] ) . "',
                            '" . clean( $_POST['labRes_para_color'] ) . "',
                            '" . clean( $_POST['labRes_para_consistency'] ) . "',
                            '" . clean( $_POST['labRes_para_result'] ) . "')";

    if ($conn->query($labResGenQuery) === TRUE) {
        create_flash_message('create-success', $flashMessage['create-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('create-failed', $flashMessage['create-failed'], FLASH_ERROR);
    }

    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
}
?>
<form id="laboratoryResultForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <dialog id="laboratoryResultModal" class="modal">
        <div class="modal-box rounded-none max-w-7xl p-0">
                <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                    <h3 class="font-medium text-green-700 text-sm">Generate Laboratory Result</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onClick="laboratoryResultModal.close();">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 lg:grid-cols-6 gap-x-6 gap-y-8 p-4 md:p-5">
                    <div class="col-span-1 md:col-span-2 lg:col-span-1">
                        <input type="date" id="labRes_date" data-label="Date" value="<?php echo date('Y-m-d'); ?>" required />
                    </div>
                    <div class="col-span-1 md:col-span-2 lg:col-span-2">
                        <input type="text" id="labRes_name" data-label="Name" value="<?php echo $_POST['lastName'] . ", " . $_POST['firstName'] . ($_POST['middleName'] ? ', ' . substr($_POST['middleName'], 0, 1) . '.' : '') ; ?>" disabled />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_sex" data-label="Sex" value="<?php echo $_POST['sex']; ?>" disabled />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_age" data-label="Age" value="<?php echo $_POST['age']; ?>" disabled />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-x-6 gap-y-8 p-4 md:p-5">
                    <div class="col-span-1 sm:col-span-2 md:col-span-1">
                        <h4 class="border-b border-gray-300 font-medium text-xs tracking-wider uppercase mt-4 mb-8">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Hepatitis</span>
                        </h4>
                        <input type="text" id="labRes_hepa_b" data-label="<span class='block overflow-hidden text-ellipsis whitespace-nowrap'>Hepatitis B Screening</span>" placeholder="Hepatitis B Screening" title="Hepatitis: Hepatitis B Screening" />
                    </div>

                    <div class="col-span-1 sm:col-span-2">
                        <h4 class="border-b border-gray-300 font-medium text-xs tracking-wider uppercase mt-4 mb-8">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Drug Test</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                            <div class="col-span-1">
                                <input type="text" id="labRes_drug_shabu" data-label="<span class='block overflow-hidden text-ellipsis whitespace-nowrap'>Methamphetamine <span class='font-light text-xs'>(Shabu)</span></span>" placeholder="Methamphetamine" title="Drug Test: Methamphetamine" />
                            </div>
                            <div class="col-span-1">
                                <input type="text" id="labRes_drug_marijuana" data-label="<span class='block overflow-hidden text-ellipsis whitespace-nowrap'>Tetrahydrocannabinol <span class='font-light text-xs'>(Marijuana)</span></span>" placeholder="Tetrahydrocannabinol" title="Drug Test: Tetrahydrocannabinol" />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 sm:col-span-2 md:col-span-3">
                        <h4 class="border-b border-gray-300 font-medium text-xs tracking-wider uppercase mt-4 mb-8">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Parasitology</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-8">
                            <div class="col-span-1">
                                <input type="text" id="para-color" data-label="Color" placeholder="Color" title="Parasitology: Color" />
                            </div>
                            <div class="col-span-1">
                                <input type="text" id="para-consistency" data-label="Consistency" placeholder="Consistency" title="Parasitology: Consistency" />
                            </div>
                            <div class="col-span-1">
                                <input type="text" id="para-result" data-label="Result" placeholder="Result" title="Parasitology: Result" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-x-6 gap-y-8 p-4 md:p-5">
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-6">
                        <h4 class="border-b border-gray-300 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Hematology</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_hemoglobin" data-label="Hemoglobin" placeholder="Hemoglobin" title="Hematology: Hemoglobin" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_hematocrit" data-label="Hematocrit" placeholder="Hematocrit" title="Hematology: Hematocrit" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_whiteblood" data-label="White Blood Cell" placeholder="White Blood Cell" title="Hematology: White Blood Cell" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_segmenters" data-label="Segmenters" placeholder="Segmenters" title="Hematology: Segmenters" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_lymphocytes" data-label="Lymphocytes" placeholder="Lymphocytes" title="Hematology: Lymphocytes" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_monocytes" data-label="Monocytes" placeholder="Monocytes" title="Hematology: Monocytes" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_eosinophils" data-label="Eosinophils" placeholder="Eosinophils" title="Hematology: Eosinophils" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_basophils" data-label="Basophils" placeholder="Basophils" title="Hematology: Basophils" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_hema_stab" data-label="Stab" placeholder="Stab" title="Hematology: Stab" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-x-6 gap-y-8 p-4 md:p-5">
                    <div class="col-span-1 sm:col-span-2 md:col-span-4 lg:col-span-6">
                        <h4 class="border-b border-gray-300 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Urinalisys</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_color" data-label="Color" placeholder="Color" title="Urinalisys: Color" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_transparency" data-label="Transparency" placeholder="Transparency" title="Urinalisys: Transparency" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_reaction" data-label="Reaction" placeholder="Reaction" title="Urinalisys: Reaction" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_gravity" data-label="Specific Gravity" placeholder="Specific Gravity" title="Urinalisys: Specific Gravity" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_protein" data-label="Protein" placeholder="Protein" title="Urinalisys: Protein" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_glucose" data-label="Glucose" placeholder="Glucose" title="Urinalisys: Glucose" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_wbc" data-label="Wbc" placeholder="Wbc" title="Urinalisys: Wbc" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_rbc" data-label="Rbc" placeholder="Rbc" title="Urinalisys: Rbc" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_mucous" data-label="Mucous Threads" placeholder="Mucous Threads" title="Urinalisys: Mucous Threads" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_epithelial" data-label="Epithelial Cells" placeholder="Epithelial Cells" title="Urinalisys: Epithelial Cells" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_amorphous" data-label="Amorphous Urates" placeholder="Amorphous Urates" title="Urinalisys: Amorphous Urates" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_bacteria" data-label="Bacteria" placeholder="Bacteria" title="Urinalisys: Bacteria" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_cast" data-label="Cast" placeholder="Cast" title="Urinalisys: Cast" />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="labRes_urin_crystals" data-label="Crystals" placeholder="Crystals" title="Urinalisys: Crystals" />
                    </div>
                </div>

                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700 mt-6">
                    <input type="hidden" name="labRes_APE_FK" value="<?php echo $id; ?>" required />
                    <input type="hidden" name="labRes_user_FK" value="<?php echo $_SESSION['userId']; ?>" />
         
                    <button type="button" class="btn <?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0" onClick="laboratoryResultModal.close();">Cancel</button>
                    <input type="submit" name="generatelaboratoryResult" value="Generate" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                </div>
        </div>
    </dialog>
</form>
<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 0);

if (isset($_POST['generateClinicalChemistry'])) {

    $clinicChemQuery =   "INSERT INTO  clinicalchemistry(
                                clinicchem_APE_FK, clinicchem_user_FK, clinicchem_date, clinicchem_fbs, clinicchem_rbs, clinicchem_hba1c, clinicchem_blood_urea_nitrogen, clinicchem_creatinine, clinicchem_blood_uric_acid, clinicchem_total_cholesterol, clinicchem_triglycerides, clinicchem_hdl, clinicchem_ldl, clinicchem_vldl, clinicchem_sgot_ast, clinicchem_sgpt_alt, clinicchem_psa, clinicchem_others)
                            VALUES('" . clean( $_POST['clinicchem_APE_FK'] ) . "',
                                    '" . clean( $_POST['clinicchem_user_FK'] ) . "',
                                    '" . clean( $_POST['clinicchem_date'] ) . "',
                                    '" . clean( $_POST['clinicchem_fbs'] ) . "',
                                    '" . clean( $_POST['clinicchem_rbs'] ) . "',
                                    '" . clean( $_POST['clinicchem_hba1c'] ) . "',
                                    '" . clean( $_POST['clinicchem_blood_urea_nitrogen'] ) . "',
                                    '" . clean( $_POST['clinicchem_creatinine'] ) . "',
                                    '" . clean( $_POST['clinicchem_blood_uric_acid'] ) . "',
                                    '" . clean( $_POST['clinicchem_total_cholesterol'] ) . "',
                                    '" . clean( $_POST['clinicchem_triglycerides'] ) . "',
                                    '" . clean( $_POST['clinicchem_hdl'] ) . "',
                                    '" . clean( $_POST['clinicchem_ldl'] ) . "',
                                    '" . clean( $_POST['clinicchem_vldl'] ) . "',
                                    '" . clean( $_POST['clinicchem_sgot_ast'] ) . "',
                                    '" . clean( $_POST['clinicchem_sgpt_alt'] ) . "',
                                    '" . clean( $_POST['clinicchem_psa'] ) . "',
                                    '" . clean( $_POST['clinicchem_others'] ) . "')";
    if ($conn->query($clinicChemQuery) === TRUE) {
        create_flash_message('create-success', $flashMessage['create-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('create-failed', $flashMessage['create-failed'], FLASH_ERROR);
    }

    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
} else if (isset($_POST['updateClinicalChemistry'])) {

    $clinicChemQuery =  "UPDATE clinicalchemistry
                    SET clinicchem_user_FK = '" . clean( $_POST['clinicchem_user_FK'] ) . "',
                        clinicchem_date = '" . clean( $_POST['clinicchem_date'] ) . "',
                        clinicchem_fbs = '" . clean( $_POST['clinicchem_fbs'] ) . "',
                        clinicchem_rbs = '" . clean( $_POST['clinicchem_rbs'] ) . "',
                        clinicchem_hba1c = '" . clean( $_POST['clinicchem_hba1c'] ) . "',
                        clinicchem_blood_urea_nitrogen = '" . clean( $_POST['clinicchem_blood_urea_nitrogen'] ) . "',
                        clinicchem_creatinine = '" . clean( $_POST['clinicchem_creatinine'] ) . "',
                        clinicchem_blood_uric_acid = '" . clean( $_POST['clinicchem_blood_uric_acid'] ) . "',
                        clinicchem_total_cholesterol = '" . clean( $_POST['clinicchem_total_cholesterol'] ) . "',
                        clinicchem_triglycerides = '" . clean( $_POST['clinicchem_triglycerides'] ) . "',
                        clinicchem_hdl = '" . clean( $_POST['clinicchem_hdl'] ) . "',
                        clinicchem_ldl = '" . clean( $_POST['clinicchem_ldl'] ) . "',
                        clinicchem_vldl = '" . clean( $_POST['clinicchem_vldl'] ) . "',
                        clinicchem_sgot_ast = '" . clean( $_POST['clinicchem_sgot_ast'] ) . "',
                        clinicchem_sgpt_alt = '" . clean( $_POST['clinicchem_sgpt_alt'] ) . "',
                        clinicchem_psa = '" . clean( $_POST['clinicchem_psa'] ) . "',
                        clinicchem_others = '" . clean( $_POST['clinicchem_others'] ) . "'
                    WHERE clinicchem_APE_FK = $id";

    if ($conn->query($clinicChemQuery) === TRUE) {
        create_flash_message('update-success', $flashMessage['update-success'], FLASH_SUCCESS);
    } else {
        create_flash_message('update-failed', $flashMessage['update-failed'], FLASH_ERROR);
    }
    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    echo $url;
    // die;
    header("Location: " . $url ."");
    exit();
}
?>
<form id="clinicalChemistryForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <dialog id="clinicalChemistryModal" class="modal">
        <div class="modal-box rounded-none max-w-5xl p-0 bg-white">
                <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                    <h3 class="font-medium text-green-700 text-sm">Generate Clinical Chemistry</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onClick="clinicalChemistryModal.close();">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-x-6 gap-y-8 p-4 lg:p-5">
                    <div class="col-span-1 lg:col-span-2">
                        <input type="date" id="clinicchem_date" data-label="Date" value="<?php echo date('Y-m-d'); ?>" required />
                    </div>
                    <div class="col-span-1 lg:col-span-2">
                        <input type="text" id="clinicchem_name" data-label="Name" value="<?php echo $_POST['lastName'] . ", " . $_POST['firstName'] . ($_POST['middleName'] ? ', ' . substr($_POST['middleName'], 0, 1) . '.' : '') ; ?>" disabled />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="clinicchem_sex" data-label="Sex" value="<?php echo $_POST['sex']; ?>" disabled />
                    </div>
                    <div class="col-span-1">
                        <input type="text" id="clinicchem_age" data-label="Age" value="<?php echo $_POST['age']; ?>" disabled />
                    </div>
                </div>

                

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-8 p-4 lg:p-5">
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <h4 class="border-b border-gray-300 text-gray-900 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Glucose and Diabetes Monitoring</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_fbs" data-label="Fasting Blood Sugar (FBS)" placeholder="Fasting Blood Sugar (FBS)" title="Fasting Blood Sugar (FBS)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">70.3 - 115.3 mg/dL</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_rbs" data-label="Random Blood Sugar (RBS)" placeholder="Random Blood Sugar (RBS)" title="Random Blood Sugar (RBS)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">80 - 140 mg/dl</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_hba1c" data-label="HbA1c" placeholder="HbA1c" title="HbA1c" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">4.5 - 6.5 %</p>
                    </div>

                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <h4 class="border-b border-gray-300 text-gray-900 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Kidney Function Tests</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_blood_urea_nitrogen" data-label="Blood Urea Nitrogen (BUN)" placeholder="Blood Urea Nitrogen (BUN)" title="Blood Urea Nitrogen (BUN)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">15 - 45 mg/dL</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_creatinine" data-label="Creatinine" placeholder="Creatinine" title="Creatinine" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">0.6 - 1.3 mg/dL</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_blood_uric_acid" data-label="Blood Uric Acid" placeholder="Blood Uric Acid" title="Blood Uric Acid" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">3.4 - 7 mg/dL</p>
                    </div>

                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <h4 class="border-b border-gray-300 text-gray-900 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Lipid Profile</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_total_cholesterol" data-label="Total Cholesterol" placeholder="Total Cholesterol" title="Total Cholesterol" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">124.9 - 201.1 mg/dL</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_triglycerides" data-label="Triglycerides" placeholder="Triglycerides" title="Triglycerides" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">39.9 - 160.3 mg/dL</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_hdl" data-label="High-Density Lipoprotein (HDL)" placeholder="High-Density Lipoprotein (HDL)" title="High-Density Lipoprotein (HDL)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">34.8 - 54.9 mg/dL</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_ldl" data-label="Low-Density Lipoprotein (LDL)" placeholder="Low-Density Lipoprotein (LDL)" title="Low-Density Lipoprotein (LDL)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">7.7 - 130.3 mg/dL</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_vldl" data-label="Very Low-Density Lipoprotein (VLDL)" placeholder="Very Low-Density Lipoprotein (VLDL)" title="Very Low-Density Lipoprotein (VLDL)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">7.7 - 31.7 mg/dL</p>
                    </div>

                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <h4 class="border-b border-gray-300 text-gray-900 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Liver Function Tests</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_sgot_ast" data-label="SGOT/AST (Aspartate Aminotransferase)" placeholder="SGOT/AST (Aspartate Aminotransferase)" title="SGOT/AST (Aspartate Aminotransferase)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">0 - 38 U/L</p>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_sgpt_alt" data-label="SGPT/ALT (Alanine Aminotransferase)" placeholder="SGPT/ALT (Alanine Aminotransferase)" title="SGPT/ALT (Alanine Aminotransferase)" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">0 - 40 U/L</p>
                    </div>

                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <h4 class="border-b border-gray-300 text-gray-900 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Prostate-Specific Antigen</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_psa" data-label="PSA" placeholder="PSA" title="PSA" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold">0 - 4 ng/mL</p>
                    </div>

                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <h4 class="border-b border-gray-300 text-gray-900 font-normal text-xs tracking-wider uppercase mt-4">
                            <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">Other</span>
                        </h4>
                    </div>
                    <div class="col-span-1">
                        <input type="text" maxLength="100" id="clinicchem_others" data-label="Other" placeholder="Other" title="Other" />
                        <p class="text-gray-500 text-xs mt-2 font-semibold"></p>
                    </div>
                </div>

              

                <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700 mt-6">
                    <input type="hidden" name="clinicchem_APE_FK" value="<?php echo $id; ?>" required />
                    <input type="hidden" name="clinicchem_user_FK" value="<?php echo $_SESSION['userId']; ?>" />
         
                    <button type="button" class="btn <?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0" onClick="clinicalChemistryModal.close();">Cancel</button>

                    <?php if(!empty($clinicalChemistry)) { ?>
                        <input type="submit" name="updateClinicalChemistry" value="Save Changes" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } else { ?>
                        <input type="submit" name="generateClinicalChemistry" value="Generate" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0">
                    <?php } ?>
                </div>
        </div>
    </dialog>
</form>


<script>
    $(document).ready( function() { 
        var clinicchem = <?php echo json_encode($clinicalChemistry); ?>;

        if(Object.keys(clinicchem).length !== 0) {
            $("input[id^='clinicchem_']:not(disabled)").each( function() {
                let clinicchem_id = $(this).attr('id');

                $(this).attr('value', clinicchem[clinicchem_id]);
            });
        }
    });
</script>
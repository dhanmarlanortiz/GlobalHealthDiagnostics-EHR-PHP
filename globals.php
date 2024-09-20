<?php
$classBtnPrimary = "btn btn-primary btn-sm text-xs rounded normal-case h-9";
global $classBtnPrimary;

$classBtnSecondary = "btn btn-secondary btn-sm text-xs rounded normal-case h-9";
global $classBtnSecondary;

$classBtnDefault = "btn btn-default btn-sm text-xs rounded normal-case h-9";
global $classBtnDefault;

$classBtnAlternate = "btn btn-sm text-xs rounded normal-case h-9 bg-sky-500 hover:bg-sky-600 border-sky-500 hover:border-sky-600 text-white";
global $classBtnAlternate;

$classTblBtnPrimary = "btn btn-primary btn-sm text-xs rounded normal-case font-normal";
global $classTblBtnPrimary;

$classBtnDanger = "btn btn-sm text-xs rounded normal-case h-9 bg-red-500 hover:bg-red-600 border-red-500 hover:border-red-600 text-white";
global $classBtnDanger;

$classTblBtnSecondary = "btn btn-secondary btn-sm text-xs rounded normal-case font-normal";
global $classTblBtnSecondary;

$classTblBtnDanger = "btn btn-sm text-xs rounded normal-case font-normal bg-red-500 hover:bg-red-600 border-red-500 hover:border-red-600 text-white";
global $classTblBtnDanger;

$classTblBtnAlternate = "btn btn-sm text-xs rounded normal-case font-normal bg-sky-500 hover:bg-sky-600 border-sky-500 hover:border-sky-600 text-white";
global $classTblBtnAlternate;

$classInputPrimary = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
global $classInputPrimary;

$classBtnSuccess = "btn btn-success btn-sm text-xs text-white hover:bg-green-500 rounded normal-case h-9";
global $classBtnSuccess;

$classMainContainer = "mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8";
global $classMainContainer;

$flashMessage = [ 'create-success' => '<strong>Success!</strong> New record has been created',
                  'create-failed' => '<strong>Failed!</strong> An error occured while creating record.',
                  'update-success' => '<strong>Success!</strong> Details has been updated.',
                  'update-failed' => '<strong>Failed!</strong> An error occured while updating details.',
                  'delete-success' => '<strong>Success!</strong> Record has been deleted.',
                  'delete-failed' => '<strong>Failed!</strong> An error occured while deleting the record.',
                  'delete-failed-linked' => '<strong>Failed!</strong> Unable to delete a data linked to other record.'];
global $flashMessage;

function base_url($print = true) {
    $host = "app.globalhealth-diagnostics.com";
    $liveURL = "https://app.globalhealth-diagnostics.com";
    $devURL = "http://localhost/globalhealth-php";
    
    $url = ($_SERVER['HTTP_HOST'] == $host) ? $liveURL : $devURL;
    if($print === true) {
        echo $url;
    } else {
        return $url;
    }
}

function getOrganization($id = null) {
    require("connection.php");

    if(null !== $id) {
        $orgQuery = "SELECT * FROM Organization WHERE id = $id";
        $orgResult = $conn->query($orgQuery);
        
        if ($orgResult !== false && $orgResult->num_rows > 0) {
            return $orgResult->fetch_assoc();
        }
    } else {
        $orgsQuery = "SELECT * FROM Organization";
        $orgsResult = $conn->query($orgsQuery);
        
        if ($orgsResult !== false && $orgsResult->num_rows > 0) {
            $orgsArray = array();
            while($orgs = $orgsResult->fetch_assoc()) {
                $orgArr = array(
                    'id' => $orgs['id'],
                    'name' => $orgs['name'],
                    'email' => $orgs['email'],
                    'phone' => $orgs['phone'],
                    'address' => $orgs['address']
                );

                array_push($orgsArray, $orgArr);
            }
            
            return $orgsArray;
            // return $orgsResult->fetch_assoc();
        }
    }
}

function getUser($id = null) {
    require("connection.php");
    
    $userQuery = "SELECT * FROM User";
    $userArray = array();

    if(null !== $id) {

        $userQuery = "SELECT U.*, O.name as organization FROM User U LEFT JOIN Organization O ON U.organizationId = O.id WHERE U.id = $id";
        $userResult = $conn->query($userQuery);
        
        if ($userResult !== false && $userResult->num_rows > 0) {
            return $userResult->fetch_assoc();
        }
    }
}

function createFormHeader($header = 'Form') {
    echo 
        '<div class="bg-white px-3 sm:px-6 py-6 border-b-2 border-green-700">
            <h2 class="font-medium text-green-700 text-sm">' . $header . '</h2>
        </div>';
}

function createMainHeader($headerText = "", $pagination = array(), $subText = null) {
    $orgDetails = getOrganization($_SESSION['organizationId']);
    $orgName = $orgDetails['name'];    
    $role = $_SESSION['role'];
    $roleName = ($role == 1 ? 'Admin' : ($role == 2 ? 'Client Administrator' : ($role == 3 ? 'Manager' : '')));
    echo '
        <header class="bg-white shadow-sm">
            <div class="flex flex-col-reverse lg:flex-row justify-between gap-3 mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">';
    
    echo        '<div class="text-xs breadcrumbs p-0 text-gray-800 overflow-hidden">
                        <ul class="flex-wrap gap-y-2">';

                        if($role == 1) {
                            foreach ($pagination as $key => $value) {
                                echo "<li class='whitespace-normal'>" . $value . "</li>";
                            }
                        } else if($role == 2 || $role == 3) {
                            echo "<li class='whitespace-normal font-medium'>" . $orgName . "</li>"; 
                        }
                            
    echo                '</ul>                
                </div>';
    /*
    echo        '<h1 class="text-2xl font-bold tracking-tight text-gray-900 mt-2 mb-2">
                    <span class="pr-3">' . $headerText . '</span>';

                    if(null !== $subText) {
                        echo '<span class="font-normal text-xl border-l-2 border-green-700 pl-3">' . $subText . '</span>';
                    }

    echo        '</h1>';
    */
    
    echo        '<div class="text-xs">
                        <ul class="flex gap-6 font-medium">';
                            echo '<li class="flex gap-2 items-center">
                                    <svg class="h-3" fill="#4b5563" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>' . 
                                    '<span class="text-gray-600">' . $_SESSION['username'] . '</span>' .
                                '</li>';
                            echo '<li class="flex gap-2 items-center">
                                    <svg class="h-3" fill="#4b5563" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>' .
                                    '<span class="text-gray-600">' . $_SESSION['email'] . '</span>' .
                                '</li>';
                            echo '<li class="flex gap-2 items-center">
                                    <svg class="h-3" fill="#4b5563" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/></svg>' .
                                    '<span class="text-gray-600">' . $roleName . '</span>' .
                                '</li>';
    echo                '</ul>
                </div>';
    
    echo        '</div>
        </header>';
}

function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function print_pre($data = null) {
    echo "<pre>";   
    print_r($data);
    echo "</pre>";
}

/*
$restrictions = [
    [ 
        'role' => $role, 
        'redirect' => $path
    ],
]
*/
function preventAccess($restrictions = null) {
    $live = 'app.globalhealth-diagnostics.com';
    $liveURL = "https://app.globalhealth-diagnostics.com";
    $devURL = "http://localhost/globalhealth-php";
    $baseURL = ($_SERVER['HTTP_HOST'] == $live) ? $liveURL : $devURL;

    if(!isset($_SESSION["valid"])){
        header("location:" . $baseURL . "/login.php");
        exit();
    } else {
        if(null !== $restrictions) {
            foreach ($restrictions as $r) {
                if( $_SESSION['role'] == $r['role'] ) {
                    header("location:" . $baseURL . "/" . $r['redirect']);
                    exit();
                }
            }
        }
    }
}


function getHeadCountAPE($organizationId, $year) {
    require("connection.php");

    $headCount = 1;
    $headCountQuery = "SELECT MAX(headCount) FROM APE WHERE organizationId = $organizationId AND YEAR(dateRegistered) = $year";
    $headCountResult = $conn->query($headCountQuery);

    if ($headCountResult !== false && $headCountResult->num_rows > 0) {
        while($hc = $headCountResult->fetch_assoc()) {
            $headCount = $hc['MAX(headCount)'] + 1;
        }
    }

    return $headCount;
}


function getMedicalExamination() {
    require("connection.php");
    $examArray = array();
    $examSql = "SELECT * FROM MedicalExamination ORDER BY name";
    $examResult = $conn->query($examSql);

    if ($examResult !== false && $examResult->num_rows > 0) {
        while($exam = $examResult->fetch_assoc()) {
            array_push($examArray, $exam);
        }
    }
    return $examArray;
}


function print_c($data) {
    $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); 
    echo "<script>";
    echo "console.log(";
    echo $data;
    echo ")";
    echo "</script>";
}


function getResultsAPE($APEFK) {
    require("connection.php");

    $sql = "SELECT RA.*, ME.name as examName FROM ResultsAPE RA LEFT JOIN MedicalExamination ME ON RA.medicalExaminationFK = ME.id WHERE APEFK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $APEFK);
    $stmt->execute();

    $results = $stmt->get_result();
    $resultsArray = array();

    if ($results !== false && $results->num_rows > 0) {
        while ($result = $results->fetch_assoc()) {
            array_push($resultsArray, $result);
        }
    }

    $stmt->close();

    return $resultsArray;
}

function getControlNumberAPE($id, $organizationId, $dateRegistered) {
    require("connection.php");

    $y = date("Y", strtotime($dateRegistered));
    $ctr = 1;
    $ctrQuery = "SELECT MAX(controlNumber) FROM APE WHERE organizationId = '$organizationId' AND YEAR(dateRegistered) = '$y'";
    $ctrResult = $conn->query($ctrQuery);

    if ($ctrResult !== false && $ctrResult->num_rows > 0) {
        while($hc = $ctrResult->fetch_assoc()) {
            $ctr = $hc['MAX(controlNumber)'] + 1;
        }
        
        $date = date('Y-m-d');
        echo $updateQuery = "UPDATE APE SET controlNumber = '{$ctr}', controlDate = '{$date}' WHERE id = '{$id}' AND controlNumber is NULL";

        if ($conn->query($updateQuery) === TRUE) {
            create_flash_message('generate-control-number-success', '<strong>Success!</strong> Control number has been generated.', FLASH_SUCCESS);
        } else {
            create_flash_message('generate-control-number-error', '<strong>FAILED!</strong> An error occurred while generating control number.', FLASH_ERROR);
        }
    }

    return $ctr;
}

function getRadiologyReport($APEFK = null) {
    require("connection.php");

    if(null !== $APEFK) {
        $sql = "SELECT * FROM RadiologyReport WHERE APEFK = $APEFK";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    } else {
        $sql = "SELECT * FROM RadiologyReport";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            $resArr = array();

            while($arr = $result->fetch_assoc()) {
                $arr = array(
                    'caseNumber' => $arr['caseNumber'],
                    'dateCreated' => $arr['dateCreated'],
                    'organizationFK' => $arr['organizationFK'],
                    'MedicalExamination_FK' => $arr['MedicalExamination_FK'],
                    'chestPA' => $arr['chestPA'],
                    'impression' => $arr['impression'],
                    'doctorFK' => $arr['doctorFK']
                );

                array_push($resArr, $arr);
            }
            
            return $resArr;
        }
    }
}

function getEcgDiagnosis($APEFK = null) {
    require("connection.php");

    if(null !== $APEFK) {
        $sql = "SELECT * FROM ecgdiagnosis WHERE ecgdiag_APE_FK = $APEFK";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    } else {
        $sql = "SELECT * FROM ecgdiagnosis";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            $resArr = array();

            while($arr = $result->fetch_assoc()) {
                $arr = array(
                    'ecgdiag_organization_FK' => $arr['ecgdiag_organization_FK'],
                    'ecgdiag_user_FK' => $arr['ecgdiag_user_FK'],
                    'ecgdiag_date' => $arr['ecgdiag_date'],
                    'ecgdiag_casenumber' => $arr['ecgdiag_casenumber'],
                    'ecgdiag_ecgdiagnosis' => $arr['ecgdiag_ecgdiagnosis'],
                    'ecgdiag_clinicaldata' => $arr['ecgdiag_clinicaldata']
                );

                array_push($resArr, $arr);
            }
            
            return $resArr;
        }
    }
}

function getClinicalChemistry($APEFK = null) {
    require("connection.php");

    if(null !== $APEFK) {
        $sql = "SELECT * FROM clinicalchemistry WHERE clinicchem_APE_FK = $APEFK";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    } else {
        $sql = "SELECT * FROM clinicalchemistry";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            $resArr = array();

            while($arr = $result->fetch_assoc()) {
                $arr = array(
                    'clinicchem_ID' => $arr['clinicchem_ID'],
                    'clinicchem_APE_FK' => $arr['clinicchem_APE_FK'],
                    'clinicchem_user_FK' => $arr['clinicchem_user_FK'],
                    'clinicchem_date' => $arr['clinicchem_date'],
                    'clinicchem_timestamp' => $arr['clinicchem_timestamp'],
                    'clinicchem_fbs' => $arr['clinicchem_fbs'],
                    'clinicchem_rbs' => $arr['clinicchem_rbs'],
                    'clinicchem_blood_urea_nitrogen' => $arr['clinicchem_blood_urea_nitrogen'],
                    'clinicchem_creatinine' => $arr['clinicchem_creatinine'],
                    'clinicchem_blood_uric_acid' => $arr['clinicchem_blood_uric_acid'],
                    'clinicchem_total_cholesterol' => $arr['clinicchem_total_cholesterol'],
                    'clinicchem_triglycerides' => $arr['clinicchem_triglycerides'],
                    'clinicchem_hdl' => $arr['clinicchem_hdl'],
                    'clinicchem_ldl' => $arr['clinicchem_ldl'],
                    'clinicchem_vldl' => $arr['clinicchem_vldl'],
                    'clinicchem_sgot_ast' => $arr['clinicchem_sgot_ast'],
                    'clinicchem_sgpt_alt' => $arr['clinicchem_sgpt_alt'],
                    'clinicchem_hba1c' => $arr['clinicchem_hba1c'],
                    'clinicchem_psa' => $arr['clinicchem_psa'],
                    'clinicchem_others' => $arr['clinicchem_others']
                );

                array_push($resArr, $arr);
            }
            
            return $resArr;
        }
    }
}


function fetchLabResultByApeId($conn, $id) {
    $sql = "SELECT * FROM LaboratoryResult WHERE labRes_APE_FK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        return $row;
    } else {
        return;
    }
}

function getLaboratoryResult($APEFK = null) {
    require("connection.php");

    if(null !== $APEFK) {
        $sql = "SELECT * FROM LaboratoryResult WHERE labRes_APE_FK = $APEFK";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    } else {
        $sql = "SELECT * FROM LaboratoryResult";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) {
            $resArr = array();

            while($arr = $result->fetch_assoc()) {
                $arr = array(
                    'labRes_APE_FK' => $arr['labRes_APE_FK'],
                    'labRes_date' => $arr['labRes_date'],
                    'labRes_hepa_b' => $arr['labRes_hepa_b'],
                    'labRes_drug_shabu' => $arr['labRes_drug_shabu'],
                    'labRes_drug_marijuana' => $arr['labRes_drug_marijuana'],
                    'labRes_hema_hemoglobin' => $arr['labRes_hema_hemoglobin'],
                    'labRes_hema_hematocrit' => $arr['labRes_hema_hematocrit'],
                    'labRes_hema_whiteblood' => $arr['labRes_hema_whiteblood'],
                    'labRes_hema_segmenters' => $arr['labRes_hema_segmenters'],
                    'labRes_hema_lymphocytes' => $arr['labRes_hema_lymphocytes'],
                    'labRes_hema_monocytes' => $arr['labRes_hema_monocytes'],
                    'labRes_hema_eosinophils' => $arr['labRes_hema_eosinophils'],
                    'labRes_hema_basophils' => $arr['labRes_hema_basophils'],
                    'labRes_hema_stab' => $arr['labRes_hema_stab'],
                    'labRes_urin_color' => $arr['labRes_urin_color'],
                    'labRes_urin_transparency' => $arr['labRes_urin_transparency'],
                    'labRes_urin_reaction' => $arr['labRes_urin_reaction'],
                    'labRes_urin_gravity' => $arr['labRes_urin_gravity'],
                    'labRes_urin_protein' => $arr['labRes_urin_protein'],
                    'labRes_urin_glucose' => $arr['labRes_urin_glucose'],
                    'labRes_urin_wbc' => $arr['labRes_urin_wbc'],
                    'labRes_urin_rbc' => $arr['labRes_urin_rbc'],
                    'labRes_urin_mucous' => $arr['labRes_urin_mucous'],
                    'labRes_urin_epithelial' => $arr['labRes_urin_epithelial'],
                    'labRes_urin_amorphous' => $arr['labRes_urin_amorphous'],
                    'labRes_urin_bacteria' => $arr['labRes_urin_bacteria'],
                    'labRes_urin_cast' => $arr['labRes_urin_cast'],
                    'labRes_urin_crystals' => $arr['labRes_urin_crystals'],
                    'labRes_para_color' => $arr['labRes_para_color'],
                    'labRes_para_consistency' => $arr['labRes_para_consistency'],
                    'labRes_para_result' => $arr['labRes_para_result']
                );

                array_push($resArr, $arr);
            }
            
            return $resArr;
        }
    }
}

function getMedExamReport($conn, $ape_fk = null) {
    if(null !== $ape_fk) {
        $medExamReport_all = array();

        $medExamReport_family_sql = "SELECT * FROM medExamReport_family WHERE medExamReport_family_ape_fk = $ape_fk";
        $medExamReport_family_result = $conn->query($medExamReport_family_sql);

        $medExamReport_history_sql = "SELECT * FROM medExamReport_history WHERE medExamReport_history_ape_fk = $ape_fk";
        $medExamReport_history_result = $conn->query($medExamReport_history_sql);

        $medExamReport_personalSocial_sql = "SELECT * FROM medExamReport_personalSocial WHERE medExamReport_personalSocial_ape_fk = $ape_fk";
        $medExamReport_personalSocial_result = $conn->query($medExamReport_personalSocial_sql);

        $medExamReport_physical_sql = "SELECT * FROM medExamReport_physical WHERE medExamReport_physical_ape_fk = $ape_fk";
        $medExamReport_physical_result = $conn->query($medExamReport_physical_sql);

        $medExamReport_recommendation_sql = "SELECT * FROM medExamReport_recommendation WHERE medExamReport_recommendation_ape_fk = $ape_fk";
        $medExamReport_recommendation_result = $conn->query($medExamReport_recommendation_sql);

        $medExamReport_system_sql = "SELECT * FROM medExamReport_system WHERE medExamReport_system_ape_fk = $ape_fk";
        $medExamReport_system_result = $conn->query($medExamReport_system_sql);

        $medExamReport_xrayEcgLab_sql = "SELECT * FROM medExamReport_xrayEcgLab WHERE medExamReport_xrayEcgLab_ape_fk = $ape_fk";
        $medExamReport_xrayEcgLab_result = $conn->query($medExamReport_xrayEcgLab_sql);
        
            
        if($medExamReport_family_result !== false && $medExamReport_family_result->num_rows > 0) {
            $medExamReport_all += $medExamReport_family_result->fetch_assoc();
        }       
            
        if($medExamReport_history_result !== false && $medExamReport_history_result->num_rows > 0) {
            $medExamReport_all += $medExamReport_history_result->fetch_assoc();
        }       
            
        if($medExamReport_personalSocial_result !== false && $medExamReport_personalSocial_result->num_rows > 0) {
            $medExamReport_all += $medExamReport_personalSocial_result->fetch_assoc();
        }       
            
        if($medExamReport_physical_result !== false && $medExamReport_physical_result->num_rows > 0) {
            $medExamReport_all += $medExamReport_physical_result->fetch_assoc();
        }       
            
        if($medExamReport_recommendation_result !== false && $medExamReport_recommendation_result->num_rows > 0) {
            $medExamReport_all += $medExamReport_recommendation_result->fetch_assoc();
        }       
            
        if($medExamReport_system_result !== false && $medExamReport_system_result->num_rows > 0) {
            $medExamReport_all += $medExamReport_system_result->fetch_assoc();
        }       
            
        if($medExamReport_xrayEcgLab_result !== false && $medExamReport_xrayEcgLab_result->num_rows > 0) {
            $medExamReport_all += $medExamReport_xrayEcgLab_result->fetch_assoc();
        }      
        
        return $medExamReport_all;
    }
}

function fetchApeDetailsById($conn, $id) {
    $sql = "SELECT firstName, middleName, lastName, age, sex, homeAddress, civilStatus, organizationId, controlNumber FROM APE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        return $row;
    } else {
        return;
    }
}

function fetchApeDetailsByOrgAndYear($conn, $o, $y) {
    $empQuery = "SELECT * FROM APE WHERE organizationId = '$o' AND YEAR(dateRegistered) = '$y'";
    $empResult = $conn->query($empQuery);

    $empResultArray = array();

    while ($row = mysqli_fetch_assoc($empResult)) {
        $empResultArray[] = $row;
    }

    return $empResultArray;
}

function fetchOrgDetailsById($conn, $id) {
    $sql = "SELECT * FROM Organization WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        return $row;
    } else {
        return;
    }
}

function fetchMedExamDetailsById($conn, $medExam_fk) {
    $me_sql = "SELECT * FROM MedicalExamination WHERE id = ?";
    $me_stmt = $conn->prepare($me_sql);
    $me_stmt->bind_param('i', $medExam_fk);
    $me_stmt->execute();
    $me_sqlresult = $me_stmt->get_result();

    if ($me_sqlresult->num_rows > 0) {
        $me_row = $me_sqlresult->fetch_assoc();
        return $me_row;
        
    } else {
        return;
    }
}

function fetchRadReportDetailsByAPEfk($conn, $medExam_fk) {
    $rr_sql = "SELECT * FROM RadiologyReport WHERE APEFK = ?";
        $rr_stmt = $conn->prepare($rr_sql);
        $rr_stmt->bind_param('i', $medExam_fk);
        $rr_stmt->execute();
        $rr_sqlresult = $rr_stmt->get_result();

    if ($rr_sqlresult->num_rows > 0) {
        $rr_row = $rr_sqlresult->fetch_assoc();
        return $rr_row;
        
    } else {
        return;
    }
}

function medExamRadioAndInput($mainClass, $label, $checkBoxId, $inputLabel=null, $inputId=null, $checkBoxVal='', $inputVal=null, $attribute=null, $inputType='text') {
    echo '<div class="'.$mainClass.' relative">';
    echo    '<label for="" class="block text-sm font-medium leading-6 text-gray-900 pr-6">'.$label.'</label>';

    echo    '<div class="flex flex-wrap gap-x-5 gap-y-1.5 border border-l border-r-0 border-t-0 border-b-0 pl-4 items-center sm:flex-nowrap sm:pl-0 sm:border-0">
                <div class="checkbox-item-1 flex items-center py-3">
                    <input id="'.$checkBoxId.'" type="radio" value="Yes" name="'.$checkBoxId.'" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" '. ($checkBoxVal=="Yes" ? "checked": "") .'>
                    <label for="'.$checkBoxId.'" class="ms-2 text-xs font-medium text-gray-500">Yes</label>
                </div>
                <div class="checkbox-item-2 flex items-center py-3">
                    <input id="'.$checkBoxId.'" type="radio" value="No" name="'.$checkBoxId.'" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" '. ($checkBoxVal=="No" ? "checked": "") .'>
                    <label for="'.$checkBoxId.'" class="ms-2 text-xs font-medium text-gray-500">No</label>
                </div>
                <div class="checkbox-item-2 flex items-center py-3">
                    <input id="'.$checkBoxId.'-void" type="radio" value="" name="'.$checkBoxId.'" class="text-blue-600 bg-gray-100 border-gray-300 h-0 w-0 opacity-0">
                    <label for="'.$checkBoxId.'-void" class="text-xs font-medium text-gray-500 opacity-40 hover:opacity-70 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="#6b7280" height="16" width="16"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                    </label>
                </div>
                ';

    if($inputLabel !== null) {
    echo        '<div class="input-wrapper sm:flex sm:items-baseline sm:gap-2 sm:pl-4 w-full">
                    <label for="'.$inputId.'" class="block text-xs font-medium whitespace-nowrap text-gray-500 mb-2 sm:mb-0">'.$inputLabel.'</label>
                    <input type="'.$inputType.'" value="'.$inputVal.'" id="'.$inputId.'" class="block w-full rounded py-1.5 px-2 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-xs sm:leading-6" name="'.$inputId.'" '.$attribute.' html-transform="false">
                </div>';
    }

    echo '</div>
    </div>
    ';
}

function medExamRadioMulti($mainClass, $mainId, $label, $options, $inputLabel=null, $inputId=null, $checked=null, $inputVal=null) {
    echo '<div class="medExamRadioMulti '.$mainClass.'">
            <label for="" class="flex items-center text-sm font-medium leading-6 text-gray-900">'.$label.'</label>';

            if(!empty($options)) {                
                echo '<div class="flex flex-wrap gap-x-5 gap-y-1.5 border border-l border-r-0 border-t-0 border-b-0 pl-4 items-center sm:pl-0 sm:border-0">';

                foreach ($options as $o => $opt) {
                    echo '<div class="flex items-center py-3">';

                    if($opt == $checked) {
                        echo '<input checked id="'.$mainId.$o.'" type="radio" value="'.$opt.'" name="'.$mainId.'" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" html-transform="false">';
                    } else {
                        echo '<input id="'.$mainId.$o.'" type="radio" value="'.$opt.'" name="'.$mainId.'" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" html-transform="false">';
                    }
                    echo    '<label for="'.$mainId.$o.'" class="ms-2 text-xs font-medium text-gray-500">'.$opt.'</label>
                        </div>';
                }
                echo '<div class="flex items-center py-3">';
                echo '<input id="'.$mainId.$o.'-void" type="radio" value="" name="'.$mainId.'" class="w-0 h-0 opacity-0 text-blue-600 bg-gray-100 border-gray-300" html-transform="false">';
                echo '<label for="'.$mainId.$o.'-void" class="text-xs font-medium text-gray-500 opacity-40 hover:opacity-70 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="#6b7280" height="16" width="16"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                     </label>';
                echo '</div>';

                if($inputLabel !== null) {
                    // $o++;
                    // echo '<div class="flex items-center">
                    //         <input id="'.$mainId.$o.'" type="radio" value="'.$opt.'" name="'.$mainId.'" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300" html-transform="false">
                    //         <label for="'.$mainId.$o.'" class="ms-2 mr-2 text-xs font-medium text-gray-500">'.$inputLabel.'</label>                                            
                    //         <input type="text" id="'.$inputId.'" value="'.$inputVal.'" class="block w-full rounded py-1.5 px-2 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-xs sm:leading-6" name="'.$inputId.'" html-transform="false" maxLength="60">
                    //     </div>';
                    echo '<div class="flex items-center w-full">
                        <input placeholder="'.$inputLabel.'" type="text" id="'.$inputId.'" value="'.$inputVal.'" class="block w-full rounded py-1.5 px-2 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-xs sm:leading-6" name="'.$inputId.'" html-transform="false" maxLength="255">
                    </div>';
                }
                echo '</div>';
            }
    echo '</div>';
}

function medExamInput($mainClass, $type, $inputLabel, $inputId, $value=null, $attribute=null) {
    echo '<div class="'.$mainClass.'">
            <input type="'.$type.'" id="'.$inputId.'" value="'.$value.'" data-label="'.$inputLabel.'" '.$attribute.' />
        </div>
    ';
}

function medExamHeader($mainClass, $headerTitle, $sectionId) {
    echo '<div class="'.$mainClass.'" id="'.$sectionId.'">
            <h4 class="border-b border-gray-300 text-gray-900 font-normal text-xs tracking-wider uppercase mt-4">
                <span class="bg-gray-300 inline-block px-3 pt-2 pb-1 rounded-t-md">'.$headerTitle.'</span>
            </h4>
        </div>';
}

function sectionOpen($sectionClass=null, $sectionId=null) {
    echo "<section id='$sectionId' class='$sectionClass'>";
}

function sectionClose() {
    echo "</section>";
}


function getLocation($conn, $id = null) {
    if(null !== $id) {
        $sql = "SELECT * FROM Location WHERE loc_id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        $results = $stmt->get_result();
        $resultsArray = array();
    
        if ($results !== false && $results->num_rows > 0) {
            $resultsArray = $results->fetch_assoc();
        }
    
        $stmt->close();
        return $resultsArray;
        
    } else {
        $sql = "SELECT * FROM Location";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        $results = $stmt->get_result();
        $resultsArray = array();
    
        if ($results !== false && $results->num_rows > 0) {
            while ($result = $results->fetch_assoc()) {
                array_push($resultsArray, $result);
            }
        }
    
        $stmt->close();
    
        return $resultsArray;
    }
}

function getProfessional($conn, $id = null) {
    if(null !== $id) {
        $sql = "SELECT * FROM HealthcareProfessionals WHERE prof_id = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        $results = $stmt->get_result();
        $resultsArray = array();
    
        if ($results !== false && $results->num_rows > 0) {
            $resultsArray = $results->fetch_assoc();
        }
    
        $stmt->close();
        return $resultsArray;
        
    } else {
        $sql = "SELECT * FROM HealthcareProfessionals";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        $results = $stmt->get_result();
        $resultsArray = array();
    
        if ($results !== false && $results->num_rows > 0) {
            while ($result = $results->fetch_assoc()) {
                array_push($resultsArray, $result);
            }
        }
    
        $stmt->close();
    
        return $resultsArray;
    }
}

function isValidImageUrl($url) {
    // Check if the URL is properly formatted
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        return false;
    }

    // Send a HEAD request to the URL
    $headers = @get_headers($url, 1);
    
    // Check if the request was successful
    if ($headers && strpos($headers[0], '200') !== false) {
        // Check if the content type is an image
        if (isset($headers['Content-Type'])) {
            $contentType = is_array($headers['Content-Type']) ? $headers['Content-Type'][0] : $headers['Content-Type'];
            return strpos($contentType, 'image/') === 0;
        }
    }

    return false;
}

function getLocationDetailsByApe($id) {
    require("connection.php");
    $query = "SELECT L.* FROM APE A LEFT JOIN Organization O On O.id = A.organizationId LEFT JOIN Location L On L.loc_id = O.location_fk WHERE A.id = $id";
    $result = $conn->query($query);
    
    if ($result !== false && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return;
}

function getTextByClass($html, $className) {
    $dom = new DOMDocument;
    libxml_use_internal_errors(true); // Prevent warnings if HTML is not well-formed
    $dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $query = "//*[contains(@class, '$className')]";
    $elements = $xpath->query($query);

    $texts = [];
    if (!is_null($elements)) {
        foreach ($elements as $element) {
            $texts[] = $element->textContent;
        }
    }
    return $texts;
}
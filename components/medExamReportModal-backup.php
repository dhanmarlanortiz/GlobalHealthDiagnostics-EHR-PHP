<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Function to insert or update records

function initializeTableArray($tableName, $sessionUserId, $apeId) {
    $tableArray = ["{$tableName}_user_fk" => $sessionUserId, "{$tableName}_ape_fk" => $apeId];
    return $tableArray;
}

function processTableData($conn, $tableName, $data, $primaryKey, $id) {
    $colNames = implode(", ", array_keys($data));
    $colValues = "'" . implode("', '", $data) . "'";
    $checkQuery = "SELECT $primaryKey FROM $tableName WHERE $primaryKey = $id";
    $updateQuery = implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($data), $data));

    $sql = "";
    if ($conn->query($checkQuery)->num_rows > 0) {
        $sql = "UPDATE $tableName SET $updateQuery WHERE {$tableName}_ape_fk = $id";
    } else {
        $sql = "INSERT INTO $tableName ($colNames) VALUES ($colValues)";
    }

    $conn->query($sql);

    // echo $sql . "<br>";
}


if (isset($_POST['generateMedExamReport'])) {
    $medExamReportTables = ['family', 'history', 'personalSocial', 'physical', 'recommendation', 'system', 'xrayEcgLab'];
    $userId = $_SESSION['userId'];
    $apeId = $_GET['id'];

    $tableArrays = [];
    foreach ($medExamReportTables as $table) {
        $tableArrays[$table] = initializeTableArray("medExamReport_$table", $userId, $apeId);
    }

    foreach ($_POST as $p_key => $p_val) {
        if (isset(explode('_', $p_key)[1])) {
            $table = explode('_', $p_key)[1];
            if (array_key_exists($table, $tableArrays)) {
                $tableArrays[$table][$p_key] = $p_val;
            }
        }
    }

    // Print the resulting arrays for demonstration
    foreach ($tableArrays as $table => $values) {
        echo "Table: $table\n";
        print_r($values);
        echo "\n";
    }

    $medExamReportTables = [
        'family', 'history', 'personalSocial', 'physical',
        'recommendation', 'system', 'xrayEcgLab'
    ];

    $id = $_GET['id'];

    foreach ($medExamReportTables as $table) {
        $data = ["medExamReport_{$table}_user_fk" => $_SESSION['userId'], "medExamReport_{$table}_ape_fk" => $id];
        foreach ($_POST as $p_key => $p_val) {
            if (strpos($p_key, "medExamReport_{$table}_") === 0) {
                $data[$p_key] = $p_val;
            }
        }

        $primaryKey = "medExamReport_{$table}_id";
        $tableName = "medExamReport_{$table}";

        processTableData($conn, $tableName, $data, $primaryKey, $id);
    }

    // die;

    // $medExamReport_family = $medExamReport_history = $medExamReport_personalSocial = $medExamReport_physical = $medExamReport_recommendation = $medExamReport_system = $medExamReport_xrayEcgLab = array();
    // $medExamReport_family_sql = $medExamReport_history_sql = $medExamReport_personalSocial_sql = $medExamReport_physical_sql = $medExamReport_recommendation_sql = $medExamReport_system_sql = $medExamReport_xrayEcgLab_sql = "";

    // foreach ($_POST as $p_key => $p_val) {
    //     if( isset(explode('_', $p_key)[1]) ) {
    //         $table = explode('_', $p_key)[1];
            
    //         switch ($table) {
    //             case 'family':
    //                 $medExamReport_family['medExamReport_family_user_fk'] = $_SESSION['userId'];
    //                 $medExamReport_family['medExamReport_family_ape_fk'] = $_GET['id'];
    //                 $medExamReport_family[$p_key] = $p_val;
    //                 break;
    //             case 'history':
    //                 $medExamReport_history['medExamReport_history_user_fk'] = $_SESSION['userId'];
    //                 $medExamReport_history['medExamReport_history_ape_fk'] = $_GET['id'];
    //                 $medExamReport_history[$p_key] = $p_val;
    //                 break;
    //             case 'personalSocial':
    //                 $medExamReport_personalSocial['medExamReport_personalSocial_user_fk'] = $_SESSION['userId'];
    //                 $medExamReport_personalSocial['medExamReport_personalSocial_ape_fk'] = $_GET['id'];
    //                 $medExamReport_personalSocial[$p_key] = $p_val;
    //                 break;
    //             case 'physical':
    //                 $medExamReport_physical['medExamReport_physical_user_fk'] = $_SESSION['userId'];
    //                 $medExamReport_physical['medExamReport_physical_ape_fk'] = $_GET['id'];
    //                 $medExamReport_physical[$p_key] = $p_val;
    //                 break;
    //             case 'recommendation':
    //                 $medExamReport_recommendation['medExamReport_recommendation_user_fk'] = $_SESSION['userId'];
    //                 $medExamReport_recommendation['medExamReport_recommendation_ape_fk'] = $_GET['id'];
    //                 $medExamReport_recommendation[$p_key] = $p_val;
    //                 break;
    //             case 'system':
    //                 $medExamReport_system['medExamReport_system_user_fk'] = $_SESSION['userId'];
    //                 $medExamReport_system['medExamReport_system_ape_fk'] = $_GET['id'];
    //                 $medExamReport_system[$p_key] = $p_val;
    //                 break;
    //             case 'xrayEcgLab':
    //                 $medExamReport_xrayEcgLab['medExamReport_xrayEcgLab_user_fk'] = $_SESSION['userId'];
    //                 $medExamReport_xrayEcgLab['medExamReport_xrayEcgLab_ape_fk'] = $_GET['id'];
    //                 $medExamReport_xrayEcgLab[$p_key] = $p_val;
    //                 break;
    //         }
    //     }
    // }

    // $medExamReport_family_col = implode(", ", array_keys($medExamReport_family));
    // $medExamReport_family_val = "'" . implode("', '", $medExamReport_family) . "'";
    // $medExamReport_family_sql = "";
    // $medExamReport_family_check = "SELECT medExamReport_family_id FROM medExamReport_family WHERE medExamReport_family_id = {$_GET['id']}";
    
    // if( $conn->query($medExamReport_family_check)->num_rows > 0 ) {
    //     $medExamReport_family_sql = "UPDATE medExamReport_family SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_family), $medExamReport_family)) . " WHERE medExamReport_family_ape_fk = ".$_GET['id'];
    // } else {
    //     $medExamReport_family_sql = "INSERT INTO medExamReport_family ($medExamReport_family_col) VALUES ($medExamReport_family_val)";
    // }
    // $conn->query($medExamReport_family_sql);

    // $medExamReport_history_col = implode(", ", array_keys($medExamReport_history));
    // $medExamReport_history_val = "'" . implode("', '", $medExamReport_history) . "'";
    // $medExamReport_history_sql = "";
    // $medExamReport_history_check = "SELECT medExamReport_history_id FROM medExamReport_history WHERE medExamReport_history_id = {$_GET['id']}";
    // if( $conn->query($medExamReport_history_check)->num_rows > 0 ) {
    //     $medExamReport_history_sql = "UPDATE medExamReport_history SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_history), $medExamReport_history)) . " WHERE medExamReport_history_ape_fk = ".$_GET['id'];
    // } else {
    //     $medExamReport_history_sql = "INSERT INTO medExamReport_history ($medExamReport_history_col) VALUES ($medExamReport_history_val)";
    // }
    // $conn->query($medExamReport_history_sql);
    
    // $medExamReport_personalSocial_col = implode(", ", array_keys($medExamReport_personalSocial));
    // $medExamReport_personalSocial_val = "'" . implode("', '", $medExamReport_personalSocial) . "'";
    // $medExamReport_personalSocial_sql = "";
    // $medExamReport_personalSocial_check = "SELECT medExamReport_personalSocial_id FROM medExamReport_personalSocial WHERE medExamReport_personalSocial_id = {$_GET['id']}";
    // if( $conn->query($medExamReport_personalSocial_check)->num_rows > 0 ) {
    //     $medExamReport_personalSocial_sql = "UPDATE medExamReport_personalSocial SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_personalSocial), $medExamReport_personalSocial)) . " WHERE medExamReport_personalSocial_ape_fk = ".$_GET['id'];
    // } else {
    //     $medExamReport_personalSocial_sql = "INSERT INTO medExamReport_personalSocial ($medExamReport_personalSocial_col) VALUES ($medExamReport_personalSocial_val)";
    // }
    // $conn->query($medExamReport_personalSocial_sql);
    
    // $medExamReport_physical_col = implode(", ", array_keys($medExamReport_physical));
    // $medExamReport_physical_val = "'" . implode("', '", $medExamReport_physical) . "'";
    // $medExamReport_physical_sql = "";
    // $medExamReport_physical_check = "SELECT medExamReport_physical_id FROM medExamReport_physical WHERE medExamReport_physical_id = {$_GET['id']}";
    // if( $conn->query($medExamReport_physical_check)->num_rows > 0 ) {
    //     $medExamReport_physical_sql = "UPDATE medExamReport_physical SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_physical), $medExamReport_physical)) . " WHERE medExamReport_physical_ape_fk = ".$_GET['id'];
    // } else {
    //     $medExamReport_physical_sql = "INSERT INTO medExamReport_physical ($medExamReport_physical_col) VALUES ($medExamReport_physical_val)";
    // }
    // $conn->query($medExamReport_physical_sql);
    
    // $medExamReport_recommendation_col = implode(", ", array_keys($medExamReport_recommendation));
    // $medExamReport_recommendation_val = "'" . implode("', '", $medExamReport_recommendation) . "'";
    // $medExamReport_recommendation_sql = "";
    // $medExamReport_recommendation_check = "SELECT medExamReport_recommendation_id FROM medExamReport_recommendation WHERE medExamReport_recommendation_id = {$_GET['id']}";
    // if( $conn->query($medExamReport_recommendation_check)->num_rows > 0 ) {
    //     $medExamReport_recommendation_sql = "UPDATE medExamReport_recommendation SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_recommendation), $medExamReport_recommendation)) . " WHERE medExamReport_recommendation_ape_fk = ".$_GET['id'];
    // } else {
    //     $medExamReport_recommendation_sql = "INSERT INTO medExamReport_recommendation ($medExamReport_recommendation_col) VALUES ($medExamReport_recommendation_val)";
    // }
    // $conn->query($medExamReport_recommendation_sql);
    
    // $medExamReport_system_col = implode(", ", array_keys($medExamReport_system));
    // $medExamReport_system_val = "'" . implode("', '", $medExamReport_system) . "'";
    // $medExamReport_system_sql = "";
    // $medExamReport_system_check = "SELECT medExamReport_system_id FROM medExamReport_system WHERE medExamReport_system_id = {$_GET['id']}";
    // if( $conn->query($medExamReport_system_check)->num_rows > 0 ) {
    //     $medExamReport_system_sql = "UPDATE medExamReport_system SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_system), $medExamReport_system)) . " WHERE medExamReport_system_ape_fk = ".$_GET['id'];
    // } else {
    //     $medExamReport_system_sql = "INSERT INTO medExamReport_system ($medExamReport_system_col) VALUES ($medExamReport_system_val)";
    // }
    // $conn->query($medExamReport_system_sql);
    
    // $medExamReport_xrayEcgLab_col = implode(", ", array_keys($medExamReport_xrayEcgLab));
    // $medExamReport_xrayEcgLab_val = "'" . implode("', '", $medExamReport_xrayEcgLab) . "'";
    // $medExamReport_xrayEcgLab_sql = "";
    // $medExamReport_xrayEcgLab_check = "SELECT medExamReport_xrayEcgLab_id FROM medExamReport_xrayEcgLab WHERE medExamReport_xrayEcgLab_id = {$_GET['id']}";
    // if( $conn->query($medExamReport_xrayEcgLab_check)->num_rows > 0 ) {
    //     $medExamReport_xrayEcgLab_sql = "UPDATE medExamReport_xrayEcgLab SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_xrayEcgLab), $medExamReport_xrayEcgLab)) . " WHERE medExamReport_xrayEcgLab_ape_fk = ".$_GET['id'];
    // } else {
    //     $medExamReport_xrayEcgLab_sql = "INSERT INTO medExamReport_xrayEcgLab ($medExamReport_xrayEcgLab_col) VALUES ($medExamReport_xrayEcgLab_val)";
    // }
    // $conn->query($medExamReport_xrayEcgLab_sql);
    
    $url = base_url(false) . "/employee-APE.php?id=" . $_GET['id'];
    header("Location: " . $url ."");
    exit();
}

if (isset($_POST['updateMedExamReport'])) {
    $medExamReport_family = $medExamReport_history = $medExamReport_personalSocial = $medExamReport_physical = $medExamReport_recommendation = $medExamReport_system = $medExamReport_xrayEcgLab = array();
    $medExamReport_family_sql = $medExamReport_history_sql = $medExamReport_personalSocial_sql = $medExamReport_physical_sql = $medExamReport_recommendation_sql = $medExamReport_system_sql = $medExamReport_xrayEcgLab_sql = "";

    foreach ($_POST as $p_key => $p_val) {
        if( isset(explode('_', $p_key)[1]) ) {
            $table = explode('_', $p_key)[1];
            
            switch ($table) {
                case 'family':
                    $medExamReport_family[$p_key] = $p_val;
                    break;
                case 'history':
                    $medExamReport_history[$p_key] = $p_val;
                    break;
                case 'personalSocial':
                    $medExamReport_personalSocial[$p_key] = $p_val;
                    break;
                case 'physical':
                    $medExamReport_physical[$p_key] = $p_val;
                    break;
                case 'recommendation':
                    $medExamReport_recommendation[$p_key] = $p_val;
                    break;
                case 'system':
                    $medExamReport_system[$p_key] = $p_val;
                    break;
                case 'xrayEcgLab':
                    $medExamReport_xrayEcgLab[$p_key] = $p_val;
                    break;
            }
        }
    }


    $medExamReport_family_sql = "UPDATE medExamReport_family SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_family), $medExamReport_family)) . " WHERE medExamReport_family_ape_fk = ".$_GET['id'];

    $medExamReport_history_sql = "UPDATE medExamReport_history SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_history), $medExamReport_history)) . " WHERE medExamReport_history_ape_fk = ".$_GET['id'];

    $medExamReport_personalSocial_sql = "UPDATE medExamReport_personalSocial SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_personalSocial), $medExamReport_personalSocial)) . " WHERE medExamReport_personalSocial_ape_fk = ".$_GET['id'];

    $medExamReport_physical_sql = "UPDATE medExamReport_physical SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_physical), $medExamReport_physical)) . " WHERE medExamReport_physical_ape_fk = ".$_GET['id'];

    $medExamReport_recommendation_sql = "UPDATE medExamReport_recommendation SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_recommendation), $medExamReport_recommendation)) . " WHERE medExamReport_recommendation_ape_fk = ".$_GET['id'];

    $medExamReport_system_sql = "UPDATE medExamReport_system SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_system), $medExamReport_system)) . " WHERE medExamReport_system_ape_fk = ".$_GET['id'];

    $medExamReport_xrayEcgLab_sql = "UPDATE medExamReport_xrayEcgLab SET " . implode(", ", array_map(function ($k, $v) { return "$k = '$v'"; }, array_keys($medExamReport_xrayEcgLab), $medExamReport_xrayEcgLab)) . " WHERE medExamReport_xrayEcgLab_ape_fk = ".$_GET['id'];

    $conn->query($medExamReport_family_sql);
    $conn->query($medExamReport_history_sql);
    $conn->query($medExamReport_personalSocial_sql);
    $conn->query($medExamReport_physical_sql);
    $conn->query($medExamReport_recommendation_sql);
    $conn->query($medExamReport_system_sql);
    $conn->query($medExamReport_xrayEcgLab_sql);

    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
}


?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm medExamReportForm">
    <dialog id="medExamReportModal" class="modal">
        <div class="modal-box rounded-none max-w-3xl lg:max-w-5xl p-0">
            
            <div class="flex items-center justify-between p-4 border-b-2 border-green-700">
                <h3 class="font-medium text-green-700 text-sm">Generate Medical Examination Report</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onClick="medExamReportModal.close();">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <?php 
                medExamHeader('px-4 md:px-5 pt-6', 'Personal Information', 'medExamReport-header-basic');
                sectionOpen('grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-8 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-basic');
                medExamInput('col-span-1 sm:col-span-1 lg:col-span-2', 'text', 'First Name', 'firstName', $_POST['firstName'], 'readonly');
                medExamInput('col-span-1 sm:col-span-1 lg:col-span-2', 'text', 'Middle Name', 'middleName', $_POST['middleName'], 'readonly');
                medExamInput('col-span-1 sm:col-span-1 lg:col-span-2', 'text', 'Last Name', 'lastName', $_POST['lastName'], 'readonly');
                medExamInput('col-span-1 sm:col-span-1', 'number', 'Age', 'age', $_POST['age'], 'readonly');
                medExamInput('col-span-1 sm:col-span-1', 'text', 'Sex', 'sex', $_POST['sex'], 'readonly');
                medExamInput('col-span-1 sm:col-span-1 lg:sm:col-span-2', 'text', 'Civil Status', 'civilStatus', $_POST['civilStatus'], 'readonly');
                medExamInput('col-span-1 sm:col-span-3 lg:sm:col-span-6', 'text', 'Home Address', 'homeAddress', $_POST['homeAddress'], 'readonly');
                medExamInput('', 'hidden', '', 'headCount', $_POST['headCount']);
                medExamInput('', 'hidden', '', 'controlNumber', $_POST['controlNumber']);
                medExamInput('', 'hidden', '', 'organizationId', $_POST['organizationId']);
                medExamInput('', 'hidden', '', 'employeeNumber', $_POST['employeeNumber']);
                medExamInput('', 'hidden', '', 'remarks', $_POST['remarks']);
                medExamInput('', 'hidden', '', 'dateRegistered', $_POST['dateRegistered']);
                sectionClose();
            ?>

            <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="medExamReportForm"> -->
            <?php 
                // I. HISTORY
                medExamHeader('px-4 md:px-5 border-t-2 border-green-700 mt-2 pt-6', 'I. HISTORY', 'medExamReport-header-history');
                sectionOpen('grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-history');
                medExamInput('col-span-1', 'text', 'Childhood Illness', 'medExamReport_history_childhoodIllness', $medExamReport['medExamReport_history_childhoodIllness'] ?? '', 'maxLength="60"');
                medExamInput('col-span-1', 'text', 'Past Illness', 'medExamReport_history_pastIllness', $medExamReport['medExamReport_history_pastIllness'] ?? '', 'maxLength="60"');
                medExamInput('col-span-1', 'text', 'Present Illness', 'medExamReport_history_presentIllness', $medExamReport['medExamReport_history_presentIllness'] ?? '', 'maxLength="60"');
                medExamInput('col-span-1', 'text', 'Supplements', 'medExamReport_history_supplements', $medExamReport['medExamReport_history_supplements'] ?? '', 'maxLength="60"');
                medExamInput('col-span-1', 'text', 'Surgeries', 'medExamReport_history_surgeries', $medExamReport['medExamReport_history_surgeries'] ?? '', 'maxLength="60"');
                medExamInput('col-span-1', 'text', 'Hospitalizations', 'medExamReport_history_hospitalizations', $medExamReport['medExamReport_history_hospitalizations'] ?? '', 'maxLength="60"');
                sectionClose();
            ?>

            <!-- </form> -->
            
            <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="medExamReportForm"> -->
            <?php 
                /*
                    medExamRadioAndInput(
                        $mainClass, 
                        $label, 
                        $checkBoxId, 
                        $inputLabel=null, 
                        $inputId=null, 
                        $inputVal=null, 
                        $attribute = null
                    )
                */

                // II. PERSONAL & SOCIAL HISTORY
                medExamHeader('px-4 md:px-5', 'II. PERSONAL & SOCIAL HISTORY', 'medExamReport-header-personalAndSocialHistory');
                sectionOpen('grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-personalAndSocialHistory');
                medExamRadioAndInput('col-span-1', 
                                    'Smoking History', 
                                    'medExamReport_personalSocial_smokingHistory', 
                                    'Pack per', 
                                    'medExamReport_personalSocial_smokingHistory_pack', 
                                    $medExamReport['medExamReport_personalSocial_smokingHistory'] ?? "", 
                                    $medExamReport['medExamReport_personalSocial_smokingHistory_pack'] ?? "", 
                                    'max="999"',
                                    'number');
                
                medExamRadioAndInput('col-span-1', 
                                    'Alcohol Intake', 
                                    'medExamReport_personalSocial_alcoholIntake', 
                                    'Bottle per', 
                                    'medExamReport_personalSocial_alcoholIntake_bottle',
                                    $medExamReport['medExamReport_personalSocial_alcoholIntake'] ?? "", 
                                    $medExamReport['medExamReport_personalSocial_alcoholIntake_bottle'] ?? "", 
                                    'max="999"',
                                    'number');


                medExamRadioAndInput('col-span-1', 'Drug Use', 'medExamReport_personalSocial_drugUse');
                medExamRadioAndInput('col-span-1', 'Allergy', 'medExamReport_personalSocial_allergy');

                echo '<div class="col-span-1 lg:col-span-2">';
                echo '<label for="medExamReport_personalSocial_forWomen" class="block text-sm font-medium leading-6 text-gray-900">For Women</label>';
                echo '</div>';
                medExamInput('col-span-1 lg:col-span-2 flex items-baseline gap-x-2 -mt-4 border-l pl-4 sm:border-0 sm:pl-0 ', 'date', "<span class='text-xs text-gray-500'>Last Menstrual Period:</span>", 'medExamReport_personalSocial_forWomen_period');
                medExamInput('col-span-1 lg:col-span-2 flex items-baseline gap-x-2 -mt-2 border-l pl-4 sm:border-0 sm:pl-0 ', 'text', "<span class='text-xs text-gray-500'>Lasting:</span>", 'medExamReport_personalSocial_lasting', null, 'placeholder=Days maxLength=3');

                medExamRadioAndInput('col-span-1', 'Are you pregnant or any chance you may be', 'medExamReport_personalSocial_pregnant', '', 'medExamReport_personalSocial_pregnant_note');
                sectionClose();
            ?>
            <!-- </form> -->

            <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="medExamReportForm"> -->
            <?php 
                // III. FAMILY HISTORY
                medExamHeader('px-4 md:px-5', 'III. FAMILY HISTORY', 'medExamReport-header-familyHistory');
                sectionOpen('grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-familyHistory');
                medExamRadioAndInput('col-span-1', 'Hypertension', 'medExamReport_family_hypertension', 'Remarks', 'medExamReport_family_hypertension_note');
                medExamRadioAndInput('col-span-1', 'Heart Disease', 'medExamReport_family_heartDisease', 'Remarks', 'medExamReport_family_heartDisease_note');
                medExamRadioAndInput('col-span-1', 'Kidney Disease', 'medExamReport_family_kidneyDisease', 'Remarks', 'medExamReport_family_kidneyDisease_note');
                medExamRadioAndInput('col-span-1', 'Diabetes Mellitus', 'medExamReport_family_diabetesMellitus', 'Remarks', 'medExamReport_family_diabetesMellitus_note');
                medExamRadioAndInput('col-span-1', 'Others', 'medExamReport_family_others', 'Remarks', 'medExamReport_family_others_note');
                sectionClose();
            ?>
            <!-- </form> -->
            
            <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="medExamReportForm"> -->
            <?php 
                // IV. REVIEW OF SYSTEMS
                medExamHeader('px-4 md:px-5', 'IV. REVIEW OF SYSTEMS', 'medExamReport-header-reviewOfSystems');
                sectionOpen('grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-reviewOfSystems');
                medExamRadioAndInput('col-span-1 lg:col-span-2', 'With Objective findings?', 'medExamReport_system_findings');
                medExamRadioAndInput('col-span-1', 'Eyes', 'medExamReport_system_eyes', 'Remarks', 'medExamReport_system_eyes_note');
                medExamRadioAndInput('col-span-1', 'ENT/Mouth', 'medExamReport_system_entMouth', 'Remarks', 'medExamReport_system_entMouth_note');
                medExamRadioAndInput('col-span-1', 'Cardiovascular', 'medExamReport_system_cardiovascular', 'Remarks', 'medExamReport_system_cardiovascular_note');
                medExamRadioAndInput('col-span-1', 'Respiratory', 'medExamReport_system_respiratory', 'Remarks', 'medExamReport_system_respiratory_note');
                medExamRadioAndInput('col-span-1', 'Gastrointestinal', 'medExamReport_system_gastrointestinal', 'Remarks', 'medExamReport_system_gastrointestinal_note');
                medExamRadioAndInput('col-span-1', 'Genitourinary', 'medExamReport_system_genitourinary', 'Remarks', 'medExamReport_system_genitourinary_note');
                medExamRadioAndInput('col-span-1', 'Musculoskeletal', 'medExamReport_system_musculoskeletal', 'Remarks', 'medExamReport_system_musculoskeletal_note');
                medExamRadioAndInput('col-span-1', 'Skin/Breast', 'medExamReport_system_skinOrBreast', 'Remarks', 'medExamReport_system_skinOrBreast_note');
                medExamRadioAndInput('col-span-1', 'Neurological', 'medExamReport_system_neurological', 'Remarks', 'medExamReport_system_neurological_note');
                medExamRadioAndInput('col-span-1', 'Endocrine', 'medExamReport_system_endocrine', 'Remarks', 'medExamReport_system_endocrine_note');
                medExamRadioAndInput('col-span-1', 'Hematological', 'medExamReport_system_hematological', 'Remarks', 'medExamReport_system_hematological_note');
                medExamRadioAndInput('col-span-1', 'Others', 'medExamReport_system_others', 'Remarks', 'medExamReport_system_others_note');
                sectionClose();
            ?>
            <!-- </form> -->

            <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="medExamReportForm"> -->
            <?php 
                // V. PHYSICAL EXAMINATION
                medExamHeader('px-4 md:px-5', 'V. PHYSICAL EXAMINATION', 'medExamReport-header-physicalExamination');
                sectionOpen('grid grid-cols-1 sm:grid-cols-4 md:grid-cols-5 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-physicalExamination1');
                medExamInput('col-span-1', 'text', 'Height (cm)', 'medExamReport_physical_height');
                medExamInput('col-span-1', 'text', 'Weight (kg)', 'medExamReport_physical_weight');
                medExamInput('col-span-1', 'text', 'Body Mass Index', 'medExamReport_physical_bmi');
                medExamInput('col-span-1', 'text', 'Blood Pressure', 'medExamReport_physical_bp');
                medExamInput('col-span-1', 'text', 'PR', 'medExamReport_physical_pr');
                medExamInput('col-span-1', 'text', 'RR', 'medExamReport_physical_rr');
                medExamInput('col-span-1 md:col-span-2', 'text', "<span class='block whitespace-nowrap text-ellipsis overflow-hidden'>Visual Acuity R/L Right</span>", 'medExamReport_physical_visual');
                medExamInput('col-span-1', 'text', 'Hearing', 'medExamReport_physical_hearing');
                medExamInput('col-span-1', 'text', 'Clarity of Speech', 'medExamReport_physical_speech');
                sectionClose();

                sectionOpen('grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-physicalExamination2');
                medExamRadioAndInput('col-span-1', 'General Appearance', 'medExamReport_physical_generalAppearance', 'Remarks', 'medExamReport_physical_generalAppearance_note');
                medExamRadioAndInput('col-span-1', 'Skin', 'medExamReport_physical_skin', 'Remarks', 'medExamReport_physical_skin_note');
                medExamRadioAndInput('col-span-1', 'Head & Neck', 'medExamReport_physical_headNeck', 'Remarks', 'medExamReport_physical_headNeck_note');
                medExamRadioAndInput('col-span-1', 'Ears, Eyes, Nose', 'medExamReport_physical_earsEyesNose', 'Remarks', 'medExamReport_physical_earsEyesNose_note');
                medExamRadioAndInput('col-span-1', 'Mouth/Throat', 'medExamReport_physical_mouthThroat', 'Remarks', 'medExamReport_physical_mouthThroat_note');
                medExamRadioAndInput('col-span-1', 'Chest Lungs', 'medExamReport_physical_chestLungs', 'Remarks', 'medExamReport_physical_chestLungs_note');
                medExamRadioAndInput('col-span-1', 'Back', 'medExamReport_physical_back', 'Remarks', 'medExamReport_physical_back_note');
                medExamRadioAndInput('col-span-1', 'Heart', 'medExamReport_physical_heart', 'Remarks', 'medExamReport_physical_heart_note');
                medExamRadioAndInput('col-span-1', 'Abdomen', 'medExamReport_physical_abdomen', 'Remarks', 'medExamReport_physical_abdomen_note');
                medExamRadioAndInput('col-span-1', 'Extremities', 'medExamReport_physical_extremities', 'Remarks', 'medExamReport_physical_extremities_note');
                medExamRadioAndInput('col-span-1', 'Neurological', 'medExamReport_physical_neurological', 'Remarks', 'medExamReport_physical_neurological_note');
                medExamRadioAndInput('col-span-1', 'Rectal', 'medExamReport_physical_rectal', 'Remarks', 'medExamReport_physical_rectal_note');
                medExamRadioAndInput('col-span-1', 'Breast', 'medExamReport_physical_breast', 'Remarks', 'medExamReport_physical_breast_note');
                medExamRadioAndInput('col-span-1', 'Genitalia', 'medExamReport_physical_genitalia', 'Remarks', 'medExamReport_physical_genitalia_note');
                sectionClose();
            ?>
            <!-- </form> -->

            <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="medExamReportForm"> -->
            <?php 
                // VI. X-RAY, ECG AND LABORATORY EXAMINATION REPORT 
                medExamHeader('px-4 md:px-5', 'VI. X-RAY, ECG AND LABORATORY EXAMINATION REPORT', 'medExamReport-header-xrayEcgAndLabExamReport');
                sectionOpen('grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-xrayEcgAndLabExamReport');
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_chestXray', 'A. Chest X-Ray', array('PA', 'LORDOTIC', 'Essentially Normal Chest', 'Significant Finding
                For Lordotic View', 'Minimal PTB, Activity Undetermined', 'Pneumonitis', 'Healed & Stable Ptb', 'Pulmonary Scar'), 'Others:', 'medExamReport_xrayEcgLab_chestXray_other', $medExamReport['medExamReport_xrayEcgLab_chestXray'] ?? '');
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_ecg', 'B. ECG Report', array('Normal', 'Significant Findings', 'Not Required'));
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_bloodCount', 'C. Complete Blood Count', array('Normal'), 'Findings:', 'medExamReport_xrayEcgLab_bloodCount_findings');
                
                /* URINALYSIS - START */
                echo '<div class="col-span-1 sm:col-span-3">';
                echo '<label for="medExamReport_xrayEcgLab_urinalysis_wbc" class="block text-sm font-medium leading-6 text-gray-900">D. Urinalysis</label>';
                echo '</div>';
                echo '<div class="col-span-1 sm:col-span-3 sm:flex sm:pl-4 sm:items-center sm:-mt-2">';
                medExamInput('flex gap-x-2 items-baseline -mt-4 border-l flex gap-x-2 items-baseline pl-4 sm:pl-0 sm:border-0', 'text', "<span class='text-xs text-gray-500'>Findings: WBC</span>", 'medExamReport_xrayEcgLab_urinalysis_wbc', null, 'placeholder=/hpf' );
                medExamRadioMulti('sm:-mt-2 sm:pl-4', 'medExamReport_xrayEcgLab_urinalysis', '', array('Normal', 'No Specimen'));
                echo '</div>';
                /* URINALYSIS - END */

                /* STOOL EXAMINATION - START */
                echo '<div class="col-span-1 sm:col-span-3">';
                echo '<label for="medExamReport_xrayEcgLab_stoolSample_positive" class="block text-sm font-medium leading-6 text-gray-900">E. Stool Examination</label>';
                echo '</div>';
                echo '<div class="col-span-1 sm:col-span-3 sm:flex sm:pl-4 sm:items-center sm:-mt-2">';
                medExamInput('flex gap-x-2 items-baseline -mt-4 border-l flex gap-x-2 items-baseline pl-4 sm:pl-0 sm:border-0', 'text', "<span class='text-xs text-gray-500'>Positive</span>", 'medExamReport_xrayEcgLab_stoolSample_positive', null, 'placeholder=/hpf' );
                medExamRadioMulti('sm:-mt-2 sm:pl-4', 'medExamReport_xrayEcgLab_stoolSample', '', array('Normal', 'No Specimen'));
                echo '</div>';
                /* STOOL EXAMINATION - END */

                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_serologicalTest', 'F. Serological Test (VDRL)', array('Reactive', 'Non-Reactive'));
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_hepatitisAScreening', 'G. Hepatitis - A Screening', array('Anti-HAV IgM Negative', 'Anti-HAV IgM Positive', ' Anti HAV IgG Positive'));
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_hepatitisBSurfaceAntigen', 'H. Hepatitis - B Surface Antigen', array('Reactive','Non-Reactive','Not Required'));
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_pregnancyTest', 'I. Pregnancy Test', array('Positive','Negative','Not Required'));
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_bloodType', 'J. Blood Type', array('AB','A','B','O'));
                medExamRadioMulti('col-span-1 sm:col-span-3', 'medExamReport_xrayEcgLab_drugTest', 'K. Drug Test', array());
                medExamRadioMulti('col-span-1 sm:col-span-3 flex gap-x-6 ml-6 -mt-4', 'medExamReport_xrayEcgLab_marijuana', '<span class="text-xs text-gray-500">Marijuana (tetrahydrocannabinol):</span>', array('Negative', 'Positive'));
                medExamRadioMulti('col-span-1 sm:col-span-3 flex gap-x-6 ml-6 -mt-8', 'medExamReport_xrayEcgLab_shabu', '<span class="text-xs text-gray-500">Shabu (Methamphetamine):</span>', array('Negative', 'Positive'));
                medExamInput('col-span-1 sm:col-span-3', 'text', 'L. Others', 'medExamReport_xrayEcgLab_other');
                sectionClose();
            ?>
            <!-- </form> -->

            <!-- <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="medExamReportForm"> -->
            <?php 
            
                // RECOMMENDATIONS 
                medExamHeader('px-4 md:px-5 border-t-2 border-green-700 mt-6 pt-6', 'RECOMMENDATIONS', 'medExamReport-header-recommendations');
                sectionOpen('grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-recommendations');

                echo "<div class='col-span-1'>";
                medExamInput('', 'text', 'Ratings', 'medExamReport_recommendation_ratings_note');
                medExamRadioMulti('', 'medExamReport_recommendation_ratings', '', array('Fit', 'Unfit', 'Pending'));
                echo "</div>";
                echo "<div class='col-span-1'>";
                    medExamInput('', 'text', 'Remarks', 'medExamReport_recommendation_remarks');
                echo "</div>";

                sectionClose();
                medExamHeader('px-4 md:px-5', 'Physician', 'medExamReport-header-physician');
                sectionOpen('grid grid-cols-1 sm:grid-cols-4 gap-4 md:gap-5 p-4 md:p-5', 'medExamReport-section-physician');
                    medExamInput('col-span-1 sm:col-span-2', 'text', 'Examining Physician', 'medExamReport_recommendation_physicianName');
                    medExamInput('col-span-1', 'text', 'License Number', 'medExamReport_recommendation_physicianLicense');
                    medExamInput('col-span-1', 'date', 'Date', 'medExamReport_recommendation_date', date('Y-m-d'));
                sectionClose();
            ?>
            <!-- </form> -->
            
            <?php 
                // RATING SYSTEM: BASED ON DOH STANDARD  
                medExamHeader('px-4 md:px-5 border-t-2 border-green-700 mt-6 pt-6', 'RATING SYSTEM: BASED ON DOH STANDARD', 'medExamReport-header-ratingSystem'); 
                sectionOpen('p-4 md:p-5', 'medExamReport-section-ratingSystem');
                echo "<p class='mb-2 text-gray-900 text-xs flex gap-x-2'><strong class='whitespace-nowrap'>CLASS A:</strong><span>Physically Fit</span></p>";
                echo "<p class='mb-2 text-gray-900 text-xs flex gap-x-2'><strong class='whitespace-nowrap'>CLASS B:</strong><span>Physically fit but with minor ailment(s), condition(s) curable within a short period of time that will not adversely affect the worker's efficiency.</span></p>";
                echo "<p class='mb-2 text-gray-900 text-xs flex gap-x-2'><strong class='whitespace-nowrap'>CLASS C:</strong><span>With abnormal finding(s), condition(s) generally not acceptable for employment.</span></p>";
                echo "<p class='mb-2 text-gray-900 text-xs flex gap-x-2'><strong class='whitespace-nowrap'>PENDING:</strong><span>Cases that are incomplete / equivocal as to classification and are being evaluated further</span></p>";
                sectionClose();
            ?>                
            <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 p-4 border-t-2 border-green-700 mt-6">
                <button type="button" class="btn <?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0" onClick="medExamReportModal.close();">Cancel</button>

                <?php if(!empty($medExamReport)) { ?>
                    <input type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0" name="generateMedExamReport" value="Save Changes" />
                <?php } else { ?>
                    <input type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0" name="generateMedExamReport" value="Generate" />
                <?php } ?>
            </div>
        </div>
    </dialog>
</form>
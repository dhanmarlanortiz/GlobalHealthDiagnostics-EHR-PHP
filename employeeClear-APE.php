<?php

error_reporting(E_ALL); 
ini_set('display_errors', 1);
ob_start();
session_start();

require_once 'connection.php';
require_once 'globals.php';
require_once 'functions/flash.php';

preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);

$q = json_decode(base64_decode( $_GET['q']), true);
$o =  $q['o'];
$y =  $q['y'];

function removeResultsApe($o, $y, $conn) {
    try {
        $sql = "SELECT fileName FROM ResultsAPE JOIN APE ON ResultsAPE.APEFK = APE.id WHERE APE.organizationId = '$o' AND APE.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        $result = $conn->query($sql);
        
        $uploadDir = 'uploads/';

        if ($result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fileName =  $row['fileName'];

                $filePath = $uploadDir . $fileName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $conn->query("DELETE FROM ResultsAPE WHERE fileName = '$fileName'");
            }

            return $result->num_rows;
        } else {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeLaboratoryResult($o, $y, $conn) {
    try {
        $sql = "DELETE lab FROM LaboratoryResult AS lab JOIN APE AS a ON lab.labRes_APE_FK = a.id WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeMedExamReportFamily($o, $y, $conn) {
    try {
        $sql = "DELETE mf FROM medExamReport_family AS mf JOIN APE AS a ON a.id = mf.medExamReport_family_ape_fk WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeMedExamReportHistory($o, $y, $conn) {
    try {
        $sql = "DELETE mh FROM medExamReport_history AS mh JOIN APE AS a ON a.id = mh.medExamReport_history_ape_fk WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeMedExamReportPersonalSocial($o, $y, $conn) {
    try {
        $sql = "DELETE mps FROM medExamReport_personalSocial AS mps JOIN APE AS a ON a.id = mps.medExamReport_personalSocial_ape_fk WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeMedExamReportPhysical($o, $y, $conn) {
    try {
        $sql = "DELETE mp FROM medExamReport_physical AS mp JOIN APE AS a ON a.id = mp.medExamReport_physical_ape_fk WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeMedExamReportRecommendation($o, $y, $conn) {
    try {
        $sql = "DELETE mr FROM medExamReport_recommendation AS mr JOIN APE AS a ON a.id = mr.medExamReport_recommendation_ape_fk WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeMedExamReportSystem($o, $y, $conn) {
    try {
        $sql = "DELETE ms FROM medExamReport_system AS ms JOIN APE AS a ON a.id = ms.medExamReport_system_ape_fk WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeMedExamReportXrayEcgLab($o, $y, $conn) {
    try {
        $sql = "DELETE mx FROM medExamReport_xrayEcgLab AS mx JOIN APE AS a ON a.id = mx.medExamReport_xrayEcgLab_ape_fk WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeRadiologyReport($o, $y, $conn) {
    try {
        $sql = "DELETE r FROM RadiologyReport AS r JOIN APE AS a ON a.id = r.APEFK WHERE a.organizationId = '$o' AND a.dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            return $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        return $e->getMessage();
    }
}

function removeAPE($o, $y, $conn) {

    try {
        $sql = "DELETE FROM APE WHERE organizationId = '$o' AND dateRegistered BETWEEN '{$y}-01-01' AND '{$y}-12-31'";
        
        if ($conn->query($sql) === FALSE) {
            create_flash_message('delete-failed', "<strong>Failed!</strong> An error occured while deleting the record.", FLASH_ERROR);
            return $conn->error;
        } else {
            create_flash_message('delete-success', "<strong>Success!</strong> Record has been deleted." , FLASH_SUCCESS);
        }
    } catch (mysqli_sql_exception $e) {
        create_flash_message('delete-failed', "<strong>Failed!</strong> An error occured while deleting the record.", FLASH_ERROR);
        return $e->getMessage();
    }
}

echo removeResultsApe($o, $y, $conn);
echo removeRadiologyReport($o, $y, $conn);
echo removeLaboratoryResult($o, $y, $conn);
echo removeMedExamReportFamily($o, $y, $conn);
echo removeMedExamReportHistory($o, $y, $conn);
echo removeMedExamReportPersonalSocial($o, $y, $conn);
echo removeMedExamReportPhysical($o, $y, $conn);
echo removeMedExamReportRecommendation($o, $y, $conn);
echo removeMedExamReportSystem($o, $y, $conn);
echo removeMedExamReportXrayEcgLab($o, $y, $conn);
echo removeAPE($o, $y, $conn);

$url = base_url(false) . "/employees-APE.php?y=$y&o=$o";
header("Location: " . $url ."");
    
exit();
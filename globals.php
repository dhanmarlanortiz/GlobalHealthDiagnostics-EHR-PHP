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

$classMainContainer = "mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8";
global $classMainContainer;

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

function createMainHeader($headerText = "", $pagination = array()) {
    echo '
        <header class="bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">' . $headerText . '</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800 overflow-hidden">
                    <ul>';
                        foreach ($pagination as $key => $value) {
                            echo "<li>" . $value . "</li>";
                        }
                            
    echo            '</ul>
                </div>
            </div>
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
    $headCountQuery = "SELECT MAX(headCount) FROM APE WHERE organizationId = $organizationId AND (dateRegistered BETWEEN '" . $year . "-01-01' AND '" . $year . "-12-31')";
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
    $examSql = "SELECT * FROM MedicalExamination";
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

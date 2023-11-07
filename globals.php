<?php



$classBtnPrimary = "btn btn-primary btn-sm text-xs rounded normal-case h-9";
global $classBtnPrimary;

$classTblBtnPrimary = "btn btn-primary btn-sm text-xs rounded normal-case font-normal";
global $classTblBtnPrimary;

$classTblBtnSecondary = "btn btn-secondary btn-sm text-xs rounded normal-case font-normal";
global $classTblBtnSecondary;

$classBtnDefault = "btn btn-default btn-sm text-xs rounded normal-case h-9";
global $classBtnDefault;

$classMainContainer = "mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8";
global $classMainContainer;

$classInputPrimary = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
global $classInputPrimary;

function getOrganization($id = null) {
    require("connection.php");
    
    $orgQuery = "SELECT * FROM Organization";
    $orgArray = array();

    if(null !== $id) {
        $orgQuery = "SELECT * FROM Organization WHERE id = $id";
        $orgResult = $conn->query($orgQuery);
        
        if ($orgResult !== false && $orgResult->num_rows > 0) {
            return $orgResult->fetch_assoc();
        }
    }
}

function createFormHeader($header = 'Form') {
    echo 
        '<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
            <ul class="flex -mb-px">
                <li class="w-full bg-white inline-block p-6 text-green-700 border-b-2 border-green-700 active text-left text-sm">
                    ' . $header . '
                </li>
            </ul>
        </div>';
}

function print_pre($data = null) {
    echo "<pre>";   
    print_r($data);
    echo "</pre>";
}
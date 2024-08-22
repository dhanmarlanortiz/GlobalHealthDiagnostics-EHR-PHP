<?php 
ob_start();
session_start();

include('header.php');
preventAccess();

$q = json_decode(base64_decode( $_GET['q']), true);
$href =  base_url(false) .  $q['href'];
$organizationId =  $q['organizationId'];
$firstName =  $q['firstName'];
$middleName =  $q['middleName'];
$lastName =  $q['lastName'];
$organizationDetail = getOrganization($organizationId);

if($_SESSION['role'] == 1) {
    include('navbar.php');
    createMainHeader($organizationDetail['name'], array("Home", "Organizations", $organizationDetail['name'], "Annual Physical Examination", "Laboratory Result"));
} else if($_SESSION['role'] == 2) { 
    include('client/navbar.php');
    createMainHeader($organizationDetail['name'], array("Annual Physical Examination", "Laboratory Result"));
} else if($_SESSION['role'] == 3) { 
    include('manager/navbar.php');
    createMainHeader($organizationDetail['name'], array("Annual Physical Examination", "Laboratory  Report"));
}


?>

<main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
    <?php createFormHeader( $firstName . " " . $middleName . " " . $lastName . " - Laboratory Result" ); ?>
    <iframe class="w-full" src="<?php echo $href; ?>" frameborder="0" style="height: 100vh; max-height: 1500px;"></iframe>

    <div class="mx-auto rounded-b-box rounded-b-box">
        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-3 sm:px-6 py-4 border-t-2 border-green-700">
            <button onclick="window.history.back()" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Back</button>
            <a href="<?php echo $href; ?>" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto mb-2 sm:mb-0" download>Download</a>
        </div>
    </div>
</main>

<?php
include('footer.php')
?>

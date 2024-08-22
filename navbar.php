<?php 

function setActiveNav($pageNames, $screen) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $parsedUri = parse_url($requestUri, PHP_URL_PATH);
    $path = $parsedUri;
    $pathArray = explode('/', $path);
    $fileName = $pathArray[count($pathArray) - 1];
    $fileName = rtrim($fileName, '.php');
    
    if(in_array($fileName, $pageNames)) {
        if( $screen == 'desktop' ) {
            echo "nav-active bg-green-900 text-white rounded px-3 py-2 text-sm font-medium";
        } else if( $screen == 'mobile' ) {
            echo "nav-active bg-green-900 text-white rounded px-3 py-2 text-base font-medium block";
        }
    } else {
        if( $screen == 'desktop' ) {
            echo "text-white hover:bg-green-800 rounded px-3 py-2 text-sm font-medium";
        } else if( $screen == 'mobile' ) {
            echo "text-white hover:bg-green-800 rounded px-3 py-2 text-base font-medium block";
        }
    }
}
?>

<nav class="bg-green-700">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <button id="mobile-menu-toggle" type="button" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                <div class="flex flex-shrink-0 items-center">
                    <img class="h-8 w-auto" src="images/ghd-logo.png" alt="Global Health Diagnostics">
                </div>
                <div class="hidden sm:ml-6 sm:block">
                    <div class="flex space-x-4">
                        <a href="<?php base_url(); ?>/home.php" class="<?php setActiveNav(array('home'), 'desktop') ?>">Home</a>
                        <a href="<?php base_url(); ?>/users.php" class="<?php setActiveNav(array('user', 'users', 'createUser'), 'desktop') ?>">Users</a>
                        <a href="<?php base_url(); ?>/locations.php" class="<?php setActiveNav(array('location', 'locations', 'locationDetails', 'locationCreate'), 'desktop') ?>">Clinics</a>
                        <a href="<?php base_url(); ?>/healthcareProfessionals.php" class="<?php setActiveNav(array('healthcareProfessional', 'healthcareProfessionals', 'healthcareProfessionalDetails', 'healthcareProfessionalCreate'), 'desktop') ?>">Healthcare Professionals</a>
                        <a href="<?php base_url(); ?>/organizations.php" class="<?php setActiveNav(array('organization', 'organizations', 'organizationDetails', 'createOrganization', 'employees-APE', 'employeeCreate-APE', 'employee-APE', 'employeesImport-APE', 'employeeViewResult-APE', 'employeesExport-APE', 'employeesCSV-APE'), 'desktop') ?>">Organizations</a>
                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                <a href="<?php base_url(); ?>/logout.php" class="<?php setActiveNav(array('logout'), 'desktop') ?>">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="hidden sm:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a href="<?php base_url(); ?>/home.php" class="<?php setActiveNav(array('home'), 'mobile') ?>">Home</a>
            <a href="<?php base_url(); ?>/users.php" class="<?php setActiveNav(array('user', 'users', 'createUser'), 'mobile') ?>">Users</a>
            <a href="<?php base_url(); ?>/locations.php" class="<?php setActiveNav(array('location', 'locations', 'locationDetails', 'locationCreate'), 'mobile') ?>">Clinics</a>
            <a href="<?php base_url(); ?>/healthcareProfessionals.php" class="<?php setActiveNav(array('healthcareProfessional', 'healthcareProfessionals', 'healthcareProfessionalDetails', 'healthcareProfessionalCreate'), 'mobile') ?>">Healthcare Professionals</a>
            <a href="<?php base_url(); ?>/organizations.php" class="<?php setActiveNav(array('organization', 'organizations', 'organizationDetails', 'createOrganization', 'employees-APE', 'employeeCreate-APE', 'employee-APE', 'employeesImport-APE', 'employeeViewResult-APE', 'employeesExport-APE', 'employeesCSV-APE'), 'mobile') ?>">Organizations</a>
        </div>
    </div>
</nav>

<script>
    $("#mobile-menu-toggle").on("click", function() {
        var mobileMenu = $("#mobile-menu");

        if(mobileMenu.hasClass("hidden")) {
            mobileMenu.removeClass("hidden");
            mobileMenu.addClass("block");
        } else {
            mobileMenu.removeClass("block");
            mobileMenu.addClass("hidden");
        }
    });
</script>
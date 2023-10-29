<?php 
    // function base_url($print = true) {
    //     $url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
    //     if($print === true) {
    //         echo $url;
    //     } else {
    //         return $url;
    //     }
    // }
?>

<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 overflow-auto">
        <div class="relative flex h-16">
            <div class="flex flex-1 gap-5 items-center justify-between">
                <div class="flex flex-shrink-0 items-start">
                    <img class="h-8 w-auto" src="images/ghd-logo.png" />
                </div>
                <div class="flex items-center gap-5">
                    <a href="<?php base_url(); ?>" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Home</a>
                    <a href="<?php base_url(); ?>/users.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Users</a>
                    <a href="<?php base_url(); ?>/organizations.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Organizations</a>
                    <a href="<?php base_url(); ?>/employees-APE.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">APE</a>
                    <!-- <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Patients</a> -->
                    <!-- <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Services</a> -->
                </div>
                <div class="flex items-end">
                    <a href="<?php base_url(); ?>/logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Logout</a>
                </div>
            </div>
            
        </div>
    </div>
</nav>
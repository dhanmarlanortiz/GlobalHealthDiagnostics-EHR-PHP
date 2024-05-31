<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$locations = getLocation($conn);
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Locations</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Locations</li> 
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<main class='<?php echo $classMainContainer; ?>'>
    <div class='bg-white p-2 md:p-4'>
        <div class='overflow-auto p-1'>
            <table class='display'> 
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($locations)) {
                        foreach ($locations as $location) {
                        echo 
                            "<tr>" .
                                "<td>" . $location["loc_name"] . "</td>" .
                                "<td>" . $location["loc_address"] . "</td>".
                                "<td>" . $location["loc_type"] . "</td>".
                                "<td class='text-right'>
                                    <a class='" . $classTblBtnPrimary . " mb-1 lg:mb-0 lg:mr-1 w-full lg:w-auto' href='" . base_url(false) . "/location.php?id=" . $location['loc_id'] . "' title='View location details'>
                                        Details
                                    </a>
                                </td>".                         
                            "</tr>";
                        }
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
        <div class="flex sm:justify-between flex-col sm:flex-row">
            <div></div>
            <div class="p-1">
                <a href="<?php base_url(); ?>/createLocation.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create Location</a>
            </div>
        </div>
    </div>

    <?php flash('delete-success'); ?>
    <?php flash('delete-failed'); ?>
    <?php flash('delete-failed-linked'); ?>
</main>


<?php
    include('footer.php');
?>
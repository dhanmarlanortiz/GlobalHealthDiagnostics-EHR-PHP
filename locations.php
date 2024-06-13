<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$locations = getLocation($conn);
createMainHeader("Clinics", array("Home", "Clinics"));
?>

<main class='<?php echo $classMainContainer; ?>'>
    <div class='bg-white p-2 md:p-4'>
        <div class='overflow-auto p-1'>
            <table class='display'> 
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Telephone</th>
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
                                "<td>" . $location["loc_address1"] . " " . $location["loc_address2"] . "</td>".
                                "<td>" . $location["loc_telephone"] . "</td>".
                                "<td class='text-right'>
                                    <a class='" . $classTblBtnPrimary . " mb-1 lg:mb-0 lg:mr-1 w-full lg:w-auto' href='" . base_url(false) . "/location.php?id=" . $location['loc_id'] . "' title='View location details'>
                                        View
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
                <a href="<?php base_url(); ?>/locationCreate.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create</a>
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
<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$professionals = getProfessional($conn);
createMainHeader("Healthcare Professionals", array("Home", "Healthcare Professionals"));
?>

<main class='<?php echo $classMainContainer; ?>'>
    <div class='bg-white p-2 md:p-4'>
        <div class='overflow-auto p-1'>
            <table class='display'> 
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>License Number</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($professionals)) {
                        foreach ($professionals as $prof) {
                        echo 
                            "<tr>" .
                                "<td>" . $prof["prof_name"] . "</td>" .
                                "<td>" . $prof["prof_role"] . "</td>" .
                                "<td>" . $prof["prof_license"] . "</td>" .
                                "<td class='text-right'>
                                    <a class='" . $classTblBtnPrimary . " mb-1 lg:mb-0 lg:mr-1 w-full lg:w-auto' href='" . base_url(false) . "/healthcareProfessional.php?id=" . $prof['prof_id'] . "' title='View information'>
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
                <a href="<?php base_url(); ?>/healthcareProfessionalCreate.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create</a>
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
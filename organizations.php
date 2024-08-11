<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$sql = "SELECT * FROM Organization";
$result = $conn->query($sql);
createMainHeader('Organizations', array('Home', 'Organizations'));

?>
<main class='<?php echo $classMainContainer; ?>'>
    <!-- <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex -mb-px">
            <li class="mr-2">
                <a href="" class="inline-block p-2 md:p-4 text-green-700 border-b-2 border-green-700 rounded-t-lg active text-left text-xs md:text-sm" aria-current="page">
                    Client Companies
                </a>
            </li>
        </ul>
    </div> -->

    <div class="bg-white p-2 md:p-4">
        <?php
        if ($result->num_rows > 0) {
            echo    
            "<div class='overflow-auto p-1'>
                <table class='display'>
                    <thead>
                        <tr>
                            <th>Organizations Name</th>
                            <th style='max-width: 200px;'>Office Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>";
                    while($org = $result->fetch_assoc()) {
                    echo 
                        "<tr>" .
                            "<td>" . $org["name"] . "</td>" .
                            "<td>" . $org["address"] . "</td>".
                            "<td class='text-right'>
                                <a class='" . $classTblBtnSecondary . " mb-1 lg:mb-0 lg:mr-1 w-full lg:w-auto' href='" . base_url(false) . "/employees-APE.php?o=" . $org['id'] . "&y=" . date('Y') . "' title='View Medical Service Record'>
                                    View
                                </a>
                                <a class='" . $classTblBtnPrimary . " w-full lg:w-auto' href='" . base_url(false) . "/organization.php?id=" . $org['id'] . "' title='View or edit organization details'>
                                    Edit
                                </a>
                            </td>".                         
                        "</tr>";
                    }
                    echo
                    "</tbody>" .
                "</table>
            </div>";
        } else {
            echo "<span class='text-sm'>Results not found.</span>";
        }
        
        $conn->close();
        ?>
    </div>
    <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
        <div class="flex sm:justify-between flex-col sm:flex-row">
            <div></div>
            <div class="p-1">
                <a href="<?php base_url(); ?>/createOrganization.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create</a>
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
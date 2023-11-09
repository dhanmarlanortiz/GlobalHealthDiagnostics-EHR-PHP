<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$sql = "SELECT * FROM Organization";
$result = $conn->query($sql);
?>


<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Organizations</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Organizations</li> 
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<main class='<?php echo $classMainContainer; ?>'>
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex -mb-px">
            <li class="mr-2">
                <a href="" class="inline-block p-2 md:p-4 text-green-700 border-b-2 border-green-700 rounded-t-lg active text-left text-xs md:text-sm" aria-current="page">
                    Client Companies
                </a>
            </li>
        </ul>
    </div>

    <div class="bg-white p-2 md:p-4">
        <?php
        if ($result->num_rows > 0) {
            echo    
            "<div class='overflow-auto p-1'>
                <table class='display'>
                    <thead>
                        <tr>
                            <th>Organizations Name</th>
                            <th>Office Address</th>
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
                                <a class='" . $classTblBtnSecondary . "  mr-1' href='" . base_url(false) . "/employees-APE.php?o=" . $org['id'] . "&y=" . date('Y') . "' title='View Medical Service Record'>
                                    View Records
                                </a>
                                <a class='" . $classTblBtnPrimary . " ' href='" . base_url(false) . "/organization.php?id=" . $org['id'] . "' title='View or edit organization details'>
                                    Edit Details
                                </a>
                            </td>".                         
                        "</tr>";
                    }
                    echo
                    "</body>" .
                "</table>
            </div>";
        } else {
            echo "Results not found.";
        }
        
        $conn->close();
        ?>
    </div>
    <div class="bg-white p-2 md:px-4 md:pb-4 border-t-2 border-green-700">
        <div class="flex sm:justify-between flex-col sm:flex-row">
            <div></div>
            <div class="p-1">
                <a href="<?php base_url(); ?>/createOrganization.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create Organization</a>
            </div>
        </div>
    </div>
</main>


<?php
  include('footer.php');
?>
<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$sql = "SELECT U.*, O.name as organization FROM User U LEFT JOIN Organization O ON U.organizationId = O.id ";
$result = $conn->query($sql);

createMainHeader('Users', array("Home", "Users"));

?>

<main class='<?php echo $classMainContainer; ?>'>
    <div class="bg-white p-2 md:p-4">
        <?php
        if ($result->num_rows > 0) {
            echo    
            "<div class='overflow-auto p-1'>
                <table class='display'>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Organization</th>
                            <th>Role</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>";
                    while($user = $result->fetch_assoc()) {
                        $userRole = ($user['role'] == 1 ? 'Admin' : ($user['role'] == 2 ? 'Client Administrator' : ($user['role'] == 3 ? 'Manager' : '')));
                        $userStatus= ($user["isActive"] == "1") ? "Active" : "Inactive";
                    echo 
                        "<tr>" .
                            "<td>" . $user["username"] . "</td>" .
                            "<td>" . $user["organization"] . "</td>".
                            "<td>" . $userRole . "</td>".
                            "<td class='text-right'><a class='" . $classTblBtnPrimary . "' href='" . base_url(false) . "/user.php?id=" . $user['id'] . "'>View</a></td>".
                        "</tr>";
                    }
                    echo
                    "</body>" .
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
                <a href="<?php base_url(); ?>/createUser.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create</a>
            </div>
        </div>
    </div>

    <?php flash('delete-success'); ?>
    <?php flash('delete-failed'); ?>
</main>

<?php
include('footer.php');
?>


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
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Users</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Users</li> 
                    </ul>
                </div>
            </div>
            <div>
                <a href="<?php base_url(); ?>/createUser.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create User</a>
            </div>
        </div>
    </div>
</header>
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
                        $userRole = ($user["role"] == "1") ? "Admin" : "Client";
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
                <a href="<?php base_url(); ?>/createUser.php" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Create User</a>
            </div>
        </div>
    </div>

    <?php flash('delete-success'); ?>
    <?php flash('delete-failed'); ?>
</main>

<?php
include('footer.php');
?>


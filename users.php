<?php 
require_once('connection.php');
include('header.php');
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
                <a href="<?php base_url(); ?>/createUser.php" class="btn btn-success hover:bg-green-600 rounded text-white normal-case">Create</a>
            </div>
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl mt-4 px-4 pt-6 pb-20 sm:px-6 lg:px-8'>
    <div class="bg-white p-6 rounded shadow-sm">

<?php
if ($result->num_rows > 0) {
    echo    
    "<table class='display'>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email Address</th>
                <th>Organization</th>
                <th>User Role</th>
                <th>Account Status</th>
            </tr>
        </thead>
        <tbody>";
        while($user = $result->fetch_assoc()) {
            $userRole = ($user["role"] == "1") ? "Admin" : "Client";
            $userStatus= ($user["isActive"] == "1") ? "Active" : "Inactive";
        echo 
            "<tr>" .
                "<td>" . $user["username"] . "</td>" .
                "<td>" . $user["email"] . "</td>" .
                "<td>" . $user["organization"] . "</td>".
                "<td>" . $userRole . "</td>" .
                "<td>" . $userStatus . "</td>" .
            "</tr>";
        }
        echo
        "</body>" .
    "</table>";
} else {
    echo "Results not found.";;
}
$conn->close();
?>
    
    </div>
</main>

<?php
include('footer.php');
?>


<?php 
require_once('connection.php');
include('header.php');
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
            <div>
                <a href="<?php base_url(); ?>/createOrganization.php" class="btn btn-success hover:bg-green-600 rounded text-white normal-case">Create</a>
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
                <th>Organizations Name</th>
                <th>Email Address</th>
                <th>Telephone Number</th>
                <th>Office Address</th>
            </tr>
        </thead>
        <tbody>";
        while($org = $result->fetch_assoc()) {
        echo 
            "<tr>" .
                "<td>" . $org["name"] . "</td>" .
                "<td>" . $org["email"] . "</td>" .
                "<td>" . $org["phone"] . "</td>".
                "<td>" . $org["address"] . "</td>".
            "</tr>";
        }
        echo
        "</body>" .
    "</table>";
} else {
    echo "Results not found.";
}
$conn->close();
?>


  </div>
</main>


<?php
  include('footer.php');
?>
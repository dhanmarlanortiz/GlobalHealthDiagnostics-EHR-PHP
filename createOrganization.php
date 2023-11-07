<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

if(!isset($_SESSION["valid"])){
	$url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
    header("location:" . $url . "/login.php");
    exit();
}
/* AUTHENTICATION - END */

require_once('connection.php');
include('header.php');
include('navbar.php');

$styleInput = "block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6";
$styleLabel = "block text-sm font-medium leading-6 text-gray-900";
$styleButtonPrimary = "rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
$styleButtonLink = "text-sm font-semibold leading-6 text-gray-900";
$styleTextError = "mt-2 text-red-400 text-xs";

// define variables and set to empty values
$name = $email = $phone = $address = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// $id = 0;
// if ($_SERVER["REQUEST_METHOD"] == "GET") {
//     $id = $_GET['id'];
//     $getQuery = "SELECT * FROM APE WHERE id = $id";
//     $apeDetailsResult = $conn->query($apeDetailsQuery);

//     if ($apeDetailsResult !== false && $apeDetailsResult->num_rows > 0) {
//         while($apeDetails = $apeDetailsResult->fetch_assoc()) {
//             $_POST = $apeDetails;
//         }
//     }
// }


$errVal = $errKey = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = test_input($_POST["name"]);
  $email = test_input($_POST["email"]);
  $phone = test_input($_POST["phone"]);
  $address = test_input($_POST["address"]);
  
  $sql = "INSERT INTO Organization (name, email, phone, address)
  VALUES ('$name', '$email', '$phone', '$address')";
  
    if ($conn->query($sql) === TRUE) {
        $id = $conn->insert_id;
        $url = base_url(false) . "/organization.php?id=" . $id;
        header("Location: " . $url ."");

    } else {

        // duplicate entry error
        if($conn->errno == 1062) {
            $errMsg = explode("'", $conn->error);
            $errVal = $errMsg[1];
            $errKey = $errMsg[3];
        }
    }
}

$conn->close();
?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Create Organization</h1>
                <div class="text-xs breadcrumbs p-0 text-gray-800">
                    <ul>
                        <li>Home</li> 
                        <li>Organizations</li> 
                        <li>Create Organization</li> 
                    </ul>
                </div>
            </div>
            <div>
            </div>
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="max-w-md mx-auto">
        <?php createFormHeader(); ?>
        <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
            <div class="space-y-12 w-full">
                <div class="">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-6 <?php echo ($errKey == 'name') ? 'input-error' : ''; ?>">
                            <input id="name" type="text" data-label="Organization Name" required />
                            <?php echo ($errKey == 'name') ? '<div class="input-error--message"><p>' . $errVal . ' already exists.</p></div>' : ''; ?>
                        </div>
                        <div class="sm:col-span-6">
                            <input id="email" type="email" data-label="Email Address" required />
                        </div>
                        <div class="sm:col-span-6">
                            <input id="phone" type="number" data-label="Telephone Number" required />
                        </div>
                        <div class="sm:col-span-6">
                            <input id="address" type="text" data-label="Office Address" required />
                        </div>                
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
            <a href="<?php base_url(); ?>/Organizations.php" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
            <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Save</button>
        </div>
    </form>
</main>

<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST) ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=email], input[type=date], select').each( function() {
            let id = $(this).attr('id');
            let placeholder = $(this).attr('data-label');

            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput);  
            $(this).attr('name', id);
            // $(this).attr('placeholder', placeholder);
        });

        if(Object.keys(post).length !== 0) {
            $('input').each( function(key) {
                let id = $(this).attr('id');
                
                // $(this).attr('value', (Object.keys(post[id]).length === 0) ? '' : post[id]);
                $(this).attr('value', post[id]);
                $(this).attr('name', id);
            });
        }

    });
</script>

<?php
  include('footer.php');
?>
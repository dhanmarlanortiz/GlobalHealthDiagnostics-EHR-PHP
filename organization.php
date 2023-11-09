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

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$id = 0;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $_POST = getOrganization($id);
}

$errVal = $errKey = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = test_input( $_POST['id'] );
    $name = test_input( $_POST['name'] );
    $email = test_input( $_POST['email'] );
    $phone = test_input( $_POST['phone'] );
    $address = test_input( $_POST['address'] );

    $orgUpdateQuery =  "UPDATE Organization SET 
                        name = '$name',
                        email = '$email',
                        phone = '$phone',
                        address = '$address'
                        WHERE id = $id";

    if ($conn->query($orgUpdateQuery) === TRUE) {
        create_flash_message('update-success', '<strong>Update Successful!</strong> Your data has been successfully updated.', FLASH_SUCCESS);
        
        $url = base_url(false) . "/organization.php?id=" . $id;
        header("Location: " . $url ."");
        
        exit();
    } else {
        create_flash_message('update-failed', '<strong>Update Failed!</strong> Please review and try again.', FLASH_ERROR);
        
        // duplicate entry error
        if($conn->errno == 1062) {
            $errMsg = explode("'", $conn->error);
            $errVal = $errMsg[1];
            $errKey = $errMsg[3];
        }
    }

}

$headerText = (null !== getOrganization($id)) ? getOrganization($id)['name'] : "Not found";

$conn->close();
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center overflow-hidden"> 
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
                        <span class="pr-3"><?php echo $headerText; ?></span>
                        <!-- <span class="font-normal text-xl border-l-2 border-green-700 pl-3">Organization Details</span> -->
                    </h1>
                    <div class="text-xs breadcrumbs p-0 text-gray-800">
                        <ul>
                            <li>Home</li> 
                            <li>Organizations</li> 
                            <li><?php echo $headerText; ?></li> 
                            <li>Details</li> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
        <div class="mx-auto rounded-b-box rounded-b-box max-w-3xl">
            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
                <ul class="flex -mb-px">
                    <li class="w-full bg-white inline-block p-6 text-green-700 border-b-2 border-green-700 active text-left text-sm">
                        Organization Details
                    </li>
                </ul>
            </div>
            <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
                <div class="space-y-12 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                        <div class="sm:col-span-2 <?php echo ($errKey == 'name') ? 'input-error' : ''; ?>">
                            <input type="text" id="name" data-label="Organization Name" required />
                            <?php echo ($errKey == 'name') ? '<div class="input-error--message"><p>' . $errVal . ' already exists.</p></div>' : ''; ?>
                        </div>
                        <div class="sm:col-span-1">
                            <input type="email" id="email" data-label="Email Address" />
                        </div> 
                        <div class="sm:col-span-1">
                            <input type="text" id="phone" data-label="Contact Number" />
                        </div>
                        <div class="sm:col-span-2">
                            <input type="text" id="address" data-label="Office Address" />
                        </div> 
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
                <a href="<?php echo base_url() . '/organizations.php'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
                <button type="submit" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Save Changes</button>
            </div>

            <?php flash('update-success'); ?>
            <?php flash('update-failed'); ?>
        </div>
    </main>

</form>

<script>
    $(document).ready( function() {
        var post = <?php echo json_encode($_POST) ?>;
        let styleInput = "block w-full rounded py-1.5 px-2 text-gray-900 border-gray-300 placeholder:text-gray-400 focus:border-green-700 focus:ring-0 focus:bg-green-50 sm:text-sm sm:leading-6";
        let styleLabel = "block text-sm font-medium leading-6 text-gray-900";

        $('input[type=text], input[type=number], input[type=date], input[type=email], select').each( function() {
            let id = $(this).attr('id');
            
            $(`<label for='${$(this).attr("id")}' class='${styleLabel}'>${ $(this).attr('data-label') }</label>`).insertBefore($(this));
            $(this).wrap(`<div class='mt-2'></div>`);
            $(this).attr('class', styleInput);  
            $(this).attr('name', id);
        });

        if(Object.keys(post).length !== 0) {
            $('input').each( function(key) {
                let id = $(this).attr('id');
                $(this).attr('value', post[id]);
                $(this).attr('name', id);
            });
        }

    });

</script>


<?php
  include('footer.php');
?>
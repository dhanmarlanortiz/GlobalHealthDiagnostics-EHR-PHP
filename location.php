<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$id = 0;
$headerText = "Location";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $location = getLocation($conn, $id);
    $_POST = $location;
    $headerText = $location['loc_name'];
}

if(empty($_POST)){
    $url = base_url(false) . "/page-not-found.php";
    header("Location: " . $url ."");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = clean( $_POST['loc_id'] );
    
    $headerText = $location['loc_name'];

    $url = base_url(false) . "/location.php?id=" . $id;
    header("Location: " . $url ."");
    exit();
}

$conn->close();

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $_GET['id'] ) ;?>" class="prompt-confirm">
    <input type="hidden" id="loc_id" name="loc_id" value="<?php echo $id; ?>">
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
                            <li>Locations</li> 
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
                        Location Details
                    </li>
                </ul>
            </div>

            <div class="flex items-center justify-end gap-x-6 bg-white px-6 py-10 border-b">
                <div class="space-y-12 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                        <div class="sm:col-span-2">
                            <input type="text" id="loc_name" data-label="Name" maxlength="50" required />
                        </div> 
                        <div class="sm:col-span-2">
                            <input type="text" id="loc_address" data-label="Address" maxlength="200" required />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end flex-col sm:flex-row gap-x-1 bg-white mt-0 px-6 py-4 border-t-2 border-green-700">
                <a href="<?php echo base_url() . '/locations.php'; ?>" class="<?php echo $classBtnDefault; ?> w-full sm:w-auto mb-2 sm:mb-0">Cancel</a>
                <button type="submit" name="saveChanges" class="<?php echo $classBtnPrimary; ?> w-full sm:w-auto">Save Changes</button>
                <button type="submit" name="delete" class="<?php echo $classBtnDanger; ?> w-full sm:w-auto">Delete Location</button>
            </div>
           
            <?php flash('update-success'); ?>
            <?php flash('update-failed'); ?>
            <?php flash('create-success'); ?>
            <?php flash('create-failed'); ?>
            <?php flash('delete-failed'); ?>
            <?php flash('delete-success'); ?>
            <?php flash('delete-failed-linked'); ?>
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
                $(this).attr('value', htmlEntityDecode(post[id]));
                $(this).attr('name', id);
            });
        }
    });
</script>

<?php
  include('footer.php');
?>

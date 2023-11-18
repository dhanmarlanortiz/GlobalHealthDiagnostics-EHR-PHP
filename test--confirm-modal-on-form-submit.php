<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

include('header.php');
?>



<form id="form1" action="submit-url1" method="post">
    <!-- Your form fields go here -->
    <button type="submit" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Submit form 1
    </button>
</form>

<form id="form2" action="submit-url2" method="post">
    <!-- Your form fields go here -->
    <input type="submit" value="submit">
    <br>
    <button type="submit" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Submit form 2
    </button>
</form>

<script>
    $(document).ready(function() {
        // $("#confirmButton").click(function() {
        //     // Get the form ID from the data attribute
        //     var formId = $("#popup-modal").data("form-id");
            
        //     // Add any form submission logic here using the formId variable
            
        //     // Close the modal after successful form submission
        //     closeModal();
        // });
        
        // $("#cancelButton").click(function() {
        //     // Close the modal when the "No, cancel" button is clicked
        //     closeModal();
        // });
        
        // $("form").submit(function() {
        //     // Get the form ID and store it in the data attribute
        //     var formId = $(this).attr("id");
        //     $("#popup-modal").data("form-id", formId);
            
        //     // Show the modal
        //     $("#popup-modal").removeClass("hidden");
            
        //     // Prevent the form from submitting
        //     return false;
        // });
        
        // function closeModal() {
        //     // Hide the modal
        //     $("#popup-modal").addClass("hidden");
        // }
    });
</script>
<?php
include('footer.php');
?>
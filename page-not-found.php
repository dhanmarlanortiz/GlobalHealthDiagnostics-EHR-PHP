<?php 
    ob_start();
    session_start();

    include('header.php');

    if($_SESSION['role'] == 1) {
        include('navbar.php');
    } else if($_SESSION['role'] == 2) { 
        include('client/navbar.php');
    }
?>
<main class='<?php echo $classMainContainer; ?> flex items-center justify-center ' style='min-height: calc(100vh - 125px);'>
    <div class="text-center">
        <p class="text-base font-semibold text-green-700">404</p>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">Page not found</h1>
        <p class="mt-6 text-base leading-7 text-gray-600">Sorry, we couldn’t find the page you’re looking for.</p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <button onclick="window.history.back()" class="rounded-md bg-green-700 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-700">Go back</button>
        </div> 
    </div>
</main>

<?php
    include('footer.php')
?>

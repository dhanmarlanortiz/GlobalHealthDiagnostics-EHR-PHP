<?php 
require_once('connection.php');
include('header.php');
include('navbar.php');

$styleInput = "block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6";
$styleLabel = "block text-sm font-medium leading-6 text-gray-900";
$styleButtonPrimary = "rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600";
$styleButtonLink = "text-sm font-semibold leading-6 text-gray-900";
$styleTextError = "mt-2 text-red-400 text-xs";
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
                <a href="<?php base_url(); ?>/organizations.php" class="btn btn-default rounded normal-case">Back</a>
            </div>
        </div>
    </div>
</header>
<main class='mx-auto max-w-7xl mt-4 px-4 pt-6 pb-20 sm:px-6 lg:px-8'>
    <form class="max-w-md mx-auto">
        <h2 class="px-6 py-4 bg-gray-200 font-semibold rounded-t-box shadow-sm">Form</h2>
        <div class="flex items-center justify-end gap-x-6 bg-white p-6 shadow-sm">
            <div class="space-y-12 w-full">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label htmlFor="username" class="<?php echo $styleLabel; ?>">Organization Name</label>
                            <div class="mt-2">
                                <input id="username" type="text" class="<?php echo $styleInput; ?>" required />
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label htmlFor="email" class="<?php echo $styleLabel; ?>">Email Address</label>
                            <div class="mt-2">
                                <input id="email" type="email" class="<?php echo $styleInput; ?>" required />
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label htmlFor="phone" class="<?php echo $styleLabel; ?>">Telephone Number</label>
                            <div class="mt-2">
                                <input id="phone" type="number" class="<?php echo $styleInput; ?>" required />
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label htmlFor="address" class="<?php echo $styleLabel; ?>">Office Address</label>
                            <div class="mt-2">
                                <input id="address" type="address" class="<?php echo $styleInput; ?>" required />
                            </div>
                        </div>
                        
                        
                
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-x-6 bg-white mt-0 pb-6 px-6 rounded-b-box shadow-sm">
            <a href="<?php base_url(); ?>/Organizations.php" class="<?php echo $styleButtonLink; ?>">Cancel</a>
            <button type="submit" class="<?php echo $styleButtonPrimary; ?>">Save</button>
        </div>
    </form>
</main>


<?php
  include('footer.php');
?>
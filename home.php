<?php 
ob_start();
session_start();

include('header.php');

preventAccess([
	['role' => 2, 'redirect' => 'client'],
	['role' => 3, 'redirect' => 'manager'],
]);

$id = $_SESSION['organizationId'];
$org = getOrganization($id);

include('navbar.php'); 
createMainHeader('Home', array('Home'));
?>
	
<div class="mx-auto max-w-3xl py-32 sm:py-48 lg:py-56">
	<div class="hidden sm:mb-8 sm:flex sm:justify-center">
		<div class="relative rounded-full px-3 py-1 text-sm leading-6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
			Version 1.0
		</div>
	</div>
	<div class="text-center">
		<h1 class="text-4xl font-bold tracking-tight text-green-700 sm:text-6xl">Global Health Diagnostics</h1>
		<p class="mt-6 text-lg leading-8 text-gray-600">Electronic Health Record</p>
	</div>

	<div class="mt-20 p-6">
        
        <dl>
            <div class="relative pl-16 mb-10">
                <dt class="text-base font-semibold leading-7 text-gray-900">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-green-700">
                        <svg class="h-6 w-6" fill="#fff" viewBox="0 0 384 512">
                            <path d="M64 48c-8.8 0-16 7.2-16 16l0 384c0 8.8 7.2 16 16 16l80 0 0-64c0-26.5 21.5-48 48-48s48 21.5 48 48l0 64 80 0c8.8 0 16-7.2 16-16l0-384c0-8.8-7.2-16-16-16L64 48zM0 64C0 28.7 28.7 0 64 0L320 0c35.3 0 64 28.7 64 64l0 384c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zm88 40c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16l0 48c0 8.8-7.2 16-16 16l-48 0c-8.8 0-16-7.2-16-16l0-48zM232 88l48 0c8.8 0 16 7.2 16 16l0 48c0 8.8-7.2 16-16 16l-48 0c-8.8 0-16-7.2-16-16l0-48c0-8.8 7.2-16 16-16zM88 232c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16l0 48c0 8.8-7.2 16-16 16l-48 0c-8.8 0-16-7.2-16-16l0-48zm144-16l48 0c8.8 0 16 7.2 16 16l0 48c0 8.8-7.2 16-16 16l-48 0c-8.8 0-16-7.2-16-16l0-48c0-8.8 7.2-16 16-16z"/>
                        </svg>
                    </div>
                    <?php echo $org['name']; ?>
                </dt>
                <dd class="mt-2 text-base leading-7 text-gray-600">
                    <p class="leading-6">
                        <?php echo ucwords(strtolower($org['address'])); ?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php echo $org['phone']; ?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php echo $org['email']; ?>
                    </p>
                </dd>
            </div>
            <div class="relative pl-16">
                <dt class="text-base font-semibold leading-7 text-gray-900">
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-green-700">
                        <svg class="h-6 w-6 text-white" fill="#fff" viewBox="0 0 448 512">
                            <path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464l349.5 0c-8.9-63.3-63.3-112-129-112l-91.4 0c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3z"/>
                        </svg>             
                    </div>
                    <?php echo $_SESSION['username']; ?>
                </dt>
                <dd class="mt-2 text-base leading-7 text-gray-600">
                    <p class="leading-6">
                        <?php echo $_SESSION['email']; ?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php echo ($_SESSION['role'] == 1 ? 'Admin' : ($_SESSION['role'] == 2 ? 'Client Administrator' : ($_SESSION['role'] == 3 ? 'Manager' : ''))); ?>
                    </p>
                </dd>
            </div>   
            
        </dl>
    </div>

</div>
<?php 
include('footer.php');
?>
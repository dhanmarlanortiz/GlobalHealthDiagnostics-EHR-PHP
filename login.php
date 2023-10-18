<?php 
include('header.php');
include('navbar.php'); 
?>
	<div class="bg-white h-full">
		<div class="relative isolate overflow-hidden h-full">
			<div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
				<div
					class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#15803d] to-[#facc15] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
					style="clipPath: 'polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)',"
				>
				</div>
			</div>

			<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
				<div class="sm:mx-auto sm:w-full sm:max-w-sm bg-white px-6 pt-6 rounded-t-box shadow-sm">
					<img class="mx-auto w-auto" src="images/ghd-logo-text-bottom.png" alt="Global health diagnostics" />
				</div>
				
				<div class="sm:mx-auto sm:w-full sm:max-w-sm bg-white p-6 rounded-b-box shadow-sm">
					<form class="space-y-6" action="#" method="POST">
						<div>
							<label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
							<div class="mt-2">
								<input id="username" name="username" type="text" autocomplete="username" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
							</div>
						</div>
						
						<div>
							<div class="flex items-center justify-between">
								<label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
								<div class="text-sm">
									<a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
								</div>
							</div>
							<div class="mt-2">
								<input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
							</div>
						</div>
						
						<div>
							<button type="submit" class="flex w-full justify-center rounded-md bg-blue-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Sign in</button>
						</div>
					</form>
				</div>
			</div>

			<div
				class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
				aria-hidden="true">
				<div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#15803d] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
					style="clipPath: 'polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)'," />
			</div>
		</div>
	</div>

<?php 
include('footer.php');
?>
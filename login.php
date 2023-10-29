<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

$adminUrl = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
$clientUrl = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com/client" : "http://localhost/globalhealth-php/client";

if(isset($_SESSION["valid"])){
	if($_SESSION["role"] == 1) {
		header("location:" . $adminUrl);
		exit();
	} else {
		header("location:" . $clientUrl);
	}
}
/* AUTHENTICATION - END */

require_once('connection.php');
include('header.php');

$styleSubmit = "flex w-full justify-center rounded-md bg-blue-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600";
$styleInput = "block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6";
$styleLabel = "block text-sm font-medium leading-6 text-gray-900";

$username = $password = "";

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = test_input($_POST["username"]);
  $password = test_input($_POST["password"]);

  $sql = "SELECT * FROM User WHERE username = '$username' AND password = '$password'";

	$result = $conn->query($sql);

	if ($result !== false && $result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$_SESSION['valid'] = true;
			$_SESSION['timeout'] = time();
			$_SESSION['userId'] = $row['id'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['role'] = $row['role'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['isActive'] = $row['isActive'];
			$_SESSION['organizationId'] = $row['organizationId'];

			if($row['role'] == 1) {
				header("location:" . $adminUrl);
				exit();
			} else {
				header("location:" . $clientUrl);
				exit();				
			}
		}
	}
}

$conn->close();

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
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="space-y-6">
						<div>
							<label for="username" class="<?php echo $styleLabel; ?>">Username</label>
							<div class="mt-2">
								<input id="username" name="username" type="text" placeholder="Username" autocomplete="username" class="<?php echo $styleInput; ?>" required />
							</div>
						</div>
						
						<div>
							<div class="flex items-center justify-between">
								<label for="password" class="<?php echo $styleLabel; ?>">Password</label>
								<div class="text-sm">
									<a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500 text-xs">Forgot password?</a>
								</div>
							</div>
							<div class="mt-2">
								<input id="password" name="password" type="password" placeholder="Password" autocomplete="current-password" class="<?php echo $styleInput; ?>" required />
							</div>
						</div>
						
						<div>
							<button type="submit" class="<?php echo $styleSubmit; ?>">Sign in</button>
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
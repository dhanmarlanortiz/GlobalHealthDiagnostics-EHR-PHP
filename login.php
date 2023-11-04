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

	<style>
		.login-page {
			background-image: 
				/* linear-gradient(91deg, rgba(21, 128, 60, .8), rgba(21, 128, 60, .2)),  */
				url(images/medical-banner-with-doctor-wearing-coat-min.jpg);
			background-size: cover;
    		background-position: center;
		}

		.login-page--inner::before {
			content: "";
			position: absolute;
			width: 100vw;
			height: 100vh;
			background-color: #fff;
			right: 100%;
			top: 0;
		}
		
		@media screen and (max-width: 641px) {
			.login-page--container {
				background-color: rgba(255,255,255,0.9);
			}
		}

		footer {
			display: none !important;
		}		
	</style>

	<div class="login-page">
		<div class="login-page--inner m-auto max-w-7xl relative">
			<div class="login-page--container flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 max-w-xl bg-white shadow-lg" style="min-height: 100vh;">
				<div class="sm:mx-auto sm:w-full sm:max-w-sm">
					<img class="mx-auto w-auto" src="images/ghd-logo.png" alt="Global health diagnostics logo" draggable="false" />
					<h1 class="font-medium mb-10 mt-5 text-3xl text-4xl text-center text-slate-600">
						Global Health <span class="block font-thin text-slate-400 text-xl tracking-wider">DIAGNOSTICS</span>
					</h1>
				</div>
				<div class="sm:mx-auto sm:w-full sm:max-w-sm">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="space-y-6">
						<div>
							<label for="username" class="<?php echo $styleLabel; ?>">Username</label>
							<div class="mt-2">
								<input id="username" name="username" type="text" placeholder="Username" autocomplete="username" class="<?php echo $classInputPrimary; ?>" required />
							</div>
						</div>
						
						<div>
							<div class="flex items-center justify-between">
								<label for="password" class="<?php echo $styleLabel; ?>">Password</label>
								<!-- <div class="text-sm">
									<a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500 text-xs">Forgot password?</a>
								</div> -->
							</div>
							<div class="mt-2">
								<input id="password" name="password" type="password" placeholder="Password" autocomplete="current-password" class="<?php echo $classInputPrimary; ?>" required />
							</div>
						</div>
						
						<div>
							<button type="submit" class="<?php echo $classBtnPrimary; ?> w-full">Sign in</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php 
include('footer.php');
?>
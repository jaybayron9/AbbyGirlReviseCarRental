<?php
session_start();
include('includes/config.php');

$show = false;
$id = isset($_GET['id']) ? $_GET['id'] : false;
$userAdmin = $dbh->query("SELECT * FROM admin WHERE id='{$id}'");
if (!$userAdmin->num_rows > 0) {
	header("location: 403.php");
} 

if (isset($_POST['change_pass'])) {
	$answer = $_POST['answer'];
	$newpassword = $_POST['newpassword'];
	$retypepassword = $_POST['retypepassword'];
	
	$checkAnswer = $dbh->query("SELECT * FROM admin WHERE id='{$id}' AND answer='{$answer}'");

	if ($checkAnswer->num_rows > 0) {
		$correctAnswer = $answer;

		if ($newpassword == $retypepassword) {
			$hashed_password = md5($newpassword);
			$update = $dbh->query("UPDATE admin SET password='{$hashed_password}' WHERE id='{$id}'");
			if ($update) {
				$show = true;
				echo '<script>alert("Password changed successfully")</script>';
			} else {
				$alert = 'Failed to change password';
			}
		} else {
			$alert = 'Password does not match';
		}
	} else {
		$alert = 'Incorrect answer';
	}
}

?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Car Rental Portal | Admin Login</title>
	<link rel="shortcut icon" href="../assets/images/favicon-icon/favicon.png">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
</head>

<body>

	<div class="login-page bk-img" style="background-image: url(img/adminlogin.jpg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center text-bold text-light mt-4x">Change Your Password</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<center><span style="color: red;"><?= isset($alert) ? $alert : '' ?></span></center>
							<div class="col-md-8 col-md-offset-2">
								<form method="post">
									<label for="hint" class="text-uppercase text-sm">Hint</label>
									<input type="text" id="hint" name="hint" value="<?= mysqli_fetch_array($userAdmin)['hint'] ?>" class="form-control mb" disabled>

									<label for="" class="text-uppercase text-sm">Answer</label>
									<input type="text" placeholder="Enter answer" value="<?= isset($correctAnswer) ? $correctAnswer : '' ?>" maxlength="30" name="answer" class="form-control mb" required>

									<label for="" class="text-uppercase text-sm">New Password</label>
									<input type="password" placeholder="**********" maxlength="30" name="newpassword" class="form-control mb" required>

									<label for="" class="text-uppercase text-sm">Retype-Password</label>
									<input type="password" placeholder="***********" maxlength="30" name="retypepassword" class="form-control mb" required>

									
									<button class="btn btn-primary btn-block" name="change_pass" type="submit">SUBMIT</button>

									<?php if ($show) { ?>
									<div style="margin-top: 25px">
										<a href="index.php">Login here</a>
									</div>
									<?php } ?>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

</body>

</html>
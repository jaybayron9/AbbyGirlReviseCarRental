<?php
session_start();
error_reporting(0);
include('includes/config.php');

$infoUser = $dbh->query("SELECT * FROM admin WHERE UserName='{$_SESSION['alogin']}'");

if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	// Code for change password	
	if (isset($_POST['submit'])) {
		$email = $_POST['email'];
		$no = $_POST['no'];
		$hint = $_POST['hint'];
		$answer = $_POST['answer'];
		$password = md5($_POST['password']);
		$newpassword = md5($_POST['newpassword']);
		$username = $_SESSION['alogin'];
		$sql = $dbh->query("SELECT Password FROM admin WHERE UserName='{$username}' and Password='{$password}'");
		if ($sql->num_rows > 0) {
			$con = $dbh->query("
					update admin 
					set 
						Email = '{$email}',
						mobileNo = '{$no}',
						Password='{$newpassword}', 
						hint = '{$hint}', 
						answer = '$answer' 
					where 
						UserName='{$username}'
				");
			$msg = "Your Password succesfully changed";
		} else {
			$error = "Your current password is not valid.";
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
		<meta name="theme-color" content="#3e454c">

		<title>Car Rental Portal | Admin Change Password</title>
		<link rel="shortcut icon" href="../assets/images/favicon-icon/favicon.png">

		<!-- Font awesome -->
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<!-- Sandstone Bootstrap CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap Datatables -->
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
		<!-- Bootstrap social button library -->
		<link rel="stylesheet" href="css/bootstrap-social.css">
		<!-- Bootstrap select -->
		<link rel="stylesheet" href="css/bootstrap-select.css">
		<!-- Bootstrap file input -->
		<link rel="stylesheet" href="css/fileinput.min.css">
		<!-- Awesome Bootstrap checkbox -->
		<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
		<!-- Admin Stye -->
		<link rel="stylesheet" href="css/style.css">
		<script type="text/javascript">
			function valid() {
				if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
					alert("New Password and Confirm Password Field do not match  !!");
					document.chngpwd.confirmpassword.focus();
					return false;
				}
				return true;
			}
		</script>
		<style>
			.errorWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #dd3d36;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.succWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #5cb85c;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}
		</style>


	</head>

	<body>
		<?php include('includes/header.php'); ?>
		<div class="ts-main-content">
			<?php include('includes/leftbar.php'); ?>
			<div class="content-wrapper">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-12">

							<h2 class="page-title">Change Password</h2>

							<div class="row">
								<div class="col-md-10">
									<div class="panel panel-default">
										<div class="panel-heading">Form fields</div>
										<div class="panel-body">
											<form method="post" name="chngpwd" class="form-horizontal" onSubmit="return valid();">
												<?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>

												<div class="form-group">
													<label class="col-sm-4 control-label">Email</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="email" id="email" value="<?= mysqli_fetch_array($infoUser)['email'] ?>" required>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Mobile no.</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="no" id="no" value="<?= mysqli_fetch_array($infoUser)['mobileNo'] ?>" required>
													</div>
												</div>
												<div class="hr-dashed"></div>
												
												<div class="form-group">
													<label class="col-sm-4 control-label">Hint</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="hint" id="hint" value="<?= mysqli_fetch_array($infoUser)['hint'] ?>" required>
													</div>
												</div>
												<div class="hr-dashed"></div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Answer</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="answer" id="answer" value="<?= mysqli_fetch_array($infoUser)['answer'] ?>" required>
													</div>
												</div>
												<div class="hr-dashed"></div>
												
												<div class="form-group">
													<label class="col-sm-4 control-label">Current Password</label>
													<div class="col-sm-8">
														<input type="password" class="form-control" name="password" id="password" required>
													</div>
												</div>
												<div class="hr-dashed"></div>

												<div class="form-group">
													<label class="col-sm-4 control-label">New Password</label>
													<div class="col-sm-8">
														<input type="password" class="form-control" name="newpassword" id="newpassword" required>
													</div>
												</div>
												<div class="hr-dashed"></div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Confirm Password</label>
													<div class="col-sm-8">
														<input type="password" class="form-control" name="confirmpassword" id="confirmpassword" required>
													</div>
												</div>
												<div class="hr-dashed"></div>



												<div class="form-group">
													<div class="col-sm-8 col-sm-offset-4">

														<button class="btn btn-primary" name="submit" type="submit">Save changes</button>
													</div>
												</div>

											</form>

										</div>
									</div>
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
<?php } ?>
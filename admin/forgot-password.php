<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include('includes/config.php');
if (isset($_POST['send'])) {
    $email = $_POST['email'];
    $contactNo = $_POST['contactNo'];
    $check = $dbh->query("SELECT * FROM admin WHERE Email='{$email}' AND mobileNo='{$contactNo}'");

    if ($check->num_rows > 0) {
        $id = mysqli_fetch_array($check)['id'];
        header("location: request-change-pass.php?id={$id}");
    } else {
        $alert = 'Invalid email or contact number';
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
                        <h1 class="text-center text-bold text-light mt-4x">Forgot Your Password?</h1>

                        <div class="well row pt-2x pb-3x bk-light">
                            <div class="col-md-8 col-md-offset-2">
                                <form method="post">
                                    <center>
                                        <p style="font-size: 15px; font-weight:18px;">
                                        We get it, stuff happens. Just enter your email address below and and answer security question to reset your password.
                                        </p>
                                    </center>
                                    <span style="color: red;"><?= isset($alert) ? $alert : '' ?></span>

                                    <div>
                                        <label for="" class="text-uppercase text-sm">Email</label>
                                        <input type="email" placeholder="example@gmail.com" name="email" class="form-control mb" required>
                                    </div>

                                    <label for="" class="text-uppercase text-sm">Contact No.</label>
                                    <input type="text" placeholder="09123456789" name="contactNo" class="form-control mb" required>

                                    <button class="btn btn-primary btn-block" name="send" type="submit">SUBMIT</button>

                                    <div style="margin-top: 25px">
                                        <a href="index.php">I remember!</a>
                                    </div>
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
<?php
session_start();
include('includes/config.php');
if (isset($_POST['login'])) {
    $email = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = $dbh->query("SELECT UserName,Password FROM admin WHERE UserName='{$email}' and Password='{$password}'");
    if ($sql->num_rows > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location = 'change-password.php'; </script>";
    } else {

        echo "<script>alert('Invalid Details');</script>";
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
                                            We get it, stuff happens. Just enter your email address
                                            below and we'll send you a link to reset your password!
                                        </p>
                                    </center>
                                    <label for="" class="text-uppercase text-sm">Email</label>
                                    <input type="email" placeholder="example@gmail.com" name="email" class="form-control mb">

                                    <button class="btn btn-primary btn-block" name="login" type="submit">SEND</button>

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
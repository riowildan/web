<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_template"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // Cek apakah password dan password2 sama
    if ($password != $password2) {
        echo "<script>alert('Password harus sama!');</script>";
        exit;
    }

    // Cek apakah username sudah ada di database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika username sudah digunakan, tampilkan popup
        echo "<script>alert('Username sudah digunakan, silakan gunakan username lain.');</script>";
        exit;
    }

    // Cek apakah email sudah ada di database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika email sudah digunakan, tampilkan popup
        echo "<script>alert('Email sudah digunakan, silakan gunakan email lain.');</script>";
        exit;
    }

    // Hash password sebelum menyimpannya ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil!'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        echo "Terjadi kesalahan saat registrasi!";
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Sign Up</title>
  </head>
  <body>
    <div class="half">
      <div class="bg order-1 order-md-2" style="background-image: url('images/bg_1.jpg');"></div>
      <div class="contents order-2 order-md-1">
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-6">
              <div class="form-block">
                <div class="text-center mb-5">
                  <h3>Register</h3>
                </div>
                <form action="" method="post">
                  <div class="form-group first">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" placeholder="JohnDoe" id="username" name="username" required>
                  </div>
                  <div class="form-group first">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" placeholder="your-email@gmail.com" id="email" name="email" required>
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" placeholder="Your Password" id="password" name="password" required>
                  </div>
                  <div class="form-group last mb-3">
                    <label for="re-password">Re-type Password</label>
                    <input type="password" class="form-control" placeholder="Re-type Your Password" id="re-password" name="password2" required>
                  </div>
                  <div class="d-sm-flex mb-5 align-items-center">
                    <label class="control control--checkbox mb-3 mb-sm-0"><span class="caption">Agree to our <a href="#">Terms and Conditions</a></span>
                      <input type="checkbox" required />
                      <div class="control__indicator"></div>
                    </label>
                    <span class="ml-auto"><a href="#" class="forgot-pass">Have an account? Login</a></span> 
                  </div>
                  <input type="submit" value="Register" class="btn btn-block btn-primary">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>

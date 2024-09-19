<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_template"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = ""; // Variabel untuk menyimpan pesan error

// Cek apakah method request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan filter input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Cek apakah username dan password tidak kosong
    if (!empty($username) && !empty($password)) {
        // Cek apakah username ada di database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika username ditemukan
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Set session untuk user yang berhasil login
                $_SESSION['username'] = $user['username'];
                echo "<script>alert('Login berhasil!');</script>";
                // Redirect ke halaman "/template"
                header("Location: /template");
                exit(); // Pastikan eksekusi dihentikan setelah redirect
            } else {
                $error_message = "Password salah!";
            }
        } else {
            $error_message = "Username tidak ditemukan!";
        }
    } else {
        $error_message = "Username dan Password tidak boleh kosong!";
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
  	<title>Login 10</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	</head>
	<body class="img js-fullheight" style="background-image: url(images/bg.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<h3 class="mb-4 text-center">Have an account?</h3>

		      	<!-- Tampilkan pesan error jika ada -->
		      	<?php if (!empty($error_message)): ?>
		      		<div class="alert alert-danger">
		      			<?php echo $error_message; ?>
		      		</div>
		      	<?php endif; ?>

		      	<form action="" method="POST" class="signin-form">
		      		<div class="form-group">
		      			<input type="text" class="form-control" placeholder="Username" name="username" required>
		      		</div>
		            <div class="form-group">
		              <input id="password-field" type="password" class="form-control" placeholder="Password" name="password" required>
		              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
		            </div>
		            <div class="form-group">
		            	<button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
		            </div>
		            <div class="form-group d-md-flex">
		            	<div class="w-50">
			            	<label class="checkbox-wrap checkbox-primary">Remember Me
									  <input type="checkbox" checked>
									  <span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#" style="color: #fff">Forgot Password</a>
								</div>
		            </div>
		          </form>
		          <a class="w-100 text-center" href="/template/register/register.php">Register</a>
		          <div class="social d-flex text-center">
		          	<a href="#" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> Facebook</a>
		          	<a href="#" class="px-2 py-2 ml-md-1 rounded"><span class="ion-logo-twitter mr-2"></span> Twitter</a>
		          </div>
			      </div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

	</body>
</html>

<?php
// ✅ CORS Headers (For development, change * to your domain in production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-KEY");

// ✅ Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline';");

// ✅ API Key Authentication
if (isset($_SERVER['HTTP_X_API_KEY'])) {
    $receivedKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if ($receivedKey !== API_SECRET_KEY) {
        http_response_code(401);
        exit('Unauthorized request.');
    }
}

// ✅ Rate Limiting
$ip = str_replace(':', '-', $_SERVER['REMOTE_ADDR']);
$rateDir = __DIR__ . "/rates";
if (!is_dir($rateDir)) {
    mkdir($rateDir, 0755, true);
}
$limitFile = "$rateDir/rate-limit-$ip.json";
$time = time();

if (file_exists($limitFile)) {
    $data = json_decode(file_get_contents($limitFile), true);
    if (($time - $data['time']) < 60) {
        $data['count']++;
        if ($data['count'] > 5) {
            http_response_code(429);
            exit('Too many requests. Try again in 1 minute.');
        }
    } else {
        $data = ['time' => $time, 'count' => 1];
    }
} else {
    $data = ['time' => $time, 'count' => 1];
}
file_put_contents($limitFile, json_encode($data));

// ✅ Continue with your original login logic
require '../config/config.php';
// require '../vendor/autoload.php'; // JWT ke liye

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if(isset($_POST['login'])) {

    // Get data from FORM
    $username = $_POST['username'];
    $email = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $connect->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $stmt->execute(array(
            ':username' => $username,
            ':email' => $email
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data == false){
            $errMsg = "User $username not found.";
        } else {
            if(password_verify($password, $data['password'])) {
                $_SESSION['id'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['fullname'] = $data['fullname'];
                $_SESSION['role'] = $data['role'];

                //  JWT Generate 
                $key = "your-secret-key";
                $payload = array(
                    "id" => $data['id'],
                    "username" => $data['username']
                );
                // $jwt = $jwt::encode($payload, $key, 'HS256');
                // echo json_encode(["token" => $jwt"]);

                //  Redirect
                header('Location: dashboard.php');
                exit;
            } else {
                $errMsg = 'Password not match.';
            }
        }
    }
    catch(PDOException $e) {
        $errMsg = $e->getMessage();
    }
}
?>

<?php include '../include/header.php'; ?>
<!-- Services -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
  <div class="container">
    <a class="navbar-brand js-scroll-trigger" href="../index.php">Logo/Home</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive"
      aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      Menu
      <i class="fa fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav text-uppercase ml-auto">
        <li class="nav-item">
          <!-- <a class="nav-link" href="login.php">Login</a> -->
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<section id="services">
    <div class="container">
        <div class="row">                
          <div class="col-md-4 mx-auto">
            <div class="alert alert-info" role="alert">
                <?php
                    if(isset($errMsg)){
                        echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
                    }
                ?>
                <h2 class="text-center">Login</h2>
                <form action="" method="post">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email Address/User Name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Email" name="username" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
                  </div>
                  <button type="submit" class="btn btn-primary" name='login' value="Login">Submit</button>
                </form>                 
             </div>
        </div>
        </div>
    </div>
</section>
<?php include '../include/footer.php'; ?>

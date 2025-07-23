<?php
require_once __DIR__ . '/../config/config.php';

// --- CORS & Security Headers ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-KEY");

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline';");

// --- API Key Auth (optional) ---
if (isset($_SERVER['HTTP_X_API_KEY'])) {
    $receivedKey = $_SERVER['HTTP_X_API_KEY'];
    if ($receivedKey !== API_SECRET_KEY) {
        http_response_code(401);
        exit('Unauthorized request.');
    }
}

// --- Rate Limiting (5 requests/minute per IP) ---
$ip = str_replace(':', '-', $_SERVER['REMOTE_ADDR']);
$rateDir = __DIR__ . "/rates";
if (!is_dir($rateDir)) mkdir($rateDir, 0755, true);
$limitFile = "$rateDir/rate-limit-$ip.json";
$time = time();
$data = ['time' => $time, 'count' => 1];

if (file_exists($limitFile)) {
    $data = json_decode(file_get_contents($limitFile), true);
    if (($time - $data['time']) < 60) {
        $data['count']++;
        if ($data['count'] > 5) {
            http_response_code(429);
            exit('Too many requests. Try again later.');
        }
    } else {
        $data = ['time' => $time, 'count' => 1];
    }
}
file_put_contents($limitFile, json_encode($data));

// --- Login Processing ---
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (isset($_POST['login'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('⚠️ CSRF token verification failed.');
    }

    $username = $_POST['username'];
    $email = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $connect->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $stmt->execute([':username' => $username, ':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $errMsg = "User not found.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            // JWT Optional - commented unless you use it for API auth
            // $key = "your-secret-key";
            // $payload = ["id" => $user['id'], "username" => $user['username']];
            // $jwt = JWT::encode($payload, $key, 'HS256');
            // echo json_encode(["token" => $jwt]);

            header('Location: dashboard.php');
            exit;
        } else {
            $errMsg = 'Incorrect password.';
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $errMsg = "Login failed. Please try again.";
    }
}
?>

<!-- Frontend starts -->
<?php include '../include/header.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
  <div class="container">
    <a class="navbar-brand js-scroll-trigger" href="../index.php">Logo/Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
      Menu <i class="fa fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav text-uppercase ml-auto">
        <li class="nav-item"></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<section id="services">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mx-auto">
        <div class="alert alert-info">
          <?php if (isset($errMsg)) echo '<div class="text-danger text-center">'.$errMsg.'</div>'; ?>
          <h2 class="text-center">Login</h2>
          <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
              <label>Email / Username</label>
              <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include '../include/footer.php'; ?>

<?php
require_once 'session_config.php';

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = "Both fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            session_regenerate_id(true); // prevent session fixation

            // Set "Remember Me" cookie if checked
            if (!empty($_POST['remember_me'])) {
                setRememberMeCookie($pdo, (int)$user['id']);
            }

            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid email or password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - KUET Career Club</title>
    <meta name="description" content="Log in to your KUET Career Club account." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main class="auth-main">
      <div class="auth-card">
        <div class="auth-header">
          <div class="auth-logo">KCC</div>
          <h1 class="auth-title">Welcome back</h1>
          <p class="auth-subtitle">Log in to your KUET Career Club account</p>
        </div>

        <?php if (!empty($error_message)): ?>
          <div class="form-alert form-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form class="auth-form" id="loginForm" action="login.php" method="POST">
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@kuet.ac.bd" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
              <input type="password" id="password" name="password" placeholder="Enter your password" required />
              <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <div class="form-group form-check-group">
            <label class="checkbox-label" for="remember_me">
              <input type="checkbox" id="remember_me" name="remember_me" value="1" />
              <span>Remember me for 30 days</span>
            </label>
          </div>

          <button type="submit" class="btn btn-primary auth-submit">Log In</button>
        </form>

        <div class="auth-divider"><span>or</span></div>

        <p class="auth-footer-text">
          Don't have an account? <a href="signup.php" class="form-link">Sign up for free</a>
        </p>
      </div>
    </main>
    <?php include 'includes/footer.php'; ?>

    <script>
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput  = document.getElementById('password');
      togglePassword.addEventListener('click', () => {
        const isText = passwordInput.type === 'text';
        passwordInput.type = isText ? 'password' : 'text';
      });
    </script>
  </body>
</html>

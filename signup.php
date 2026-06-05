<?php
require_once 'session_config.php';

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name            = trim($_POST['name']);
    $email           = trim($_POST['email']);
    $studentId       = trim($_POST['studentId']);
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($name) || empty($email) || empty($studentId) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error_message = "Passwords do not match.";
    } else {
        // Check duplicate email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error_message = "This email is already registered.";
        } else {
            // Check duplicate student ID
            $stmt = $pdo->prepare("SELECT id FROM users WHERE student_id = ?");
            $stmt->execute([$studentId]);
            if ($stmt->fetch()) {
                $error_message = "This Student ID is already registered.";
            } else {
                // Insert user
                $stmt = $pdo->prepare("INSERT INTO users (name, email, student_id, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $studentId, $password]);

                // Auto-login after signup
                $_SESSION['user_id']   = $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = 'member';
                header("Location: dashboard.php");
                exit;
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - KUET Career Club</title>
    <meta name="description" content="Create your KUET Career Club account." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main class="auth-main">
      <div class="auth-card">
        <div class="auth-header">
          <div class="auth-logo">KCC</div>
          <h1 class="auth-title">Create your account</h1>
          <p class="auth-subtitle">Join KUET Career Club today</p>
        </div>

        <?php if (!empty($success_message)): ?>
          <div class="form-alert form-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
          <div class="form-alert form-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form class="auth-form" id="signupForm" action="signup.php" method="POST">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required />
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@kuet.ac.bd" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
          </div>

          <div class="form-group">
            <label for="studentId">Student ID</label>
            <input type="text" id="studentId" name="studentId" placeholder="e.g. 2207025" value="<?php echo isset($_POST['studentId']) ? htmlspecialchars($_POST['studentId']) : ''; ?>" required />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required />
          </div>

          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter your password" required />
          </div>

          <button type="submit" class="btn btn-primary auth-submit">Sign Up</button>
        </form>

        <div class="auth-divider"><span>or</span></div>

        <p class="auth-footer-text">
          Already have an account? <a href="login.php" class="form-link">Log in</a>
        </p>
      </div>
    </main>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>

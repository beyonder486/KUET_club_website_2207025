<?php
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name            = htmlspecialchars(trim($_POST['name']));
    $email           = htmlspecialchars(trim($_POST['email']));
    $studentId       = htmlspecialchars(trim($_POST['studentId']));
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
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $success_message = "Welcome, " . $name . "! You have successfully signed up for KUET Career Club.";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - KUET Career Club</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <header>
      <div class="brand">
        <span class="logo">KUET Career Club</span>
      </div>
      <nav>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="events.html">Events</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="resources.html">Resources</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="login.html">Login</a></li>
        </ul>
      </nav>
      <a class="header-cta nav-active" href="signup.php">Sign Up</a>
    </header>
    <main>
      <section id="signup">
        <h2>Sign Up for KUET Career Club</h2>

        <?php if (!empty($success_message)): ?>
          <p style="color: green; font-weight: bold;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
          <p style="color: red; font-weight: bold;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form id="signupForm" action="signup.php" method="POST">
          <label for="name">Full Name:</label>
          <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>

          <label for="studentId">Student ID:</label>
          <input type="text" id="studentId" name="studentId" value="<?php echo isset($_POST['studentId']) ? htmlspecialchars($_POST['studentId']) : ''; ?>" required>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>

          <label for="confirmPassword">Confirm Password:</label>
          <input type="password" id="confirmPassword" name="confirmPassword" required>

          <button type="submit">Sign Up</button>
          <button type="reset">Reset</button>
        </form>
      </section>
    </main>
    <footer>
      <div class="footer-links">
        <div>
          <h3>About us</h3>
          <ul>
            <li><a href="#">Team</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Careers</a></li>
            <li><a href="#">Our values</a></li>
          </ul>
        </div>
      </div>
    </footer>
  </body>
</html>

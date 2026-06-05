<?php
require_once 'session_config.php';

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        $success_message = "Your message has been sent! We'll get back to you soon.";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact - KUET Career Club</title>
    <meta name="description" content="Get in touch with KUET Career Club." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main class="auth-main">
      <div class="auth-card" style="max-width:520px;">
        <div class="auth-header">
          <div class="auth-logo">✉</div>
          <h1 class="auth-title">Get in Touch</h1>
          <p class="auth-subtitle">Have a question or suggestion? We'd love to hear from you.</p>
        </div>

        <?php if (!empty($success_message)): ?>
          <div class="form-alert form-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
          <div class="form-alert form-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form class="auth-form" action="contact.php" method="POST">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Full name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="you@kuet.ac.bd" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
          </div>
          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="What's this about?" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" required />
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary auth-submit">Send Message</button>
        </form>
      </div>
    </main>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logged_in = isset($_SESSION['user_id']);
$user_name = $logged_in ? $_SESSION['user_name'] : '';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<header>
  <div class="brand">
    <span class="logo">KUET Career Club</span>
  </div>
  <nav>
    <ul>
      <li><a href="index.php" <?php echo $current_page === 'index' ? 'class="nav-active"' : ''; ?>>Home</a></li>
      <li><a href="events.php" <?php echo $current_page === 'events' ? 'class="nav-active"' : ''; ?>>Events</a></li>
      <li><a href="about.php" <?php echo $current_page === 'about' ? 'class="nav-active"' : ''; ?>>About</a></li>
      <li><a href="resources.php" <?php echo $current_page === 'resources' ? 'class="nav-active"' : ''; ?>>Resources</a></li>
      <li><a href="contact.php" <?php echo $current_page === 'contact' ? 'class="nav-active"' : ''; ?>>Contact</a></li>
      <?php if ($logged_in): ?>
        <li><a href="dashboard.php" <?php echo $current_page === 'dashboard' ? 'class="nav-active"' : ''; ?>>Dashboard</a></li>
      <?php else: ?>
        <li><a href="login.php" <?php echo $current_page === 'login' ? 'class="nav-active"' : ''; ?>>Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <?php if ($logged_in): ?>
    <a class="header-cta" href="logout.php">Logout</a>
  <?php else: ?>
    <a class="header-cta <?php echo $current_page === 'signup' ? 'nav-active' : ''; ?>" href="signup.php">Sign Up</a>
  <?php endif; ?>
</header>
<script src="bg.js"></script>

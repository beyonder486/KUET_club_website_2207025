<?php
require_once 'session_config.php';

// Clear "Remember Me" cookie and tokens if user is logged in
if (isset($_SESSION['user_id'])) {
    clearRememberMe($pdo, (int)$_SESSION['user_id']);
}

// Destroy the session
session_unset();
session_destroy();

header("Location: index.php");
exit;

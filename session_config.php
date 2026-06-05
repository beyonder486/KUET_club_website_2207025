<?php
/**
 * Session & Cookie Configuration — KUET Career Club
 * --------------------------------------------------
 * Include this file at the VERY TOP of every PHP page (before any output):
 *   require_once 'session_config.php';
 *
 * It handles:
 *   1. Secure session settings (HttpOnly, SameSite, lifetime)
 *   2. "Remember Me" cookie-based auto-login
 *   3. CSRF token generation for forms
 */

require_once 'db_config.php';

// ─── 1. SESSION CONFIGURATION ──────────────────────────────────────
// Set session cookie parameters BEFORE session_start()
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,            // session cookie — dies when browser closes
    'path'     => '/',
    'domain'   => '',           // current domain
    'secure'   => $is_https,    // only over HTTPS when available
    'httponly'  => true,         // JavaScript cannot access session cookie
    'samesite' => 'Lax',        // CSRF protection
]);

// Use a custom session name instead of the default PHPSESSID
session_name('KCC_SESSION');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── 2. SESSION SECURITY ───────────────────────────────────────────
// Regenerate session ID periodically to prevent session fixation
if (!isset($_SESSION['_created'])) {
    $_SESSION['_created'] = time();
} elseif (time() - $_SESSION['_created'] > 1800) {
    // Regenerate session ID every 30 minutes
    session_regenerate_id(true);
    $_SESSION['_created'] = time();
}

// Track last activity for idle timeout (30 minutes)
if (isset($_SESSION['_last_activity']) && (time() - $_SESSION['_last_activity'] > 1800)) {
    // Session has been idle too long — destroy it
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['_last_activity'] = time();


// ─── 3. "REMEMBER ME" AUTO-LOGIN ──────────────────────────────────
// If the user is NOT logged in but has a remember_me cookie, try auto-login
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    // Look up the token in the database
    $stmt = $pdo->prepare("
        SELECT rt.user_id, rt.expires_at, u.name, u.role
        FROM remember_tokens rt
        JOIN users u ON rt.user_id = u.id
        WHERE rt.token = ? AND rt.expires_at > NOW()
        LIMIT 1
    ");
    $stmt->execute([$token]);
    $row = $stmt->fetch();

    if ($row) {
        // Valid token — restore session
        $_SESSION['user_id']   = $row['user_id'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_role'] = $row['role'];
        session_regenerate_id(true);
    } else {
        // Invalid or expired token — clean up the cookie
        setcookie('remember_me', '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => $is_https,
            'httponly'  => true,
            'samesite' => 'Lax',
        ]);
    }
}


// ─── 4. HELPER: SET "REMEMBER ME" COOKIE & TOKEN ──────────────────
/**
 * Call this after a successful login when "remember me" is checked.
 * Creates a secure random token, stores it in the DB, and sets a cookie.
 */
function setRememberMeCookie(PDO $pdo, int $userId): void
{
    $token     = bin2hex(random_bytes(32));  // 64-char hex token
    $expiresAt = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60); // 30 days
    $is_https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    // Store in DB
    $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $token, $expiresAt]);

    // Set cookie
    setcookie('remember_me', $token, [
        'expires'  => time() + 30 * 24 * 60 * 60,
        'path'     => '/',
        'secure'   => $is_https,
        'httponly'  => true,
        'samesite' => 'Lax',
    ]);
}


// ─── 5. HELPER: CLEAR "REMEMBER ME" COOKIE & TOKENS ──────────────
/**
 * Call this on logout to remove the cookie and all stored tokens for the user.
 */
function clearRememberMe(PDO $pdo, int $userId): void
{
    $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    // Delete all tokens for this user from DB
    $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
    $stmt->execute([$userId]);

    // Expire the cookie
    setcookie('remember_me', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'secure'   => $is_https,
        'httponly'  => true,
        'samesite' => 'Lax',
    ]);
}


// ─── 6. CSRF TOKEN HELPER ─────────────────────────────────────────
/**
 * Generate or retrieve the CSRF token for the current session.
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate a submitted CSRF token against the session token.
 */
function validateCsrf(string $submittedToken): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $submittedToken);
}

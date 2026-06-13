# KUET Career Club (KUET_club_website_2207025)

## Project Overview

KUET Career Club is a small PHP website for KUET students to discover and register for events, access curated career resources, and connect with alumni. It includes member authentication, a user dashboard, event registration, and an admin panel for managing events and messages.

## Quick Start

- Import the database: open `setup.sql` and import into a MySQL database named `kc`.
- Update database credentials in `db_config.php` if needed (defaults assume XAMPP local setup).
- Serve the project with XAMPP/Apache and open `index.php` in your browser.

## Key Features

- Authentication & Sessions: sign up, login, secure session settings, "remember me" tokens (see `signup.php`, `login.php`, `session_config.php`).
- Events System: create, list, filter (type/status), seat counting, and register for events (see `events.php`, `admin.php`, `setup.sql`).
- Resources: curated career resource cards with client-side category filters (see `resources.php` / `resources.html`).
- Member Dashboard: profile and registered events for logged-in users (see `dashboard.php`).
- Admin Panel: admin-only event management, view registrations, and handle contact messages (see `admin.php`).
- Contact Messages: stored in the database for admin review (see `contact_messages` table in `setup.sql`).

## Important Files

- `index.php` — Home page and hero content.
- `db_config.php` — PDO database connection.
- `session_config.php` — Session hardening, CSRF helpers, remember-me helpers.
- `signup.php`, `login.php`, `logout.php` — Authentication flows.
- `events.php`, `resources.php`, `dashboard.php` — Main user features.
- `admin.php` — Admin dashboard and event management.
- `setup.sql` — Database schema and seed data (includes an admin user).

## Security Notes

- Passwords are currently stored and compared in plain text. Replace with `password_hash()` and `password_verify()` for production.
- CSRF helper functions exist in `session_config.php` (`csrfToken()` / `validateCsrf()`), but forms should include hidden CSRF inputs.
- Session cookies are hardened (`HttpOnly`, `SameSite`, `secure` when HTTPS) in `session_config.php`.

## Next Steps / Recommendations

- Migrate password storage to `password_hash()` / `password_verify()`.
- Add CSRF tokens to all POST forms.
- Consider input validation and output escaping where missing to harden against XSS/SQL injection.

If you want, I can also add this as `features.md` instead, or make further security improvements.
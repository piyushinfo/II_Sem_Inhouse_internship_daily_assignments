<?php
/**
 * auth_check.php
 * The "golden pattern" from Module 4: check session → redirect or proceed.
 *
 * Include this at the VERY TOP of every page that should require a
 * logged-in user — before any HTML is echoed and before session_start()
 * is called anywhere else on the page.
 *
 * Usage:
 *   <?php include 'auth_check.php'; ?>
 *   ... rest of the protected page ...
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    // No valid session — send them straight back to the login page.
    header("Location: login.php");
    exit(); // Critical: without exit(), PHP keeps running the rest of the page.
}
?>

<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/google_auth.php';

// Get the authorization code
$code = isset($_GET['code']) ? $_GET['code'] : null;

if ($code) {
    // Handle the callback
    if (handleGoogleCallback($code)) {
        // Successful login/registration
        header('Location: dashboard.php');
        exit();
    }
}

// If we get here, something went wrong
$_SESSION['error'] = 'Failed to authenticate with Google. Please try again.';
header('Location: login.php');
exit(); 
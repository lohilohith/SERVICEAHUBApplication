<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to home page
// Change 'yourproject' to your actual project folder name if needed
$homeURL = "http://localhost/yourproject/index.php";

// Optional: prevent caching issues
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

header("Location: $homeURL");
exit();

ob_end_flush();
?>

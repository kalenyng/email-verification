<?php
// config.php

// Set to true for local/dev, false for production
define('APP_DEBUG', true);

if (APP_DEBUG) {
    // Use your actual local host
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'email_verification');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // Use your actual production host
    define('DB_HOST', 'sql208.infinityfree.com');
    define('DB_NAME', 'if0_38615228_email_verification');
    define('DB_USER', 'if0_38615228');
    define('DB_PASS', 'OgvxSKIaxSgH');
}

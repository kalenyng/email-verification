<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';           // Your PDO connection setup
require_once 'send_contact.php'; // Your PHPMailer function

$email = $_SESSION['email_to_verify'] ?? '';
if (!$email) {
    header("Location: register.php");
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: register.php");
    exit;
}

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        // Delete user from database
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        session_destroy();
        header("Location: register.php"); // Redirect after deletion
        exit;
    } elseif (isset($_POST['contact'])) {
        if (sendContactEmail($user['name'], $email)) {
            $message = "Contact email sent successfully!";
        } else {
            $error = "Failed to send contact email. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.1/dist/lux/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
        }

        .card {
            border-radius: 20px;
        }

        .btn {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="max-width: 480px; width: 100%;">
            <div class="text-center mb-4">
                <i class="bi bi-person-circle fs-1 text-primary"></i>
                <h2 class="mt-2">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
                <p class="text-muted">What would you like to do?</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <button type="submit" name="contact" class="btn btn-outline-primary mb-3">
                    <i class="bi bi-envelope-at me-2"></i> CLick here and I'll be in touch!
                </button>
                <button type="submit" name="remove" class="btn btn-outline-danger"
                    onclick="return confirm('Are you sure you want to remove your email?');">
                    <i class="bi bi-trash me-2"></i> Remove Email
                </button>
            </form>
        </div>
    </div>
</body>

</html>
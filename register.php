<?php
session_start();
require_once 'db.php';
require_once 'send_email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$name || !$password) {
        $error = "Please enter a valid name, email, and password.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered. Please verify or login.";
        } else {
            // Hash password securely
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Generate verification code
            $verificationCode = random_int(100000, 999999);

            // Insert user including password hash
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, verification_code, is_verified, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
            $stmt->execute([$name, $email, $passwordHash, $verificationCode]);

            // Send verification email
            if (sendVerificationEmail($email, $verificationCode)) {
                // Save email in session to use in verify.php
                $_SESSION['email_to_verify'] = $email;

                $_SESSION['message'] = "Verification code sent to $email. Please check your inbox.";
                header('Location: verify.php');
                exit;
            } else {
                $error = "Failed to send verification email. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <!-- Bootstrap CSS (Bootswatch Lux theme) -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.1/dist/lux/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5" style="max-width: 480px;">
        <h2 class="mb-4">Register</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nameInput" class="form-label">Name:</label>
                <input type="text" class="form-control" id="nameInput" name="name" required />
            </div>

            <div class="mb-3">
                <label for="emailInput" class="form-label">Email:</label>
                <input type="email" class="form-control" id="emailInput" name="email" required />
            </div>

            <div class="mb-3">
                <label for="passwordInput" class="form-label">Password:</label>
                <input type="password" class="form-control" id="passwordInput" name="password" required />
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>

</html>
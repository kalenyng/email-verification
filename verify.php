<?php
session_start();
require_once 'db.php';

$email = $_SESSION['email_to_verify'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputCode = trim($_POST['code'] ?? '');

    if (!$email || !$inputCode) {
        $error = "Verification email not found or code missing.";
    } else {
        $stmt = $pdo->prepare("SELECT id, verification_code, is_verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "Email not found. Please register first.";
        } elseif ($user['is_verified']) {
            $_SESSION['email_to_verify'] = $email; // keep email for index.php
            header("Location: index.php");
            exit;
        } elseif ($inputCode === $user['verification_code']) {
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verified_at = NOW(), verification_code = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            $_SESSION['email_to_verify'] = $email; // keep email for index.php
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify Email</title>
    <!-- Bootstrap CSS (Bootswatch Lux theme) -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.1/dist/lux/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5" style="max-width: 480px;">
        <h2 class="mb-4">Verify your email</h2>

        <?php if ($message): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!isset($success)): ?>
            <p>Email to verify: <strong><?= htmlspecialchars($email) ?></strong></p>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="codeInput" class="form-label">Verification Code:</label>
                    <input type="text" class="form-control" id="codeInput" name="code" maxlength="6" required />
                </div>

                <button type="submit" class="btn btn-primary">Verify</button>
            </form>
        <?php else: ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <hr />
    </div>
</body>

</html>
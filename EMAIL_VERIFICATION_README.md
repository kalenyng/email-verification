
# 📧 Email Verification System

## Overview

This PHP-based email verification system allows you to manage user registrations and verify email addresses through a secure token-based mechanism. It includes features for user registration, email verification, and contact form handling. ✉️✅

---

## Features

- 📝 **User Registration**: Allows users to register with their email addresses.
- 🔒 **Email Verification**: Sends a verification link to the user's email to confirm their identity.
- 📬 **Contact Form**: Enables users to send messages via a contact form.
- 📧 **PHPMailer Integration**: Utilizes PHPMailer for sending emails securely.

---

## Requirements

- PHP 7.1 or higher 🐘
- MySQL or MariaDB 🗄️
- Composer (for dependency management) 📦
- A mail server or SMTP service (e.g., Gmail, SendGrid) 📤

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/kalenyng/email-verification.git
cd email-verification
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Set Up the Database

Import the provided SQL file or run the following SQL queries to create the database and tables:

```sql
-- Create the database
CREATE DATABASE email_verification;

-- Use the database
USE email_verification;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(255) NOT NULL
);

-- Create the contact_messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Configure Database Connection

Edit the `db.php` file to include your database credentials:

```php
<?php
$host = 'localhost';
$dbname = 'email_verification';
$username = 'root';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
```

### 5. Configure PHPMailer

Make sure PHPMailer is installed. If not, install it via Composer:

```bash
composer require phpmailer/phpmailer
```

Then configure PHPMailer in your `send_email.php` file:

```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_email_password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('from_email@example.com', 'Mailer');
    $mail->addAddress('recipient@example.com', 'Joe User');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    $mail->send();
    echo 'Message has been sent 📤';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {\$mail->ErrorInfo} ❌";
}
?>
```

Replace the placeholders with your actual SMTP details.

---

## Usage

1. **Register a User**: Navigate to `register.php` and fill out the registration form. 📝
2. **Verify Email**: After registration, a verification link will be sent to the provided email address. Click the link to verify your email. ✔️
3. **Send a Message**: Use the contact form to send a message, which will be stored in the database. 💬

---

<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /movie_web/cur_v1/contact.php');
    exit;
}

// Validate inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

if (!empty($errors)) {
    // In a real application, you might want to store errors in session and redirect
    die('Please fill all required fields correctly.');
}

try {
    // Insert into database
    $stmt = $pdo->prepare('INSERT INTO messages (name, email, phone, message) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $phone, $message]);

    // Send email
    $to = 'salomonmusare44@gmail.com';
    $subject = 'New Contact Form Submission - MovieVerse';
    $emailMessage = "Name: $name\n";
    $emailMessage .= "Email: $email\n";
    $emailMessage .= "Phone: $phone\n\n";
    $emailMessage .= "Message:\n$message";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    mail($to, $subject, $emailMessage, $headers);

    // Redirect to success page
    header('Location: /movie_web/cur_v1/contact.php?success=1');
    exit;
} catch (PDOException $e) {
    // Log error and show generic message
    error_log('Contact form error: ' . $e->getMessage());
    die('An error occurred. Please try again later.');
}
?> 
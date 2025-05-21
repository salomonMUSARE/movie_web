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

    // Prepare email content
    $emailContent = "Name: $name\n";
    $emailContent .= "Email: $email\n";
    $emailContent .= "Phone: $phone\n\n";
    $emailContent .= "Message:\n$message";

    // Send to admin
    $toAdmin = 'salomonmusare44@gmail.com';
    $subjectAdmin = 'New Contact Form Submission - MovieVerse';
    $headersAdmin = "From: $email\r\n";
    $headersAdmin .= "Reply-To: $email\r\n";
    $headersAdmin .= "X-Mailer: PHP/" . phpversion();

    mail($toAdmin, $subjectAdmin, $emailContent, $headersAdmin);

    // Send copy to user
    $toUser = $email;
    $subjectUser = 'Your Message to MovieVerse - Confirmation';
    $headersUser = "From: MovieVerse <noreply@movieverse.com>\r\n";
    $headersUser .= "Reply-To: salomonmusare44@gmail.com\r\n";
    $headersUser .= "X-Mailer: PHP/" . phpversion();

    $userEmailContent = "Dear $name,\n\n";
    $userEmailContent .= "Thank you for contacting MovieVerse. Here's a copy of your message:\n\n";
    $userEmailContent .= $emailContent;
    $userEmailContent .= "\n\nWe'll get back to you soon!\n\n";
    $userEmailContent .= "Best regards,\nMovieVerse Team";

    mail($toUser, $subjectUser, $userEmailContent, $headersUser);

    // Redirect to success page
    header('Location: /movie_web/cur_v1/contact.php?success=1');
    exit;
} catch (PDOException $e) {
    // Log error and show generic message
    error_log('Contact form error: ' . $e->getMessage());
    die('An error occurred. Please try again later.');
}
?> 
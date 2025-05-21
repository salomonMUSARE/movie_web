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

    // Prepare detailed email content for admin
    $adminEmailContent = "New Contact Form Submission Received!\n\n";
    $adminEmailContent .= "Client Details:\n";
    $adminEmailContent .= "----------------\n";
    $adminEmailContent .= "Name: $name\n";
    $adminEmailContent .= "Email: $email\n";
    $adminEmailContent .= "Phone: $phone\n\n";
    $adminEmailContent .= "Message:\n";
    $adminEmailContent .= "----------------\n";
    $adminEmailContent .= $message;
    $adminEmailContent .= "\n\n";
    $adminEmailContent .= "Submitted at: " . date('Y-m-d H:i:s') . "\n";
    $adminEmailContent .= "----------------\n";

    // Send detailed copy to admin
    $toAdmin = 'salomonmusare44@gmail.com';
    $subjectAdmin = 'New Contact Form Submission - MovieVerse';
    $headersAdmin = "From: MovieVerse Contact Form <noreply@movieverse.com>\r\n";
    $headersAdmin .= "Reply-To: $email\r\n";
    $headersAdmin .= "X-Mailer: PHP/" . phpversion();

    mail($toAdmin, $subjectAdmin, $adminEmailContent, $headersAdmin);

    // Send simple confirmation to user
    $toUser = $email;
    $subjectUser = 'Thank You for Contacting MovieVerse';
    $headersUser = "From: MovieVerse <noreply@movieverse.com>\r\n";
    $headersUser .= "Reply-To: salomonmusare44@gmail.com\r\n";
    $headersUser .= "X-Mailer: PHP/" . phpversion();

    $userEmailContent = "Dear $name,\n\n";
    $userEmailContent .= "Thank you for contacting MovieVerse. We have received your message and will get back to you soon.\n\n";
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
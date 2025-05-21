<?php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieVerse - Your Ultimate Movie Destination</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/movie_web/cur_v1/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/movie_web/cur_v1/index.php">
                <i class="bi bi-camera-reels-fill me-2"></i>MovieVerse
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/movie_web/cur_v1/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/movie_web/cur_v1/about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/movie_web/cur_v1/contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php if (basename($_SERVER['PHP_SELF']) === 'index.php'): ?>
    <div class="hero-banner">
        <div class="container">
            <h1 class="animate__animated animate__fadeIn">Welcome to MovieVerse</h1>
            <p class="lead animate__animated animate__fadeIn animate__delay-1s">Discover the magic of cinema</p>
        </div>
    </div>
    <?php endif; ?>
    <main class="container py-4"> 
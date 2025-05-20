<?php
require_once 'config.php';

header('Content-Type: application/json');

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;

try {
    // Get total count
    $countStmt = $pdo->query('SELECT COUNT(*) FROM movies');
    $totalMovies = $countStmt->fetchColumn();
    $totalPages = ceil($totalMovies / $perPage);

    // Get movies for current page
    $stmt = $pdo->prepare('SELECT id, title, release_year, poster_url FROM movies ORDER BY id LIMIT ? OFFSET ?');
    $stmt->execute([$perPage, $offset]);
    $movies = $stmt->fetchAll();

    echo json_encode([
        'movies' => $movies,
        'total_pages' => $totalPages,
        'current_page' => $page
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 
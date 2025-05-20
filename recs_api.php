<?php
require_once 'config.php';

header('Content-Type: application/json');

$movieId = intval($_GET['id'] ?? 0);
$offset = max(0, intval($_GET['offset'] ?? 0));

if ($movieId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid movie ID']);
    exit;
}

try {
    $stmt = $pdo->prepare('
        SELECT id, title, poster_url, release_year 
        FROM movies 
        WHERE id != ? 
        ORDER BY id 
        LIMIT 3 OFFSET ?
    ');
    $stmt->execute([$movieId, $offset]);
    $recommendations = $stmt->fetchAll();

    echo json_encode($recommendations);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 
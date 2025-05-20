<?php
require_once 'config.php';

header('Content-Type: application/json');

$query = trim($_GET['query'] ?? '');

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, title FROM movies WHERE title LIKE ? ORDER BY title LIMIT 5');
    $stmt->execute(['%' . $query . '%']);
    $results = $stmt->fetchAll();

    echo json_encode($results);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 
<?php
// movies_api.php
require 'config.php';

$perPage = 9;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

// total count
$total = $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
$pages = ceil($total / $perPage);

// fetch page
$stmt = $pdo->prepare("SELECT id, title, release_year, poster_url FROM movies ORDER BY id LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();

echo json_encode([
  'movies' => $stmt->fetchAll(PDO::FETCH_ASSOC),
  'pages'  => $pages
]);

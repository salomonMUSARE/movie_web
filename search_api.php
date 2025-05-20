<?php
// search_api.php
require 'config.php';

$q = trim($_GET['q'] ?? '');
if ($q === '') { echo '[]'; exit; }

$stmt = $pdo->prepare("SELECT id, title FROM movies WHERE title LIKE :q ORDER BY title LIMIT 5");
$stmt->execute([':q'=> "%{$q}%"]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

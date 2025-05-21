<?php
require_once 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /movie_web/cur_v1/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM movies WHERE id = ?');
$stmt->execute([$_GET['id']]);
$movie = $stmt->fetch();

if (!$movie) {
    header('Location: /movie_web/cur_v1/index.php');
    exit;
}
?>

<div class="movie-detail">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                     alt="<?= htmlspecialchars($movie['title']) ?>" 
                     class="img-fluid rounded">
            </div>
            <div class="col-md-8">
                <h1><?= htmlspecialchars($movie['title']) ?></h1>
                <div class="mb-3">
                    <span class="badge bg-primary"><?= htmlspecialchars($movie['release_year']) ?></span>
                    <span class="badge bg-secondary"><?= htmlspecialchars($movie['genre']) ?></span>
                </div>
                <p class="lead"><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="recommendations">
    <div class="container">
        <h2 class="mb-4">You might also like</h2>
        <div id="recommendationsCarousel" class="carousel slide recommendations-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Content loaded via AJAX -->
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?> 
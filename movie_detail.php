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
            <div class="carousel-controls position-relative">
                <button class="carousel-control-prev" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="prev" style="left: -50px;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="next" style="right: -50px;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.recommendations-carousel {
    position: relative;
    padding: 0 50px;
}

.carousel-inner {
    overflow: visible;
}

.carousel-item .row {
    display: flex;
    justify-content: center;
    margin: 0;
}

.carousel-item .col-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding: 0 15px;
}

.carousel-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    z-index: 5;
}

.carousel-control-prev,
.carousel-control-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.7);
    transform: scale(1.1);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 20px;
    height: 20px;
}
</style>

<?php
require_once 'includes/footer.php';
?> 
<?php
// movie_detail.php
require 'config.php';

// 1) Get current movie
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
  header('Location: index.php');
  exit;
}
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch();
if (!$movie) {
  echo "Movie not found.";
  exit;
}

// 2) Fetch recommendations
$stmt = $pdo->prepare("
  SELECT id, title, poster_url, release_year
  FROM movies
  WHERE id != ?
  ORDER BY id
");
$stmt->execute([$id]);
$recommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($movie['title']) ?></title>
  <style>
    body { font-family: sans-serif; margin:2rem; }
    .poster { float:left; margin-right:2rem; width:300px; }
    .details { overflow:hidden; }
    /* Carousel styles */
    .carousel { clear:both; margin-top:3rem; }
    .carousel h2 { margin-bottom:1rem; }
    .carousel-container {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .nav-btn {
      background: none;
      border: none;
      font-size: 2rem;
      cursor: pointer;
      padding: 0 1rem;
    }
    .carousel-cards {
      display: flex;
      gap: 1rem;
    }
    .carousel-card {
      background: #fff;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      width: 180px;
      text-align: center;
      overflow: hidden;
    }
    .carousel-card img {
      width: 100%;
      height: 120px;
      object-fit: cover;
    }
    .carousel-card p {
      margin: 0.5rem 0;
      font-size: 0.9rem;
      padding: 0 0.5rem;
    }
  </style>
</head>
<body>

  <h1><?= htmlspecialchars($movie['title']) ?></h1>
  <img class="poster" src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="">
  <div class="details">
    <p><strong>Year:</strong> <?= $movie['release_year'] ?></p>
    <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
    <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
  </div>

  <div class="carousel">
    <h2>You might also like</h2>
    <div class="carousel-container">
      <button id="prev" class="nav-btn">&larr;</button>
      <div id="recommendations" class="carousel-cards"></div>
      <button id="next" class="nav-btn">&rarr;</button>
    </div>
  </div>

  <script>
    // 3) Inject PHP recommendations into JS
    const recs = <?= json_encode($recommendations, JSON_HEX_TAG) ?>;
    let idx = 0;
    const perView = 3;

    function renderRecommendations() {
      const slice = recs.slice(idx, idx + perView);
      const container = document.getElementById('recommendations');
      container.innerHTML = slice.map(m => `
        <div class="carousel-card">
          <a href="movie_detail.php?id=${m.id}">
            <img src="${m.poster_url}" alt="${m.title}">
            <p>${m.title} (${m.release_year})</p>
          </a>
        </div>
      `).join('');
    }

    document.getElementById('prev').addEventListener('click', () => {
      if (idx >= perView) {
        idx -= perView;
        renderRecommendations();
      }
    });
    document.getElementById('next').addEventListener('click', () => {
      if (idx + perView < recs.length) {
        idx += perView;
        renderRecommendations();
      }
    });

    // 4) Initial render
    renderRecommendations();
  </script>

</body>
</html>

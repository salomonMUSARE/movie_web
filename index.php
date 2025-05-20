<?php
// index.php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie Gallery</title>
  <style>
    body { font-family: sans-serif; margin:2rem; background:#f5f5f5; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; }
    .card { background:#fff; border-radius:6px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1); text-align:center; }
    .card img { width:100%; height:300px; object-fit:cover; }
    .card h3 { margin:0.5rem 0; font-size:1.1rem; }
    #search-container { position:relative; margin-bottom:1rem; }
    #search-input { width:100%; padding:0.5rem; font-size:1rem; }
    #search-results { position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #ccc; max-height:200px; overflow-y:auto; display:none; }
    #search-results div { padding:0.5rem; cursor:pointer; }
    #search-results div:hover { background:#eee; }
    .pagination { margin:1rem 0; text-align:center; }
    .pagination span { margin:0 .25rem; cursor:pointer; }
    .pagination .active { font-weight:bold; }
  </style>
</head>
<body>

  <h1>Movie Gallery</h1>

  <div id="search-container">
    <input id="search-input" placeholder="Search movies…" autocomplete="off">
    <div id="search-results"></div>
  </div>

  <div id="movies-grid" class="grid"></div>

  <div class="pagination" id="pager"></div>

  <script>
    let currentPage = 1, totalPages = 1;

    async function fetchPage(page, append=false) {
      const res = await fetch(`movies_api.php?page=${page}`);
      const { movies, pages } = await res.json();
      totalPages = pages;
      const grid = document.getElementById('movies-grid');
      if (!append) grid.innerHTML = '';
      movies.forEach(m => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
          <a href="movie_detail.php?id=${m.id}">
            <img src="${m.poster_url}" alt="${m.title}">
            <h3>${m.title} (${m.release_year})</h3>
          </a>`;
        grid.appendChild(card);
      });
      renderPager();
    }

    function renderPager() {
      const pc = document.getElementById('pager');
      pc.innerHTML = '';
      for (let i=1;i<=totalPages;i++) {
        const span = document.createElement('span');
        span.textContent = i;
        span.className = (i===currentPage?'active':'');
        span.onclick = ()=>{ currentPage=i; fetchPage(i); window.scrollTo(0,0); };
        pc.appendChild(span);
      }
    }

    // lazy load
    window.addEventListener('scroll', () => {
      if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 10) {
        if (currentPage < totalPages) {
          fetchPage(++currentPage, true);
        }
      }
    });

    // live search
    const input = document.getElementById('search-input');
    const results = document.getElementById('search-results');
    let timer;
    input.addEventListener('input', () => {
      clearTimeout(timer);
      const q = input.value.trim();
      if (!q) return results.style.display='none';
      timer = setTimeout(async ()=>{
        const res = await fetch(`search_api.php?q=${encodeURIComponent(q)}`);
        const arr = await res.json();
        results.innerHTML = arr.map(m=>`<div data-id="${m.id}">${m.title}</div>`).join('');
        results.style.display = arr.length? 'block':'none';
      },300);
    });
    results.addEventListener('click', e => {
      if (e.target.dataset.id) {
        window.location = `movie_detail.php?id=${e.target.dataset.id}`;
      }
    });

    // initialize
    fetchPage(1);
  </script>

</body>
</html>

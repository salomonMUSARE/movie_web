$(document).ready(function() {
    let currentPage = 1;
    let isLoading = false;
    let searchTimeout;

    // Search functionality
    $('#search-input').on('input', function() {
        const query = $(this).val().trim();
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            $('.search-results').removeClass('active').empty();
            return;
        }

        searchTimeout = setTimeout(() => {
            $.get('/movie_web/cur_v1/search_api.php', { query: query })
                .done(function(data) {
                    const results = $('.search-results');
                    results.empty();
                    
                    if (data.length > 0) {
                        data.forEach(movie => {
                            results.append(`
                                <div class="search-item" data-id="${movie.id}">
                                    ${movie.title}
                                </div>
                            `);
                        });
                        results.addClass('active');
                    } else {
                        results.removeClass('active');
                    }
                });
        }, 300);
    });

    // Handle search result clicks
    $(document).on('click', '.search-item', function() {
        const movieId = $(this).data('id');
        window.location.href = `/movie_web/cur_v1/movie_detail.php?id=${movieId}`;
    });

    // Close search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $('.search-results').removeClass('active');
        }
    });

    // Load movies for a specific page
    function loadMovies(page) {
        if (isLoading) return;
        isLoading = true;

        $.get('/movie_web/cur_v1/movies_api.php', { page: page })
            .done(function(data) {
                const movies = data.movies;
                const totalPages = data.total_pages;
                
                if (page === 1) {
                    $('.movie-grid').empty();
                }

                movies.forEach(movie => {
                    $('.movie-grid').append(`
                        <div class="card movie-card" onclick="window.location.href='/movie_web/cur_v1/movie_detail.php?id=${movie.id}'" style="cursor: pointer;">
                            <img src="${movie.poster_url}" class="card-img-top" alt="${movie.title}">
                            <div class="card-body">
                                <h5 class="card-title">${movie.title}</h5>
                                <p class="card-text">${movie.release_year}</p>
                            </div>
                        </div>
                    `);
                });

                // Update pagination
                updatePagination(page, totalPages);
                currentPage = page;
            })
            .always(function() {
                isLoading = false;
            });
    }

    // Update pagination links
    function updatePagination(currentPage, totalPages) {
        const pagination = $('.pagination');
        pagination.empty();

        for (let i = 1; i <= totalPages; i++) {
            pagination.append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }
    }

    // Handle pagination clicks
    $(document).on('click', '.pagination .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadMovies(page);
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    // Infinite scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadMovies(currentPage + 1);
        }
    });

    // Load initial movies
    if ($('.movie-grid').length) {
        loadMovies(1);
    }

    // Recommendations carousel
    if ($('.recommendations-carousel').length) {
        let currentOffset = 0;
        const movieId = new URLSearchParams(window.location.search).get('id');

        function loadRecommendations(offset) {
            // Show loading state
            $('.carousel-inner').html(`
                <div class="carousel-item active">
                    <div class="row position-relative">
                        <div class="col-4">
                            <button class="carousel-control-prev position-absolute" style="left: -30px; top: 50%; transform: translateY(-50%);" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                        </div>
                        <div class="col-4"><div class="loading-placeholder" style="height: 300px;"></div></div>
                        <div class="col-4"><div class="loading-placeholder" style="height: 300px;"></div></div>
                        <div class="col-4"><div class="loading-placeholder" style="height: 300px;"></div></div>
                        <div class="col-4">
                            <button class="carousel-control-next position-absolute" style="right: -30px; top: 50%; transform: translateY(-50%);" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            `);

            // Load recommendations
            $.get('/movie_web/cur_v1/recs_api.php', { id: movieId, offset: offset })
                .done(function(data) {
                    $('.carousel-inner').html(`
                        <div class="carousel-item active">
                            <div class="row position-relative">
                                <div class="col-4">
                                    <button class="carousel-control-prev position-absolute" style="left: -30px; top: 50%; transform: translateY(-50%);" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                </div>
                                ${data.map(movie => `
                                    <div class="col-4">
                                        <div class="card movie-card" onclick="window.location.href='/movie_web/cur_v1/movie_detail.php?id=${movie.id}'" style="cursor: pointer;">
                                            <img src="${movie.poster_url}" class="card-img-top" alt="${movie.title}">
                                            <div class="card-body">
                                                <h5 class="card-title">${movie.title}</h5>
                                                <p class="card-text">${movie.release_year}</p>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                                <div class="col-4">
                                    <button class="carousel-control-next position-absolute" style="right: -30px; top: 50%; transform: translateY(-50%);" type="button" data-bs-target="#recommendationsCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                });
        }

        // Handle carousel navigation
        $(document).on('click', '.carousel-control-prev', function(e) {
            e.preventDefault();
            currentOffset = Math.max(0, currentOffset - 3);
            loadRecommendations(currentOffset);
        });

        $(document).on('click', '.carousel-control-next', function(e) {
            e.preventDefault();
            currentOffset += 3;
            loadRecommendations(currentOffset);
        });

        // Load initial recommendations
        loadRecommendations(0);
    }
}); 
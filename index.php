<?php
require_once 'includes/header.php';
?>

<div class="search-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="position-relative">
                    <input type="text" id="search-input" class="form-control form-control-lg" 
                           placeholder="Search for movies...">
                    <div class="search-results"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="movie-grid"></div>
    <nav aria-label="Movie pagination">
        <ul class="pagination"></ul>
    </nav>
</div>

<?php
require_once 'includes/footer.php';
?> 
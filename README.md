# MovieVerse

MovieVerse is a modern PHP and MySQL website for movie enthusiasts to discover and explore films. The platform features a responsive design, real-time search, and a user-friendly interface.

## Features

- Responsive movie grid with infinite scroll
- Real-time movie search with autocomplete
- Detailed movie pages with recommendations
- Contact form with email notifications
- Modern UI with Bootstrap 5
- AJAX-powered interactions
- Mobile-friendly design

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- mod_rewrite enabled (for Apache)

## Installation

1. Clone the repository to your web server's document root:
   ```bash
   git clone https://github.com/yourusername/movieverse.git
   ```

2. Create a MySQL database named `movie_app`:
   ```sql
   CREATE DATABASE movie_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. Import the database schema:
   ```bash
   mysql -u slm -p movie_app < messages_table.sql
   ```

4. Create the movies table:
   ```sql
   CREATE TABLE movies (
       id INT AUTO_INCREMENT PRIMARY KEY,
       title VARCHAR(255) NOT NULL,
       description TEXT NOT NULL,
       release_year INT NOT NULL,
       genre VARCHAR(100) NOT NULL,
       poster_url TEXT NOT NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
   ```

5. Configure your web server to point to the project directory.

6. Ensure the following directories are writable by the web server:
   - `/images` (for movie posters)
   - `/logs` (for error logs)

## Configuration

The database connection settings are in `config.php`. Update them if needed:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'movie_app');
define('DB_USER', 'slm');
define('DB_PASS', 'Business@me1');
define('DB_CHARSET', 'utf8mb4');
```

## Directory Structure

```
movieverse/
├── config.php
├── index.php
├── about.php
├── contact.php
├── movie_detail.php
├── contact_submit.php
├── movies_api.php
├── search_api.php
├── recs_api.php
├── messages_table.sql
├── includes/
│   ├── header.php
│   └── footer.php
├── css/
│   └── style.css
├── js/
│   └── main.js
└── images/
    └── cinema-bg.jpg
```

## Usage

1. Visit the homepage to browse movies
2. Use the search bar to find specific movies
3. Click on a movie card to view details
4. Use the contact form to send messages
5. Explore the about page to learn more

## Security

- All user inputs are properly sanitized
- Database queries use prepared statements
- CSRF protection on forms
- XSS prevention with proper escaping
- Secure password handling

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Credits

- Designed by Solomon
- Built with Bootstrap 5
- Icons by Bootstrap Icons
- jQuery for AJAX functionality 
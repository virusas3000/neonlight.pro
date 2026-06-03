<?php
/**
 * WordPress Configuration File for Docker
 *
 * neonlighthk-wp
 */

/** Set the database connection constants from environment variables. */
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') ?: 'wordpress' );
define( 'DB_USER', getenv('WORDPRESS_DB_USER') ?: 'wordpress' );
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') ?: '' );
define( 'DB_HOST', getenv('WORDPRESS_DB_HOST') ?: 'db' );
define( 'DB_CHARSET', getenv('WORDPRESS_DB_CHARSET') ?: 'utf8mb4' );
define( 'DB_COLLATE', getenv('WORDPRESS_DB_COLLATE') ?: 'utf8mb4_unicode_ci' );

/** Authentication Unique Keys and Salts. */
define( 'AUTH_KEY',         'c9vK4pLm7xN2wQ8zR5tY0jB3uF6aD1eH9gJ4kM7nP2qS5vW8yZ1bC4fI7lO0rT3u' );
define( 'SECURE_AUTH_KEY',  'xH2mN5qR8tW1yZ4bC7fI0lO3rT6uX9aD2eG5hJ8kM1nP4qS7vW0yZ3bC6fI9lO2r' );
define( 'LOGGED_IN_KEY',    'aD5eG8hJ1kM4nP7qS0vW3yZ6bC9fI2lO5rT8uX1aD4eG7hJ0kM3nP6qS9vW2yZ5b' );
define( 'NONCE_KEY',        'eG1hJ4kM7nP0qS3vW6yZ9bC2fI5lO8rT1uX4aD7eG0hJ3kM6nP9qS2vW5yZ8bC1f' );
define( 'AUTH_SALT',        'jK3mN6pQ9sV2wY5zB8cE1gH4iL7kN0oR3tU6wX9yZ2bC5dF8gI1jL4mO7pQ0sT3v' );
define( 'SECURE_AUTH_SALT', 'mN5pQ8sV1wY4zB7cE0gH3iL6kN9oR2tU5wX8yZ1bC4dF7gI0jL3mO6pQ9sV2wY5z' );
define( 'LOGGED_IN_SALT',   'pQ7sV0wY3zB6cE9gH2iL5kN8oR1tU4wX7yZ0bC3dF6gI9jL2mO5pQ8sV1wY4zB7c' );
define( 'NONCE_SALT',       'sV9wY2zB5cE8gH1iL4kN7oR0tU3wX6yZ9bC2dF5gI8jL1mO4pQ7sV0wY3zB6cE9g' );

/** WordPress Database Table prefix. */
$table_prefix = 'nlwp_';

/** Redis Object Cache configuration. */
define( 'WP_REDIS_HOST', getenv('WP_REDIS_HOST') ?: 'redis' );
define( 'WP_REDIS_PORT', getenv('WP_REDIS_PORT') ?: 6379 );
define( 'WP_REDIS_PREFIX', getenv('WP_REDIS_PREFIX') ?: 'nlwp' );
define( 'WP_REDIS_DATABASE', getenv('WP_REDIS_DATABASE') ?: 0 );
define( 'WP_REDIS_TIMEOUT', getenv('WP_REDIS_TIMEOUT') ?: 1 );
define( 'WP_REDIS_READ_TIMEOUT', getenv('WP_REDIS_READ_TIMEOUT') ?: 1 );
define( 'WP_REDIS_DISABLE_METRICS', true );

/** Memory limit. */
define( 'WP_MEMORY_LIMIT', '512M' );

/** Disable file editing from the admin dashboard. */
define( 'DISALLOW_FILE_EDIT', true );

/** Force direct filesystem method (no FTP prompts). */
define( 'FS_METHOD', 'direct' );

/** Multisite is disabled. */
define( 'WP_ALLOW_MULTISITE', false );

/**#@+
 * Developer-friendly debugging settings.
 */
define( 'WP_DEBUG', getenv('WP_DEBUG') === 'true' );
define( 'WP_DEBUG_LOG', getenv('WP_DEBUG_LOG') === 'true' );
define( 'WP_DEBUG_DISPLAY', getenv('WP_DEBUG_DISPLAY') === 'true' );

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

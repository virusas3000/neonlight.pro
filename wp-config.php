<?php
/**
 * WordPress Configuration File
 *
 * @package NeonLightHK
 */

// Database settings
define( 'DB_NAME',     'neonlighthk' );
define( 'DB_USER',     'wpuser' );
define( 'DB_PASSWORD', 'wppass' );
define( 'DB_HOST',     'localhost' );
define( 'DB_CHARSET',  'utf8mb4' );
define( 'DB_COLLATE',  '' );

// Salts — regenerate these at https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'qVw8xYz1aBc2DeF3gHiJ4kLmN5oPqR6sTuV7wXyZ');
define('SECURE_AUTH_KEY',  'aBc3DeF4gHiJ5kLmN6oPqR7sTuV8wXyZ9qVw1xYz2');
define('LOGGED_IN_KEY',    'DeF5gHiJ6kLmN7oPqR8sTuV9wXyZ0qVw1xYz2aBc3');
define('NONCE_KEY',        'gHiJ7kLmN8oPqR9sTuV0wXyZ1qVw2xYz3aBc4DeF5');
define('AUTH_SALT',        'kLmN9oPqR0sTuV1wXyZ2qVw3xYz4aBc5DeF6gHiJ7');
define('SECURE_AUTH_SALT', 'oPqR1sTuV2wXyZ3qVw4xYz5aBc6DeF7gHiJ8kLmN9');
define('LOGGED_IN_SALT',   'sTuV3wXyZ4qVw5xYz6aBc7DeF8gHiJ9kLmN0oPqR1');
define('NONCE_SALT',       'wXyZ5qVw6xYz7aBc8DeF9gHiJ0kLmN1oPqR2sTuV3');

// Table prefix
$table_prefix = 'nlwp_';

// Debug
define( 'WP_DEBUG', false );

// Performance
define('WP_MEMORY_LIMIT', '512M');
define('FS_METHOD', 'direct');

// Security
define('DISALLOW_FILE_EDIT', true);

// Multisite disabled
define('WP_ALLOW_MULTISITE', false);

// Stop editing
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';

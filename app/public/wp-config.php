<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'vyVlrBlFkudJM429aVvcwmUWWFBBVPCHAhsV4feOKgqgXNrxatRvqsVIIBVDJOKvw0Ckojpsl4QM6oXXnzJcBA==');
define('SECURE_AUTH_KEY',  'RkXU0pG1KmrKA5qrgXF3T96naOodw78opUPkJxJu6YEZqqpRfnn8DS40XniNukFEuathtda6Vhy8ahTY7bmbqw==');
define('LOGGED_IN_KEY',    'O5HyTMx8ZXqgx9Rrn96O+8btfJK+henysvg+mNUqzcM2Hh3w1VrlFnuKd43VODhGq8yhuc8Ff/PzrkjU0JqAdQ==');
define('NONCE_KEY',        '+N8uyS3FBEFbqsP7YW8jq45dB+CCIK4w/o4f1sTNOk7rpSG2EGUw8guzFHqQ4ULn7YR8pxp4u87elH3e0vCubg==');
define('AUTH_SALT',        'tzwwWUjxMLw6PEPg1M5SSAOucUbmi9ggjWMM5XxVjI5LjXu5eXR/BckWYKn5b8vY/4DOtqXXUBzLfNOaWKSjUw==');
define('SECURE_AUTH_SALT', 'XqU3B/3EWgA17aYl0m7SzQmH6r7yZ89SGSmZ3s8SHAVrmUbaq9vzAl7zQlVTPL/+dA4tn1zlAeNg8h1XzNO9Jw==');
define('LOGGED_IN_SALT',   'ra/iaNMp2TrDtNXxUAe2lWO0NTMRl6+YWwzp0ijTRbAdu/cYt5ZrkI1Inj1hkTTPqRGBzNDmZttc2h3Uu4QBaA==');
define('NONCE_SALT',       '5BQZzaWC/z50ZDaz80fHfKCt5Fgm0bSqOTDYdnqEx/h/2JCNNmlgvX5T2VpT1olAJfdXdJ2aspgyjbt9MDIXrQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';





/* Inserted by Local by Flywheel. See: http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy */
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
	$_SERVER['HTTPS'] = 'on';
}

/* Inserted by Local by Flywheel. Fixes $is_nginx global for rewrites. */
if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) && strpos( $_SERVER['SERVER_SOFTWARE'], 'Flywheel/' ) !== false ) {
	$_SERVER['SERVER_SOFTWARE'] = 'nginx/1.10.1';
}
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

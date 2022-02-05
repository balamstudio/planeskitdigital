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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

define('FORCE_SSL_ADMIN', true);
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
    $_SERVER['HTTPS'] = 'on';
}

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'shop_builder');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'rootme');

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ';7o[u] ]^G#[B#E]BIED}k(F>R2vkp[#vJ,G=*{C*DqVb7T!krP.*~k?>uHY8)~*' );
define( 'SECURE_AUTH_KEY',  'h8:9rvn*6L|hwQL1 $+-,`-S.Fr{-KW;5hRscW xHyHI9v1kpoeIu5xI0jDS&2,F' );
define( 'LOGGED_IN_KEY',    '+<o*+c3Mt%RJG ($zV!n_zKb:1g-RWGmEsO|zhqcslYxN+Q)ccE%$YT (_FdvaN!' );
define( 'NONCE_KEY',        'H)XEOnT]q*s9A;aai6r9}&FiaChhU-kGQx=gF43I)cGcj6^ X29ewuccT!EV7jrp' );
define( 'AUTH_SALT',        '5Z-!oY~JGnDR,Aly@g@lM*G!;k?W/.yf/8mC~!H`-1r.r|]&de~-hJL,JzwaB$<f' );
define( 'SECURE_AUTH_SALT', 'ylju;v};cx7K<A5(4LUi&hLj^X+z.dV>Bxbo4[+e;+4}$2tas`%K=G;SNiz(i4(H' );
define( 'LOGGED_IN_SALT',   '{`q=f1(bPh+8Ef).ni $~}K-IAB] }@M~rs5py&d!*?BCQKkBNNng+S&%*lb_j Z' );
define( 'NONCE_SALT',       '@lXl3mD2LI:RmN@MnS.OAjSjZiRi>v4)qCV1,UmtT9^FDEpA%%i?V[5xRnwDGLme' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'km_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/* ADDED BY ZONAIT */
ini_set('display_errors','Off');
ini_set('error_reporting', 'E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING' );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define('WP_MEMORY_LIMIT', '1024M' );
define('WP_CACHE', true);

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

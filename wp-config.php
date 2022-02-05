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
define( 'DB_NAME', 'konibitmientrete_site' );

/** MySQL database username */
define( 'DB_USER', 'konibitmientrete_user' );

/** MySQL database password */
define( 'DB_PASSWORD', '2u7pUbykVkEfwDkWs8' );

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
define('AUTH_KEY',         'AO-m9(K$7@L^n.Mh+lGkx[-WfCyF!udXC#(p/kC3>Ag{4tRUWiV(fsq-Y +P-)ul');
define('SECURE_AUTH_KEY',  '3v7G&V_}{H$~yRaw%&O}CF7PTCjUHu]h%gz)$S<V&V-!lbHz.$4q:X9deYtC`teu');
define('LOGGED_IN_KEY',    'A7%yW/gM)+9?SY/>DaX[?~t#-5t_wE~rEUm!./l[*p4B}3Y1|a~%AVApHOoIqqH6');
define('NONCE_KEY',        'PL^1GfPJ=I&y-Zs[b9m~}p;l6DRF|F0_|#lPj^~)+0_ad7J+Urv5zN<7-,4>3oCQ');
define('AUTH_SALT',        ' zo3cJJG)2TrC5L9A|/u2Oq>vTWNXV_7V2-lu@[W,-Y?}5%G>A5ZH0o=Pvo;NCVW');
define('SECURE_AUTH_SALT', '3@g2Z|-b+6Eb-W.%ISnsflMSn:2P)mbdsH3IPL`|T0Lg2+(lv?>ibN84=[++sM!X');
define('LOGGED_IN_SALT',   '1!%eh7ja$zsi+,N/R~3ZfjJH$sIBJYX$IW&7 K=q-q[W##,?bHnmk&Ja/bg6-(vz');
define('NONCE_SALT',       '>-5csyKSi*Yx1{a>r0D3|j!DW4hdLH6(kv0+jwB4d-F~[>FVOi7twm(}E6m-D~o)');

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
// define( 'WP_DEBUG', true );

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

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

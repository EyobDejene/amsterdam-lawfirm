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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'md378528db522372');

/** MySQL database username */
define('DB_USER', 'md378528db522372');

/** MySQL database password */
define('DB_PASSWORD', '3oOwbtxbFiA6C9W5');

/** MySQL hostname */
define('DB_HOST', 'db-2b.mijndomein-ws.nl');

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY', 'H%D9xViz3Q *m~.uo,&LOF$Z~1Oz#-Gy2@}rG=[}~)5MoK9aUU:wB9n:c_fXs#-)');
define('SECURE_AUTH_KEY', 'uyqpWn|u:vUI>O>EE;I]z/%!`o2#iW.6|y5M@|<Hws0>y*lG#FQBjt3+@A+CXqZh');
define('LOGGED_IN_KEY', ',3 d+Xmx&o&o(1HT8A+9Fjw{=Txg 0o$Qt*Hx;|l${=(-[Yvf eAFkzB3itxt8^i');
define('NONCE_KEY', 'gvFN=%l+mbc_(B-&7Q[S,]Z(^lcN#G,UE|PP*&DkaW];k,J:mb4IZ:;*(BB9DtyU');
define('AUTH_SALT', '~0+XcmH>:1T>wWs8-MtOEybjbLJ n#9/EY*HE}HF[P{lH!7` II}v|v-.ZMEkTld');
define('SECURE_AUTH_SALT', 'h32jILSp=&;~JZ5FqKe^%2pIp+JJObcw?Bq&K4q8mL[LQW`)^Y_a4!fX8p_ys|yT');
define('LOGGED_IN_SALT', 'N3iu|CuIvfFJnU#~~J^tQ|S(ylvsx-+|r^e>GiNt_5utpbaD|]+1!15_KZtA+5H7');
define('NONCE_SALT', 'GF@uNUa>b-tXs@3&We1[|owh|Ork D!-l|/[YZl:fY`1t-/r>+hq3?iJ_=Gb*oqm');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

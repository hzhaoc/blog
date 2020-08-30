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
define( 'DB_NAME', 'blog_db' );

/** MySQL database username */
define( 'DB_USER', 'gatechUser' );

/** MySQL database password */
define( 'DB_PASSWORD', 'gatech123' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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

define('AUTH_KEY',         '0p2:rasZt=%=_U|P+HCo;?0b<CIU!<N?#w8VO[?H`Us^Ewk//.Xrtd30p^Lua^4v');
define('SECURE_AUTH_KEY',  'rl~UjM,ow%;ePl,. -O}QCj,#tHy$v M}Q1v+4n6`-ktwA;WIE|_:H1FQM:iLota');
define('LOGGED_IN_KEY',    '!-/G1gEjXN.*+~.Ui6N1Mx|)T(_>|0Vmp%U>/LBdeRAK+#:$[Wf4~t({YPD3xRAz');
define('NONCE_KEY',        '9mVw_u|-XZc4w.,c1>7s_:~Z<G=U X4Y7%$Z6R|Ec-Fm~EB4g?@Qc4ft@T;XeCbD');
define('AUTH_SALT',        'XRA#N^=Es7-KL*UA&slO} |QTtg)[l-B?Tuf5HGuh>ls5(nH,1vZF|e8Ye^DqSs+');
define('SECURE_AUTH_SALT', 'P|}2z+jdr.1_U6WtS~5m%;|DS[Gb}m2C$An{LR-+K^{f(nM5ac>{Tin5Wltk,@[q');
define('LOGGED_IN_SALT',   '[mD2)TINFuJT``a%g{Acr++Q K>TyTrNHIy(&ea#aHyF.)2])4v 6hx|R8{N}=1+');
define('NONCE_SALT',       '!DC!YN %9[Al=n|:pf,PS{4:<}{$3VLcn;sm#;h_J+cv^#Gk.:ue*/;J:uOeQO #');

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

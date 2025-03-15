<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'soxo' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'd=;*A=]:2<+F%ec/!G$ac,AyXg0C|&Cm]XwPp;^WLlPU;vj9u4](g;wY>F`_xR&8' );
define( 'SECURE_AUTH_KEY',  'Dq^JI6hnB+MNn s!,i:,ZTOu w~z#/[bS6YTs^w2oi*nQS>=UQot?Ie0h<o~?[!0' );
define( 'LOGGED_IN_KEY',    'Q-l,S9*Q~#KgDXgjzl-O$^eX^jS:Z_/FY8-VaI_tqQQ2d5<nSOtLjc~JfxSpk0l@' );
define( 'NONCE_KEY',        'g`uvYr)WOE3m$@~z?cJYWumNj=X4kR3Ggq{<Iap=vQCUk9e~vl#&6d);4b6fq$>L' );
define( 'AUTH_SALT',        '=GsG_$.1oY*MC3M)tRCk.6 hX.csWMIu-Hv,HK{Gu&qdf0VnQ<.B}Tl)alTYnkc3' );
define( 'SECURE_AUTH_SALT', 'Tzi|*XEb9!XcoC`(`S[8E^cJ;sqagUpD=gi9]Dy?$_Uqz.ar;jY/b)~#4(ky%JgR' );
define( 'LOGGED_IN_SALT',   'O(xST7}|]O^hW(]ZTHdQ1JGdZpn7P04t&rVI2dSkSLx&u$|!`#zFo%2O#}/ZDwYZ' );
define( 'NONCE_SALT',       '}X1>6]sWa]px1E?dK!UxB3df5Sq}0_sX}~a6-gr6L?Qk/L9hmoO|Kir!A906irfg' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

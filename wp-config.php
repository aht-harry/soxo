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
define( 'AUTH_KEY',         'pPwM6BUW9~#bay,T!f3XjvcdD.1u681lDirN!5w(989^)%o|HH]+:k1/o1{3QILA' );
define( 'SECURE_AUTH_KEY',  '4ae,IZ_uiyDgT4mgiL>k_8e]pK>*rSd._oVe<bHn&UI5Cb1l{|ZCv*}wta.ii2CK' );
define( 'LOGGED_IN_KEY',    't{gU~4$wG5Jv20m9B[#hP3/rd{%<kQ-sDV/b>Rfq9QauOz$NOp>,q^>8b8~D.aZZ' );
define( 'NONCE_KEY',        'w{/GHM,O`mp4ZR}Xolu-N)9Tyi6@H6YCKhs0mJ[kH!lA_@t#/:4lmC&48R[zkv8^' );
define( 'AUTH_SALT',        '$#y~*r5 nM =9B>9OO}[4QvC,*-R4nZJ6=V-<d=rIuR4dgNrX`OfHk.T2R$K%wnO' );
define( 'SECURE_AUTH_SALT', 'o]L@m=cF6;~C[??bPmG>.t2GM:lDXZQ1dR ZY)=obg)ws@9%8)=yw&Y&Qt`.JQlj' );
define( 'LOGGED_IN_SALT',   'Dj} lC(l4AudpJIy,8MAkhjy_U&f>wg/y/}m@$CWF;F4kB- -E.vMwL:}|=4K0[G' );
define( 'NONCE_SALT',       '6qUdFdU(BS_8`G_UjdPvymW1>hlV>j0zi9Z  ]t7/Ck20Q#[d%_6zon=sqmJ=o$q' );

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

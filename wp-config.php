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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_bestworldvision' );

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
define( 'AUTH_KEY',         'lMj(L::AOU884I/WTOn_1myXf^]pA$YE$$<m`<fkJL:Fq>j7}JOW`d6 }dp2gt1v' );
define( 'SECURE_AUTH_KEY',  'jVfr&TvwLEw?/Rx+Vp+<)-~<+Tq/?YUBiQn|tA;Tuds4&n!OJRVgN?b2S p ;A!5' );
define( 'LOGGED_IN_KEY',    ',`&d>[t=)siEvt0DUB;o^[1fzaYwAS~;<e&<p#EU<J?|!FCQ89ks4Dkg^R1M_5K7' );
define( 'NONCE_KEY',        'oQ21LMY w:7WP$gi%ig]8z?tbC4cMgB.@]AO;*NCyjKKC8`*4E?i]aEVsmf{|6?Q' );
define( 'AUTH_SALT',        '|OgOve/A7G^urHr6 GE2#DA*,+/o/20R!#$C|QH]gLho,I-|#jcjKmd2Tv1Xa3*h' );
define( 'SECURE_AUTH_SALT', 'Ep~quL-H=bQLXBVmds10f5($jpLH4uV?yv@-Icvz ZTE1cY:Iq FdM97h<1hc]VA' );
define( 'LOGGED_IN_SALT',   '9kAfNdqcZt#sn+f%j*4IQJ#gAKspQHRP&`EbT<I$:bDHs(8asb;N+%i4d]sG{fjK' );
define( 'NONCE_SALT',       '(Lc~M{#q2LqWgals}FO@YV~vp4Py}dO;6b^#f_Ku..+f$?Y2!I:HelKioz5uHvOL' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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

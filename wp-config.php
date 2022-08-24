<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_lms' );

/** Database username */
define( 'DB_USER', 'db_user' );

/** Database password */
define( 'DB_PASSWORD', '123' );

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
define( 'AUTH_KEY',         'WMM_?w_ZsL%.|CG.0EY=<C*c;G/$J/qmH(.Vaqnb509g#: pPNWN+IAlE@FW?:df' );
define( 'SECURE_AUTH_KEY',  'Dv9a!Q8V A1Ei($5/jr0n9zgbD.-4) $yEWLT-zRAXPpsB~!+(8a2n2ePEEB+5;d' );
define( 'LOGGED_IN_KEY',    '7.&8@W8)8R(uVk@qb%}*mS,WIH&FyO`/y:ws_{-R~>2`w@@|q=Y|!)O4l]u`a!Ug' );
define( 'NONCE_KEY',        'mb>DU6vv)nbsOa=cuEbPmP+<L+|}{WHp)F0Q>U;H`E4V4JcssW;?acpc=P.@pI5W' );
define( 'AUTH_SALT',        ')iyEiM>]wD?G|L=U_1moq9!oR%O(9rF mW*Wwsn(n;:wuTcu(~4}c|D0N^NZ6miz' );
define( 'SECURE_AUTH_SALT', 'gnN/ntQTxfL_?5&$F8!xTpOZ7QS})PyYzOa4;mEBG[cK9]&L>cy?%MVcR s&/qT?' );
define( 'LOGGED_IN_SALT',   '~5*T>fuHVTUWupPX9~IU||UfF{Y8NLmA<)dYQhEz$eC&ZsXFc(Lj4^Y0e!/c+yvw' );
define( 'NONCE_SALT',       'hS&I[ #no}drrT+D.p_N@t_0xAo:JSGe4m0N 0q>+Mu|g$(3*xOdj)?R;B$|K3FW' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

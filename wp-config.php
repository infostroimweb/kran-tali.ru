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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'krantali');

/** MySQL database username */
define('DB_USER', 'krantali');

/** MySQL database password */
define('DB_PASSWORD', '2ITXKjJPRz');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'oeUduNYmq2jfZppEfCrPPG1V3P3Ke*aj#t7PJZV)Ky*IlT0zyz*QZf@X(%S43j7F');
define('SECURE_AUTH_KEY',  'eGit7OZJTClkZIgaxY^3@zMIfNLJ3zHE7To@!4&V1QGNT3^soeS!m1ha%o1Yl7V(');
define('LOGGED_IN_KEY',    'FaUdIrN4RWkW8XP^qivH!t2eVW7d#i8AW21A#TnC6jg8MZnoMrgz@PRhPlg@LmIQ');
define('NONCE_KEY',        'uw*Ek2qWTaNWAgyPOYPvZ1vLisACxMkKmkLtxzc#w%fX3%iu7znC9prN^Sfzk46V');
define('AUTH_SALT',        'vh9csB&0&weK8abmpr0iO(x^6oKi7kl#(4%ygojqwZC^qvn*VtAu@iasL#^B*HCL');
define('SECURE_AUTH_SALT', '5^a#mCiS3Wb%lH2ybvm8OWDMRG4I!ngPj5E2Z)I!aT(SfV&d@v@KI4tPR4&G^tsb');
define('LOGGED_IN_SALT',   'Nu)6%qI%(hgnrXqTP)jDzyrZydVtunXu&1)##UyxdPhgAaQ(MWn)GdzDRNb47nE(');
define('NONCE_SALT',       '8p6O)VYbHUGAAjsictLuQTYP0R!7J!FLV6&(1SvYYPUzpe#JTP0TCv*L8^W!1PJe');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'cms_wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

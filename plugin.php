<?php
/**
 * WordPress Reverse Proxy
 *
 * @package   wp-reverse-proxy
 * @author    Ivan Kristianto <ivan@ivankristianto.com>
 * @copyright 2023-2024 Ivan Kristianto
 * @license   GPL v2 or later
 *
 * Plugin Name:  WordPress Reverse Proxy
 * Description:  A WordPress plugin to create a reverse proxy for any URL.
 * Version:      0.1.0
 * Author:       Ivan Kristianto
 * Author URI:   https://github.com/ivankristianto/
 * Plugin URI:   https://github.com/ivankristianto/wp-reverse-proxy/
 * Text Domain:  wp-reverse-proxy
 * Requires PHP: 8.1
 * Tested up to: 6.5
 * License:      GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

declare( strict_types=1 );

namespace WPReverseProxy;

/**
 * Shortcut constant to the path of this file.
 */
const BASE_DIR  = __DIR__;
const BASE_FILE = __FILE__;

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/inc/namespace.php';

// Kickstart the plugin.
bootstrap();

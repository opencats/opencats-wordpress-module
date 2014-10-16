<?php

/**
 * Plugin name: WP OpenCats
 * Plugin URI: https://github.com/UltraSimplified/OpenCATS/wordpress-plugin/wp-opencats
 * Description: Integrate OpenCATS job handling into WordPress
 * Version: 0.1
 * Author: Robin Layfield
 * Author URI: http://ultrasimplified.com
 */

define( 'OCATS_URL',     plugin_dir_url( __FILE__ )  );
define( 'OCATS_PATH',    plugin_dir_path( __FILE__ ) );
define( 'OCATS_VERSION', '0.1'                       );

function ocats_init() {}
add_action( 'plugins_loaded', 'ocats_init' );
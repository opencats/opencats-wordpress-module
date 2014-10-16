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

function opencats_init() {
	add_action( 'wp_enqueue_scripts', 'opencats_enqueue_scripts' );
}
add_action( 'plugins_loaded', 'opencats_init' );

function ocats_enqueue_scripts() {
    wp_enqueue_style( 'opencats', OCATS_URL . '/assets/css/opencats.css', array(), OCATS_VERSION );
}

function ocats_admin_settings(){
	add_options_page( 'OpenCATS', 'OpenCATS', 'manage_options', 'opencats', 'opencats_plugin_options' );
}
add_action( 'admin_menu', 'ocats_admin_settings' );

function opencats_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}
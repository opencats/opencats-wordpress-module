<?php

/**
 * Plugin name: WP OpenCats
 * Plugin URI: https://github.com/UltraSimplified/OpenCATS
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
    // variables for the field and option names 
    $ocats_db_host = 'ocats_db_host';
    $ocats_db_port = 'ocats_db_port';
    $ocats_db_name = 'ocats_db_name';
    $ocats_db_username = 'ocats_db_username';
    $ocats_db_password = 'ocats_db_password';
    
    $hidden_field_name = 'ocats_submit_hidden';

    // Read in existing option value from database
    $db_host = get_option( $ocats_db_host );
    $db_port = get_option( $ocats_db_port );
    $db_name = get_option( $ocats_db_name );
    $db_username = get_option( $ocats_db_username );
    $db_password = get_option( $ocats_db_password );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        
        // Read their posted value
        $db_host = $_POST[ $ocats_db_host ];
        $db_port = $_POST[ $ocats_db_port ];
        $db_name = $_POST[ $ocats_db_name ];
        $db_username = $_POST[ $ocats_db_username ];
        $db_password = $_POST[ $ocats_db_password ];

        // Save the posted value in the database
        update_option( $ocats_db_host, $db_host );
        update_option( $ocats_db_port, $db_port );
        update_option( $ocats_db_name, $db_name );
        update_option( $ocats_db_username, $db_username );
        update_option( $ocats_db_password, $db_password );

				// check database connection
				if( $link = mysqli_connect($db_host, $db_username, $db_password, $db_name, $db_port ) ): ?>
				<div class="updated"><p><strong><?php _e('successfully connected to OpenCATS database.', 'ocats' ); ?></strong></p></div>
<?php		else: ?>
<div class="error"><p><strong><?php _e('cannot connect to database.', 'ocats' ); ?></strong></p></div>
<?php		endif;
        // Put an settings updated message on the screen
?>
<div class="updated"><p><strong><?php _e('settings saved.', 'ocats' ); ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'OpenCATS Settings', 'opencats' ) . "</h2>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<?php echo "<h3>" . __( 'Database settings', 'opencats' ) . "</h3>"; ?>
<p><?php _e("host:", 'opencats' ); ?> 
<input type="text" name="<?php echo $ocats_db_host; ?>" value="<?php echo $db_host; ?>" size="20">
</p>
<p><?php _e("port:", 'opencats' ); ?> 
<input type="text" name="<?php echo $ocats_db_port; ?>" value="<?php echo $db_port; ?>" size="20">
</p>
<p><?php _e("database name:", 'opencats' ); ?> 
<input type="text" name="<?php echo $ocats_db_name; ?>" value="<?php echo $db_name; ?>" size="20">
</p>
<p><?php _e("username:", 'opencats' ); ?> 
<input type="text" name="<?php echo $ocats_db_username; ?>" value="<?php echo $db_username; ?>" size="20">
</p>
<p><?php _e("password:", 'opencats' ); ?> 
<input type="text" name="<?php echo $ocats_db_password; ?>" value="<?php echo $db_password; ?>" size="20">
</p><hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
 
}

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function opencats_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'ocats',         // Widget slug.
                 'OpenCATS',         // Title.
                 'opencats_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'opencats_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function opencats_dashboard_widget_function() {

	// Display whatever it is you want to show.
	echo "Hello World, I'm a great Dashboard Widget";
}
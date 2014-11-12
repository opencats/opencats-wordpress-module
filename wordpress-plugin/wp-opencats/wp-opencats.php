<?php

/**
 * Plugin name: WP OpenCats
 * Plugin URI: https://github.com/UltraSimplified/OpenCATS
 * Description: Integrate OpenCATS job handling into WordPress
 * Version: 0.8
 * Author: Robin Layfield
 * Author URI: http://ultrasimplified.com
 */

define( 'OCATS_URL',     plugin_dir_url( __FILE__ )  );
define( 'OCATS_PATH',    plugin_dir_path( __FILE__ ) );
define( 'OCATS_VERSION', '0.7'                       );

function opencats_init() {
	add_action( 'wp_enqueue_scripts', 'opencats_enqueue_scripts' );
}
add_action( 'plugins_loaded', 'opencats_init' );

function opencats_enqueue_scripts() {
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

  // Read their value
  $db_host = get_option( 'ocats_db_host' );
  $db_port = get_option( 'ocats_db_port' );
  $db_name = get_option( 'ocats_db_name' );
  $db_username = get_option( 'ocats_db_username' );
  $db_password = get_option( 'ocats_db_password' );
	
	// connect to database
	if( $link = mysqli_connect( $db_host, $db_username, $db_password, $db_name ) ):
		$result = mysqli_query( $link, "SELECT * FROM `joborder` WHERE `status` = 'active' ORDER BY `date_created` DESC");
	
		// display count of jobs
		$num_jobs = mysqli_num_rows( $result );
		echo ( $num_jobs == 1 ) ? "<p>There is <strong>1</strong> upcoming job.</p>" : "<p>There are <strong>{$num_jobs}</strong> upcoming jobs.</p>";
		// get list of upcoming jobs
		while( $row = $result->fetch_assoc() ):
			echo '<p>' . $row['title'] . '</p>';
		endwhile;
	else:
		echo '<p>Failed to connect to OpenCATS database</p>';
	endif;
}

/*function add_jobs_to_menu( $items, $args ){

	//change theme location with your them location name

	if( $args->theme_location != 'Main' )
		return $items; 

		$link = '<li id="log-in-out-link" class="menu-item menu-type-link"><a href="/profile/" title="' .  __( 'Jobs' ) .'">' . __( 'Jobs' ) . '</a></li>';

	return $items .= $link;
}
add_filter( 'wp_nav_menu_items', 'add_jobs_to_menu', 50, 2 ); */

function opencats_add_query_vars_filter( $vars ){
  $vars[] = "job_id";
  return $vars;
}
add_filter( 'query_vars', 'opencats_add_query_vars_filter' );

function opencats_job_listing() {

  // Read their value
  $db_host = get_option( 'ocats_db_host' );
  $db_port = get_option( 'ocats_db_port' );
  $db_name = get_option( 'ocats_db_name' );
  $db_username = get_option( 'ocats_db_username' );
  $db_password = get_option( 'ocats_db_password' );
	$output = '';
	
	// connect to database
	if( $link = mysqli_connect( $db_host, $db_username, $db_password, $db_name ) ):
  $result = mysqli_query( $link, "SELECT * FROM `joborder` WHERE `status` = 'active' ORDER BY `date_created` DESC");
	
		// display count of jobs
		$num_jobs = mysqli_num_rows( $result );
		$output .= ( $num_jobs == 1 ) ? "<p>There is <strong>1</strong> upcoming job.</p>" : "<p>There are <strong>{$num_jobs}</strong> upcoming jobs.</p>";
		// get list of upcoming jobs
		while( $row = $result->fetch_assoc() ):
			$output .= '<h3>' . $row['title'] . '</h3>';
			$output .= '<h4>' . $row['city'] . '</h4>';
			$output .= '<strong>Salary:</strong> ' . $row['salary'] . '<br/>';
			$output .= '<strong>Start date:</strong> ' . mysql2date( 'd M Y', $row['start_date'] ) . '<br/>';
			$output .= '<a href="/job/?job_id=' . $row['joborder_id'] . '">Read more &rarr;</a>';
			$output .= '<hr/>';
		endwhile;
	else:
		$output .= '<p>Failed to connect to OpenCATS database</p>';
	endif;
	
	return $output;
}

// Creating the widget
class opencats_widget extends WP_Widget {
 
  function __construct() {
  parent::__construct(
  // Base ID of your widget
  'opencats_widget',
 
  // Widget name will appear in UI
  __('Latest Jobs Widget', 'opencats_widget_domain'),
 
  // Widget description
  array( 'description' => __( 'Random latest jobs listing', 'opencats_widget_domain' ), )  
  );
}
 
// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
  $title = apply_filters( 'widget_title', $instance['title'] );
  $count = apply_filters( 'widget_count', $instance['count'] );
  // before and after widget arguments are defined by themes
  echo $args['before_widget'];
  if ( ! empty( $title ) )
    echo $args['before_title'] . $title . $args['after_title'];
 
  // Read their value
  $db_host = get_option( 'ocats_db_host' );
  $db_port = get_option( 'ocats_db_port' );
  $db_name = get_option( 'ocats_db_name' );
  $db_username = get_option( 'ocats_db_username' );
  $db_password = get_option( 'ocats_db_password' );
	
	// connect to database
	if( $link = mysqli_connect( $db_host, $db_username, $db_password, $db_name ) ):
		$result = mysqli_query( $link, "SELECT * FROM `joborder` WHERE `status` = 'active' ORDER BY RAND() LIMIT $count");
	
		// display count of jobs
		$num_jobs = mysqli_num_rows( $result );
		echo ( $num_jobs == 1 ) ? "<p>There is <strong>1</strong> upcoming job.</p>" : "<p>There are <strong>{$num_jobs}</strong> upcoming jobs.</p>";
		// get list of upcoming jobs
		while( $row = $result->fetch_assoc() ):
			echo '<h3>' . $row['title'] . '</h3>';
			echo '<h4>' . $row['city'] . '</h4>';
			echo '<strong>Salary:</strong> ' . $row['salary'] . '<br/>';
			echo '<strong>Start date:</strong> ' . mysql2date( 'd M Y', $row['start_date'] ) . '<br/>';
			echo '<a href="/job/?job_id=' . $row['joborder_id'] . '">Read more &rarr;</a>';
			echo '<hr/>';
		endwhile;
	else:
		echo '<p>Failed to connect to OpenCATS database</p>';
	endif;

    echo $args['after_widget'];
  }

// Widget Backend
public function form( $instance ) {
  if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
  } else {
    $title = __( 'New title', 'opencats_widget_domain' );
  }

  if ( isset( $instance[ 'count' ] ) ) {
    $count = $instance[ 'count' ];
  } else {
    $count = 5;
  }

  // Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of items:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
</p>
    <?php
  }

  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags($new_instance['title'] ) : '';
    $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags($new_instance['count'] ) : '';

    return $instance;
  }
} // Class opencats_widget ends here

// Register and load the widget
function opencats_load_widget() {
    register_widget( 'opencats_widget' );
}
add_action( 'widgets_init', 'opencats_load_widget' );

function opencats_jobs( $atts ) {
	extract( shortcode_atts( array (
		'count' => 5
	), $atts ) );
	
  $db_host = get_option( 'ocats_db_host' );
  $db_port = get_option( 'ocats_db_port' );
  $db_name = get_option( 'ocats_db_name' );
  $db_username = get_option( 'ocats_db_username' );
  $db_password = get_option( 'ocats_db_password' );
	
	$output = '';
	
	// connect to database
	if( $link = mysqli_connect( $db_host, $db_username, $db_password, $db_name ) ):
		$result = mysqli_query( $link, "SELECT * FROM `joborder` WHERE `status` = 'active' ORDER BY RAND() LIMIT $count");
	
		// display count of jobs
		while( $row = $result->fetch_assoc() ):
			$output .= '<h5>' . $row['title'] . ' (' . $row['city'] . ')</h5>';
			$output .= ( !empty( $row['salary'] ) ) ? '<strong>Salary:</strong> ' . $row['salary'] . '<br/>' : '';
			$output .= ( !empty( $row['start_date'] ) ) ? '<strong>Start date:</strong> ' . mysql2date( 'd M Y', $row['start_date'] ) . '<br/>' : '';
			$output .= '<a href="' . get_bloginfo('wpurl') . '/job/?job_id=' . $row['joborder_id'] . '">Read more &rarr;</a>';
			$output .= '<hr/>';
		endwhile;
	else:
		$output .= '<p>Failed to connect to OpenCATS database</p>';
	endif;
	return $output;
}

add_shortcode( 'jobs', 'opencats_jobs' );

function opencats_job_details( $id ) {

  // Read their value
  $db_host = get_option( 'ocats_db_host' );
  $db_port = get_option( 'ocats_db_port' );
  $db_name = get_option( 'ocats_db_name' );
  $db_username = get_option( 'ocats_db_username' );
  $db_password = get_option( 'ocats_db_password' );
	
	// connect to database
	if( $link = mysqli_connect( $db_host, $db_username, $db_password, $db_name ) ):
		$result = mysqli_query( $link, "SELECT * FROM `joborder` WHERE `joborder_id` = '" . $id . "'");
		$row = $result->fetch_assoc();
		print_r( '<pre>');
		print_r ($row );	
		print_r( '</pre>' );
		// get list of upcoming jobs
	else:
		echo '<p>Failed to connect to OpenCATS database</p>';
	endif;
}


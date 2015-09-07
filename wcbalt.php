<?php
	
/*
Plugin Name: WordCamp Asynchronous Events
Description: Demonstrates asynchronous processing with WordPress
Version:     0.1-alpha
Author:      Aaron Brazell
Author URI:  http://technosailor.com
*/

define( 'WC_ASYNC_VERSION', '1.0' );
define( 'WC_ASYNC_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_ASYNC_PATH', dirname( __FILE__ ) . '/' );
define( 'WC_ASYNC_BASENAME', plugin_basename( __FILE__ ) );
define( 'WC_ASYNC_CLASS_DIR', WC_ASYNC_PATH . 'classes/' );

require_once( WC_ASYNC_CLASS_DIR . 'class-wc-async-tasks.php' );
require_once( WC_ASYNC_CLASS_DIR . 'class-wc-async-save-post.php' );

class WC_Async_Events {
	
	public function __construct() {
		$start_time = microtime( false );
		
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}
		
		if( defined( 'WP_CLI' ) && WP_CLI ) {
			return false;
		}
		
		//$this->run_test( 'save_post' );
		$this->run_test( 'wp_async_save_post' );
		
		$end_time = microtime( false );
		
		error_log( sprintf( 'Total Execution Time: %s seconds',  $end_time - $start_time ) );
	
	}
	
	public function run_test( $hook ) {
		add_action( $hook,function() {
			sleep( 15 );
		} );
		update_option( $hook, 'hook ran' );
	}
}

$wc_async_events = new WC_Async_Events;
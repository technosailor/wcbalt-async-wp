<?php

/**
 * Class WC_Async_Save_Post
 *
 * Extends WP_Async_Task and creates an async hook wp_async_save_post
 */
class WC_Async_Save_Post extends WP_Async_Task {

    /**
     * The hook extension that is being generated
     *
     * @var string
     */
    protected $action = 'save_post';

    /**
     * Sorts a numerical array of data passed to the hook, sanitizes and returns useful scalar values for the hook
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function prepare_data( $data ) {

        $post_id = $this->sanitize_id( $data[0] );
        $post = $data[1];
        $update = (bool) $data[2];
        $post_data = $_POST;

        if( ! $this->is_wp_post_object( $post ) ) {
            throw new Exception( __( 'Post is not a valid WP_Post Object', 'thewrap' ) );
        }

        if( !is_numeric( $post_id ) ) {
            throw new Exception( __( 'Post ID must be a number.', 'thewrap' ) );
        }

        return array( 'post_id' => $post_id, 'post' => $post, 'update' => $update, 'post_data' => $post_data );
    }

    /**
     * Task runner that receives POST data generated in self::prepare_data()
     */
    protected function run_action() {

        $post_id = $this->sanitize_id( $_POST['post_id'] );
        $post = get_post( $post_id );
		update_option( 'wp_async_save_post_args', array( 'post_id' => $post_id, 'post' => $post ) );
        do_action( 'wp_async_' . $this->action, $post_id, $post );
    }
	
	public function sanitize_id( $numeric_string ) {
        return is_string( $numeric_string ) ?  (int) preg_replace( '/\D/', '', $numeric_string ) : (int) $numeric_string;
	}
	
    public function is_wp_post_object( $object ) {
        if( 'WP_Post' !== get_class( $object ) ) {
            return false;
        }

        return true;
    }
}
new WC_Async_Save_Post;
<?php

class Comment_Form_Integration extends Mautic_For_Wordpress_Integration {
	
	public $name        = 'Comment Form';
	public $description = 'WordPress default comment form';

	public function add_hooks() {
		add_filter( 'comment_form_fields', array( $this, 'add_checkbox_field' ) );
		add_action( 'comment_post', array( $this, 'sync_commentor_data' ) );
	}

	public function sync_commentor_data( $comment_id, $comment_approved = '' ) {
		// is this a spam comment?
		if ( $comment_approved === 'spam' ) {
			return false;
		}
		$comment = get_comment( $comment_id );
		$data    = array(
			'email'     => $comment->comment_author_email,
			'firstname' => $comment->comment_author,
		);
		$this->may_be_sync_data( $data );
	}

	public function add_checkbox_field( $comment_fields ) {
		if ( ! $this->is_implicit() ) {
			$checked                              = $this->is_checkbox_precheck() ? 'checked ' : '';
			$comment_fields['mwb_m4wp_subscribe'] = '<p class="comment-form-subscribe">' .
			'<input id="mwb_m4wp_subscribe" name="mwb_m4wp_subscribe" type="checkbox" value="yes" ' . $checked . ' />' .
			'<label for="mwb_m4wp_subscribe">' . $this->get_option( 'checkbox_txt' ) . '</label></p>';
		}
		return $comment_fields;
	}

	// check dependencies for the integration here
	public function is_active() {
		return true;
	}

}

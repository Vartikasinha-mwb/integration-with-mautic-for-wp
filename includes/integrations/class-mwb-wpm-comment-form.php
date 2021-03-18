<?php
/**
 * Comment form integration.
 *
 * @link       https://makewebbetter.com
 * @since      3.0.0
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes
 */

/**
 * The Comment form integration functionality.
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Wpm_Comment_Form extends Mwb_Wpm_Integration_Base {

	/**
	 * The name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $name    Name of the integration.
	 */
	public $name =  'Comment Form' ;

	/**
	 * The description of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $name    Name of the integration.
	 */
	public $description = 'WordPress default comment form';

	/**
	 * Add hooks related to integration.
	 */
	public function add_hooks() {
		add_filter( 'comment_form_fields', array( $this, 'add_checkbox_field' ) );
		add_action( 'comment_post', array( $this, 'sync_commentor_data' ) );
	}

	/**
	 * Sync user.
	 *
	 * @param int    $comment_id Comment id.
	 * @param string $comment_approved comment status.
	 */
	public function sync_commentor_data( $comment_id, $comment_approved = '' ) {
		// is this a spam comment?
		if ( 'spam' === $comment_approved ) {
			return false;
		}
		$comment = get_comment( $comment_id );
		$data    = array(
			'email'     => $comment->comment_author_email,
			'firstname' => $comment->comment_author,
		);
		$this->may_be_sync_data( $data );
	}

	/**
	 * Add optin checkbox field.
	 *
	 * @param array $comment_fields Comment fields arary.
	 */
	public function add_checkbox_field( $comment_fields ) {
		if ( ! $this->is_implicit() ) {
			$checked                              = $this->is_checkbox_precheck() ? 'checked ' : '';
			$comment_fields['mwb_m4wp_subscribe'] = '<p class="comment-form-subscribe">' .
			'<input id="mwb_m4wp_subscribe" name="mwb_m4wp_subscribe" type="checkbox" value="yes" ' . $checked . ' />' .
			'<label for="mwb_m4wp_subscribe">' . $this->get_option( 'checkbox_txt' ) . '</label></p>';
		}
		return $comment_fields;
	}

	/**
	 * Check if integration is active.
	 *
	 * @return bool.
	 */
	public function is_active() {
		return true;
	}

}

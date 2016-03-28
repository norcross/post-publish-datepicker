<?php
/**
 * Store Last Login Date - Core Module
 *
 * Contains our core functionality
 *
 * @package Post Publish Datepicker
 */

/**
 * Start up the engine.
 */
class PPDP_Core
{

	/**
	 * Call our hooks and filters.
	 */
	public function init() {

		// Bail on non admin.
		if ( ! is_admin() ) {
			return;
		}

		// Handle our actions.
		add_action( 'admin_enqueue_scripts',            array( $this, 'load_scripts_styles'     )           );
		add_action( 'post_submitbox_misc_actions',      array( $this, 'submitbox_new_datetime'  )           );
	}

	/**
	 * Load admin side CSS and JS.
	 *
	 * @param  string $hook  The admin page hook.
	 *
	 * @return void
	 */
	public function load_scripts_styles( $hook ) {

		// Only load on the post editor.
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		// set the version based on dev or not
		$vers   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : PPDP_VER;
		$cssfx  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.css' : '.min.css';
		$jsfx   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.js' : '.min.js';

		// CSS
		wp_enqueue_style( 'ppdp-admin', plugins_url( '/css/ppdp.admin' . $cssfx, __FILE__), array(), $vers, 'all' );

		// set JS dependencies
		$deps	= array( 'jquery', 'jquery-ui-core', 'jquery-ui-tooltip', 'jquery-effects-slide' );

		// Load JS files.
		wp_enqueue_script( 'datepick', plugins_url( '/js/jquery.pickmeup' . $jsfx, __FILE__ ), $deps, '2.9.0', true );
		wp_enqueue_script( 'timepick', plugins_url( '/js/jquery.timepicker' . $jsfx, __FILE__ ), $deps, '1.8.10', true );
		wp_enqueue_script( 'ppdp-admin', plugins_url( '/js/ppdp.admin' . $jsfx, __FILE__ ), $deps, $vers, true );
	}

	/**
	 * Our new date / time box.
	 *
	 * @param WP_Post $post WP_Post object for the current post.
	 *
	 * @return void
	 */
	public function submitbox_new_datetime( $post ) {

		// Fetch my global post object.
		$postob = get_post_type_object( $post->post_type );
		$canpub = current_user_can( $postob->cap->publish_posts );

		// Bail if we don't have the cap.
		if ( ! $canpub ) {
			return;
		}

		/* translators: Publish box date format, see http://php.net/date */
		$datef = __( 'M j, Y @ H:i' );
		if ( 0 != $post->ID ) {
			if ( 'future' == $post->post_status ) { // scheduled for publishing at a future date
				$stamp = __( 'Scheduled for: <strong>%1$s</strong>' );
			} elseif ( 'publish' == $post->post_status || 'private' == $post->post_status ) { // already published
				$stamp = __( 'Published on: <strong>%1$s</strong>' );
			} elseif ( '0000-00-00 00:00:00' == $post->post_date_gmt ) { // draft, 1 or more saves, no date specified
				$stamp = __( 'Publish <strong>immediately</strong>' );
			} elseif ( time() < strtotime( $post->post_date_gmt . ' +0000' ) ) { // draft, 1 or more saves, future date specified
				$stamp = __( 'Schedule for: <strong>%1$s</strong>' );
			} else { // draft, 1 or more saves, date specified
				$stamp = __( 'Publish on: <strong>%1$s</strong>' );
			}
			$date = date_i18n( $datef, strtotime( $post->post_date ) );
		} else { // draft (no saves, and thus no date specified)
			$stamp = __( 'Publish <strong>immediately</strong>' );
			$date = date_i18n( $datef, strtotime( current_time( 'mysql' ) ) );
		}

		// Now echo out our boxes.
		echo '<div class="misc-pub-section curtime misc-pub-pickerdatetime">';

			// Handle the current published date.
			echo '<span id="timestamp"> ' . sprintf( $stamp, $date ) . '</span>';

			// Add our edit button.
			echo ' <a href="#edit_pickerstamp" class="edit-timestamp edit-pickerstamp hide-if-no-js"><span aria-hidden="true">' . __( 'Edit' ) . '</span> <span class="screen-reader-text">' . __( 'Edit date and time' ) . '</span></a>';

			echo '<fieldset id="timestampdiv" class="pickerstamp-fieldset hide-if-js">';
				echo '<legend class="screen-reader-text">' . __( 'Date and time' ) . '</legend>';

				// My hidden fields for each item
				echo self::datepicker_hidden_fields( $post );

				// My buttons.
				echo '<p>';
					echo '<a href="#edit_pickerstamp" data-action="save" class="action-pickerstamp save-pickerstamp hide-if-no-js button">' . __( 'OK' ) . '</a>';
					echo '<a href="#edit_pickerstamp" data-action="cancel" class="action-pickerstamp cancel-pickerstamp hide-if-no-js button-cancel">' . __('Cancel' ) . '</a>';
				echo '</p>';

			// Close my fieldset.
			echo '</fieldset>';

		echo '</div>';
	}

	/**
	 * Load up our hidden fields with all the various date fields.
	 *
	 * @param  [type] $post [description]
	 * @return [type]       [description]
	 */
	public static function datepicker_hidden_fields( $post ) {

		// Fetch all our field items.
		$aj = current_time( 'timestamp' );

		$jj = mysql2date( 'd', $post->post_date, false );
		$mm = mysql2date( 'm', $post->post_date, false );
		$aa = mysql2date( 'Y', $post->post_date, false );
		$hh = mysql2date( 'H', $post->post_date, false );
		$mn = mysql2date( 'i', $post->post_date, false );
		$ss = mysql2date( 's', $post->post_date, false );

		$cur_jj = gmdate( 'd', $aj );
		$cur_mm = gmdate( 'm', $aj );
		$cur_aa = gmdate( 'Y', $aj );
		$cur_hh = gmdate( 'H', $aj );
		$cur_mn = gmdate( 'i', $aj );

		$map = array(
			'mm' => array( $mm, $cur_mm ),
			'jj' => array( $jj, $cur_jj ),
			'aa' => array( $aa, $cur_aa ),
			'hh' => array( $hh, $cur_hh ),
			'mn' => array( $mn, $cur_mn ),
		);

		// Set our empty.
		$build  = '';

		foreach ( $map as $timeunit => $value ) {

			list( $unit, $curr ) = $value;

			$build .= '<input type="hidden" id="hidden_' . $timeunit . '" name="hidden_' . $timeunit . '" value="' . $unit . '" />' . "\n";
			$cur_timeunit = 'cur_' . $timeunit;
			$build .= '<input type="hidden" id="' . $cur_timeunit . '" name="' . $cur_timeunit . '" value="' . $curr . '" />' . "\n";
		}

		// Return our fields.
		return $build;
	}

} // End class

// Instantiate our class
$PPDP_Core = new PPDP_Core();
$PPDP_Core->init();



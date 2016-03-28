<?php
/*
Plugin Name: Post Publish Datepicker
Plugin URI: http://reaktivstudios.com/
Description: Swap out the default date input with a datepicker.
Author: Andrew Norcross
Version: 0.0.1
Requires at least: 4.0
Author URI: http://reaktivstudios.com/
Text Domain: post-publish-datepicker
Domain Path: /languages
GitHub Plugin URI: https://github.com/norcross/post-publish-datepicker
*/
/*  Copyright 2016 Andrew Norcross

	The MIT License (MIT)

	Copyright (c) 2016 Andrew Norcross

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

if( ! defined( 'PPDP_BASE ' ) ) {
	define( 'PPDP_BASE', plugin_basename(__FILE__) );
}

if( ! defined( 'PPDP_DIR' ) ) {
	define( 'PPDP_DIR', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'PPDP_VER' ) ) {
	define( 'PPDP_VER', '0.0.1' );
}

// lets start the engine
class Post_Publish_Datepicker_Core {

	/**
	 * Static property to hold our singleton instance
	 * @var $instance
	 */
	static $instance = false;

	/**
	 * this is our constructor.
	 * there are many like it, but this one is mine
	 */
	private function __construct() {
		add_action( 'plugins_loaded',                       array( $this, 'textdomain'                  )           );
		add_action( 'plugins_loaded',                       array( $this, 'load_files'                  )           );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return $instance
	 */
	public static function getInstance() {

		// check for self instance
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		// return the instance
		return self::$instance;
	}

	/**
	 * load our textdomain for localization
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'post-publish-datepicker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * load our files. we only want it to load
	 * on admin or on the login page
	 *
	 * @return [type] [description]
	 */
	public function load_files() {

		// load our admin piece
		if ( is_admin() ) {
			require_once( 'lib/admin.php'  );
		}
	}

/// end class
}

// Instantiate our class
$Post_Publish_Datepicker_Core = Post_Publish_Datepicker_Core::getInstance();
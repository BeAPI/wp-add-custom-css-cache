<?php
/*
 Plugin Name: WP Add Custom CSS Cache
 Version: 1.0.0
 Plugin URI: http://www.beapi.fr
 Description: Cache the WP Add Custom CSS option to a real file
 Author: BE API Technical team
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Text Domain: wp-add-custom-css-cache
 
 ----
 
 Copyright 2016 BE API Technical team (human@beapi.fr)
 
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Prevent direct file access
if( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once dirname( __FILE__ ) . '/inc/cache.php';

class WASS_Cache{
	/**
	 * The WASS_Cache_File object
	 *
	 * @var WASS_Cache_File
	 */
	private $cache_file;

	public function __construct( $blog_id ) {
		/**
		 * Setup blog and cache file
		 */
		$this->cache_file = new WASS_Cache_File( absint( $blog_id ) );

		/**
		 * On option update, refresh the cache file
		 **/
		add_action( 'update_option_wpacc_settings', array( $this, 'refresh_cache_file' ) );

		/**
		 * Remove the data from the SCSS
		 * Enqueue our own WASS file
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'register_style' ), 99999 );
	}

	/**
	 * Generate the file on activation
	 */
	public function activation() {
		$this->refresh_cache_file();
	}

	/**
	 * Delete file upon deactivation
	 */
	public function deactivation() {
		$this->cache_file->flush();
	}

	/**
	 * SCSS handle
	 */
	public function register_style() {
		/**
		 * Do not enqueue not cached files
		 */
		if ( ! $this->cache_file->is_cache_file() ) {
			return;
		}

		wp_register_style( 'wass_style', $this->cache_file->get_cache_url() );
		wp_enqueue_style( 'wass_style' );

		wp_deregister_style( 'wp-add-custom-css' );
	}

	/**
	 * Refresh the cache file based on the options from the backoffice
	 *
	 * @return bool
	 */
	public function refresh_cache_file() {
		$options     = get_option( 'wpacc_settings' );
		$raw_content = isset( $options['main_custom_style'] ) ? $options['main_custom_style'] : '';
		$content     = wp_kses( $raw_content, array( '\'', '\"' ) );
		$content     = str_replace( '&gt;', '>', $content );

		// Replace the file content
		$this->cache_file->flush();
		return $this->cache_file->set( $content );
	}
}

/**
 * Generate the class
 **/
$wass_cache = new WASS_Cache( get_current_blog_id() );

/**
 * Generate files upon activation
 * Remove files upon deactivation
 */
register_activation_hook( __FILE__, array( $wass_cache, 'activation' ) );
register_deactivation_hook( __FILE__, array( $wass_cache, 'deactivation' ) );

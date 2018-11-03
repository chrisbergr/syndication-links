<?php
/**
 * Plugin Name: Syndication Links
 * Plugin URI: http://wordpress.org/plugins/syndication-links
 * Description: Add Syndication Links to Your Content
 * Version: 4.0.1
 * Author: David Shanske
 * Author URI: http://david.shanske.com
 * Text Domain: syndication-links
 * Domain Path:  /languages
 */

define( 'SYNDICATION_LINKS_VERSION', '4.0.1' );


function syndication_links_load( $files ) {
	if ( empty( $files ) ) {
		return;
	}
	$path = plugin_dir_path( __FILE__ ) . 'includes/';
	foreach ( $files as $file ) {
		if ( file_exists( $path . $file ) ) {
			require_once $path . $file;
		}
	}
}

function syndication_links_init() {
	syndication_links_load(
		array(
			'simple-icons.php', // Icon Information
			'class-syn-meta.php', // Information on Metadata
			'class-syn-config.php', // Configuration Options
			'class-social-plugins.php', // Social Plugin Add-Ons
			'functions.php', // Global Functions
		)
	);
	if ( 1 === intval( get_option( 'syndication_posse_enable', 0 ) ) ) {
		syndication_links_load(
			array(
				'class-syndication-provider.php', // Syndication Provider Base Class
				'class-post-syndication.php', // Post syndication logic
				'class-syndication-provider-webmention.php', // Class for Any Webmention Based Service
				'class-syndication-provider-indienews.php', // Indienews Syndication Provider
				'class-syndication-provider-bridgy.php', // Bridgy Base Class
				'class-syndication-provider-bridgy-twitter.php', // Twitter via Bridgy
				'class-syndication-provider-bridgy-github.php', // Github via Bridgy
				'class-syndication-provider-bridgy-flickr.php', // Flickr via Bridgy
			)
		);
	}
	load_plugin_textdomain( 'syndication-links', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'syndication_links_init' );

function syndication_links_privacy_declaration() {
	if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
		$content = __(
			'Syndication Links, which are links to the same content on other sites, may be displayed on comments, but only if supplied by the submitter or if your comment was
			generated by webmention, if they appear on your site.',
			'syndication-links'
		);
		wp_add_privacy_policy_content(
			'Syndication Links',
			wp_kses_post( wpautop( $content, false ) )
		);
	}
}

add_action( 'admin_init', 'syndication_links_privacy_declaration' );

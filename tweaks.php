<?php
/*
 * http://wp-snippets.com/articles/7-code-snippets-you-should-use-on-every-site/
 * http://www.gonzoblog.nl/2011/11/15-more-useful-wordpress-hacks-and-code-snippets/
 * http://www.catswhocode.com/blog/10-super-useful-wordpress-shortcodes
 * http://cms.html.it/articoli/leggi/3926/twitter-nellheader-wordpress-senza-plugin/9/
 * http://www.smashingmagazine.com/2010/07/01/10-useful-wordpress-security-tweaks/
 *
 */

// remove wordpress version generator
remove_action('wp_head', 'wp_generator');

// login error
add_filter('login_errors',create_function('$a', "return null;"));

global $user_ID; 
if($user_ID) {
  if(!current_user_can('administrator')) {
    if (strlen($_SERVER['REQUEST_URI']) > 255 ||
		  strpos($_SERVER['REQUEST_URI'], "eval(") ||
		  strpos($_SERVER['REQUEST_URI'], "CONCAT") ||
		  strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
		  strpos($_SERVER['REQUEST_URI'], "base64")) {
	
	@header("HTTP/1.1 414 Request-URI Too Long");
	@header("Status: 414 Request-URI Too Long");
	@header("Connection: Close");
	@exit;
    }
  }
}


add_action('login_head', 'custom_login_logo');
function custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('template_directory').'/images/login_logo.png) !important; }
    </style>';
}

// notification and admin bar
if ( !current_user_can('administrator') ) {
    add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
    add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );

	// disable the admin bar
	add_filter('show_admin_bar', '__return_false');	
}


// disable auto ping
function disable_self_ping( &$links ) {
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);	
	}
add_action( 'pre_ping', 'disable_self_ping' );



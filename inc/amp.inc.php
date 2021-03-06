<?php

// AMP stuff

add_action( 'init', 'wpse26388_rewrites_init', 9999 );
function wpse26388_rewrites_init(){
    //echo "AMP custom rewrite rule hook\n<br>";
//	add_rewrite_rule('^(.*)\.amp/?$', 'index.php?pagename=$matches[1]&amp=1', 'top' );
////	add_rewrite_rule('^amp/?$', 'index.php?pagename=$matches[1]&page=$matches[2]&amp=1', 'top' );
////	add_rewrite_rule('^amp/(.?.+?)(?:/([0-9]+))?/?$', 'index.php?pagename=$matches[1]&page=$matches[2]&amp=1', 'top' );
//add_rewrite_rule('^amp/?$', 'index.php?pagename=$matches[1]&amp=1', 'top' );
    $home_id = get_option( 'page_on_front' );

	add_rewrite_rule('(.?.+?)(?:/([0-9]+))?/amp/?$', 'index.php?pagename=$matches[1]&page=$matches[2]&amp=1', 'top' );
	add_rewrite_rule('^amp/?$', 'index.php?page_id='.$home_id.'&amp=1', 'top' );
	//add_rewrite_rule('^amp/?$', 'index.php?pagename=&page=home&amp=1', 'top' );
	//add_rewrite_rule('^amp/?$', 'index.php?name=home&amp=1', 'top' );
}

add_filter( 'query_vars', 'wpse26388_query_vars' );
function wpse26388_query_vars( $query_vars ){
	$query_vars[] = 'amp';
    //print_r($query_vars);

    return $query_vars;
}





function makeplugins_add_json_endpoint() {
    add_rewrite_endpoint( 'bojan', EP_NONE | EP_PERMALINK );
}
//add_action( 'init', 'makeplugins_add_json_endpoint' );

function makeplugins_json_template_redirect() {
    global $wp_query;
 
    die('bbbbbbbb');
    //var_dump(is_home());
    //var_dump(is_front_page());
    // if this is not a request for json or a singular object then bail
    //echo 222222222;
   // print_r($wp_query->query_vars);
    if ( ! isset( $wp_query->query_vars['amp'] ) || ! is_singular() )
    return;
    
    //echo 333333333;
    // include custom template
    include dirname( __FILE__ ) . '/../page-amp.php';
    exit;
}
//add_action( 'template_redirect', 'makeplugins_json_template_redirect' );




/*
add_filter('pre_option_page_on_front', 'page_on_front');
function page_on_front() {
    $options = get_option('theme_options');
    echo 1111;
    return $options['page_on_front'];
}
add_filter('pre_option_page_for_posts', 'page_for_posts');
function page_for_posts() {
    $options = get_option('theme_options');
    echo 2222;
    return $options['page_for_posts'];
}
*/


/*
 * IMPORTANT
 * Disable front page redirect for AMP
 */
function disable_canonical_redirect_for_front_page( $redirect ) {
    if ( is_page() && $front_page = get_option( 'page_on_front' ) ) {
        if ( is_page( $front_page ) ) {
            $redirect = false;
        }
    }
    return $redirect;
}
add_filter( 'redirect_canonical', 'disable_canonical_redirect_for_front_page' );





add_filter( 'template_include', 'portfolio_page_template' );

function portfolio_page_template( $template ) {

    //echo "amp: " . get_query_var('amp');
   // var_dump( is_home() );
   // var_dump( is_front_page() );
    
    
    if (get_query_var('amp') && is_front_page()) {
        $new_template = locate_template( array( 'front-page-amp.php' ) );
        
       // echo 34343434;
        
      //  the_ID();
      //  die('aaaaaaa2');
        // echo "<br>";
        // echo $home_id = (int)get_option( 'page_on_front' );
        
		if ( !empty( $new_template ) ) {
            return $new_template;
		}
    }
    //die('aaaaaaa');
    // is page
    if (get_query_var('amp') && is_page()) {
        //echo "amp1: " . get_query_var('amp');
		$new_template = locate_template( array( 'page-amp.php' ) );
		if ( !empty( $new_template ) ) {
			return $new_template;
		}
    }

    /*
	if ( is_page( 'portfolio' ) ) {
		$new_template = locate_template( array( 'portfolio-page-template.php' ) );
		if ( !empty( $new_template ) ) {
			return $new_template;
		}
	}
*/

//echo "\n<br>amp: " . get_query_var('amp');
//echo "\n<br>template: " .$template;
	return $template;
}



function append_query_string($url, $post, $leavename=false ) {
    //global $wp_query, $post;
    if (get_query_var('amp')) {
        //echo "\n<br>amp22: " . get_query_var('amp');
        //echo $url;
        $url = 'amp/'.$url;
    }
    return $url;
}
//add_filter('the_permalink', 'append_query_string');
//add_filter('post_link', 'append_query_string');


function my_page_template_redirect()
{
    global $wp_query;

    //print_r($wp_query);

    /*
    if( is_page( 'goodies' ) && ! is_user_logged_in() )
    {
        wp_redirect( home_url( '/signup/' ) );
        die;
    }
    */

    //echo "amp: " . get_query_var('amp');
//echo "\n<br>template: " .$template;
}
add_action( 'template_redirect', 'my_page_template_redirect' );



function change_pricing($content) {
    if( get_query_var( 'amp' ) && is_page() && is_main_query() ) {
            
            $string = $content;
            $pattern = '/<img(.*?)>/s';
            $replacement = '<amp-img$1></amp-img>';
            
            $content = preg_replace($pattern, $replacement, $string);

    }       

    return $content;
}

//add_filter('the_content', 'change_pricing');





/**
 * Disable the emoji's
 */
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

/**
* Filter function used to remove the tinymce emoji plugin.
* 
* @param array $plugins 
* @return array Difference betwen the two arrays
*/
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

/**
* Remove emoji CDN hostname from DNS prefetching hints.
*
* @param array $urls URLs to print for resource hints.
* @param string $relation_type The relation type the URLs are printed for.
* @return array Difference betwen the two arrays.
*/
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

        $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }
   
   return $urls;
}





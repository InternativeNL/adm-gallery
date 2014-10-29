<?php
/*
Plugin Name: Admium Gallery
Plugin URI: www.admium.nl
Description: Display an overview of Wordpress galleries used on child pages.
Author: Admium
Version: 1.0
Author URI: www.admium.nl
License: GPLv2 or later
Text Domain: adm-gallery
Domain Path: /languages
GitHub Plugin URI: AdmiumNL/adm-gallery
*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/* todo
	   - pagination (currently shows all child galleries)
	   - when child pages are found but none with [gallery] the script still outputs an empty <ul></ul>
	   - when custom thumbnail is disabled, let user decide what image to use (theme may already have custom thumbnails)
	*/
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function adm_gallery_load_textdomain() {
    load_plugin_textdomain('adm-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
}
add_action( 'plugins_loaded', 'adm_gallery_load_textdomain' );

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

require_once("adm-settings.php"); // constants with default settings

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (get_option('adm_gallery_custom_thumbnail', ADM_GALLERY_CUSTOM_THUMBNAIL) == 1){
    function register_custom_image_size() {
        add_image_size( 'adm-gallery-thumb', get_option('adm_gallery_custom_thumbnail_width', ADM_GALLERY_CUSTOM_THUMBNAIL_WIDTH), get_option('adm_gallery_custom_thumbnail_height', ADM_GALLERY_CUSTOM_THUMBNAIL_HEIGHT), get_option('adm_gallery_custom_thumbnail_crop', ADM_GALLERY_CUSTOM_THUMBNAIL_CROP) ); 
    }
    add_action('init', 'register_custom_image_size');
    $adm_image_size = "adm-gallery-thumb";
} else {
    $adm_image_size = "thumbnail";
}

///////////////////////////////////////////////

if (get_option('adm_gallery_css', ADM_GALLERY_CSS) == 1){
    function register_plugin_styles() {
    	wp_register_style( 'adm-gallery', plugin_dir_url(__FILE__) . 'css/plugin.css', array(), rand(1,100000000000) );
    	wp_enqueue_style( 'adm-gallery' );
    }
    add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
}
///////////////////////////////////////////////

function adm_gallery($atts) {
	
	global $post, $adm_image_size;
	$pattern = get_shortcode_regex();
	
    extract( shortcode_atts( array (
        'post_type'             => 'any',
        'orderby'               => get_option('adm_gallery_orderby', ADM_GALLERY_ORDERBY),
        'order'                 => get_option('adm_gallery_order', ADM_GALLERY_ORDER),
        'posts_per_page'        => -1,
        'post_status'           => 'publish',
        'class'                 => 'adm_gallery',
        'image_size'            => $adm_image_size,
        'nr_columns'            => get_option('adm_gallery_columns', ADM_GALLERY_COLUMNS),
    ), $atts ) );

	$args = array(
	    "post_parent"           => $post->ID,
	    'post_type'             => $post_type,
	    'orderby'               => $orderby,
	    'order'                 => $order,
	    'posts_per_page'        => $posts_per_page,
	    'post_status'           => $post_status,
    );

	$the_query = new WP_Query( $args );
	
	ob_start();
	
    if ( $the_query->have_posts() ) {
    	echo '<ul class="' .$class. ' columns-' . $nr_columns . '">';
    	while ( $the_query->have_posts() ) {
    		$the_query->the_post();
    		
    		$content = get_the_content();
    		
            // easiest, can also use has_shortcode but I need the $matches array later on
    		if ( preg_match_all( '/'. $pattern .'/s', $content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'gallery', $matches[2] ) ) {
    		    // childpage contains a gallery shortcode
    		    
    		    $nrOfGallerys = count($matches[3]);
    		    for ($i = 0; $i < $nrOfGallerys; $i++){
    		        $atts = shortcode_parse_atts( $matches[3][$i] );
    		        if ( isset( $atts['ids'] ) ){
    		            // image ids are set, so get random image from this list
    		            $ids = explode(",", $atts['ids']);
    		            if (is_array($ids) && shuffle($ids)){
    		                $image = $ids[0];
    		                break; // found an image, no need to loop any further
    		            }
    		        } else {
        		        
        		        // no image ids in shortcode, so get random image from attached media (just like the [gallery] shortcode does)
        		        $media = get_attached_media( 'image' );
        		        if (is_array($media) && shuffle($media)){
        		            $image = $media[0]->ID;
        		            break; // found an image, no need to loop any further
        		        }
    		        }
    		    }
    		        		    
                echo '<li>';
                    echo "<h3><a href=' " . get_permalink() . " '>" . get_the_title() . "</a></h3>";
                    echo "<a href=' " . get_permalink() . " '>" . wp_get_attachment_image($image, $image_size, 0, array("alt"=>get_the_title())) . "</a>";
                echo '</li>';

            } else {
            
                // childpage contains no gallery, so do not use it
                
            }
    		
    	}
    	echo '</ul>';
    } else {
    	// no posts found
    }
    
    $output = ob_get_contents();
    
    ob_end_clean();

    /* Restore original Post Data */
    wp_reset_postdata();
    
    return $output;

}
add_shortcode('adm_gallery', 'adm_gallery');

require("adm-admin.php");
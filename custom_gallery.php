<?php
/*  
*   Wordpress Custom Gallery
*   *********************************************
*   Override default wordpress image gallery
*   with your Bootstrap ready markup 
*
*   Place this function in your theme functions.php
*/

add_filter( 'use_default_gallery_style', '__return_false' );
add_filter('post_gallery','customFormatGallery',10,2);

function customFormatGallery($string,$attr){

    $posts = get_posts(array('include' => $attr['ids'],'post_type' => 'attachment'));
    $output = '<div class="WPTheme__gallery"><div class="row">';
    $columns = (isset($attr['columns'])) ? $attr['columns'] : "3";
    
    switch ($attr['columns']) {
        case '7':
        case '8':
        case '9': 
            $columns_class = "col-md-2";   
            break;
        
        default:
            $columns_class = "col-md-" . 12/$columns;
            break;
    }
    

    foreach($posts as $imagePost){
        $thumbnail = wp_get_attachment_image_src($imagePost->ID, $attr['size']);
        $image = wp_get_attachment_image_src($imagePost->ID, 'e');
        $title = $imagePost->post_excerpt;
        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        $alt = ($alt != "") ? $alt : $title;
        $output .= '<a href="' . $image[0] . '" title="' . $title . '" class="WPTheme__gallery-item ' . $columns_class . '">';
        $output .= '    <img class="WPTheme__gallery-item-image" src="' . $thumbnail[0] . '" alt="' . $alt . '" title="' . $title . '">';
        $output .= '    <span class="class="WPTheme__gallery-item-over"><span>' . $title . '</span></span>';
        $output .= '</a>';
    }

    $output .= "</div></div>";

    return $output;
}

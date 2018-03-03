<?php
/*  
*   Wordpress Custom Gallery
*   *********************************************
*   Override default wordpress image gallery
*   with your Bootstrap ready markup 
*/

add_filter( 'use_default_gallery_style', '__return_false' );
add_filter('post_gallery','customFormatGallery',10,2);

function customFormatGallery($string,$attr){

    $posts = get_posts(array('include' => $attr['ids'],'post_type' => 'attachment'));
    $output = '<div class="WPTheme__gallery"><div class="row mhTheme__chocolat chocolat-parent">';
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
    
    //printObject($attr);
    foreach($posts as $imagePost){
        $thumbnail = wp_get_attachment_image_src($imagePost->ID, $attr['size']);
        $image = wp_get_attachment_image_src($imagePost->ID, 'e');
        $output .= '<a href="' . $image[0] . '" title="" class="WPTheme__gallery-item ' . $columns_class . '">';
        $output .= '    <img src="' . $thumbnail[0] . '" alt="" title="">';
        $output .= '</a>';
    }

    $output .= "</div></div>";

    return $output;
}


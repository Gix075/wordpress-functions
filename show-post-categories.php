<?php

/* ************************************************* */
/* SHOW POST CATEGORIES */
/* ************************************************* */
function show_post_categories() {
    if (get_post_type() == "post") {
        $markup = '<ul class="entry-categories">';
        $categories = wp_get_post_categories( get_the_ID() );
        foreach($categories as $c){
            $cat = get_category( $c );
            $cat_id = get_cat_ID( $cat->name );
            $markup .= '<li><a href="'.get_category_link($cat_id).'">'.$cat->name.'</a></li>';
        }
        $markup .= '</ul>';
    }
    return $markup;
}

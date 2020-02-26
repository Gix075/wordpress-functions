<?php



function custom_terms_list($options) {

    $options_default = array(
        "show_taxonomy_label" => false,
        "show_terms_as_links => true
    );
    $markup = '';
    global $post;

    $taxonomies = get_object_taxonomies( $post );

    foreach ( $taxonomies as $taxonomy ) {
        $taxonomy = get_taxonomy( $taxonomy );
        //print_r($taxonomy);
        if ( $taxonomy->query_var && $taxonomy->hierarchical ) {

            $markup .= '<div class="entry-categories">';
                $markup .= '<h6>' . $taxonomy->labels->name . '</h6>';

                $terms = wp_get_post_terms( $post->ID, $taxonomy->name, array( 'orderby' => 'term_id' ) );
                foreach ( $terms as $term ) {
                    //print_r($term);
                    $link = get_term_link($term->term_id,$taxonomy->name);
                    $markup .= '<a href="'.$link.'">'.$term->name.'</a>';

                }
            $out .= '</div>';
        }
    }
}

<?php

// Show Hierarchical categories ordered by ID

function custom_terms_list($options) {

    $options_default = array(
        "show_taxonomy_label" => false,
        "show_terms_as_links" => true,
        "separator" => ""
    );
    $settings = array_replace_recursive($options_default, $options);
    $markup = '';
    global $post;

    $taxonomies = get_object_taxonomies( $post );

    foreach ( $taxonomies as $taxonomy ) {
        $taxonomy = get_taxonomy( $taxonomy );

        if ( $taxonomy->query_var && $taxonomy->hierarchical ) {

            $markup .= '<div class="entry-categories">';

                if($settings['show_taxonomy_label'] === true) $markup .= '<h6>' . $taxonomy->labels->name . '</h6>';

                $terms = wp_get_post_terms( $post->ID, $taxonomy->name, array( 'orderby' => 'term_id' ) );

                $i = 0;
                foreach ( $terms as $key => $term ) {
                    //print_r($term);

                    $separator = ( !empty($settings['separator']) && $i != 0) ? $settings['separator'] : "";
                    if( $settings['show_terms_as_links'] === true ) {
                        $link = get_term_link($term->term_id,$taxonomy->name);
                        $markup .= $separator.'<a href="'.$link.'">'.$term->name.'</a>';
                    }else{
                        $markup .= $separator."<span>".$term->name."</span>";
                    }
                    $i++;

                }

            $markup .= '</div>';
        }
    }

    return $markup;
}

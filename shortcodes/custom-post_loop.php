<?php

/* CUSTOM POST LOOP */
/* ========================================= */
function custompost_loop($atts) {

    $a = shortcode_atts( array(
        'post_type' => "post",
        'taxonomy' => "",
        'terms' => "",
        'featured' => "false",
        'random' => "false",
        'template' => "default",
        'columns' => 3,
        'limit' => -1,
        'excerpt' => "true"
    ), $atts );

    if (is_admin()) return FALSE;

    $featured_posts = get_option( 'sticky_posts');
    //print_r($featured_posts);

    // Update 26/03/2019 Random query
	// If random and featrued parameters are both "true", featured will override random parameter
    if ($a['featured'] == "true" && $a['random'] == "true") {
		$a['random'] == "false";
    }

    if ($a['featured'] == "true") {
        $query_limit = -1;
    } else {
        $query_limit = $a['limit'];
    }
	
	if($a['template'] == "bootstrap") {
		$col_size = 12/$a['columns'];
		$bootstrat_col_class = "col-md-".$col_size;
		$wrapper_class = " row";
	}else{
		$col_size = round(100/$a['columns'], 4);
		$default_col_class = "gix_custompostloop__column_".$col_size;
		$wrapper_class = "";
	}
	
	
    // SINGLE POST TEMPLATES
    // *********************************
    $templates = array();
    $templates['default'] = '   <article class="gix_custompostloop__post template_default '.$default_col_class.'" data-cat="{{TERMSLUG}}">';
    $templates['default'] .= '      <div class="gix_custompostloop__post-inner">';
    $templates['default'] .= '          <div class="gix_custompostloop__post-header">';
    $templates['default'] .= '              <div class="gix_custompostloop__post-header_image">';
    $templates['default'] .= '                  {{IMAGE}}';
    $templates['default'] .= '              </div>';
    $templates['default'] .= '              <div class="gix_custompostloop__post-header_title">';
    $templates['default'] .= '                  <h3>{{TITLE}}</h3>';
    $templates['default'] .= '              </div>';
    $templates['default'] .= '          </div>';
    $templates['default'] .= '          <div class="gix_custompostloop__post-body">';
    $templates['default'] .= '              <div class="gix_custompostloop__post-body_text">';
    $templates['default'] .= '                  {{TEXT}}';
    $templates['default'] .= '              </div>';
    $templates['default'] .= '          </div>';
    $templates['default'] .= '      </div>';
    $templates['default'] .= '  </article>';

    $templates['bootstrap'] = '   <article class="gix_custompostloop__post template_bootstrap '.$bootstrat_col_class.'" data-cat="{{TERMSLUG}}">';
    $templates['bootstrap'] .= '      <div class="gix_custompostloop__post-inner">';
    $templates['bootstrap'] .= '          <div class="gix_custompostloop__post-header">';
    $templates['bootstrap'] .= '              <a href="{{PERMALINK}}" title="{{TITLE}}">';
    $templates['bootstrap'] .= '                  <div class="gix_custompostloop__post-header_image">';
    $templates['bootstrap'] .= '                      {{IMAGE}}';
    $templates['bootstrap'] .= '                  </div>';
    $templates['bootstrap'] .= '                  <div class="gix_custompostloop__post-header_label" style="background:{{COLOR}};">';
    $templates['bootstrap'] .= '                      {{LABEL}}';
    $templates['bootstrap'] .= '                  </div>';
    $templates['bootstrap'] .= '              </a>';
    $templates['bootstrap'] .= '          </div>';
    $templates['bootstrap'] .= '          <div class="gix_custompostloop__post-body">';
    $templates['bootstrap'] .= '              <div class="gix_custompostloop__post-header_title">';
    $templates['bootstrap'] .= '                  <h3>{{TITLE}}</h3>';
    $templates['bootstrap'] .= '              </div>';
    $templates['bootstrap'] .= '              <div class="gix_custompostloop__post-body_text">';
    $templates['bootstrap'] .= '                  {{TEXT}}';
    $templates['bootstrap'] .= '              </div>';
    $templates['bootstrap'] .= '              <div class="gix_custompostloop__post-body_button">';
    $templates['bootstrap'] .= '                  {{BUTTON}}';
    $templates['bootstrap'] .= '              </div>';
    $templates['bootstrap'] .= '          </div>';
    $templates['bootstrap'] .= '      </div>';
    $templates['bootstrap'] .= '  </article>';

    if($a['taxonomy'] != "" && $a['terms'] != "") {
        $term = get_term_by('slug', $a['terms'], $a['taxonomy']);
        $term_title = $term->name;
        $term_slug = $term->slug;
    }

	if($a['taxonomy'] != "" && $a['terms'] != "") {
	    // CATEGORY WRAPPER
	    // *********************************
	    $markup_category = '';
	    $markup_category .= '   <div class="fc_custompostloop_wrapper'.$wrapper_class.' taxonomy_'.$a['taxonomy'].' term_'.$a['terms'].'">';
	    $markup_category .= '       <div class="fc_custompostloop_term-meta">';
	    $markup_category .= '           <div class="fc_custompostloop_term-meta_nickname">';
	    $markup_category .= '               <h2>'.$term_title.'</h2>';
	    $markup_category .= '           </div>';
	    $markup_category .= '       </div>';
	    $markup_category .= '        <div class="fc_custompostloop taxonomy_'.$a['taxonomy'].' term_'.$a['terms'].'">';
	    $markup_category .= '           {{CONTENT}}';
	    $markup_category .= '        </div>';
	    $markup_category .= '   </div>';
    }else{
    	$markup_category = '';
		$markup_category .= '       <div class="fc_custompostloop_wrapper'.$wrapper_class.' taxonomy_'.$a['taxonomy'].' term_'.$a['terms'].' ">';
	    $markup_category .= '           {{CONTENT}}';
	    $markup_category .= '       </div>';
    }

    // Taxonomy based query
	if($a['taxonomy'] != "" && $a['terms'] != "") {
		$taxquery = array(
			array(
				'taxonomy' => $a['taxonomy'],
		        'terms' => $a['terms'],
		        'field' => 'slug',
		        'include_children' => true,
		        'operator' => 'IN'
			)
		);
	}

    elseif ($a['taxonomy'] != "" && $a['terms'] == "") {
        $orderedTerms = get_terms( $a['taxonomy'] , array(
            //'taxonomy' => $a['taxonomy'],
            'orderby' => 'name',
            'order' => 'DESC'
        ) );


        $orderedTerms_array = array();
        foreach($orderedTerms as $key=>$term){
            $orderedTerms_array[] = $term->slug;
        }


        $taxquery = array(
			array(
				'taxonomy' => $a['taxonomy'],
		        'terms' => $orderedTerms_array,
		        'field' => 'slug',
		        'include_children' => true,
		        'operator' => 'IN'
			)
		);


    }

    else{
		$taxquery = array();
	}


    $args = array(
    	'post_type' => $a['post_type'],
    	'posts_per_page' => $query_limit,
    	'tax_query' => $taxquery
	);


	if($a['random'] == "true") {
		$args['orderby'] = 'rand';
	}


    $i = 0;
    $featured_selected = 0;
	$loop = new WP_Query( $args );
    $markup = "";


    while ( $loop->have_posts() ) : $loop->the_post();

        if($a['taxonomy'] != "") {
            $term = wp_get_post_terms( get_the_ID(), $a['taxonomy'] );
            $term_id = $term[0]->term_id;
            $term_slug = $term[0]->slug;
            $term_nickname = rwmb_meta( 'line_nickname', array( 'object_type' => 'term' ), $term_id );
            $term_nickname = ($term_nickname != "") ? $term_nickname : $term[0]->name;
            $term_color = rwmb_meta( 'color_1', array( 'object_type' => 'term' ), $term_id );
        }


        if($a['featured'] == "false" || ($a['featured'] == "true" && in_array(get_the_ID(), $featured_posts) && $featured_selected < $a['limit'])) {


            $template_name = $a['template'];
	        $markup_single = $templates[$template_name];

            $post_title = get_the_title();
            $post_content = ($a['excerpt'] == "true") ? get_the_excerpt() : get_the_content();
            $permalink = get_the_permalink();
            $permalink_button = '<a href="'.get_the_permalink().'" title="'.$post_title.'">Approfondisci <i class="fas fa-arrow-right"></i></a>';

			$markup_single = str_replace("{{TITLE}}", $post_title, $markup_single);
			$markup_single = str_replace("{{TEXT}}", $post_content, $markup_single);
			$markup_single = str_replace("{{IMAGE}}", get_the_post_thumbnail(), $markup_single);
            $markup_single = str_replace("{{LABEL}}", $term_nickname, $markup_single);
            $markup_single = str_replace("{{PERMALINK}}", $permalink, $markup_single);
            $markup_single = str_replace("{{BUTTON}}", $permalink_button, $markup_single);
            $markup_single = str_replace("{{COLOR}}", $term_color, $markup_single);
            $markup_single = str_replace("{{TERMSLUG}}", $term_slug, $markup_single);

	        $markup .= $markup_single;
        }


        $i++;

	endwhile;

    $output = str_replace("{{CONTENT}}", $markup, $markup_category);
    return $output;

}

add_shortcode( 'custompostLoop', 'custompost_loop' );

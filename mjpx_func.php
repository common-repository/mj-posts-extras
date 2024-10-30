<?php

function mjpx_getRelatedCatPosts( $mjpx_postid , $mjpx_title="" ) {
	global $wpdb;
	$mjpx_sql = "SELECT ".$wpdb->prefix."term_relationships.term_taxonomy_id FROM ".$wpdb->prefix."posts INNER JOIN ".$wpdb->prefix."term_relationships ON ".$wpdb->prefix."posts.ID=".$wpdb->prefix."term_relationships.object_id WHERE ".$wpdb->prefix."posts.ID=".$mjpx_postid;
	$mjpx_posts = $wpdb->get_results($mjpx_sql);
	$mjpx_tax_id = $mjpx_posts[0]->term_taxonomy_id;
	if( get_option('mjpx_num_cat_posts') != '' ) $mjpx_num_per_page = get_option('mjpx_num_cat_posts');
	else $mjpx_num_per_page = 3;
	$mjpx_output = "";
	if($mjpx_title!="")
	$mjpx_output .= '<p class="mjpx-title">'.$mjpx_title.'</p>';
	$mjpx_output .= '<ul class="mjpx-list">';
		$args=array(
			'post__not_in' 		=> array($mjpx_postid),
			'cat' 				=> $mjpx_tax_id,
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> $mjpx_num_per_page
		);
		$temp = $wp_query;
		$wp_query = null;
		$wp_query = new WP_Query($args);

		if( have_posts() ) : 
		while ($wp_query->have_posts()) : $wp_query->the_post();
			$mjpx_output .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
		endwhile;
		endif;
	$mjpx_output .= '</ul>';
	return $mjpx_output;
}

function mjpx_getRelatedCustomPosts( $mjpx_postid , $mjpx_title="" ) {
	global $wpdb;
	$mjpx_sql = "SELECT post_type FROM ".$wpdb->prefix."posts WHERE ID=".$mjpx_postid;
	$mjpx_posts = $wpdb->get_results($mjpx_sql);
	$mjpx_post_type = $mjpx_posts[0]->post_type;
	if( get_option('mjpx_num_custom_posts') != '' ) $mjpx_num_per_page = get_option('mjpx_num_custom_posts');
	else $mjpx_num_per_page = 3;
	$mjpx_output = "";
	if($mjpx_title!="")
	$mjpx_output .= '<p class="mjpx_title">'.$mjpx_title.'</p>';
	$mjpx_output .= '<ul class="mjpx-list">';
		$args=array(
			'post__not_in' 		=> array($mjpx_postid),
			'post_type' 		=> $mjpx_post_type,
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> $mjpx_num_per_page
		);
		$temp = $wp_query;
		$wp_query = null;
		$wp_query = new WP_Query($args);

		if( have_posts() ) : 
		while ($wp_query->have_posts()) : $wp_query->the_post();
			$mjpx_output .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
		endwhile;
		endif;
	$mjpx_output .= '</ul>';
	return $mjpx_output;
}

function mjpx_getMostCommentedPosts( $mjpx_title="" ) {
	if( get_option('mjpx_num_mostcomm_posts') != '' ) $mjpx_num_per_page = get_option('mjpx_num_mostcomm_posts');
	else $mjpx_num_per_page = 3;
	
	global $wpdb;
	$mjpx_sql = "SELECT ID FROM ".$wpdb->prefix."posts ORDER BY comment_count DESC LIMIT ".$mjpx_num_per_page;
	$mjpx_posts = $wpdb->get_results($mjpx_sql);
	$mjpx_posts_array = array();
	foreach ($mjpx_posts as $mjpx_post) {
		array_push($mjpx_posts_array,$mjpx_post->ID);
	}
	
	
	$mjpx_output = "";
	if($mjpx_title!="") $mjpx_output .= '<p class="mjpx_title">'.$mjpx_title.'</p>';
	$mjpx_output .= '<ul class="mjpx-list">';
		$args=array(
			'post__in'			=> $mjpx_posts_array,
			'orderby'			=> 'comment_count',
			'order'				=> 'DESC',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> get_option('mjpx_num_posts')
		);
		$temp = $wp_query;
		$wp_query = null;
		$wp_query = new WP_Query($args);

		if( have_posts() ) : 
		while ($wp_query->have_posts()) : $wp_query->the_post();
			$mjpx_output .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
		endwhile;
		endif;
	$mjpx_output .= '</ul>';
	return $mjpx_output;
}

function mjpx_getLatestComments( $mjpx_title="" ) {
	if( get_option('mjpx_num_latest_comm') != '' ) $mjpx_num_per_page = get_option('mjpx_num_latest_comm');
	else $mjpx_num_per_page = 3;
	
	global $wpdb;
	$mjpx_sql = "SELECT comment_ID,comment_post_ID,comment_content FROM ".$wpdb->prefix."comments ORDER BY comment_date DESC LIMIT ".$mjpx_num_per_page;
	$mjpx_posts = $wpdb->get_results($mjpx_sql);
	
	$mjpx_output = "";
	if($mjpx_title!="") $mjpx_output .= '<p class="mjpx_title">'.$mjpx_title.'</p>';
	$mjpx_output .= '<ul class="mjpx-list">';
	
	for($i=0; $i<count($mjpx_posts); $i++) {
		$args=array(
			'post_type' => 'any',
			'p'			=> $mjpx_posts[$i]->comment_post_ID
		);
		$temp = $wp_query;
		$wp_query = null;
		$wp_query = new WP_Query($args);

		if( have_posts() ) : 
		while ($wp_query->have_posts()) : $wp_query->the_post();
					if( strlen($mjpx_posts[$i]->comment_content)>30 )
						$mjpx_comm_content = strip_tags(substr($mjpx_posts[$i]->comment_content,0,30).'...');
					else
						$mjpx_comm_content = strip_tags($mjpx_posts[$i]->comment_content);
					$mjpx_output .= '<li>'.$mjpx_comm_content.' <a class="comm_arr" href="'.get_permalink().'#comment-'.$mjpx_posts[$i]->comment_ID.'">&raquo;</a></li>';
		endwhile;
		endif;
	}
	$mjpx_output .= '</ul>';
	return $mjpx_output;
}

?>
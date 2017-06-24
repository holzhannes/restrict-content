<?php
/*******************************************
* Restrict Content Display Column in Tables
*******************************************/
add_filter('manage_posts_columns', 'restrict_content_col');
add_filter('manage_pages_columns', 'restrict_content_col');

function restrict_content_col($defaults){

	$defaults['restrict_content_col'] = __('Restrict Content', 'restrict-content');	/*Add our column to the list of default columns*/

	return $defaults;

}

add_action('manage_posts_custom_column', 'restrict_content_data', 10, 2);
add_action('manage_pages_custom_column', 'restrict_content_data', 10, 2);

function restrict_content_data($column_name, $id) {
    if( $column_name == 'restrict_content_col' ) {

    	$rcUserLevel = get_post_meta($id, 'rcUserLevel', true);
    	$rcFeedHide = get_post_meta($id, 'rcFeedHide', true);

    	$post = get_post($id);
		$shortCodeUsed = has_shortcode( $post->post_content, 'restrict' );

		if(!empty($rcUserLevel)){
			if ($rcUserLevel == 'Administrator' || $rcUserLevel == 'Editor' || $rcUserLevel == 'Author' || $rcUserLevel == 'Contributor' || $rcUserLevel == 'Subscriber') {
				echo '<span class="dashicons dashicons-lock"></span> '. displayUserLevel( $rcUserLevel );
			}
		}

		if(!empty($shortCodeUsed)){
			if ($shortCodeUsed){
				echo '<br>';
				echo '<span class="dashicons dashicons-hidden"></span> '. __('Parts restricted', 'restrict-content');
			}
		}

		if(!empty($rcFeedHide)){
			if ($rcFeedHide == 'on'){
				echo '<br>';
				echo '<span class="dashicons dashicons-rss"></span> ' . __('Feed restricted', 'restrict-content');
			}
		}
	}
}

function displayUserLevel( $rcUserLevel ) {

	switch ($rcUserLevel) {
		case 'Administrator':
			return __('Administrator', 'restrict-content');
			break;
		case 'Editor':
			return __('Editor', 'restrict-content');
			break;
		case 'Author':
			return __('Author', 'restrict-content');
			break;
		case 'Contributor':
			return __('Contributor', 'restrict-content');
			break;
		case 'Subscriber':
			return __('Subscriber', 'restrict-content');
			break;
		default:
			return $rcUserLevel;
	}
}
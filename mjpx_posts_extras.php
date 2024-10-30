<?php
/*
Plugin Name: MJ Posts Extras
Plugin URI: http://markojakic.net/mjpx
Description: This plugin enables you to display lists of posts related stuff, like most popular, from same category, etc.
Version: 0.1
Author: Marko Jakic
Author URI: http://markojakic.net/
License: GPL
*/

include_once 'mjpx_func.php';

add_action('admin_menu', 'mjpx_create_menu');
add_action('init', 'mjpx_related_cat_posts');
add_action('init', 'mjpx_related_custom_posts');
add_action('init', 'mjpx_most_commented_posts');
add_action('init', 'mjpx_latest_comments');


function mjpx_related_cat_posts( $mjpx_postid,$mjpx_title="" ) {
	return mjpx_getRelatedCatPosts( $mjpx_postid,$mjpx_title );
}
function mjpx_related_custom_posts( $mjpx_postid,$mjpx_title="" ) {
	return mjpx_getRelatedCustomPosts( $mjpx_postid,$mjpx_title );
}
function mjpx_most_commented_posts( $mjpx_title="" ) {
	return mjpx_getMostCommentedPosts( $mjpx_title );
}
function mjpx_latest_comments( $mjpx_title="" ) {
	return mjpx_getLatestComments( $mjpx_title );
}


function mjpx_create_menu() {
	add_options_page('MJ Posts Extras Settings', 'MJ Posts Extras', 'administrator', __FILE__, 'mjpx_settings_page');
	add_action( 'admin_init', 'register_mjpx_settings' );
}


function register_mjpx_settings() {
	register_setting( 'mjpx-settings-group', 'mjpx_num_cat_posts' );
	register_setting( 'mjpx-settings-group', 'mjpx_num_custom_posts' );
	register_setting( 'mjpx-settings-group', 'mjpx_num_mostcomm_posts' );
	register_setting( 'mjpx-settings-group', 'mjpx_num_latest_comm' );
}

function mjpx_settings_page() {
?>
<div class="wrap">
<h2>MJ Posts Extras</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'mjpx-settings-group' ); ?>
	<p>Default for all is 3</p>
    <table class="form-table">
        <tr valign="top">
        <th style="width:380px;" scope="row">Number of common category posts:</th>
        <td><input type="text" name="mjpx_num_cat_posts" value="<?php echo get_option('mjpx_num_cat_posts'); ?>" /></td>
        </tr>
		<tr><td colspan="2">e.g. <i>echo mjpx_related_cat_posts( $postID , "Related" );</i></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr valign="top">
        <th style="width:380px;" scope="row">Number of custom posts of the same type:</th>
        <td><input type="text" name="mjpx_num_custom_posts" value="<?php echo get_option('mjpx_num_custom_posts'); ?>" /></td>
        </tr>
		<tr><td colspan="2">e.g. <i>echo mjpx_related_custom_posts( $postID , "Movies" );</i></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr valign="top">
        <th style="width:380px;" scope="row">Number of most commented (popular) posts:</th>
        <td><input type="text" name="mjpx_num_mostcomm_posts" value="<?php echo get_option('mjpx_num_mostcomm_posts'); ?>" /></td>
        </tr>
		<tr><td colspan="2">e.g. <i>echo mjpx_most_commented_posts( "Most Popular" );</i></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr valign="top">
        <th style="width:380px;" scope="row">Number of latest comments:</th>
        <td><input type="text" name="mjpx_num_latest_comm" value="<?php echo get_option('mjpx_num_latest_comm'); ?>" /></td>
        </tr>
		<tr><td colspan="2">e.g. <i>echo mjpx_latest_comments( "Latest Comments" );</i></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">If this plugin saved you time and you think it's worth a penny, please add some coins to my Moneybookers account: jakicmeister@gmail.com </td></tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>
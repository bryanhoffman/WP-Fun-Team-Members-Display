<?php

/*
 * Plugin Name:       WP Fun Team Members Display
 * Description:       Manage Team members' bios and display them in a fun way
 * Author:            Bryan Hoffman
 * Author URI:        https://bryanhoffman.xyz/
 */

/*
 * The create_posttypes function creates the group members
 * custom post type
 */
  
function create_group_member_post_type() {
    register_post_type( 'group_members',
        array(
            'labels' => array(
                'name' => __( 'Group Members' ),
                'singular_name' => __( 'Group Member' )
            ),
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 4,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => 0,
        )
    );
}
add_action( 'init', 'create_group_member_post_type' );

/*
 * This plugin requires the plugin Advanced Custom Fields
 * The create_posttypes function needs ACF to create custom post types
 */

// check that ACF is installed
if(in_array('advanced-custom-fields/acf.php', apply_filters('active_plugins', get_option('active_plugins')))) {

     function add_group_member_field_groups() {
	acf_add_local_field_group(array(
		'key' => 'group_632a5aa4a3ca4',
		'title' => 'Group Members',
		'fields' => array(
			array(
				'key' => 'field_632a5acc52aec',
				'label' => 'Title',
				'name' => 'title',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_632a5ad852aed',
				'label' => 'Regular Image',
				'name' => 'regular_image',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_632a5af152aee',
				'label' => 'Silly Image',
				'name' => 'silly_image',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'preview_size' => 'medium',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_632a5f874ec6a',
				'label' => 'Pronouns',
				'name' => 'pronouns',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => 'e.g. (they/them)',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'group_members',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

     }
     add_action('acf/init', 'add_group_member_field_groups');

} else {
	// display message in admin about needing ACF plugin
	function ACF_required_admin_notice() {
	    echo '<div class="notice notice-warning">
		<p>The plugin Advanced Custom Fields (ACF) is required for WP Fun Group Members Display</p>
	    </div>';
	}
	add_action('admin_notices', 'ACF_required_admin_notice' );
}

// Group Member Stuff
// function that shows group members with their profiles
function group_members_full_profile_shortcode() { 
$message = "";  
$members = get_posts([
  'post_type' => 'group_members',
  'post_status' => 'publish',
  'numberposts' => -1,
  'order' => 'DESC'
]);

    if( is_array($members) ) {
        foreach( $members as $member ) {
				$message=$message."<div class=\"profile\"><img class=\"profile-image\" src=\"".wp_get_attachment_image_url( $member->regular_image, "medium")."\">";
				$message=$message."<h3 class=\"profile-name\">".$member->post_title." ".$member->pronouns."</h3>";
				if($member->title) { $message = $message."<h4 class=\"profile-title\">".$member->title."</h4>"; }
				$message=$message."<span class=\"profile-bio\">".$member->post_content."</span></div>";
        }
        
    }
  
// Output needs to be return
return $message;
}
// register shortcode
add_shortcode('group_members_with_profiles', 'group_members_full_profile_shortcode');


// function that shows group members with their profiles
function group_members_preview_shortcode() { 
$message = "";  
$members = get_posts([
  'post_type' => 'group_members',
  'post_status' => 'publish',
  'numberposts' => -1,
  'order' => 'DESC'
]);

    if( is_array($members) ) {
		$message=$message."<div class=\"profile-grid\">";
        foreach( $members as $member ) {
				$message=$message."<div class=\"profile-preview\"><img class=\"profile-image\" src=\"".wp_get_attachment_image_url( $member->regular_image, "medium")."\"><img class=\"silly-image\" src=\"".wp_get_attachment_image_url( $member->silly_image, "medium")."\">";
				$message=$message."<h5 class=\"profile-name\">".$member->post_title." ".$member->pronouns."</h5>";
				if($member->title) { $message = $message."<h6 class=\"profile-title\">".$member->title."</h6>"; }
				$message=$message."</div>";
        } 
		$message=$message."</div>";
    }
	
return $message;
}
// register shortcode
add_shortcode('group_members_preview', 'group_members_preview_shortcode');

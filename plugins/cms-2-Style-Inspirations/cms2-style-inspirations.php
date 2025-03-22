<?php
/*
 * Plugin Name: Style Inspirations Post Type
 */

add_action( 'init', 'cms2_announcement_pt' );

function cms2_announcement_pt() {

   $labels = array(

      'name'                     => __( 'Announcements', 'cms2' ),
      'singular_name'            => __( 'Announcement', 'cms2' ),
      'add_new'                  => __( 'Add New', 'cms2' ),
      'add_new_item'             => __( 'Add New Announcement', 'cms2' ),
      'edit_item'                => __( 'Edit Announcement', 'cms2' ),
      'new_item'                 => __( 'New Announcement', 'cms2' ),
      'view_item'                => __( 'View Announcement', 'cms2' ),
      'view_items'               => __( 'View Announcements', 'cms2' ),
      'search_items'             => __( 'Search Announcements', 'cms2' ),
      'not_found'                => __( 'No Announcements found.', 'cms2' ),
      'not_found_in_trash'       => __( 'No Announcements found in Trash.', 'cms2' ),
      'parent_item_colon'        => __( 'Parent Announcements:', 'cms2' ),
      'all_items'                => __( 'All Announcements', 'cms2' ),
      'archives'                 => __( 'Announcement Archives', 'cms2' ),
      'attributes'               => __( 'Announcement Attributes', 'cms2' ),
      'insert_into_item'         => __( 'Insert into Announcement', 'cms2' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this Announcement', 'cms2' ),
      'featured_image'           => __( 'Featured Image', 'cms2' ),
      'set_featured_image'       => __( 'Set featured image', 'cms2' ),
      'remove_featured_image'    => __( 'Remove featured image', 'cms2' ),
      'use_featured_image'       => __( 'Use as featured image', 'cms2' ),
      'menu_name'                => __( 'Announcements', 'cms2' ),
      'filter_items_list'        => __( 'Filter Announcement list', 'cms2' ),
      'filter_by_date'           => __( 'Filter by date', 'cms2' ),
      'items_list_navigation'    => __( 'Announcements list navigation', 'cms2' ),
      'items_list'               => __( 'Announcements list', 'cms2' ),
      'item_published'           => __( 'Announcement published.', 'cms2' ),
      'item_published_privately' => __( 'Announcement published privately.', 'cms2' ),
      'item_reverted_to_draft'   => __( 'Announcement reverted to draft.', 'cms2' ),
      'item_scheduled'           => __( 'Announcement scheduled.', 'cms2' ),
      'item_updated'             => __( 'Announcement updated.', 'cms2' ),
      'item_link'                => __( 'Announcement Link', 'cms2' ),
      'item_link_description'    => __( 'A link to an announcement.', 'cms2' ),
   );

   $args = array(
      'labels'                => $labels,
      'public'                => true,
      'has_archive'           => true,
      'show_in_rest'          => true,
      'menu_icon'             => 'dashicons-megaphone',
      'capability_type'       => 'post',
      'capabilities'          => array(),
      'supports'              => array( 'title', 'editor', 'revisions' ),
      'rewrite'               => array( 'slug' => 'announcement' ),
   );

   register_post_type( 'cms2-announcement-pt', $args );

}
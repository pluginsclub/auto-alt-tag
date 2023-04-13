<?php
/**
 * Plugin Name: Auto Alt Tag
 * Description: Automatically adds the post title as the alt tag for featured images and images in the post content.
 * Version: 1.0
 * Author: pluginsclub
 */
function pluginsclub_auto_alt_tag_for_images($content) {
  if (is_singular()) {
    global $post;
    $post_title = $post->post_title;
    
    // Find all images in the post content
    preg_match_all('/<img[^>]+>/i', $content, $all_images);
    
    // Loop through all images and add the post title as the alt tag if the alt tag is empty
    foreach($all_images[0] as $image) {
      if (preg_match('/alt=""/i', $image)) {
        $new_image = str_replace('alt=""', 'alt="' . $post_title . '"', $image);
        $content = str_replace($image, $new_image, $content);
      }
    }
    
    // Find the featured image and add the post title as the alt tag if the alt tag is empty
    if (has_post_thumbnail()) {
      $thumbnail_id = get_post_thumbnail_id($post->ID);
      $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      if (empty($alt)) {
        update_post_meta($thumbnail_id, '_wp_attachment_image_alt', $post_title);
      }
    }
  }
  return $content;
}
add_filter('the_content', 'pluginsclub_auto_alt_tag_for_images');

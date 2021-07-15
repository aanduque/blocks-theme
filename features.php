<?php

/**
 * Only logged users.
 */
add_action('template_redirect', function() {

  if (is_user_logged_in()) {

      return;

  }
  
  auth_redirect();

  exit;

});    

/**
 * Styles and Scripts
 */
add_action('wp_enqueue_scripts', function() {

  wp_enqueue_style('icons', 'https://cdn.linearicons.com/free/1.0.0/icon-font.min.css');

  wp_enqueue_style('blocks', get_template_directory_uri() . '/style.css');

  wp_register_script('blocks', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0', true);
        
  wp_enqueue_script('blocks'); // Enqueue it!

});
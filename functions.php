<?php

function university_files() {
  // load css from build
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

  // load fontawesome
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  // load google font
  wp_enqueue_style('google-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

  //load js file
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
}

// add css and js files
add_action('wp_enqueue_scripts', 'university_files');


function university_features() {
  // add function for dynamic title
  add_theme_support('title-tag');
  // add menu
  register_nav_menu('headerMenuLocation', 'Header Menu Loaction');
}


// when adding a feature to a theme, you call add_theme_support
add_action('after_setup_theme', 'university_features');


function university_adjust_queries($query) {
  // query for program post type
  if (!is_admin() AND is_post_type_archive('program') AND is_main_query()) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }


  // query for event
  if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              )
            ));
  }
}

// to perform custom query
add_action('pre_get_posts', 'university_adjust_queries');
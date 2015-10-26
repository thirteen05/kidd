<?php

// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'THEMENAME' ),
) );


function enqueue_home_animation() {
  wp_enqueue_script( 'script-name', get_template_directory_uri() . '/animate/1305-Main_edge.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'enqueue_home_animation' );



?>
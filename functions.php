<?php

// Declare all your add_action and add_filter here so they all run when you call the init function
function Init_My_Theme() {

    // Declare image sizes here
    add_action( 'after_setup_theme', 'My_Theme_theme_support_options' );

    // Add all your scripts and styles here
    add_action( 'wp_enqueue_scripts', 'My_Theme_load_scripts_and_styles', 1 );

    // Add all the sidebars
    add_action( 'widgets_init', 'My_Theme_add_sidebars_and_menus', 1 );

    // Add the custom taxonomies
    add_action( 'init', 'My_Theme_register_custom_taxonomy', 1 );

    // On the posts page, add a column
    add_filter( 'manage_posts_columns', 'My_Theme_add_taxonomy_column', 10, 1 );
    add_action( 'manage_posts_custom_column', 'My_Theme_manage_taxonomy_column', 10, 2 );
}

// Add all the functions you hooked above below here

function My_Theme_theme_support_options() {
    add_theme_support( 'post-thumbnails' );

    add_image_size( 'related-image', 200, 200, true );
    add_image_size( 'slideshow-image', 640, 480, true );
}

// Add our scripts and styles
function My_Theme_load_scripts_and_styles() {

    wp_enqueue_script( 'jquery' );

    wp_enqueue_style(
        'My_Theme_stylesheet',
        get_template_directory_uri()."/style.css",
        false,
        null,
        'all'
    );
}

// Register Sidebars and Menus
function My_Theme_add_sidebars_and_menus() {
    register_sidebar( array(
        'name'          => 'Sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ) );

    register_nav_menus( array(
        'My_Theme_top_menu' => 'Navigation Bar',
    ) );
}

// Register a custom taxonomy
function My_Theme_register_custom_taxonomy() {
    $taxonomy_name = 'custom-taxonomy';

    $taxonomy_labels = array(
        'name'              => __( 'Custom Taxonomies' ),
        'singular_name'     => __( 'Custom' ),
        'all_items'         => __( 'All Custom Taxonomies' ),
        'edit_item'         => __( 'Edit Custom Taxonomy' ),
        'update_item'       => __( 'Update Custom Taxonomy' ),
        'add_new_item'      => __( 'Add New Custom Taxonomy' ),
        'new_item_name'     => __( 'New Custom Taxonomy' ),
        'menu_name'         => __( 'Custom Taxonomies' )
    );

    register_taxonomy(
        $taxonomy_name,
        array( 'post' ),
        array(
            'hierarchical'      => false,
            'labels'            => $taxonomy_labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'query_var'         => true,
            'rewrite' => array(
                'slug'       => "customs",
                'feeds'      => true,
                'with_front' => false,
            ),
            'has_archive' => false
        )
    );
}

// Add a column to show the taxonomy
function My_Theme_add_taxonomy_column( $columns ) {
    $columns['custom-taxonomy'] = _x( 'Custom Taxonomy', 'column name' );
    return $columns;
}

// Show the content of that new taxonomy column
function My_Theme_manage_taxonomy_column( $column_name, $post_id ) {
    if ( $column_name != 'custom-taxonomy' ) {
        return;
    }

    $post = get_post( $post_id );

    $categories = get_the_terms( $post_id, 'custom-taxonomy' );
    if ( !empty( $categories ) ) {
        $out = array();
        foreach ( $categories as $category ) {
            $out[] = sprintf( '<a href="%s">%s</a>',
                esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'custom-taxonomy' => $category->slug ), 'edit.php' ) ),
                esc_html( sanitize_term_field( 'name', $category->name, $category->term_id, 'custom-taxonomy', 'display' ) )
            );
        }
        echo join( ', ', $out );
    } else {
        _e( 'No Custom Taxonomy' );
    }
}

// Start running everything here:
Init_My_Theme();

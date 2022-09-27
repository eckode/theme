<?php

namespace Eckode\init;

use Eckode\utils\{
	menu,
	rest
};

function init()
{
	// define( __NAMESPACE__ . '\REST_BASES', rest\map_bases() );
	menu\init();

	$labels = array(
		'name'                  => _x( 'Products', 'Post Type General Name', 'eckode' ),
		'singular_name'         => _x( 'Products', 'Post Type Singular Name', 'eckode' ),
		'menu_name'             => __( 'Products', 'eckode' ),
		'name_admin_bar'        => __( 'Product', 'eckode' ),
		'archives'              => __( 'Product Archives', 'eckode' ),
		'attributes'            => __( 'Product Attributes', 'eckode' ),
		'parent_item_colon'     => __( 'Parent Item:', 'eckode' ),
		'all_items'             => __( 'All Products', 'eckode' ),
		'add_new_item'          => __( 'Add New Product', 'eckode' ),
		'add_new'               => __( 'Add New', 'eckode' ),
		'new_item'              => __( 'New Product', 'eckode' ),
		'edit_item'             => __( 'Edit Product', 'eckode' ),
		'update_item'           => __( 'Update Product', 'eckode' ),
		'view_item'             => __( 'View Product', 'eckode' ),
		'view_items'            => __( 'View Products', 'eckode' ),
		'search_items'          => __( 'Search Products', 'eckode' ),
		'not_found'             => __( 'Not found', 'eckode' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'eckode' ),
		'featured_image'        => __( 'Featured Image', 'eckode' ),
		'set_featured_image'    => __( 'Set featured image', 'eckode' ),
		'remove_featured_image' => __( 'Remove featured image', 'eckode' ),
		'use_featured_image'    => __( 'Use as featured image', 'eckode' ),
		'insert_into_item'      => __( 'Insert into item', 'eckode' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Product', 'eckode' ),
		'items_list'            => __( 'Products list', 'eckode' ),
		'items_list_navigation' => __( 'Products list navigation', 'eckode' ),
		'filter_items_list'     => __( 'Filter Products list', 'eckode' ),
	);
	$args   = array(
		'label'               => __( 'Products', 'eckode' ),
		'description'         => __( 'Post Type Description', 'eckode' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
		'taxonomies'          => ['category', 'post_tag'],
		'rest_base'           => 'products',
		'rewrite'             => array(
			'slug'         => 'products'
		)
	);
	register_post_type( 'product', $args );

	$labels = array(
		'name'                  => _x( 'Entries', 'Post Type General Name', 'eckode' ),
		'singular_name'         => _x( 'Entries', 'Post Type Singular Name', 'eckode' ),
		'menu_name'             => __( 'Entries', 'eckode' ),
		'name_admin_bar'        => __( 'Entry', 'eckode' ),
		'archives'              => __( 'Entry Archives', 'eckode' ),
		'attributes'            => __( 'Entry Attributes', 'eckode' ),
		'parent_item_colon'     => __( 'Parent Item:', 'eckode' ),
		'all_items'             => __( 'All Entries', 'eckode' ),
		'add_new_item'          => __( 'Add New Entry', 'eckode' ),
		'add_new'               => __( 'Add New', 'eckode' ),
		'new_item'              => __( 'New Entry', 'eckode' ),
		'edit_item'             => __( 'Edit Entry', 'eckode' ),
		'update_item'           => __( 'Update Entry', 'eckode' ),
		'view_item'             => __( 'View Entry', 'eckode' ),
		'view_items'            => __( 'View Entries', 'eckode' ),
		'search_items'          => __( 'Search Entries', 'eckode' ),
		'not_found'             => __( 'Not found', 'eckode' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'eckode' ),
		'featured_image'        => __( 'Featured Image', 'eckode' ),
		'set_featured_image'    => __( 'Set featured image', 'eckode' ),
		'remove_featured_image' => __( 'Remove featured image', 'eckode' ),
		'use_featured_image'    => __( 'Use as featured image', 'eckode' ),
		'insert_into_item'      => __( 'Insert into item', 'eckode' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Entry', 'eckode' ),
		'items_list'            => __( 'Entries list', 'eckode' ),
		'items_list_navigation' => __( 'Entries list navigation', 'eckode' ),
		'filter_items_list'     => __( 'Filter Entries list', 'eckode' ),
	);
	$args   = array(
		'label'               => __( 'Entries', 'eckode' ),
		'description'         => __( 'Post Type Description', 'eckode' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'entries',
		'exclude_from_search' => false,
		'taxonomies'          => ['category', 'post_tag'],
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
		'rest_base'           => 'entries',
		'rewrite'             => array(
			'slug'         => 'entries'
		)
	);
	register_post_type( 'entry', $args );

	$tax_labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'eckode' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'eckode' ),
		'menu_name'                  => __( 'Category', 'eckode' ),
		'all_items'                  => __( 'All categories', 'eckode' ),
		'parent_item'                => __( 'Parent Category', 'eckode' ),
		'parent_item_colon'          => __( 'Parent Category:', 'eckode' ),
		'new_item_name'              => __( 'New Category Name', 'eckode' ),
		'add_new_item'               => __( 'Add New Category', 'eckode' ),
		'edit_item'                  => __( 'Edit Category', 'eckode' ),
		'update_item'                => __( 'Update Category', 'eckode' ),
		'view_item'                  => __( 'View Category', 'eckode' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'eckode' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'eckode' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'eckode' ),
		'popular_items'              => __( 'Popular categories', 'eckode' ),
		'search_items'               => __( 'Search categories', 'eckode' ),
		'not_found'                  => __( 'Not Found', 'eckode' ),
		'no_terms'                   => __( 'No categories', 'eckode' ),
		'items_list'                 => __( 'Categories list', 'eckode' ),
		'items_list_navigation'      => __( 'Categories list navigation', 'eckode' ),
	);
	$rewrite    = array(
		'slug'         => 'categories',
		'with_front'   => false,
		'hierarchical' => true,
	);
	$args       = array(
		'labels'            => $tax_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'rewrite'           => $rewrite,
		'show_in_rest'      => true,
		'rest_base'         => 'entry-categories',
	);
	register_taxonomy( 'entry-category', array( 'entry' ), $args );
}

add_action('init', __NAMESPACE__ . '\init');

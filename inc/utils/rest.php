<?php

/**
 * REST related functions
 */

namespace Eckode\utils\rest;

/**
 * Map Bases
 * 
 * Outputs object containing all REST API endpoints. NB: this function must be called after init hook has run. Function return value is cached per request for subsequent calls.
 * 
 * @return array
 */
function map_bases(): array
{

	if (0 === did_action('init')) {
		_doing_it_wrong(__FUNCTION__, 'Function can only be used once the "init" hook has fired', '0.0.1');
		return ['taxonomy' => [], 'post_type' => []];
	}

	static $cached_return = null;

	if (null !== $cached_return) {
		return $cached_return;
	}

	$post_types = $taxonomies = [];

	$registered_post_types = get_post_types(['public'   => true]);
	$registered_taxonomies = get_taxonomies(['public'   => true]);

	$taxonomy_disallowed_list = ['post_format'];

	foreach ($registered_post_types as $registered_post_type) {
		$post_types[$registered_post_type] = get_post_type_object($registered_post_type)->rest_base;
	}

	foreach ($registered_taxonomies as $registered_taxonomy) {
		if (in_array($registered_taxonomy, $taxonomy_disallowed_list)) {
			continue;
		}
		$taxonomies[$registered_taxonomy] = get_taxonomy($registered_taxonomy)->rest_base;
	}

	$cached_return = apply_filters(__FUNCTION__, ['taxonomy' => $taxonomies, 'post_type' => $post_types]);
	
	return $cached_return;
}

<?php

declare(strict_types=1);

namespace Eckode\utils\boot;

use Eckode\Boot;

/**
 * @return array
 */
function get_json(): array
{

	global $wp;
	$current_uri = parse_url(home_url(add_query_arg([], $wp->request)));

	$boot_object = [
		'id' 	       => 0,
		'path' 		   => trailingslashit(ltrim($current_uri['path'] ?? '', '/')),
		'content' 	   => '',
		'context' 	   => '',
		'contextValue' => '',
	];

	$queried_object      = get_queried_object();

	$boot = new Boot($queried_object);

	if (is_front_page()) {
		$boot_object['context'] = 'home';
		$boot_object['contextValue'] = 'static';
		// show_on_front?
		if (is_home()) {
			$boot_object['title'] = 'Welcome to {Site Name}';
			$boot_object['contextValue'] = 'dynamic';
		} else {
			$boot_object['id'] = $queried_object->ID;
			$boot_object['title'] = $queried_object->post_title;
			$boot_object['content'] = $queried_object->post_content;
		}

		$boot_object = $boot->home($boot_object);
	} else if (is_archive()) {
		if (is_tag() || is_tax() || is_category()) {
			$boot_object['id'] 			 = $queried_object->term_id;
			$boot_object['title'] 		 = $queried_object->name;
			$boot_object['content'] 	 = $queried_object->description;
			$boot_object['context'] 	 = 'taxonomy_archive';
			$boot_object['contextValue'] = $queried_object->taxonomy;

			$boot_object = $boot->taxonomy($boot_object, $queried_object);
		} else if (is_post_type_archive()) {
			$boot_object['title'] 		 = $queried_object->label;
			$boot_object['content'] 	 = $queried_object->description;
			$boot_object['context'] 	 = 'post_type_archive';
			$boot_object['contextValue'] = $queried_object->name;

			$boot_object = $boot->post_type_archive($boot_object, $queried_object->name);
		}
	} else if (is_single() || is_singular()) {
		$boot_object['id'] 			 = $queried_object->ID;
		$boot_object['title'] 		 = $queried_object->post_title;
		$boot_object['content'] 	 = $queried_object->post_content ?? '';
		$boot_object['context'] 	 = 'single';
		$boot_object['contextValue'] = $queried_object->post_type;
	} else if (is_404()) {
		$boot_object['title'] 		 = __('Page not found!', 'eckode');
		$boot_object['context'] 	 = 'not_found';
	}

	return $boot_object;
}

<?php

declare(strict_types=1);

namespace Eckode\utils\boot;

use Eckode\Boot;
use Eckode\Unify;

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
		'context_value' => '',
	];

	$queried_object      = get_queried_object();

	$boot = new Boot($queried_object);
	$boot_object = Unify::configure(wp_parse_args($queried_object, $boot_object));

	if (is_front_page()) {
		$boot_object = $boot_object->map();
		$boot_object = $boot->home($boot_object);
	} else if (is_archive()) {
		$boot_object = $boot_object->map();
		if (is_tag() || is_tax() || is_category()) {
			$boot_object = $boot->taxonomy($boot_object, $queried_object);
		} else if (is_post_type_archive()) {
			$boot_object = $boot->post_type_archive($boot_object, $queried_object->name);
		}
	} else if (is_singular()) {
		$boot_object = $boot_object->add_props(['excerpt', 'post_image'])->map();
		$boot_object = $boot->post_type($boot_object, $queried_object);
		// if (is_single()) {
		// } else {
		// }
	}

	return (array) $boot_object;
}

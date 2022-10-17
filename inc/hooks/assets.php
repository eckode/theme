<?php

namespace Eckode\hooks\assets;

use Eckode\utils\{
	enqueue,
	boot,
	rest,
	menu
};

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function enqueue(): void
{
	enqueue\script('index');
	wp_localize_script('index', 'Eckode', [
		'config'  => [
			'endpoint' => rest_url('wp/v2'),
			'nonces' => [
				'rest' => wp_create_nonce('wp_rest'),
			],
			'posts_per_page' => (int) get_option('posts_per_page', 9),
		],
		'static' => [
			'menus' => menu\get_json(),
		],
		'rest_bases' => rest\map_bases(),
		'boot' 		 => boot\get_json(),
	]);
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue');

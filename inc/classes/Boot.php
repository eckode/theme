<?php

/**
 * Boot object
 * Above the fold
 */

namespace Eckode;

use Eckode\Rest;

use WP_Post_Type, 
	WP_Term, 
	WP_Post;

class Boot
{

	/**
	 * Default arguments to pass to boot query
	 * 
	 * @var array $default_args
	 */
	private $default_args = [
		'posts_per_page' => 10
	];

	/**
	 * Boot construstor
	 * 
	 * @param array $args Query args to pass to Query class
	 * @return void 
	 */
	function __construct($args = [])
	{
		$this->default_args = wp_parse_args($args, $this->default_args);
		$this->rest_query = new Rest();
	}

	public function home(array $initial_object_type): array
	{
		$initial_object_type['props'] = $this->rest_query->query([
			'posts_per_page'      => $this->default_args['posts_per_page'],
			'ignore_sticky_posts' => 1
		]);
		return $initial_object_type;
	}

	public function post_type_archive(array $initial_object_type, string $post_type): array
	{
		$initial_object_type['props'] = $this->rest_query->query([
			'posts_per_page' => $this->default_args['posts_per_page'],
			'post_type'      => $post_type
		]);
		return $initial_object_type;
	}

	public function taxonomy(array $initial_object_type, WP_Term $item): array
	{
		$initial_object_type['props'] = $this->rest_query->query([
			'posts_per_page' => $this->default_args['posts_per_page'],
			'tax_query'      => [
				[
					'taxonomy'         => $item->taxonomy,
					'field'            => 'id',
					'terms'            => $item->term_id,
					'include_children' => false
				]
			]
		]);
		return $initial_object_type;
	}

	public function post_type(array $initial_object_type, WP_Post $item): array
	{

		return $initial_object_type;
	}
}

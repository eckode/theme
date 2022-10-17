<?php

/**
 * REST
 */

namespace Eckode;

use WP_Post, WP_REST_Request, WP_REST_Response, WP_Term, WP_Post_Type, WP_Query;

use function Eckode\utils\rest\map_bases;

/**
 * Class REST
 *
 * @package Eckode\Core
 *
 * @since   0.0.1
 */
class Rest
{

	const HTTP_HEADER_PREFIX = 'X-Eckode';

	protected const POST_TYPES = [
		'post',
		'page',
		'entry',
		'product'
	];

	protected const TAXONOMIES = [
		'category',
		'post_tag',
		'entry-category'
	];

	protected static string $date_format;

	protected static array $rest_routes;

	/**
	 * REST constructor.
	 */
	function __construct()
	{

		static::$date_format ??= get_option('date_format');

		// Call once across all instantiations
		static::$rest_routes ??= map_bases();

		/** Loop over post types and bind rest_prepare_{context} action */
		foreach (apply_filters(__CLASS__ . '\prepare_post_type', static::POST_TYPES) as $type) {
			add_action('rest_prepare_' . $type, [$this, 'intercept_rest_prepare'], 100, 3);
		}
		/** Loop over taxonomies and bind rest_prepare_{context} action */
		foreach (apply_filters(__CLASS__ . '\prepare_taxonomy', static::TAXONOMIES) as $tax) {
			add_action('rest_prepare_' . $tax, [$this, 'intercept_rest_prepare'], 100, 3);
		}

		// Post type archives
		add_action('rest_prepare_post_type', [$this, 'intercept_rest_prepare'], 100, 3);
	}

	/**
	 * Intercept
	 *
	 * Proxy method to all rest_prepare_* actions
	 *
	 * @param {WP_REST_Response} $data
	 * @param {WP_Post | WP_term | WP_Post_Type} $item
	 * @param {WP_REST_Request} $request
	 *
	 * @return  WP_REST_Response
	 *
	 * @since   0.0.1
	 */
	public function intercept_rest_prepare(WP_REST_Response $data, $item, WP_REST_Request $request): WP_REST_Response
	{

		/** @var \WP_REST_Request $params */
		$params = $request->get_params();

		/** Ensure we only proceed if Eckode Request is set */
		if (!isset($params['ecko'])) {
			return $data;
		}

		foreach(array_keys($data->get_links()) as $key) {
            $data->remove_link($key);
        }

		/** @var string $current_action */
		$current_action = str_replace('rest_prepare_', '', current_action());
		/** If action is rest_prepare_{$post_type} */
		if (in_array($current_action, static::POST_TYPES, true)) {

			//Get post, reduce payload of archives
			return $this->prepare_post_type($data, $item, $request);
		}
		/** If action is rest_prepare_{$taxonomy} */
		if (in_array($current_action, static::TAXONOMIES, true)) {
			return $this->prepare_taxonomy_archive($data, $item, $request);
		}
		/** If action is rest_prepare_post_type */
		if ('post_type' === $current_action) {
			return $this->prepare_post_type_archive($data, $item, $request);
		}

		/** Remove _links */
		$thing = '';
		return $data;
	}

	/**
	 * Post Type
	 *
	 * Restructure all single post type objects to Eckode structure.
	 * 
	 * @param WP_REST_Response $data WP REST Response object
	 * @param WP_Post $post WP Post object
	 * @param WP_REST_Request $request WP REST Request object
	 * 
	 * @return WP_REST_Response 
	 */
	public function prepare_post_type(WP_REST_RESPONSE $data, WP_Post $post, WP_REST_Request $request)
	{
		$data->data = Unify::configure($data->data, true)
			->add_context('single', $data->data['type'])
			->add_props([
				'date',
				'excerpt',
				'comments',
				'image',
			])
			->map();

		return $data;
	}

	/**
	 * Taxonomy Archive
	 *
	 * Restructure all /wp/v2/{taxonomy} requests to Eckode structure.
	 *
	 * @param WP_REST_Response $data 
	 * @param WP_Term $item 
	 * @param WP_REST_Request $request
	 * 
	 * @return WP_REST_Response 
	 */
	protected function prepare_taxonomy_archive(WP_REST_RESPONSE $data, WP_Term $item, WP_REST_Request $request)
	{

		$data->data['props'] = static::query([
			'tax_query'      => [
				[
					'taxonomy'         => $item->taxonomy,
					'field'            => 'id',
					'terms'            => $item->term_id,
					'include_children' => false
				]
			],
			'posts_per_page' => get_option('posts_per_page')
		]);

		global $wp_taxonomies;
		if (isset($wp_taxonomies[$item->taxonomy])) {
			$data->data['post_type'] = $wp_taxonomies[$item->taxonomy]->object_type[0];
		}

		return $data;
	}

	/**
	 * Post Type Archive
	 *
	 * Restructure all /wp/v2/types/* requests to Eckode structure.
	 * 
	 * @param WP_REST_Response $data 
	 * @param WP_Post_Type $item 
	 * @param WP_REST_Request $request 
	 * 
	 * @return WP_REST_Response 
	 */
	protected function prepare_post_type_archive(WP_REST_Response $data, WP_Post_Type $item, WP_REST_Request $request)
	{

		/** @var \WP_REST_Request $params */
		$params = $request->get_params();

		static::query($data->data, [
			'post_type'      => $params['type'],
			'posts_per_page' => get_option('posts_per_page')
		]);

		return $data;
	}

	/**
	 * Query
	 *
	 * Method for performing all WP_Query's
	 *
	 * @param array $_data 
	 * @param array $args 
	 * 
	 * @return array 
	 */
	public function query(array $args): array
	{
		$data = [];

		/** @var \WP_Query $query */
		$query = new WP_Query($args);
		if (!$query->have_posts()) {
			wp_reset_postdata();
			return $data;
		} else {
			$data['count']  = $query->post_count;
			$data['found'] = (int) $query->found_posts;
			foreach ($query->posts as $p) {
				$data[$p->post_type][] = Unify::configure($p)->add_context('single', $p->post_type)->add_props(['excerpt'])->map();
			}
			wp_reset_postdata();
		}

		return $data;
	}

	/**
	 * Format & Guarantee responses
	 * 
	 * @param mixed $code 
	 * @param string $message 
	 * @param string $header_key 
	 * @param int $status_code 
	 * 
	 * @return WP_REST_Response 
	 */
	protected static function format_response($code = null, $message = '', $header_key = '', $status_code = 200)
	{

		$message = '' !== $message ? $message : __('No message provided', 'vuew');
		$response = new WP_REST_Response([
			'data'    => [
				'status' => $status_code
			],
			'code'    => $code,
			'message' => $message,
		]);

		$header_key = '' === $header_key ? $header_key : '-' . $header_key;

		$response->set_status($status_code);
		$response->header(self::HTTP_HEADER_PREFIX . $header_key, $message);

		return $response;
	}
}

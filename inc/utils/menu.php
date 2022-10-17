<?php

declare(strict_types=1);

/**
 * Menu utilities.
 */

namespace Eckode\utils\menu;

use Eckode\Unify;

// Constants
use const Eckode\{
	ALLOWED_MENUS,
	CONTENT_MODEL
};

/**
 * Init hook
 * 
 * - Register menu locations
 */
function init(): void
{
	register_nav_menus(
		[
			'footer' => __('Footer', 'eckode'),
			'main'   => __('Main', 'eckode'),
		]
	);
}

function get_json(): array
{
	$model = wp_parse_args([
		'context' 	   	   => 'single',
		'context_value' 	   => 'nav_menu_item',
		// Temporary
		'menu_item_parent' => 0,
		// Props
		'props' 	   => [
			'id' 		 => 0,
			'breadcrumb' => [],
			'children' 	 => [],
			'label' 	 => '',
			'parent_id'  => 0,
		]
	], CONTENT_MODEL);

	$found_menus 		= [];
	$page_on_front 		= (int) get_option('page_on_front');
	$stored_locations 	= array_keys(get_nav_menu_locations());
	$filter_callback 	= function ($value, $key) {
		if (in_array($key, ['menu_item_parent', 'id'], true) && 0 === $value) {
			return true;
		}
		return !!$value;
	};

	foreach ($stored_locations as $location) {
		if (!in_array($location, ALLOWED_MENUS, true)) {
			continue;
		}
		// Exit if no saved menu exists for $location
		if (false === ($menu = wp_get_nav_menu_items($location))) {
			continue;
		}

		$found_menus[$location] = [];
		foreach ($menu as $menu_item) {

			// @todo Add support for external links
			$path = trim(wp_make_link_relative($menu_item->url), '/');
			$path = '' === $path ? '/' : $path;

			$new_menu_item = Unify::configure($menu_item)
				->add_context('single', 'nav_menu_item')
				->override_mapping(['title' => 'title'])
				->add_props(['excerpt'])
				->additional_props(function ($unify) use ($path, $page_on_front) {
					$props = [
						/** Attributes */
						'title' 	 	=> $unify['post_excerpt'],
						'target' 	 	=> $unify['target'],
						'classes' 	 	=> !empty($unify['classes'][0]) ? $unify['classes'] : [],
						/** Target props, these are specific to "nav_menu_item" */
						'id' 		 	=> (int) $unify['object_id'],
						'context' 	   	=> 'post_type' === $unify['type'] ? 'single' : $unify['type'],
						'context_value'	=> $unify['object'],
						'breadcrumb' 	=> '' !== $path ? explode('/', $path) : null,
					];
					// Special treatment for custom menu items.
					if ($unify['type'] === 'custom') {
						// Home
						if ('/' === $path) {
							if ($page_on_front > 0) {
								$props['id'] 		   = $page_on_front;
								$props['context'] 	   = 'single';
								$props['context_value'] = 'page';
							} else {
								$props['context'] 	   = 'home';
								$props['context_value'] = 'latest_posts';
							}
						} else {
							$url_to_check = $unify['url'];
							$site_url = untrailingslashit(home_url());

							// Test if valid url i.e. /path
							if (!filter_var($url_to_check, FILTER_VALIDATE_URL)) {
								$url_to_check = trailingslashit($site_url) . $path;
							}

							// External?
							if (false === (strpos($url_to_check, $site_url))) {
								$props['context'] 	   = 'external';
								$props['context_value'] = $unify['url'];
							} else {
								$post_id = url_to_postid($url_to_check);
								if ($post_id > 0) {
									$post = get_post($post_id);
									$props['id'] 		   = $post->ID;
									$props['context'] 	   = 'single';
									$props['context_value'] = $post->post_type;
								} else {
									$props['context'] 	   = 'not_found';
									$props['context_value'] = '';
								}
							}
						}
					}
					return $props;
				})
				->map();

			$new_menu_item['path'] = $path;

			// This property is removed in tree()
			$new_menu_item['menu_item_parent'] = (int) $menu_item->menu_item_parent;

			/** Filter both arrays to optimize outputs */
			$new_menu_item['props'] = array_filter($new_menu_item['props'], $filter_callback, ARRAY_FILTER_USE_BOTH);
			$found_menus[$location][] = array_filter($new_menu_item, $filter_callback, ARRAY_FILTER_USE_BOTH);
		}

		$found_menus[$location] = tree($found_menus[$location]);
	}

	return $found_menus;
}

/**
 * 
 * @link https://stackoverflow.com/a/29384894/1954596
 * 
 * @param array $elements 
 * @param int $parentId 
 * @return array 
 */
function tree(array $elements, int $parentId = 0): array
{
	$branch = [];
	foreach ($elements as $element) {
		if ($element['menu_item_parent'] === $parentId) {
			$children = tree($elements, $element['id']);
			unset($element['menu_item_parent']);
			if ($children) {
				$element['children'] = $children;
			}
			$branch[] = $element;
		}
	}
	return $branch;
}

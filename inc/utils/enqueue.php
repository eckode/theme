<?php
/**
 * Enqueue constants and helpers.
 *
 * @package Eckode
 */

namespace Eckode\utils\enqueue;

use const Eckode\{
	PATH,
	URL,
	VER
};

define(
	__NAMESPACE__ . '\WEBPACK_DEV_SERVER',
	[
		'running' => SCRIPT_DEBUG && file_exists( PATH . '.wds' ),
		'url'     => URL . 'build/',
	]
);

define(
	__NAMESPACE__ . '\ASSETS_URL',
	SCRIPT_DEBUG ?
		( WEBPACK_DEV_SERVER['running'] ? WEBPACK_DEV_SERVER['url'] : URL . 'build/' ) :
		URL . 'dist/'
);

/**
 * Asset Path, small abstraction to allow
 * manipulation of asset paths that potentially
 * derive from Webpack-Dev-Server.
 *
 * @param string $name Used to reference handle and file name.
 * @param string $ext Used to establish asset type.
 * @param bool   $path Should this method return asset location as path.
 *
 * @return string
 */
function path( string $name, string $ext = 'js', bool $path = false ): string {
	$asset_loc = $path ? PATH . 'build/' : URL . 'build/';
	if ( WEBPACK_DEV_SERVER['running'] ) {
		$asset_loc = WEBPACK_DEV_SERVER['url'];
	}
	$extension = ( ! SCRIPT_DEBUG && ! WEBPACK_DEV_SERVER['running'] ? '.min.' : '.' ) . $ext;

	return $asset_loc . $name . $extension;
}

/**
 * WP Enqueue Style wrapper.
 *
 * @param string $handle wp_enqueue_style handle.
 * @param array  $dependencies An array of wp_enqueue_style dependencies.
 * @param string $media <link> tag attribute media.
 */
function style( string $handle, array $dependencies = [], string $media = '' ): void {
	/** If WDS is running, CSS is injected via it's JS counterpart */
	if ( WEBPACK_DEV_SERVER['running'] ) {
		script( $handle );
		return;
	}
	wp_enqueue_style(
		$handle,
		path( $handle, 'css' ),
		$dependencies,
		VER,
		$media
	);
}

/**
 * WP Enqueue Script wrapper.
 *
 * @param string $handle wp_enqueue_script handle.
 * @param array  $dependencies An array of wp_enqueue_script dependencies.
 * @param bool   $footer wp_enqueue_script footer parameter.
 */
function script( string $handle, array $dependencies = [], bool $footer = true ): void {
	wp_enqueue_script(
		$handle,
		path( $handle ),
		$dependencies,
		VER,
		$footer
	);
}

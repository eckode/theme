<?php
/**
 * The template for displaying the header.
 *
 * @package Eckode
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="theme-color" content="#d23226" />
		<?php wp_head(); ?>

		<link rel="stylesheet" href="https://jaredrethman.com/wp-content/themes/eckode/build/index.css" />	
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
        <div id="root"></div>

<?php

namespace Eckode\utils\structures;

/**
 * List chunk pluck
 *
 * Like wp_list_pluck() but with the ability to match chunks of data using a index array of items to
 * pluck from associative array
 *
 * @param   $list
 * @param   array $keys_to_plucks
 *
 * @return  mixed
 *
 * @since   0.0.1
 */
function list_chunk_pluck( $list, $keys_to_plucks = [] ){

	/** @var array $plucks */
	$plucks = $reduced_fields = [];

	//Negate early
	if( ! isset( $keys_to_plucks[ 0 ] ) ){
		return $list;
	}

	//Make keys to pluck an associative array
	foreach( $keys_to_plucks as $pluck ){
		$plucks[ $pluck ] = null;
	}

	//Match up the items from required
	//keys and intersect with object|array passed.
	foreach ( $list as $k => $value ) {
		/** Added reduced array(s) to returned menus */
		$reduced_fields[ $k ] = array_intersect_key( (array) $value, $plucks );
	}

	return $reduced_fields;

}
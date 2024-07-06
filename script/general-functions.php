<?php


function data_setup( $abgvzs, &$data, $vz_in = 'all' ) {
	$count = count( $abgvzs );
	if ( $vz_in == 'all' ) {
		if ( $count > 0 ) {
			foreach ( $abgvzs as $vz => $url ) {
				vz_setup(
					$vz,
					$url,
					$data
				);
			}
		} elseif ( $count == 0 ) {
			die( 'no list' );
		}
	} else {
		vz_setup(
			$vz_in,
			$abgvzs[ $vz_in ],
			$data
		);
	}
	return $data;
}

function vz_setup( $vz, $url, &$data ) {
	$data[ 'lists' ][ $vz ] = array();

	$baseurl = return_base_url( $url );

	$src_file_name = 'abgeordnetenliste';
	$src_path = 'tmp/'. $vz . '/src/';
	$src_file = $src_path . $src_file_name;

	if ( ! file_exists( $src_file ) ) {
		$src_url = file_get_contents( $url );
		file_put_contents(
			$src_file,
			$src_url
		);
	}

	$src = file_get_contents( $src_file );
	//$src = file_get_contents( $url );

	$domdoc = initialize_domdoc( $src );

	$name = domdoc_unique_element_by_tag( $domdoc, 'title' );

	$data[ 'lists' ][ $vz ] = array(
		'name'    => $name,
		'url'     => $url,
		'baseurl' => $baseurl,
		'src'     => $src,
		'domdoc'  => $domdoc,
	);

	return $data;
}

function setup_current_arr_ele( $vz, &$data ) {
	$vz_arr = array();
	$data[ 'current' ] = array();
	$vz_arr[ 'vz' ] = $vz;
	$current = array_merge( $vz_arr, $data[ 'lists' ][ $vz ] );
	$data[ 'current' ] = $current;
}

function currently_processing_msg( $data ) {
	echo PHP_EOL;
	echo 'Attempting to parse »' . $data[ 'current' ][ 'vz' ] . '«:' . PHP_EOL;
	echo '→ ' . $data[ 'current' ][ 'name' ] . PHP_EOL;
	echo '→ ' . $data[ 'current' ][ 'url' ] . PHP_EOL;
	echo PHP_EOL;
}


function array_values_recursive( array $array ) {
	$array = array_values( $array );
	for ( $i = 0, $x = count( $array ); $i < $x; ++$i ) {
		if ( is_array( $array[$i] ) ) {
			$array[$i] = array_values_recursive( $array[$i] );
		}
	}
	return $array;
}


function initialize_domdoc( $src, $err_msg = false ) {
	$domdoc = new DOMDocument();
	if ( ! $err_msg ) {
		libxml_use_internal_errors( true );
	}
	$domdoc->loadHTML( $src );
	return $domdoc;
}

function domdoc_unique_element_by_tag( $domdoc, $element ) {
	$element_dom = $domdoc->getElementsByTagName( $element );
	$unique = (bool) $element_dom->length;
	if ( $unique ) {
		foreach ( $element_dom as $element ) {
			$result = $element->nodeValue;
		}
	} else {
		die( 'not unique' );
	}
	return $result;
}

function domxpath_node_to_array( $node ) {
	$array = false;

	if ( $node->hasAttributes() ) {
		foreach ( $node->attributes as $attr ) {
			$array[ $attr->nodeName ] = $attr->nodeValue;
		}
	}

	if ( $node->hasChildNodes() ) {
		if ( $node->childNodes->length == 1 ) {
			$array[ $node->firstChild->nodeName ] = $node->firstChild->nodeValue;
		} else {
			foreach ( $node->childNodes as $childNode ) {
				if ( $childNode->nodeType != XML_TEXT_NODE ) {
					$array[ $childNode->nodeName ][] = domxpath_node_to_array( $childNode );
				}
			}
		}
	}
	return $array;
}


function get_element_by_tag_name_unique( $node, $tag_name, $type = '' ) {
	$element = $node->getElementsByTagName( $tag_name );
	$element_exists = $element->length;
	if ( $element_exists ) {
		foreach ( $element as $ele ) {
			if ( empty( $type ) ) {
				$result = $ele->nodeValue;
			} elseif ( ! empty( $type ) ) {
				$result = $ele->getAttribute( $type );
			}
		}
	} else {
		$result = false;
	}

	return $result;
}

function get_element_by_tag_name_item_nr( $node, $tag_name, $nr = '0', $type = '' ) {
	$element = $node->getElementsByTagName( $tag_name );
	$element_item = $element->item( $nr );
	$element_exists = count( $element_item );
	if ( $element_exists ) {
		if ( empty( $type ) ) {
			$result = $element_item->nodeValue;
		} elseif ( ! empty( $type ) ) {
			$result = $element_item->getAttribute( $type );
		}
	} else {
		$result = false;
	}
	return $result;
}

function array_trim( &$array ) {
	$array = array_map( 'trim', $array );
}

function return_base_url( $url ) {
	$parsed = parse_url( $url );
	$base = $parsed[ 'scheme' ] . '://' . $parsed[ 'host' ];
	return $base;
}

function mailto_remover( $href ) {
	$mail = preg_replace( '/^mailto:/', '', $href );
	return $mail;
}

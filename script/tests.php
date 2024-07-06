<?php

include 'variable-profil-url-daten.php';

//file_put_contents( 'tmp/test/default_profil_url_arr_struc.php', var_export( $profil_url_daten, true ) );

$profil_url_daten_obj = new ProfilURLDaten();

//print_r( $profil_url_daten_obj );
$profil_url_daten_obj->pud_dump();
die;

function af_cb_empty_all_array_values(&$v, $k) {
	$v = '';
}

function array_merge_recursive_distinct ( array &$array1, array &$array2 ) {
	$merged = $array1;

	foreach ( $array2 as $key => &$value ) {
		if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
		{
			$merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
		}
		else
		{
			$merged [$key] = $value;
		}
	}

	return $merged;
}

function array_of_arrays_merge_recusrsive_distinct( &$array_to_analyse ) {
	foreach ( $array_to_analyse as $key => $value ) {
		if ( $key == 0 ) continue;
		$current_key = $key;
		$last_key = $key - 1;
		$current_array = $array_to_analyse[ $current_key ];
		$last_array = $array_to_analyse[ $last_key ];
		if ( $key == 1 ) {
			$merge_array = $last_array;
		} else {
			$merge_array = $new_distinct_array;
		}
		$new_distinct_array = array_merge_recursive_distinct( $current_array, $merge_array );
		unset( $array_to_analyse[ $last_key ] );
	}
	return $new_distinct_array;
}

function bt_xml_distinct_array_mitgliedschaften_korrektor( &$bt_xml_array ) {
	foreach (
		$bt_xml_array
		[ 'btpXMLData' ]
		[ 'mdb' ]
		[ 'mdbInfo' ]
		[ 'mdbMitgliedschaften' ]
		as $key => $value
	) {
		//print_r($key.PHP_EOL);

		$mdb_gremien_exep = array(
			'mdbStellvVorsitzSonstigesGremium',
			'mdbVorsitzSonstigesGremium'
		);

		if ( empty( $value ) ) continue;

		foreach ( $value as $ke => $va ) {
			//print_r($ke.PHP_EOL);

			if ( $ke == '@attributes' ) continue;

			foreach ( $va as $k => $v) {
				//print_r($k.PHP_EOL);

				if (
					! is_int( $k )
					&& ! in_array(
						$ke,
						$mdb_gremien_exep
					)
				) {
					unset( $va[ $k ] );
				}
				if ( $k > 0 ) {
					unset( $va[ $k ] );
				}

				//print_r($k.PHP_EOL);
			}
			//print_r($va);

			$bt_xml_array[ 'btpXMLData' ][ 'mdb' ][ 'mdbInfo' ][ 'mdbMitgliedschaften' ][ $key ][ $ke ] = $va;

		}
	}
}


$tmp = json_decode(file_get_contents('tmp/bt_mdb_db.json'), true);
//print_r(count($tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ]).PHP_EOL);
//print_r($tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ 629 ][ 'btpXMLData' ][ 'mdb' ][ 'mdbInfo' ][ 'mdbMitgliedschaften' ][ 'mdbOrdentlichesMitgliedGremien' ]);die;

$cdu_tmp = json_decode(file_get_contents('tmp/cdu_mdb_db.json'), true);
$spd_tmp = json_decode(file_get_contents('tmp/spd_mdb_db.json'), true);
$linke_tmp = json_decode(file_get_contents('tmp/linke_mdb_db.json'), true);
$gruene_tmp = json_decode(file_get_contents('tmp/gruene_mdb_db.json'), true);

$mdbs_array_tmp = $tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ];
//print_r( $mdbs_array_tmp );die;
//array_walk_recursive( $mdbs_array_tmp, 'af_cb_empty_all_array_values' );
//print_r( $mdbs_array_tmp );die;
//print_r(array_keys($mdbs_array_tmp));die;


// $cdu_mdb_tmp = $cdu_tmp[0];
// $spd_mdb_tmp = $spd_tmp[0];
// $linke_mdb_tmp = $linke_tmp[0];
// $gruene_mdb_tmp = $gruene_tmp[0];

// array_walk_recursive( $cdu_mdb_tmp, 'af_cb_empty_all_array_values' );
// array_walk_recursive( $spd_mdb_tmp, 'af_cb_empty_all_array_values' );
// array_walk_recursive( $linke_mdb_tmp, 'af_cb_empty_all_array_values' );
// array_walk_recursive( $gruene_mdb_tmp, 'af_cb_empty_all_array_values' );
// file_put_contents( 'tmp/test/cdu_arr_struc.php', var_export( $cdu_mdb_tmp, true ) );
// file_put_contents( 'tmp/test/spd_arr_struc.php', var_export( $cdu_mdb_tmp, true ) );
// file_put_contents( 'tmp/test/linke_arr_struc.php', var_export( $cdu_mdb_tmp, true ) );
// file_put_contents( 'tmp/test/gruene_arr_struc.php', var_export( $cdu_mdb_tmp, true ) );
// //print_r( $cdu_mdb_tmp );
// die;

//$alle_parteien_url_tmp = $cdu_tmp + $spd_tmp + $linke_tmp + $gruene_tmp;
$alle_parteien_url_tmp = array_merge( $cdu_tmp, $spd_tmp, $linke_tmp, $gruene_tmp );
//print_r(count($alle_parteien_url_tmp).PHP_EOL);
//print_r($alle_parteien_url_tmp);


// PF URL
$array_to_analyse = $spd_tmp;

array_walk_recursive( $array_to_analyse, 'af_cb_empty_all_array_values' );

$new_distinct_array = array_of_arrays_merge_recusrsive_distinct( $array_to_analyse );
//print_r( $new_distinct_array ); die;

//file_put_contents( 'tmp/test/url_misc_arr_struc.php', var_export( $new_distinct_array, true ) );
print_r($new_distinct_array);die;

// BT XML
$array_to_analyse = $mdbs_array_tmp;

array_walk_recursive( $array_to_analyse, 'af_cb_empty_all_array_values' );

$new_distinct_array = array_of_arrays_merge_recusrsive_distinct( $array_to_analyse );
//print_r( $new_distinct_array ); die;

bt_xml_distinct_array_mitgliedschaften_korrektor( $new_distinct_array );
//print_r($new_distinct_array[ 'btpXMLData' ][ 'mdb' ][ 'mdbInfo' ][ 'mdbMitgliedschaften' ]);die;
//file_put_contents( 'tmp/test/xml_arr_struc.php', var_export( $new_distinct_array, true ) );
print_r($new_distinct_array);die;




die;
function recursive_array_search($needle,&$haystack) {
	foreach($haystack as $key=>$value) {
		$current_key=$key;
		if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
			return $current_key;
		}
		unset( $haystack[ $key ] );
	}
	return false;
}

function recursive_array_search_two( $needle_one, $needle_two, $haystack ) {
	$ret_ind = false;
	while ( count( $haystack ) > 0 ) {
		$needle_one_res = recursive_array_search( $needle_one, $haystack );

		if ( $needle_one_res == false ) {
			return false;
		} else {
			$needle_two_res = recursive_array_search( $needle_two, $haystack[ $needle_one_res ] );
		}

		if ( $needle_two_res === 'Name' ) {
			$ret_ind = true;
			break;
		} else {
			$haystack = array_filter( $haystack );
		}
	}

	if ( $ret_ind == true ) {
		return $needle_one_res;
	} else {
		return false;
	}
}

function set_nns_vns_var_for_search( $array, $index, &$nns, &$vns ) {
	$nns = $array[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ $index ]['btpXMLData']['mdb']['mdbInfo']['mdbZuname'];
	//print_r($nns);
	$vns = $array[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ $index ]['btpXMLData']['mdb']['mdbInfo']['mdbVorname'];
	//print_r($vns);
}

$nns = '';
$vns = '';
set_nns_vns_var_for_search( $tmp, 34, $nns, $vns );
print_r( $nns . PHP_EOL );
print_r( $vns . PHP_EOL );
$mfp = recursive_array_search_two( $nns, $vns, $alle_parteien_url_tmp );
print_r( $mfp . PHP_EOL );
//print_r( $alle_parteien_url_tmp[ $mfp ] );
//print_r( $tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ 34 ]);
//die;


foreach ( $tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ] as $k => $v ) {
	if ($k > 0 ) die;

// 	print_r($v);
// 	print_r($k.PHP_EOL);
// 	print_r($v['mdbName']['@value'].PHP_EOL);
	$name = $v['btpXMLData']['mdb']['mdbInfo']['mdbAdelstitel'] . ' ' . $v['btpXMLData']['mdb']['mdbInfo']['mdbZuname'];
// 	print_r($v['btpXMLData']['mdb']['mdbInfo']['mdbAdelstitel'] . ' ' . $v['btpXMLData']['mdb']['mdbInfo']['mdbZuname'].PHP_EOL);
// 	print_r($v['btpXMLData']['mdb']['mdbInfo']['mdbVorname'].PHP_EOL);
// 	print_r($v['btpXMLData']['mdb']['mdbInfo']['mdbPartei'].PHP_EOL);
// 	print_r( $linke_tmp[ recursive_array_search( $name, $linke_tmp ) ] );
	$pfp = $linke_tmp[ recursive_array_search( $name, $linke_tmp ) ];

	$tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ $k ][ 'pfpURLData' ] = $pfp;

	print_r($tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ $k ]);

// 	if (
// 		! empty(
// 			$tmp[ 'mdbUebersicht' ]
// 			[ 'mdbs' ][ 'mdb' ]
// 			[ $k ][ 'btpXMLData' ][ 'mdb' ]
// 			[ 'mdbInfo' ][ 'mdbMitgliedschaften' ]
// 			[ 'mdbStellvVorsitzSonstigeGremien' ]
// 			[ 'mdbStellvVorsitzSonstigesGremium' ]
// 		)
// 	) {
// 		print_r(
// 		$tmp[ 'mdbUebersicht' ]
// 		[ 'mdbs' ][ 'mdb' ]
// 		[ $k ][ 'btpXMLData' ][ 'mdb' ]
// 		[ 'mdbInfo' ][ 'mdbMitgliedschaften' ]
// 		[ 'mdbStellvVorsitzSonstigeGremien' ]
// 		[ 'mdbStellvVorsitzSonstigesGremium' ]
// 		);
// 	}

}

// $names = array_column( $tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ], 'mdbName' );
// $names = array_map(
// 	function ($value) {
// 		return  $value['btpXMLData']['mdb']['mdbInfo']['mdbZuname'] . ' ' . $value['btpXMLData']['mdb']['mdbInfo']['mdbVorname'];
// 	},
// 	$tmp[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ]
// );
// print_r(array_unique($names));

die;
<?php

$db_file_arr = 'tmp/bt_mdb_db';

$bt_domdoc = $data[ 'current' ][ 'domdoc' ];


$xml_url = 'http://www.bundestag.de/xml/mdb/index.xml';

$xml_file_bn = basename( $xml_url );
$xml_file_bn_wo_ext = basename( $xml_file_bn, '.xml' );

$xml_path = 'tmp/bundestag/xml/';
$arr_path = 'tmp/bundestag/array/';
$xml_path_abg = $xml_path . 'abg/';
$arr_path_abg = $arr_path . 'abg/';

$xml_file = $xml_path . $xml_file_bn;
$arr_file = $arr_path . $xml_file_bn_wo_ext;


if ( ! file_exists( $xml_file ) ) {
	$xml_src_url = file_get_contents( $xml_url );
	file_put_contents(
		$xml_file,
		$xml_src_url
	);
}
$xml_src = file_get_contents( $xml_file );


$xml_src_arr = XML2Array::createArray( $xml_src );
if ( ! file_exists( $arr_file ) ) {
	file_put_contents(
		$arr_file,
		var_export( $xml_src_arr, true )
	);
}


//print_r( $txarray[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ] );
foreach ( $xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ] as $key => $abg ) {
	//print_r($key . PHP_EOL );
	//print_r( $abg[ 'mdbInfoXMLURL' ] . PHP_EOL );

	$status = $abg[ 'mdbID' ][ '@attributes' ][ 'status' ];
	if ( $status != 'Aktiv' ) {
		//print_r( $status . PHP_EOL );
		//print_r( $key . PHP_EOL );
		unset( $xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ $key ] );
		continue;
	}

	$abg_xml_url = $abg[ 'mdbInfoXMLURL'];

	$abg_file_bn = basename( $abg_xml_url );
	$abg_file_bn_wo_ext = basename( $abg_file_bn, '.xml' );

	$abg_xml_file = $xml_path_abg . $abg_file_bn;
	$abg_arr_file = $arr_path_abg . $abg_file_bn_wo_ext;

	if ( ! file_exists( $abg_xml_file ) ) {
		file_put_contents(
			$abg_xml_file,
			file_get_contents( $abg_xml_url )
		);
	}

	$abg_xml_src = file_get_contents( $abg_xml_file	);

	$abg_arr_src = XML2Array::createArray($abg_xml_src);
	if ( ! file_exists( $abg_arr_file ) ) {
		file_put_contents(
		$abg_arr_file,
		var_export( $abg_arr_src, true )
		);
	}

	$xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ $key ][ 'btpXMLData' ] = $abg_arr_src;


	/***
	 $voa_box = $profil_xpath->query( '//*[contains( @class, "voa_tab1" )]' );
	 //print_r($voa_box);
	 $voa_arr = array();
	 foreach ( $voa_box as $vb ) {
	 //print_r($vb);
	 //print_r(preg_match('/Stufe ([0-9]+)/i', $vb->nodeValue, $voa_st ));
	 //print_r($hit);
	 preg_match('/Stufe ([0-9]+)/i', $vb->nodeValue, $voa_st );
	 if ( ! empty( $voa_st ) ) {
	 $eml = preg_match('/([0-9]+), Stufe/i', $vb->nodeValue);
	 $mtl = preg_match('/(monatlich), Stufe/i', $vb->nodeValue);
	 $jhr = preg_match('/(jährlich), Stufe/i', $vb->nodeValue);
	 print_r($vb->nodeValue.PHP_EOL);print_r('e:'.$eml);print_r('m:'.$mtl);print_r('j:'.$jhr.PHP_EOL);
	 if ( $eml || $mtl || $jhr ) {
	 if ( $eml ) {
	 $voa_arr['stufen'][ 'eml' ][] = $voa_st[1];
	 } elseif ( $mtl ) {
	 $voa_arr['stufen'][ 'mtl' ][] = $voa_st[1];
	 } elseif ( $jhr ) {
	 $voa_arr['stufen'][ 'jhr' ][] = $voa_st[1];
	 }
	 } else {
	 echo 'nich möglich';
	 }
	 }
	 }
	 $voa_stufen = array(
	 'min' => array(
	 '1' => '1000',
	 '2' => '3501',
	 '3' => '7001',
	 '4' => '15001',
	 '5' => '30001',
	 '6' => '50001',
	 '7' => '75001',
	 '8' => '100001',
	 '9' => '150001',
	 '10' => '250001',
	 ),
	 'max' => array(
	 )
	 );
	 if ( ! empty( $voa_arr ) ) {
	 $voa_min = '0';
	 if ( isset( $voa_arr[ 'stufen' ][ 'eml' ] ) ) {
	 foreach ( $voa_arr[ 'stufen' ][ 'eml' ] as $emlst ) {
	 $voa_min = $voa_min + $voa_stufen[ 'min' ][ $emlst ];
	 }
	 }
	 if ( isset( $voa_arr[ 'stufen' ][ 'mtl' ] ) ) {
	 foreach ( $voa_arr[ 'stufen' ][ 'mtl' ] as $mtlst ) {
	 $voa_min = $voa_min + ( 12 * $voa_stufen[ 'min' ][ $mtlst ]);
	 }
	 }
	 if ( isset( $voa_arr[ 'stufen' ][ 'jhr' ] ) ) {
	 foreach ( $voa_arr[ 'stufen' ][ 'jhr' ] as $jhrst ) {
	 $voa_min = $voa_min + $voa_stufen[ 'min' ][ $jhrst ];
	 }
	 }
	 $voa_arr[ 'min' ] = $voa_min;
	 }
	 /***/

	$bt_mdb_url_data = array();

	$bt_mdb_url_data[] = array(
		'VOA' => '',
	);

	$xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ][ $key ][ 'btpURLData' ] = $bt_mdb_url_data;

}


//print_r( count($xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ]) . PHP_EOL );
array_splice( $xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ], 0 , 0 );
//print_r( count($xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ]) . PHP_EOL );

//krsort( $xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ] );
//print_r( key( $xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ] ) ); die;
// print_r( key( $xml_src_arr[ 'mdbUebersicht' ][ 'mdbs' ][ 'mdb' ] ) ); die;


file_put_contents( $db_file_arr, var_export( $xml_src_arr, true ) );
file_put_contents( $db_file_arr.'.json', json_encode( $xml_src_arr, true ) );
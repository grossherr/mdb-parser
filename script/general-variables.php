<?php

// Daten Container
$data = array();

$data[ 'args' ] = array();

// Arguments $argv
$data[ 'args' ][ 'script' ][ 'name' ] = $argv[ 0 ];
if ( ! isset( $argv[ 1 ] ) ) {
	$data[ 'args' ][ 'script' ][ 'vz' ] = 'spd';
} else {
	$data[ 'args' ][ 'script' ][ 'vz' ] = $argv[ 1 ];
}

// Abgeordnetenverzeichnisse
$abg_vzs_std = array(
	'bundestag' => 'http://www.bundestag.de/bundestag/abgeordnete18/alphabet',
	'spd'       => 'http://www.spdfraktion.de/abgeordnete/all?view=list',
	'gruene'    => 'http://www.gruene-bundestag.de/fraktion/abgeordnete_ID_4389869.html',
	'linke'     => 'http://www.linksfraktion.de/abgeordnete/',
	'cdu'       => 'https://www.cducsu.de/abgeordnete',
);

$data[ 'args' ][ 'abgvzs' ] = $abg_vzs_std;
$abgvzs = $data[ 'args' ][ 'abgvzs' ];

$vz = $data[ 'args' ][ 'script' ][ 'vz' ];

data_setup( $abgvzs, $data, $vz );



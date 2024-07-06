<?php

/**
 * MdB Parser
 */

/**
 * Variables, Functions
 */


if ( $argv[1] == 'test' ) {
	include 'tests.php';
}


include 'general-functions.php';

include 'tmp-functions.php';

include 'general-variables.php';

include 'variable-profil-url-daten.php';



setup_current_arr_ele( $vz, $data );
currently_processing_msg( $data );

switch ( $vz ) {
	case 'bundestag':
		include 'parser-bundestag.php';
		break;
	case 'spd':
		include 'parser-spd.php';
		break;
	case 'gruene':
		include 'parser-gruene.php';
		break;
	case 'linke':
		include 'parser-linke.php';
		break;
	case 'cdu':
		include 'parser-cdu.php';
		break;
	case 'all':
		//include 'script/parser-bundestag.php';
		//include 'script/parser-spd.php';
		//include 'script/parser-gruene.php';
		break;
	default:
		echo PHP_EOL . 'no match' . PHP_EOL;
		break;
}

//print_r($data);

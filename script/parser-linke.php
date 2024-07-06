<?php

// $profil_url_daten_obj = new ProfilURLDaten();
// //print_r( $profil_url_daten_obj );
// $profil_url_daten_obj->pud_dump();
// die;

$mdb_db = array();
$mdb_db_tmp = array();

$mdb_tmp = array();

$db_file_arr = 'tmp/linke_mdb_db';

$baseurl = $data[ 'current' ][ 'baseurl' ];

$mdb_lst_pg_dd = $data[ 'current' ][ 'domdoc' ];


$mdb_lst_xp = new DOMXpath( $mdb_lst_pg_dd );
$mdb_lst_divs = $mdb_lst_xp->query(
	'//div[contains( @class, "listenElement" )]'
);
//print_r($mdb_lst_divs);

foreach ( $mdb_lst_divs as $mdb_lst_div ) {
	//print_r($mdb_lst_div);

	$profil_url_daten_obj = new ProfilURLDaten();

	$mdb_data_arr = $profil_url_daten_obj->profil_url_daten;


	$name_from_list = $mdb_lst_xp->query(
		'.//a[1]',
		$mdb_lst_div
	);
	// 	print_r($name_from_list);

	foreach ( $name_from_list as $nfl ) {
		//print_r( $nfl->getAttribute( 'title' ) . PHP_EOL );
		//print_r( $nfl->getAttribute( 'href' ) . PHP_EOL );

		$name_arr = array();

		$nfl_val = $nfl->getAttribute( 'title' );

		$mdb_pl_href = $nfl->getAttribute( 'href' );
		//print_r( $mdb_pl_href . PHP_EOL );

		// Clean Up Whitespaces
		$nfl_str = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $nfl_val );
		//print_r($nfl_str . PHP_EOL );

		$nfl_str = preg_replace( '/([A-Z])(\.)/', '$1', $nfl_str );
		//print_r($nfl_str . PHP_EOL );



		// Separate Title from Name
		$nfl_ti_fn_nn_arr = explode( '.', $nfl_str );
		$nfl_fn_nn_str = trim( array_pop( $nfl_ti_fn_nn_arr ) );


		// Create Array with NN FN
		$nfl_fn_nn_arr = explode( ' ', $nfl_fn_nn_str );
		//print_r($nfl_fn_nn_arr);

		if ( count($nfl_fn_nn_arr) > 2 ) {

			//print_r($nfl_fn_nn_arr);

			if ( preg_match( '/\b(\sde|Graf|van\s)\b/', $nfl_str ) ) {
				//print_r(preg_match( '/\b(\sde|Graf\s)\b/', $nfl_str ));
				$mdb_nn = trim( $nfl_fn_nn_arr[1] . ' ' . $nfl_fn_nn_arr[2] );
				$mdb_vn = trim( $nfl_fn_nn_arr[0] );
			} elseif ( preg_match( '/\b(\svon\s)\b/', $nfl_str ) ) {
				//print_r(preg_match( '/\b(\svon\s)\b/', $nfl_str ));
				if ( preg_match( '/\b(\svon der\s|\sFreiherr von\s)\b/', $nfl_str ) ) {
					$mdb_nn = trim( $nfl_fn_nn_arr[1] . ' ' . $nfl_fn_nn_arr[2] . ' ' . $nfl_fn_nn_arr[3] );
					$mdb_vn = trim( $nfl_fn_nn_arr[0] );
				} elseif ( preg_match( '/\b(\svon\s)\b/', $nfl_str ) ) {
					$mdb_nn = trim( $nfl_fn_nn_arr[1] . ' ' . $nfl_fn_nn_arr[2] );
					$mdb_vn = trim( $nfl_fn_nn_arr[0] );
				}
			} else {
				$mdb_nn = trim( $nfl_fn_nn_arr[2] );
				if ( strlen( $nfl_fn_nn_arr[1] ) == 1 ) {
					$mdb_vn = trim( $nfl_fn_nn_arr[0] . ' ' . $nfl_fn_nn_arr[1] . '.' );
				} else {
					$mdb_vn = trim( $nfl_fn_nn_arr[0] . ' ' . $nfl_fn_nn_arr[1] );
				}
			}

		} else {
			$mdb_nn = trim( $nfl_fn_nn_arr[1] );
			$mdb_vn = trim( $nfl_fn_nn_arr[0] );
		}

		//print_r( $mdb_vn . ' ' . $mdb_nn . PHP_EOL );

		$nfl_ti_arr = $nfl_ti_fn_nn_arr;
		$mdb_ti = '';
		if ( ! empty( $nfl_ti_arr ) ) {
			foreach ( $nfl_ti_arr as $title_ele ) {
				$mdb_ti .= $title_ele . '.';
			}
		}

		$name_arr = array(
			'Vorname' => $mdb_vn,
			'Nachname' => $mdb_nn,
			'Titel' => $mdb_ti,
		);
		//print_r($name_arr);

		$mdb_data_arr[ 'name' ][ 'nachname' ] = $mdb_nn;
		$mdb_data_arr[ 'name' ][ 'vorname' ] = $mdb_vn;
		$mdb_data_arr[ 'name' ][ 'adelszusatz' ] = '';
		$mdb_data_arr[ 'name' ][ 'titel' ] = $mdb_ti;

	}


	$mdb_pl_url = $baseurl . $mdb_pl_href;
	//print_r( $mdb_pl_url . PHP_EOL );

	$src_file_name = basename( pathinfo( $mdb_pl_url )['dirname'] );
	$src_path = 'tmp/linke/src/abg/';
	$src_file = $src_path . $src_file_name;

	if ( ! file_exists( $src_file ) ) {
		$src_url = file_get_contents( $mdb_pl_url );
		file_put_contents(
		$src_file,
		$src_url
		);
	}

	$mdb_pl_src = file_get_contents( $src_file );
	//$mdb_pl_src = file_get_contents( $mdb_pl_url );

	$mdb_pl_dd = initialize_domdoc( $mdb_pl_src );
	$mdb_pl_xp = new DOMXpath( $mdb_pl_dd );



	$linkliste_arr = array();

	$mdb_pl_lk_el = $mdb_pl_xp->query(
		"//div[contains( @class, 'elemTeaser' )]
		//a[contains( @class, 'linkMehr' )]"
	);
	//print_r( $mdb_pl_lk_el );

	foreach ( $mdb_pl_lk_el as $mp_lk_el ) {
		//print_r( $mp_lk_el );
		//$mp_lk_val_str = $mp_lk_el->nodeValue;
		//print_r( $mp_lk_val_str . PHP_EOL );
		$mp_lk_val_href = $mp_lk_el->getAttribute( 'href' );
		//print_r( $mp_lk_val_href . PHP_EOL );
		$linkliste_arr[ 'Homepage' ] = $mp_lk_val_href;

		$verw_arr = $profil_url_daten_obj->verweisbasis;
		$verw_arr[ 'text' ] = 'Persönliche Seite';
		$verw_arr[ 'href' ] = $mp_lk_val_href;

		$mdb_data_arr[ 'verweise' ][ 'persoenlicheseite' ] = $verw_arr;
	}


	$biogra_arr = array();

	$mdb_pl_bg_els = $mdb_pl_xp->query(
		"//div[contains( @class, 'mdbProfil' )]
		//div[contains( @class, 'mdbKopf' )]
		//div[contains( @class, 'mdbDetails' )]"
	);
	//print_r( $mdb_pl_bg_els );

	foreach ( $mdb_pl_bg_els as $mp_bg_el ) {
		//print_r( $mp_bg_el );
		//$mp_bg_val_str = $mp_bg_el->nodeValue;
		//print_r( $mp_bg_val_str . PHP_EOL );

		$mp_bg_el_gebd = $mdb_pl_xp->query(
			'.//span[contains( @class, "mdbGeburtstag" )]',
			$mp_bg_el
		);
		foreach ( $mp_bg_el_gebd as $mp_gebd ) {
			//print_r($mp_gebd);
			$mp_gebd_val = $mp_gebd->nodeValue;

			$biogra_arr[ 'Geburtsdatum' ] = '';
			//print_r( $mp_gebd_val . PHP_EOL );
			//print_r( preg_match( '/(\d{2}(.*?)(\d{4}))/', $mp_gebd_val, $geb_dat_arr ) );
			preg_match( '/(\d{1,2}(.*?)(\d{4}))/', $mp_gebd_val, $geb_dat_arr );
			//print_r( $geb_dat_arr[ 0 ] . PHP_EOL );
			$biogra_arr[ 'Geburtsdatum' ] = $geb_dat_arr[ 0 ];

			$mdb_data_arr[ 'biografie' ][ 'geburtsdatum' ] = $geb_dat_arr[ 0 ];
		}

		$mp_bg_el_ber = $mdb_pl_xp->query(
			'.//span[contains( @class, "mdbBeruf" )]',
			$mp_bg_el
		);
		foreach ( $mp_bg_el_ber as $mp_ber ) {
			//print_r($mp_ber);
			$mp_ber_val = $mp_ber->nodeValue;
			$mp_ber_val = trim(
				preg_replace( '/^Beruf:\s/', '', $mp_ber_val )
			);
			//print_r( $mp_ber_val . PHP_EOL );
			$mp_ber_arr = explode( ',', $mp_ber_val );
			$mp_ber_arr = array_filter( $mp_ber_arr );
			array_trim( $mp_ber_arr );
			//print_r( $mp_ber_arr );
			$biogra_arr[ 'Beruf' ] = $mp_ber_arr;

			$mdb_data_arr[ 'biografie' ][ 'beruf' ] = $mp_ber_arr;
		}

		$mp_bg_el_mdt = $mdb_pl_xp->query(
			'.//div[contains( @class, "mdbMandat" )]',
			$mp_bg_el
		);
		foreach ( $mp_bg_el_mdt as $mp_mdt ) {
			//print_r($mp_mdt);
			$mp_mdt_val = $mp_mdt->nodeValue;
			//print_r( $mp_mdt_val . PHP_EOL );
			if ( preg_match( '/^Landesliste/', $mp_mdt_val, $ll_match ) ) {
				//print_r( $ll_match );
				$mp_mdt_arr = explode( ' ', $mp_mdt_val );
				//print_r( $mp_mdt_arr );
				$biogra_arr[ 'Mandat' ] = array(
					'Typ' => $mp_mdt_arr[ 0 ],
					'Bundesland' => $mp_mdt_arr[ 1 ],
				);
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'typ' ] = $mp_mdt_arr[ 0 ];
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'bundesland' ] = $mp_mdt_arr[ 1 ];
			} elseif ( preg_match( '/^Direktmandat/', $mp_mdt_val, $dm_match ) ) {
				//print_r( $dm_match );
				$mp_mdt_arr = explode( ' ', $mp_mdt_val );
				//print_r( $mp_mdt_arr );
				$biogra_arr[ 'Mandat' ] = array(
					'Typ' => $mp_mdt_arr[ 0 ],
					'wknr' => $mp_mdt_arr[ 3 ],
				);
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'typ' ] = $mp_mdt_arr[ 0 ];
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'wahlkreisnummer' ] = $mp_mdt_arr[ 3 ];
			}
			//$biogra_arr[ 'Mandat' ] = $mp_mdt_arr;
		}

		$mp_bg_el_fkt = $mdb_pl_xp->query(
			'.//div[contains( @class, "mdbFunktion" )]',
			$mp_bg_el
		);
		foreach ( $mp_bg_el_fkt as $mp_fkt ) {
			//print_r($mp_fkt);
			$mp_fkt_val = $mp_fkt->nodeValue;
			//print_r( $mp_fkt_val . PHP_EOL );
			$biogra_arr[ 'Funktion' ] = $mp_fkt_val;

			$mdb_data_arr[ 'politik' ][ 'fraktion' ][ 'funktionen' ] = $mp_fkt_val;
		}

		$mp_bg_el_mgl = $mdb_pl_xp->query(
			'.//span[last()]',
			$mp_bg_el
		);
		foreach ( $mp_bg_el_mgl as $mp_mgl ) {
			//print_r($mp_mgl);
			$mp_mgl_val = $mp_mgl->nodeValue;
			//print_r( $mp_mgl_val . PHP_EOL );
			preg_match_all( '/(\d{1,2})/', $mp_mgl_val, $geb_mgl_arr );
			//print_r( $geb_mgl_arr );
			$biogra_arr[ 'MitgliedWahlperioden' ] = $geb_mgl_arr[ 1 ];

			$mdb_data_arr[ 'politik' ][ 'bundestag' ][ 'legislaturperioden' ] = $geb_mgl_arr[ 1 ];
		}
	}


	//print_r( parse_url( $mdb_pl_url ) );
	//print_r( pathinfo( parse_url( $mdb_pl_url, PHP_URL_PATH ) ) );

	$mdb_pi_tmp = pathinfo( parse_url( $mdb_pl_url, PHP_URL_PATH ) );
	$mdb_pl_path = $mdb_pi_tmp[ 'dirname' ];
	$mdb_cntc_bn = '/kontakt';

	$mdb_cntc_url = $baseurl . $mdb_pl_path . $mdb_cntc_bn;
	//print_r( $mdb_cntc_url );

	$mdb_cntc_src = file_get_contents( $mdb_cntc_url );
	$mdb_cntc_dd = initialize_domdoc( $mdb_cntc_src );
	$mdb_cntc_xp = new DOMXpath( $mdb_cntc_dd );


	$mdb_cntc_adr_arr = array();

	$mdb_cntc_els = $mdb_cntc_xp->query(
		"//div[contains( @class, 'mdbProfil' )]
		//div[contains( @class, 'kontakt' )]"
	);
	//print_r( $mdb_cntc_els );

	foreach ( $mdb_cntc_els as $mdb_cntc_el ) {
		//print_r( $mdb_cntc_el );

		$mdb_cntc_cur_buero = array();

		$kontakt_arr = $profil_url_daten_obj->kontakt;

		$bd_str = '';
		$bd_hnr = '';
		$bd_plz = '';
		$bd_ort = '';
		$bd_mail = '';
		$bd_tel = '';
		$bd_fax = '';

		$mp_wk_el_bn = $mdb_cntc_xp->query(
			'.//h5',
			$mdb_cntc_el
		);
		foreach ( $mp_wk_el_bn as $mp_bn ) {
			//print_r($mp_bn);
			$mp_bn_val = $mp_bn->nodeValue;
			$mp_bn_val = preg_replace( '/,/', '', $mp_bn_val );
			//print_r( preg_match( '/,/', $mp_bn_val ) );
			//print_r( $mp_bn_val . PHP_EOL );

			if ( preg_match( '/^Deutscher Bundestag/', $mp_bn_val ) ) {
				$mdb_cntc_cur_buero[ 'Bezeichnung' ] = $mp_bn_val;
				$kontakt_arr[ 'bezeichnung' ] = $mp_bn_val;
			} elseif ( preg_match( '/^Wahlkreisbüro|Landesgruppe/', $mp_bn_val ) ) {
				$mdb_cntc_cur_buero[ 'Bezeichnung' ] = 'Wahlkreisbüro';
				$kontakt_arr[ 'bezeichnung' ] = 'Wahlkreisbüro';
			} elseif ( preg_match( '/^Bürgerbüro|BürgerInnenbüro/', $mp_bn_val ) ) {
				$mdb_cntc_cur_buero[ 'Bezeichnung' ] = 'Bürgerbüro';
				$kontakt_arr[ 'bezeichnung' ] = 'Bürgerbüro';
			} else {
				print_r($mdb_nn.PHP_EOL);die;
			}
		}


		$mp_wk_el_sa = $mdb_cntc_xp->query(
			'.//span[contains( @itemprop, "street-address" )]',
			$mdb_cntc_el
		);
		foreach ( $mp_wk_el_sa as $mp_sa ) {
			//print_r($mp_sa);
			$mp_sa_val = $mp_sa->nodeValue;
			//print_r( $mp_sa_val . PHP_EOL );
			$str_hnr_arr = preg_split( '/(?=\d)/', $mp_sa_val, 2 );
			//print_r( $str_hnr_arr );
			$bd_str = trim( $str_hnr_arr[ 0 ] );
			$bd_hnr = $str_hnr_arr[ 1 ];
		}


		$mp_wk_el_pc = $mdb_cntc_xp->query(
			'.//span[contains( @itemprop, "postal-code" )]',
			$mdb_cntc_el
		);
		foreach ( $mp_wk_el_pc as $mp_pc ) {
			//print_r($mp_pc);
			$mp_pc_val = $mp_pc->nodeValue;
			//print_r( $mp_pc_val . PHP_EOL );
			$bd_plz = $mp_pc_val;
		}


		$mp_wk_el_ly = $mdb_cntc_xp->query(
			'.//span[contains( @itemprop, "locality" )]',
			$mdb_cntc_el
		);
		foreach ( $mp_wk_el_ly as $mp_ly ) {
			//print_r($mp_ly);
			$mp_ly_val = $mp_ly->nodeValue;
			//print_r( $mp_ly_val . PHP_EOL );
			$bd_ort = $mp_ly_val;
		}


		$mp_wk_el_tl = $mdb_cntc_xp->query(
			'.//span[contains( @itemprop, "tel" )]',
			$mdb_cntc_el
		);
		foreach ( $mp_wk_el_tl as $mp_tl ) {
			//print_r($mp_tl);
			$mp_tl_val = trim( $mp_tl->nodeValue );
			$mp_tl_val = preg_replace( '/\(|\)|\-|\//', '', $mp_tl_val );
			$mp_tl_val = preg_replace( '/^\+49/', '0', $mp_tl_val );
			//print_r( $mp_tl_val . PHP_EOL );
			$bd_tel = $mp_tl_val;
		}


		$mp_wk_el_fx = $mdb_cntc_xp->query(
			'.//span[contains( @itemprop, "fax" )]',
			$mdb_cntc_el
		);
		foreach ( $mp_wk_el_fx as $mp_fx ) {
			//print_r($mp_fx);
			$mp_fx_val = trim( $mp_fx->nodeValue );
			$mp_fx_val = preg_replace( '/\(|\)|\-|\//', '', $mp_fx_val );
			$mp_fx_val = preg_replace( '/^\+49/', '0', $mp_fx_val );
			//print_r( $mp_fx_val . PHP_EOL );
			$bd_fax = $mp_fx_val;
		}


		$mp_wk_el_el = $mdb_cntc_xp->query(
			'.//a[contains( @class, "linkEmail" )]',
			$mdb_cntc_el
		);
		foreach ( $mp_wk_el_el as $mp_el ) {
			//print_r($mp_el);
			$mp_el_val = $mp_el->nodeValue;
			//print_r( $mp_el_val . PHP_EOL );
			$bd_mail = $mp_el_val;
		}

		$mdb_cntc_cur_buero[ 'Adresse' ][ 'frag' ] = array (
			'Straße' => $bd_str,
			'Hausnummer' => $bd_hnr,
			'Postleitzahl' => $bd_plz,
			'Ort' => $bd_ort,
		);

		$mdb_cntc_cur_buero[ 'Adresse' ][ 'full' ] =
			$mdb_vn
			. ' '
			. $mdb_nn
			. PHP_EOL
			. $bd_str
			. ' '
			. $bd_hnr
			. PHP_EOL
			. $bd_plz
			. ' '
			. $bd_ort;

		$mdb_cntc_cur_buero[ 'Kontakt' ][ 'Mail' ] = $bd_mail;
		$mdb_cntc_cur_buero[ 'Kontakt' ][ 'Telefon' ] = $bd_tel;
		$mdb_cntc_cur_buero[ 'Kontakt' ][ 'Fax' ] = $bd_fax;

		$mdb_cntc_adr_arr[] = $mdb_cntc_cur_buero;


		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'strasse' ] = $bd_str;
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'hausnummer' ] = $bd_hnr;
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'postleitzahl' ] = $bd_plz;
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'ort' ] = $bd_ort;

		$kontakt_arr[ 'adresse' ][ 'komplett' ] =
			$mdb_vn
			. ' '
			. $mdb_nn
			. PHP_EOL
			. $kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'strasse' ]
			. ' '
			. $kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'hausnummer' ]
			. PHP_EOL
			. $kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'postleitzahl' ]
			. ' '
			. $kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'ort' ];

		$kontakt_arr[ 'eda' ][ 'mail' ] = $bd_mail;
		$kontakt_arr[ 'eda' ][ 'telefon' ] = $bd_tel;
		$kontakt_arr[ 'eda' ][ 'fax' ] = $bd_fax;

		$mdb_data_arr[ 'kontakte' ][] = $kontakt_arr;

	}

	$mdb_data_arr[ 'url' ] = $mdb_pl_url;
	$mdb_db[] = $mdb_data_arr;

	/***/
	$mdb_db_tmp[] = array(
		'Name' => $name_arr,
 		'Kontaktdaten' => $mdb_cntc_adr_arr,
// 		'Fraktion' => $ffkt_fl_arr,
// 		'Wahl' => $wahl_arr,
 		'Links' => $linkliste_arr,
 		'Bio' => $biogra_arr,
		'PURL' => $mdb_pl_url,
	);
	/***/

}

// print_r($mdb_db);die;
// print_r($mdb_db_tmp);die;

file_put_contents( $db_file_arr, var_export( $mdb_db, true ) );
file_put_contents( $db_file_arr.'.json', json_encode( $mdb_db, true ) );

<?php

// $profil_url_daten_obj = new ProfilURLDaten();
// //print_r( $profil_url_daten_obj );
// $profil_url_daten_obj->pud_dump();
// die;

$mdb_db = array();
$mdb_db_tmp = array();

$db_file_arr = 'tmp/cdu_mdb_db';

$baseurl = $data[ 'current' ][ 'baseurl' ];

$mdb_lst_pg_dd = $data[ 'current' ][ 'domdoc' ];


$mdb_lst_xp = new DOMXpath( $mdb_lst_pg_dd );
$mdb_lst_divs = $mdb_lst_xp->query(
	'//div[contains( @class, "abgeordnete_az_content" )]
	/div[contains( @class, "node-abgeordneter" )]'
);
//print_r($mdb_lst_divs);

foreach ( $mdb_lst_divs as $mdb_lst_div ) {
	//print_r($mdb_lst_div);

	$profil_url_daten_obj = new ProfilURLDaten();

	$mdb_data_arr = $profil_url_daten_obj->profil_url_daten;


	$name_from_list = $mdb_lst_xp->query(
		'.//div[contains(@class, "group-right")]/h2/a',
		$mdb_lst_div
	);
	//print_r( $name_from_list );

	foreach ( $name_from_list as $nfl ) {
		//print_r( $nfl );

		$name_arr = array();

		$nfl_val = $nfl->nodeValue;
		//print_r($nfl_val . PHP_EOL );

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

			if ( preg_match( '/\b(\sde|Graf\s)\b/', $nfl_str ) ) {
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

	$src_file_name = basename( $mdb_pl_url );
	$src_path = 'tmp/cdu/src/abg/';
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

	$mdb_cntc_adr_arr = array();


	$mdb_pl_ba = $mdb_pl_xp->query(
		"//div[contains( @class, 'group-adressinfo' )]
		//div[contains( @class, 'field-name-field-kontakt-berlin' )]
		//div[contains( @class, 'adr' )]"
	);
	//print_r( $mdb_pl_ba );

	foreach ( $mdb_pl_ba as $mp_ba ) {
		//print_r( $mp_ba );
		//$mp_ba_val_str = $mp_ba->nodeValue;
		//print_r( $mp_ba_val_str . PHP_EOL );

		$bb_adr_data = array(
			'Bezeichnung' => 'Berliner Büro',
			'Adresse' => array(
				'frag' => array(
					'Straße' => 'Platz der Republik',
					'Hausnummer' => '1',
					'Postleitzahl' => '11011',
					'Ort' => 'Berlin'
				),
			)
		);

		$bb_adr_data[ 'Adresse' ][ 'full' ] =
			$mdb_vn
			. ' '
			. $mdb_nn
			. PHP_EOL
			. $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Straße' ]
			. ' '
			. $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Hausnummer' ]
			. PHP_EOL
			. $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Postleitzahl' ]
			. ' '
			. $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Ort' ];


		$kontakt_arr = $profil_url_daten_obj->kontakt;

		/***
		$mpax_sa_div = $mdb_pl_xp->query(
			'./div[contains( @class, "street-address" )]',
			$mp_ba
		);
		//print_r( $mpax_sa_div );

		foreach ( $mpax_sa_div as $mp_sa_div ) {
			//print_r( $mp_sa_div );
			$mp_sa_div_val = $mp_sa_div->nodeValue;
			print_r( $mp_sa_div_val );
		}

		$mpax_pc_div = $mdb_pl_xp->query(
			'./span[contains( @class, "postal-code" )]',
			$mp_ba
		);
		//print_r( $mpax_pc_div );

		foreach ( $mpax_pc_div as $mp_pc_div ) {
			//print_r( $mp_pc_div );
			$mp_pc_div_val = $mp_pc_div->nodeValue;
			print_r( $mp_pc_div_val );
		}

		$mpax_ly_div = $mdb_pl_xp->query(
			'./span[contains( @class, "locality" )]',
			$mp_ba
		);
		//print_r( $mpax_ly_div );

		foreach ( $mpax_ly_div as $mp_ly_div ) {
			//print_r( $mp_ly_div );
			$mp_ly_div_val = $mp_ly_div->nodeValue;
			print_r( $mp_ly_div_val );
		}
		/***/

		$kontakt_arr = $profil_url_daten_obj->kontakt;

		/***/
		$mpax_em_div = $mdb_pl_xp->query(
			'./div[contains( @class, "email" )]/span/a',
			$mp_ba
		);
		//print_r( $mpax_em_div );

		foreach ( $mpax_em_div as $mp_em_div ) {
			//print_r( $mp_em_div );
			$mp_em_div_val = preg_replace( '/\n|\r|\s+/m', '', $mp_em_div->nodeValue );
			//print_r( $mp_em_div_val );
			$bb_mail = $mp_em_div_val;

		}

		$mpax_tl_div = $mdb_pl_xp->query(
			'./div[contains( @class, "tel" )]/span',
			$mp_ba
		);
		//print_r( $mpax_tl_div );

		foreach ( $mpax_tl_div as $mp_tl_div ) {
			//print_r( $mp_ly_div );
			$mp_tl_div_val = preg_replace( '/\n|\r|\s+/m', '', $mp_tl_div->nodeValue );
			//print_r( $mp_tl_div_val );
			$bb_tel = preg_replace(
				'/[\/\-]/',
				' ',
				$mp_tl_div_val
			);
			//print_r( $bb_tel . PHP_EOL );
		}
		/***/

		$bb_adr_data[ 'Kontakt' ][ 'Mail' ] = $bb_mail;
		$bb_adr_data[ 'Kontakt' ][ 'Telefon' ] = $bb_tel;

		$mdb_cntc_adr_arr[] = $bb_adr_data;


		$kontakt_arr[ 'bezeichnung' ] = 'Berliner Büro';
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'strasse' ] = $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Straße' ];
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'hausnummer' ] = $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Hausnummer' ];
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'postleitzahl' ] = $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Postleitzahl' ];
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'ort' ] = $bb_adr_data[ 'Adresse' ][ 'frag' ][ 'Ort' ];

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

		$kontakt_arr[ 'eda' ][ 'mail' ] = $bb_mail;
		$kontakt_arr[ 'eda' ][ 'telefon' ] = $bb_tel;
		$kontakt_arr[ 'eda' ][ 'fax' ] = '';

		$mdb_data_arr[ 'kontakte' ][] = $kontakt_arr;

	}


	/***/
	$mdb_pl_wa = $mdb_pl_xp->query(
		"//div[contains( @class, 'group-adressinfo' )]
		//div[contains( @class, 'group-wahl-wrapper' )]
		//div[contains( @class, 'adr' )]"
	);
	//print_r( $mdb_pl_wa );
	//print_r( $mdb_pl_wa->length . PHP_EOL );

	foreach ( $mdb_pl_wa as $mp_wa ) {
		//print_r( $mp_wa );

		$kontakt_arr = $profil_url_daten_obj->kontakt;

		$wa_str = '';
		$wa_hnr = '';
		$wa_plz = '';
		$wa_ort = '';
		$wa_mail = '';
		$wa_tel = '';
		$wa_adr_data = array();
		$wa_adr_data[ 'Bezeichnung' ] = 'Wahlkreisbüro';

		$mp_wa_val_str = preg_replace( '/\n|\r|\s+/m', '', $mp_wa->nodeValue );
		//print_r( $mp_wa_val_str . PHP_EOL );
		if ( empty( $mp_wa_val_str ) ) {
			//print_r( 'leer' . PHP_EOL );
			continue;
		}
		//print_r( $mp_wa );

		$mpax_sa_div = $mdb_pl_xp->query(
			'./div[contains( @class, "street-address" )]',
			$mp_wa
		);
		//print_r( $mpax_sa_div );

		foreach ( $mpax_sa_div as $mp_sa_div ) {
			//print_r( $mp_sa_div );
			$mp_sa_div_val = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $mp_sa_div->nodeValue );
			//print_r( $mp_sa_div_val );
			$str_hnr_arr = preg_split( '/(?=\d)/', $mp_sa_div_val, 2 );
			array_trim( $str_hnr_arr );
			//print_r( $str_hnr_arr );
			$wa_str = $str_hnr_arr[ 0 ];
			$wa_hnr = $str_hnr_arr[ 1 ];
		}

		$mpax_pc_div = $mdb_pl_xp->query(
			'./span[contains( @class, "postal-code" )]',
			$mp_wa
		);
		//print_r( $mpax_pc_div );

		foreach ( $mpax_pc_div as $mp_pc_div ) {
			//print_r( $mp_pc_div );
			$mp_pc_div_val = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $mp_pc_div->nodeValue );
			//print_r( $mp_pc_div_val );
			$wa_plz = $mp_pc_div_val;
		}

		$mpax_ly_div = $mdb_pl_xp->query(
			'./span[contains( @class, "locality" )]',
			$mp_wa
		);
		//print_r( $mpax_ly_div );

		foreach ( $mpax_ly_div as $mp_ly_div ) {
			//print_r( $mp_ly_div );
			$mp_ly_div_val = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $mp_ly_div->nodeValue );
			//print_r( $mp_ly_div_val );
			$wa_ort = $mp_ly_div_val;
		}

		$mpax_em_div = $mdb_pl_xp->query(
			'./div[contains( @class, "email" )]/span/a',
			$mp_wa
		);
		//print_r( $mpax_em_div );

		foreach ( $mpax_em_div as $mp_em_div ) {
			//print_r( $mp_em_div );
			$mp_em_div_val = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $mp_em_div->nodeValue );
			//print_r( $mp_em_div_val );
			$wa_mail = $mp_em_div_val;
		}

		$mpax_tl_div = $mdb_pl_xp->query(
			'./div[contains( @class, "tel" )]/span',
			$mp_wa
		);
		//print_r( $mpax_tl_div );

		foreach ( $mpax_tl_div as $mp_tl_div ) {
			//print_r( $mp_ly_div );
			$mp_tl_div_val = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $mp_tl_div->nodeValue );
			//print_r( $mp_tl_div_val );
			$wa_tel = preg_replace(
				'/[\/\-]/',
				' ',
				$mp_tl_div_val
			);
		}

		$wa_adr_data[ 'Adresse' ][ 'frag' ] = array (
			'Straße' => $wa_str,
			'Hausnummer' => $wa_hnr,
			'Postleitzahl' => $wa_plz,
			'Ort' => $wa_ort,
		);

		$wa_adr_data[ 'Adresse' ][ 'full' ] =
			$mdb_vn
			. ' '
			. $mdb_nn
			. PHP_EOL
			. $wa_str
			. ' '
			. $wa_hnr
			. PHP_EOL
			. $wa_plz
			. ' '
			. $wa_ort;

		$wa_adr_data[ 'Kontakt' ][ 'Mail' ] = $wa_mail;
		$wa_adr_data[ 'Kontakt' ][ 'Telefon' ] = $wa_tel;


		$mdb_cntc_adr_arr[] = $wa_adr_data;


		$kontakt_arr[ 'bezeichnung' ] = 'Wahlkreisbüro';
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'strasse' ] = $wa_str;
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'hausnummer' ] = $wa_hnr;
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'postleitzahl' ] = $wa_plz;
		$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'ort' ] = $wa_ort;

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

		$kontakt_arr[ 'eda' ][ 'mail' ] = $wa_mail;
		$kontakt_arr[ 'eda' ][ 'telefon' ] = $wa_tel;
		$kontakt_arr[ 'eda' ][ 'fax' ] = '';

		$mdb_data_arr[ 'kontakte' ][] = $kontakt_arr;

	}
	/***/


	$wahl_arr = array();

	$mdb_pl_wk = $mdb_pl_xp->query(
		"//div[contains( @class, 'group-adressinfo' )]
		//div[contains( @class, 'group-wahl-wrapper' )]
		//div[contains( @class, 'wahlkreis-name' )]"
	);
	//print_r( $mdb_pl_wk );
	//print_r( $mdb_pl_wk->length . PHP_EOL );

	foreach ( $mdb_pl_wk as $mp_wk ) {
		//print_r( $mp_wk );
		//print_r( $mp_wk->nodeValue . PHP_EOL );
		preg_match( '/(?:\()(\d*)(?:\))/', $mp_wk->nodeValue, $wk_nr_arr );
		//print_r( $wk_nr_arr );

		if ( ! empty( $wk_nr_arr[ 1 ] ) ) {
			$wahl_arr[ 'unsp' ] = array(
				'wknr' => $wk_nr_arr[ 1 ],
			);

			$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'wahlkreisnummer' ] = $wk_nr_arr[ 1 ];
		}
	}


	$linkliste_arr = array();

	$mdb_pl_ll_lis = $mdb_pl_xp->query(
		"//div[contains( @class, 'group-personal-links' )]//li" );
	//print_r( $mdb_pl_ll_lis );

	foreach ( $mdb_pl_ll_lis as $mp_ll_li ) {
		//print_r( $mp_ll_li );

		$mp_ll_li_val = trim( $mp_ll_li->nodeValue );
		//print_r( $mp_ll_li_val . PHP_EOL );
		$mp_ll_li_val = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $mp_ll_li_val );

		$mp_ll_li_link = $mdb_pl_xp->query(
			'.//a',
			$mp_ll_li
		);
		foreach ( $mp_ll_li_link as $mlll ) {
			$mp_ll_li_href = $mlll->getAttribute( 'href' );
			//print_r( $mp_ll_li_href . PHP_EOL );
		}

		//$linkliste_arr[ $mp_ll_li_val ] = $mp_ll_li_href;

		$link_arr = array(
			'txt_val' => $mp_ll_li_val,
			'href' => $mp_ll_li_href,
		);
		if ( preg_match( '/^homepage|website|persönliche homepage/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Homepage' ] = $link_arr;
		} elseif ( preg_match( '/youtube|you tube/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'Youtube' ] = $link_arr;
		} elseif ( preg_match( '/facebook/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'Facebook' ] = $link_arr;
		} elseif ( preg_match( '/twitter/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'Twitter' ] = $link_arr;
		} elseif ( preg_match( '/google+/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'Google+' ] = $link_arr;
		} elseif ( preg_match( '/flickr/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'flickr' ] = $link_arr;
		} elseif ( preg_match( '/xing/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'xing' ] = $link_arr;
		} elseif ( preg_match( '/meinvz/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'meinvz' ] = $link_arr;
		} elseif ( preg_match( '/studivz|VZ\-Netz/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'studivz' ] = $link_arr;
		} elseif ( preg_match( '/myspace/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'MySpace' ] = $link_arr;
		} elseif ( preg_match( '/^friendfeed/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'friendfeed' ] = $link_arr;
		} elseif ( preg_match( '/^abgeordnetenwatch/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'PolSpez' ][ 'abgeordnetenwatch' ] = $link_arr;
		} elseif ( preg_match( '/^Videoarchiv der Reden/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Bundestag' ][ 'Reden' ] = $link_arr;
		} elseif ( preg_match( '/^Deutscher Bundestag/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Bundestag' ][ 'Profil' ] = $link_arr;
		} elseif ( preg_match( '/Landesgruppe/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Landesgruppe' ] = $link_arr;
		} elseif ( preg_match( '/^Landesverband/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Landesverband' ] = $link_arr;
		} elseif ( preg_match( '/Kreisverband/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Kreisverband' ] = $link_arr;
		} elseif ( preg_match( '/Landkreis|\bKreis\b/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Landkreis' ] = $link_arr;
		} elseif ( preg_match( '/\b\w*kreis\b/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Landkreis' ] = $link_arr;
		} elseif ( preg_match( '/\bStadt\b|\bBergstadt\b/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Stadt' ] = $link_arr;
		} elseif ( preg_match( '/\bGemeinde\b/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Gemeinde' ] = $link_arr;
		} elseif ( preg_match( '/^CDU/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'CDU-Link' ][ $mp_ll_li_val ] = $link_arr;
		} else {
			$linkliste_arr[ 'Sonstige' ][ 'Unsortiert' ][ $mp_ll_li_val ] = $link_arr;
		}


		$verw_arr = $profil_url_daten_obj->verweisbasis;
		$verw_arr[ 'text' ] = $mp_ll_li_val;
		$verw_arr[ 'href' ] = $mp_ll_li_href;

		if ( preg_match( '/^homepage|website|persönliche homepage/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'persoenlicheseite' ] = $verw_arr;
		} elseif ( preg_match( '/youtube|you tube/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Youtube' ] = $verw_arr;
		} elseif ( preg_match( '/facebook/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Facebook' ] = $verw_arr;
		} elseif ( preg_match( '/twitter/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Twitter' ] = $verw_arr;
		} elseif ( preg_match( '/google+/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Google+' ] = $verw_arr;
		} elseif ( preg_match( '/flickr/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'flickr' ] = $verw_arr;
		} elseif ( preg_match( '/xing/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'xing' ] = $verw_arr;
		} elseif ( preg_match( '/meinvz/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'meinvz' ] = $verw_arr;
		} elseif ( preg_match( '/studivz|VZ\-Netz/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'studivz' ] = $verw_arr;
		} elseif ( preg_match( '/myspace/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'MySpace' ] = $verw_arr;
		} elseif ( preg_match( '/^friendfeed/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'friendfeed' ] = $verw_arr;
		} elseif ( preg_match( '/^abgeordnetenwatch/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'spezifisch' ][ 'abgeordnetenwatch' ] = $verw_arr;
		} elseif ( preg_match( '/^Videoarchiv der Reden/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'bundestag' ][ 'reden' ] = $verw_arr;
		} elseif ( preg_match( '/^Deutscher Bundestag/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'bundestag' ][ 'profil' ] = $verw_arr;
		} elseif ( preg_match( '/Landesgruppe/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Landesgruppe' ] = $verw_arr;
		} elseif ( preg_match( '/^Landesverband/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Landesverband' ] = $verw_arr;
		} elseif ( preg_match( '/Kreisverband/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Kreisverband' ] = $verw_arr;
		} elseif ( preg_match( '/Landkreis|\bKreis\b/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Landkreis' ] = $verw_arr;
		} elseif ( preg_match( '/\b\w*kreis\b/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Landkreis' ] = $verw_arr;
		} elseif ( preg_match( '/\bStadt\b|\bBergstadt\b/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Stadt' ] = $verw_arr;
		} elseif ( preg_match( '/\bGemeinde\b/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Gemeinde' ] = $verw_arr;
		} elseif ( preg_match( '/^CDU/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'CDU-Link' ][ $mp_ll_li_val ] = $verw_arr;
		} else {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'unsortiert' ][ $mp_ll_li_val ] = $verw_arr;
		}

	}


	$biogra_arr = array();

	$mdb_pl_bg_els = $mdb_pl_xp->query(
		"//div[contains( @class, 'group-infobereich' )]"
	);
	//print_r( $mdb_pl_bg_els );
	//print_r($mdb_nn . PHP_EOL);

	foreach ( $mdb_pl_bg_els as $mp_bg_el ) {
		//print_r( $mp_bg_el );


		$mp_bg_el_gebd = $mdb_pl_xp->query(
			'.//time[contains( @class, "date-display-single" )]',
			$mp_bg_el
		);
		foreach ( $mp_bg_el_gebd as $mp_gebd ) {
			//print_r($mp_gebd);
			$mp_gebd_val = $mp_gebd->nodeValue;
			//print_r( $mp_gebd_val . PHP_EOL );
			$biogra_arr[ 'Geburtsdatum' ] = $mp_gebd_val;

			$mdb_data_arr[ 'biografie' ][ 'geburtsdatum' ] = $mp_gebd_val;
		}


		$mp_bg_el_gebo = $mdb_pl_xp->query(
			'.//div[contains( @class, "group-birthday" )]//child::text()[last()]',
			$mp_bg_el
		);
		//print_r($mp_bg_el_gebo);
		$mp_gebo_val = '';
		if ( $mp_bg_el_gebo->length > 0 ) {
			$gebo_ind = $mp_bg_el_gebo->length - 1;
			$mp_gebo_val = $mp_bg_el_gebo->item( $gebo_ind )->nodeValue;

			$mdb_data_arr[ 'biografie' ][ 'geburtsort' ] = $mp_bg_el_gebo->item( $gebo_ind )->nodeValue;
		}
		//print_r( $mp_gebo_val . PHP_EOL );
		$biogra_arr[ 'Geburtsort' ] = $mp_gebo_val;

		$mp_bg_el_ber = $mdb_pl_xp->query(
			'.//div[contains( @class, "field-name-beruf-gendered" )]',
			$mp_bg_el
		);
		foreach ( $mp_bg_el_ber as $mp_ber ) {
			//print_r($mp_ber);
			$mp_ber_val = $mp_ber->nodeValue;
			//print_r( $mp_ber_val . PHP_EOL );
			$biogra_arr[ 'Beruf' ] = $mp_ber_val;

			$mdb_data_arr[ 'biografie' ][ 'beruf' ] = $mp_ber_val;
		}


		$mp_bg_el_fkt = $mdb_pl_xp->query(
			'.//h3',
			$mp_bg_el
		);
		$biogra_arr[ 'Funktion' ] = '';
		foreach ( $mp_bg_el_fkt as $mp_fkt ) {
			//print_r($mp_fkt);
			$mp_fkt_val = $mp_fkt->nodeValue;
			//print_r( $mp_fkt_val . PHP_EOL );
			$biogra_arr[ 'Funktion' ] = $mp_fkt_val;

			$mdb_data_arr[ 'politik' ][ 'fraktion' ][ 'funktionen' ] = $mp_fkt_val;
		}

	}

	$mdb_data_arr[ 'url' ] = $mdb_pl_url;
	$mdb_db[] = $mdb_data_arr;

	/***/
	$mdb_db_tmp[] = array(
		'Name' => $name_arr,
		'Kontaktdaten' => $mdb_cntc_adr_arr,
// 		'Fraktion' => $ffkt_fl_arr,
		'Wahl' => $wahl_arr,
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
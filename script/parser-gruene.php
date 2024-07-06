<?php

// $profil_url_daten_obj = new ProfilURLDaten();
// //print_r( $profil_url_daten_obj );
// $profil_url_daten_obj->pud_dump();
// die;

$mdb_db = array();
$mdb_db_tmp = array();

$db_file_arr = 'tmp/gruene_mdb_db';

$baseurl = $data[ 'current' ][ 'baseurl' ];

$mdb_lst_pg_dd = $data[ 'current' ][ 'domdoc' ];


$mdb_lst_dn = $mdb_lst_pg_dd->getElementById( 'abgeordnete_slides_container' );

$mdb_lst_dd = new DomDocument;
$mdb_lst_dd->appendChild(
	$mdb_lst_dd->importNode(
		$mdb_lst_dn,
		true
	)
);

$mdb_lst_xp = new DOMXpath( $mdb_lst_dd );
$mdb_lst_divs = $mdb_lst_xp->query( '//div[contains( @class, "tt_content_list_item" )]' );

foreach ( $mdb_lst_divs as $mdb_lst_div ) {

	$profil_url_daten_obj = new ProfilURLDaten();

	$mdb_data_arr = $profil_url_daten_obj->profil_url_daten;

// 	print_r($mdb_data_arr); die;

	$name_from_list = $mdb_lst_xp->query(
		'.//div[contains(@class, "abgeordnete_text")]/p/a',
		$mdb_lst_div
	);
	//print_r( $name_from_list );

	foreach ( $name_from_list as $nfl ) {
		//print_r( $nfl->getAttribute( 'href' ) );

		$name_arr = array();

		$nfl_val = $nfl->nodeValue;
		$mdb_pl_href = '/' . $nfl->getAttribute( 'href' );

		// Clean Up Whitespaces
		$nfl_str = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $nfl_val );

		// Separate Title from Name
		$nfl_ti_fn_nn_arr = explode( '.', $nfl_str );
		$nfl_fn_nn_str = trim( array_pop( $nfl_ti_fn_nn_arr ) );

		// Create Array with NN FN
		$nfl_fn_nn_arr = explode( ' ', $nfl_fn_nn_str );

		$mdb_nn = trim( $nfl_fn_nn_arr[1] );
		$mdb_vn = trim( $nfl_fn_nn_arr[0] );

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

		$mdb_data_arr[ 'name' ][ 'nachname' ] = $mdb_nn;
		$mdb_data_arr[ 'name' ][ 'vorname' ] = $mdb_vn;
		$mdb_data_arr[ 'name' ][ 'adelszusatz' ] = '';
		$mdb_data_arr[ 'name' ][ 'titel' ] = $mdb_ti;

	}


	$ffkt_from_list = $mdb_lst_xp->query(
		'.//div[contains(@class, "abgeordnete_text")]/p[last()]',
		$mdb_lst_div
	);
	//print_r( $ffkt_from_list );

	foreach ( $ffkt_from_list as $ffl ) {
		//print_r( $ffl );

		$ffkt_fl_arr = array();

		$ffkt_str = $ffl->nodeValue;
		//print_r( $ffkt_str );

		$ffkt_arr = explode( "\n", $ffkt_str );
		//print_r( $ffkt_arr );

		$ffkt_fl_arr[ 'Funktionen' ] = $ffkt_arr;

		$mdb_data_arr[ 'politik' ][ 'fraktion' ][ 'funktionen' ] = $ffkt_arr;

	}


	$mail_from_list = $mdb_lst_xp->query(
		'.//div[contains(@class, "email_link")]/a',
		$mdb_lst_div
	);
	//print_r( $mail_from_list );

	foreach ( $mail_from_list as $mfl ) {

		$href_mailto = $mfl->getAttribute( 'href' );
		$buero_mail = mailto_remover( $href_mailto );

	}



	$mdb_pl_url = $baseurl . $mdb_pl_href;
	//print_r( $mdb_pl_url . PHP_EOL );

	$src_file_name = basename( $mdb_pl_url, '.html' );
	$src_path = 'tmp/gruene/src/abg/';
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

	$kontakt_arr = $profil_url_daten_obj->kontakt;
	//print_r($kontakt_arr);die;

	$mdb_pl_ba = $mdb_pl_xp->query(
		"//div[contains( @id, 'abgeordnete_links' )]
		/p[contains( @class, 'bodytext' )]" );
	//print_r( $mdb_pl_ba );

	foreach ( $mdb_pl_ba as $mp_ba ) {
		//print_r( $mp_ba );

		$mp_ba_val_str = $mp_ba->nodeValue;
		//print_r( $mp_ba_val . PHP_EOL );

		$tel_fax_div = preg_match( '/(?:.*?)T:(?:.*?)/', $mp_ba_val_str );
		//print_r( $tel_fax_div . PHP_EOL );

		if ( ! $tel_fax_div ) {
			continue;
		} else {
			$mp_ba_val_arr = explode( 'F', $mp_ba_val_str );
			foreach ( $mp_ba_val_arr as $key => $value) {
				$cvalue = trim(
					preg_replace(
						'/\s+/u',
						' ',
						preg_replace(
							'/[a-zA-Z\,\.\:\/\-\Ü]/',//[^0-9,.]
							'',
							$value
						)
					)
				);
				$mp_ba_val_arr[ $key ] = $cvalue;
			}
			//print_r( $mp_ba_val_arr );

			$bb_tel = '';
			if ( isset( $mp_ba_val_arr[0] ) ) {
				$bb_tel = $mp_ba_val_arr[0];
			}

			$bb_fax = '';
			if ( isset( $mp_ba_val_arr[1] ) ) {
				$bb_fax = $mp_ba_val_arr[1];
			}
		}
	}

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
	$bb_adr_data[ 'Kontakt' ] = array(
		'Mail' => $buero_mail
	);
	$bb_adr_data[ 'Kontakt' ][ 'Telefon' ] = $bb_tel;
	$bb_adr_data[ 'Kontakt' ][ 'Fax' ] = $bb_fax;

	$mdb_cntc_adr_arr[] = $bb_adr_data;

	$kontakt_arr[ 'bezeichnung' ] = 'Berliner Büro';
	$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'strasse' ] = 'Platz der Republik';
	$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'hausnummer' ] = '1';
	$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'postleitzahl' ] = '11011';
	$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'ort' ] = 'Berlin';

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

	$kontakt_arr[ 'eda' ][ 'mail' ] = $buero_mail;
	$kontakt_arr[ 'eda' ][ 'telefon' ] = $bb_tel;
	$kontakt_arr[ 'eda' ][ 'fax' ] = $bb_fax;

	$mdb_data_arr[ 'kontakte' ][] = $kontakt_arr;


	//$wk_adr_data = array();

	$mdb_pl_wk_cntc = $mdb_pl_xp->query(
		"//div[contains( @id, 'block_50_right' )]
		/div[contains( @id, 'tabs' )]
		/div[contains( @id, 'parlament' )]
		/div[contains( @class, 'wk-kontakt' )]
		//p[contains( @class, 'bodytext' )]"
	);
	//print_r( $mdb_pl_wk_cntc );

	foreach ( $mdb_pl_wk_cntc as $key => $mp_wk_cntc ) {
		//print_r( $mp_wk_cntc );
		//print_r( $mp_wk_cntc->nodeValue );
		//print_r( $key );

		$mwcn_arr_buero = array();

		$kontakt_arr = $profil_url_daten_obj->kontakt;

		$mp_wk_cntc_nodes = $mdb_pl_xp->query(
			'.//node()',
			$mp_wk_cntc
		);
		//print_r( $mp_wk_cntc_nodes );

		$mwcn_arr = array();

		foreach ( $mp_wk_cntc_nodes as $mwcn ) {
			//print_r( $mwcn );

			if ( isset( $mwcn->nodeType ) && $mwcn->nodeType == 3 ) {
				//print_r( $mwcn->nodeValue );

				$mwcn_val = $mwcn->nodeValue;

				$mwcn_arr[] = $mwcn_val;
			}
		}
		//print_r( $mwcn_arr );

		if ( $mdb_nn == 'Hofreiter' && $key == 2 ) {
			array_unshift( $mwcn_arr, 'BÜRO' );
			//print_r( $mwcn_arr );
			//die;
		} elseif ( $mdb_nn == 'Verlinden' && $key == 1 ) {
			array_unshift( $mwcn_arr, 'BÜRO' );
		} elseif ( $mdb_nn == 'Kekeritz' && $key == 1 ) {
			continue;
		} elseif ( $mdb_nn == 'Krischer' && $key == 1 ) {
			continue;
		} elseif ( $mdb_nn == 'Özdemir' && $key == 1 ) {
			array_unshift( $mwcn_arr, 'BÜRO' );
			//print_r($mdb_pl_wk_cntc->item(2));
			$mp_wk_cntc_nodes_tmp = $mdb_pl_xp->query(
				'.//node()',
				$mdb_pl_wk_cntc->item(2)
			);
			$mwcn_arr_tmp = array();
			foreach ( $mp_wk_cntc_nodes_tmp as $mwcn_tmp ) {
				//print_r( $mwcn );
				if ( isset( $mwcn_tmp->nodeType ) && $mwcn_tmp->nodeType == 3 ) {
					//print_r( $mwcn->nodeValue );
					$mwcn_val_tmp = $mwcn_tmp->nodeValue;
					$mwcn_arr_tmp[] = $mwcn_val_tmp;
				}
			}
			//print_r($mwcn_arr_tmp);
			foreach ( $mwcn_arr_tmp as $matmp) {
				array_push( $mwcn_arr, $matmp );
			}
			//print_r($mwcn_arr);
			//die;
		} elseif ( $mdb_nn == 'Rüffer' ) {
			if ( $key == 2 ) {
				$mp_wk_cntc_nodes_tmp = $mdb_pl_xp->query(
					'.//node()',
					$mdb_pl_wk_cntc->item(1)
				);
				$mwcn_arr_tmp = array();
				foreach ( $mp_wk_cntc_nodes_tmp as $mwcn_tmp ) {
					//print_r( $mwcn );
					if ( isset( $mwcn_tmp->nodeType ) && $mwcn_tmp->nodeType == 3 ) {
						//print_r( $mwcn->nodeValue );
						$mwcn_val_tmp = $mwcn_tmp->nodeValue;
						$mwcn_arr_tmp[] = $mwcn_val_tmp;
					}
				}
				//print_r($mwcn_arr_tmp);
				$mwcn_arr_tmp = array_reverse( $mwcn_arr_tmp );
				foreach ( $mwcn_arr_tmp as $matmp) {
					array_unshift( $mwcn_arr, $matmp );
				}
				//print_r($mwcn_arr_tmp);
				array_unshift( $mwcn_arr, 'BÜRO' );
				$mail_tmp = $mwcn_arr[ 6 ] . $mwcn_arr[ 7 ];
				unset($mwcn_arr[ 5 ]);
				unset($mwcn_arr[ 6 ]);
				unset($mwcn_arr[ 7 ]);
				unset($mwcn_arr[ 8 ]);
				array_push( $mwcn_arr, $mail_tmp );
				//print_r($mwcn_arr);
				//die;
			}
			if ( $key == 3 ) continue;
		} elseif ( $mdb_nn == 'Strengmann-Kuhn' && $key == 1 ) {
			array_unshift( $mwcn_arr, 'BÜRO' );
		}

		if (
			preg_match( '/\b(\w*bür\w*)\b/iu', $mwcn_arr[ 0 ] )
			|| count( $mwcn_arr ) > 2
		) {
			//print_r( PHP_EOL . 'BÜRO' . PHP_EOL );

			unset( $mwcn_arr[ 0 ] );

			//print_r( $mwcn_arr );

			//print_r( PHP_EOL );
			foreach ( $mwcn_arr as $mwcn_key => $mwcn_val ) {
				//print_r( strlen( $mwcn_val ) . PHP_EOL );
				$mwcn_val_len = strlen( $mwcn_val );
				if ( $mwcn_val_len < 7 ) unset( $mwcn_arr[ $mwcn_key ] );
			}
			//print_r( PHP_EOL );

			$mwcn_ac = count( $mwcn_arr );
			//print_r( $mwcn_ac . PHP_EOL );

			if ( $mwcn_ac > 2 ) {

				//print_r( $mwcn_arr );

				$mwcn_arr_buero_tmp = array();
				foreach ( $mwcn_arr as $mwcn_key => $mwcn_val ) {
					$mwcn_val_cl = preg_replace( '/^\s+|\n|\r|\s+$/u', '', $mwcn_val );
					if (
						preg_match(
							'/^\b(mitarbeit\w*)/iu',
							$mwcn_val_cl
						)
					) {
						break;
					}
					if (
						preg_match(
							'/^\b(mo\-|für\w*)|(.*?)(\(zugang\w*)|^\b(hannover|dannenberg|lüneburg)\b|^(haus der|bad hers\w*)/iu',
							$mwcn_val_cl
						)
					) {
						continue;
					}
					$mwcn_arr_buero_tmp[] = $mwcn_val_cl;
				}
				//print_r( $mwcn_arr_buero_tmp );
			} else {
				//print_r( $mwcn_arr );
				//print_r( ' >2 ' . $mdb_nn);
			}

			$mabt_mail_arr = array();
			$mabt_tel_arr = array();
			$mabt_fax_arr = array();
			$mabt_str = '';
			$mabt_hnr = '';
			$mabt_plz = '';
			$mabt_ort = '';
			$mabt_mail = '';
			$mabt_tel = '';
			$mabt_fax = '';
			foreach ( $mwcn_arr_buero_tmp as $mabt_key => $mabt_val ) {
				if ( $mabt_key == 0 ) {
					$str_hnr_arr = preg_split( '/(?=\d)/', $mabt_val, 2 );
					array_trim( $str_hnr_arr );
					//print_r( $str_hnr_arr );
					$mabt_str = $str_hnr_arr[ 0 ];
					$mabt_hnr = $str_hnr_arr[ 1 ];
				}
				if ( $mabt_key == 1 ) {
					//$mabt_val = preg_replace( '/^(\s*)(?:\d))/', '', $mabt_val );
					if ( $mdb_nn == 'Wilms' ) {
						$mabt_val = substr( $mabt_val, 2 );
					}
					$plz_ort_arr = preg_split( '/(?!\d)/', $mabt_val, 2 );
					array_trim( $plz_ort_arr );
					//print_r( $plz_ort_arr );
					$mabt_plz = $plz_ort_arr[ 0 ];
					$mabt_ort = $plz_ort_arr[ 1 ];
				}
				if ( $mabt_key > 1 ) {
					if ( preg_match( '/^(T.|T:|T+|Tel)/', $mabt_val ) ) {
						$mabt_tel_arr[] = trim(
							preg_replace(
								'/(\s+)/',
								'',
								preg_replace(
									'/([^0-9|\s*])/',
									'',
									preg_replace(
										'/^(T.|T:|T+|Tel)/',
										'',
										$mabt_val
									)
								)
							)
						);
						//print_r( $mabt_tel_arr );
						$mabt_tel = $mabt_tel_arr[ 0 ];
					}
					if ( preg_match( '/^(F.|F:|F+|Fax)/', $mabt_val ) ) {
						$mabt_fax_arr[] = trim(
							preg_replace(
								'/(\s+)/',
								'',
								preg_replace(
									'/([^0-9|\s*])/',
									'',
									preg_replace(
										'/^(F.|F:|F+|Fax)/',
										'',
										$mabt_val
									)
								)
							)
						);
						//print_r( $mabt_fax_arr );
						$mabt_fax = $mabt_fax_arr[ 0 ];
					}
					if ( preg_match( '/(\@)/', $mabt_val ) ) {
						$mabt_mail_arr[] = strtolower( $mabt_val );
						//print_r( $mabt_mail_arr );
						$mabt_mail = $mabt_mail_arr[ 0 ];
					}
					if ( preg_match( '/(\(at\))/', $mabt_val ) ) {
						$mabt_mail_arr[] = strtolower(
							preg_replace(
								'/(\s*)/',
								'',
								preg_replace(
									'/(\(at\))/',
									'@',
									$mabt_val
								)
							)
						);
						//print_r( $mabt_mail_arr );
						$mabt_mail = $mabt_mail_arr[ 0 ];
					}
				}
			}

			$mwcn_arr_buero[ 'Bezeichnung' ] = 'Wahlkreisbüro';
			$mwcn_arr_buero[ 'Adresse' ][ 'full' ] =
			$mdb_vn
			. ' '
				. $mdb_nn
				. PHP_EOL
				. $mabt_str
				. ' '
					. $mabt_hnr
					. PHP_EOL
					. $mabt_plz
					. ' '
						. $mabt_ort;
			$mwcn_arr_buero[ 'Adresse' ][ 'frag' ] = array(
				'Straße' => $mabt_str,
				'Hausnummer' => $mabt_hnr,
				'Postleitzahl' => $mabt_plz,
				'Ort' => $mabt_ort,
			);
			$mwcn_arr_buero[ 'Kontakt' ] = array(
				'Mail' => $mabt_mail,
				'Telefon' => $mabt_tel,
				'Fax' => $mabt_fax,
			);
			//print_r($mwcn_arr_buero);

			$kontakt_arr[ 'bezeichnung' ] = 'Wahlkreisbüro';
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'strasse' ] = $mabt_str;
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'hausnummer' ] = $mabt_hnr;
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'postleitzahl' ] = $mabt_plz;
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'ort' ] = $mabt_ort;

			$kontakt_arr[ 'adresse' ][ 'komplett' ] =
				$mdb_vn
				. ' '
				. $mdb_nn
				. PHP_EOL
				. $mabt_str
				. ' '
				. $mabt_hnr
				. PHP_EOL
				. $mabt_plz
				. ' '
				. $mabt_ort;

			$kontakt_arr[ 'eda' ][ 'mail' ] = $mabt_mail;
			$kontakt_arr[ 'eda' ][ 'telefon' ] = $mabt_tel;
			$kontakt_arr[ 'eda' ][ 'fax' ] = $mabt_fax;

			$mdb_data_arr[ 'kontakte' ][] = $kontakt_arr;

			if ( ! empty( $mwcn_arr_buero ) ) {
				$mdb_cntc_adr_arr[] = $mwcn_arr_buero;
			}

		} else {
			//print_r( $mwcn_arr );
			//print_r( ' bür ' . $mdb_nn );
		}
	}



	$mdb_pl_wk_info = $mdb_pl_xp->query(
		"//div[contains( @id, 'block_50_right' )]
		/div[contains( @id, 'tabs' )]
		/div[contains( @id, 'parlament' )]
		/div[contains( @class, 'wk-info' )]
		//a[contains( text(), 'Wahlkreis' )]"
	);
	//print_r( $mdb_pl_wk_info );

	$wahl_arr = array();

	$mdb_pl_wk_info_exists = $mdb_pl_wk_info->length;

	if ( $mdb_pl_wk_info_exists ) {
		foreach ( $mdb_pl_wk_info as $key => $mp_wk_info ) {
			//print_r( $mdb_nn . ': ' . $mp_wk_info->nodeValue . PHP_EOL );

			$mp_wk_info_href = $mp_wk_info->getAttribute( 'href' );
			//print_r( $mp_wk_info_href . PHP_EOL );

			//print_r( parse_url( $mp_wk_info_href ) );
			$mp_wk_info_wknr_para = parse_url( $mp_wk_info_href, PHP_URL_QUERY );
			//print_r( $mp_wk_info_wknr_para . PHP_EOL );

			$mp_wk_info_wknr_arr = explode( '=', $mp_wk_info_wknr_para );
			//print_r( $mp_wk_info_wknr_arr );

			if ( ! empty( $mp_wk_info_wknr_arr[ 0 ] ) ) {
				$wahl_arr[ 'unsp' ] = array(
					$mp_wk_info_wknr_arr[ 0 ] => $mp_wk_info_wknr_arr[ 1 ],
				);

				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'wahlkreisnummer' ] = $mp_wk_info_wknr_arr[ 1 ];
			}

		}
	}


	$linkliste_arr = array();

	$mdb_pl_ll_lis = $mdb_pl_xp->query(
		"//div[contains( @id, 'block_25_right' )]/div[contains( @id, 'links' )]/ul/li" );
	//print_r( $mdb_pl_ll_lis );

	foreach ( $mdb_pl_ll_lis as $mp_ll_li ) {
		//print_r( $mp_ll_li );

		$mp_ll_li_val = trim( $mp_ll_li->nodeValue );
		$mp_ll_li_link = $mdb_pl_xp->query(
			'.//a',
			$mp_ll_li
		);
		foreach ( $mp_ll_li_link as $mlll ) {
			$mp_ll_li_href = $mlll->getAttribute( 'href' );
		}

		$link_arr = array(
			'txt_val' => $mp_ll_li_val,
			'href' => $mp_ll_li_href,
		);
		if ( preg_match( '/^homepage|website|blog/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Homepage' ] = $link_arr;
		} elseif ( preg_match( '/^youtube/iu', $mp_ll_li_val ) ) {
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
		} elseif ( preg_match( '/studivz/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'studivz' ] = $link_arr;
		} elseif ( preg_match( '/friendfeed/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'SozialeNetzwerke' ][ 'friendfeed' ] = $link_arr;
		} elseif ( preg_match( '/^Reden im Videoarchiv des Bundestags/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Bundestag' ][ 'Reden' ] = $link_arr;
		} elseif ( preg_match( '/Porträt auf|Porträt \(|Porträt bei/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Bundestag' ][ 'Profil' ] = $link_arr;
		} elseif ( preg_match( '/abgeordnetenwatch/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'spezifisch' ][ 'abgeordnetenwatch' ] = $link_arr;
		} elseif ( preg_match( '/Veröffentlichungspflichtige|Veröffentlichungspflichte/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'spezifisch' ][ 'VOA' ] = $link_arr;
		} elseif ( preg_match( '/leichter sprache/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'spezifisch' ][ 'leichtesprache' ] = $link_arr;
		} elseif ( preg_match( '/gebärdensprache/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'spezifisch' ][ 'gebärdensprache' ] = $link_arr;
		} elseif ( preg_match( '/^Landesgruppe/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Landesgruppe' ] = $link_arr;
		} elseif ( preg_match( '/\.net|\.eu|\.com|\.de$/iu', $mp_ll_li_val ) ) {
			$linkliste_arr[ 'Homepage' ] = $link_arr;
		} else {
			$linkliste_arr[ 'Sonstige' ][ 'Unsortiert' ][ $mp_ll_li_val ] = $link_arr;
		}

		$verw_arr = $profil_url_daten_obj->verweisbasis;
		$verw_arr[ 'text' ] = $mp_ll_li_val;
		$verw_arr[ 'href' ] = $mp_ll_li_href;

		if ( preg_match( '/^homepage|website|blog/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'persoenlicheseite' ] = $link_arr;
		} elseif ( preg_match( '/^youtube/iu', $mp_ll_li_val ) ) {
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
		} elseif ( preg_match( '/studivz/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'studivz' ] = $verw_arr;
		} elseif ( preg_match( '/friendfeed/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'friendfeed' ] = $verw_arr;
		} elseif ( preg_match( '/^Reden im Videoarchiv des Bundestags/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'bundestag' ][ 'reden' ] = $verw_arr;
		} elseif ( preg_match( '/Porträt auf|Porträt \(|Porträt bei/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'bundestag' ][ 'profil' ] = $verw_arr;
		} elseif ( preg_match( '/abgeordnetenwatch/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'spezifisch' ][ 'abgeordnetenwatch' ] = $verw_arr;
		} elseif ( preg_match( '/Veröffentlichungspflichtige|Veröffentlichungspflichte/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'spezifisch' ][ 'VOA' ] = $verw_arr;
		} elseif ( preg_match( '/leichter sprache/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'spezifisch' ][ 'leichtesprache' ] = $verw_arr;
		} elseif ( preg_match( '/gebärdensprache/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'spezifisch' ][ 'gebärdensprache' ] = $verw_arr;
		} elseif ( preg_match( '/^Landesgruppe/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Landesgruppe' ] = $verw_arr;
		} elseif ( preg_match( '/\.net|\.eu|\.com|\.de$/iu', $mp_ll_li_val ) ) {
			$mdb_data_arr[ 'verweise' ][ 'persoenlicheseite' ] = $verw_arr;
		} else {
			$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'unsortiert' ][ $mp_ll_li_val ] = $verw_arr;
		}
	}


	/***/
	$mdb_pl_ad_xp_bg = $mdb_pl_xp->query(
		'//div[contains( @id, "vita" )]
		/p[contains( @class, "bodytext" )][1]'
	);

	$biogra_arr = array();

	foreach ( $mdb_pl_ad_xp_bg as $mpax_bg ) {
		//print_r( $mpax_bg );

		//print_r( $mdb_nn . PHP_EOL );

		$biogra_arr[ 'Geburtsdatum' ] = '';
		//print_r( $mpax_bg->nodeValue . PHP_EOL );
		//print_r( preg_match( '/(\d{2}(.*?)(\d{4}))/', $mpax_bg->nodeValue, $geb_dat_arr ) );
		preg_match( '/(\d{1,2}(.*?)(\d{4}))/', $mpax_bg->nodeValue, $geb_dat_arr );
		//print_r( $geb_dat_arr[ 0 ] . PHP_EOL );
		$biogra_arr[ 'Geburtsdatum' ] = $geb_dat_arr[ 0 ];

		$mdb_data_arr[ 'biografie' ][ 'geburtsdatum' ] = $geb_dat_arr[ 0 ];

		preg_match( '/(?:\sin\s)(\b\w*\b)/u', $mpax_bg->nodeValue, $geb_ort_arr );
		$biogra_arr[ 'Geburtsort' ] = '';
		if ( isset( $geb_ort_arr[ 1 ] ) ) {
			//print_r( $geb_ort_arr[ 1 ] . PHP_EOL );
			$biogra_arr[ 'Geburtsort' ] = $geb_ort_arr[ 1 ];

			$mdb_data_arr[ 'biografie' ][ 'geburtsort' ] = $geb_ort_arr[ 1 ];
		}

		//print_r( $biogra_arr );
	}
	/***/

	$mdb_data_arr[ 'url' ] = $mdb_pl_url;
	$mdb_db[] = $mdb_data_arr;

	$mdb_db_tmp[] = array(
		'Name' => $name_arr,
		'Kontaktdaten' => $mdb_cntc_adr_arr,
		'Fraktion' => $ffkt_fl_arr,
		'Wahl' => $wahl_arr,
		'Links' => $linkliste_arr,
		'Bio' => $biogra_arr,
		'PURL' => $mdb_pl_url,
	);

}

// print_r($mdb_db);die;
// print_r($mdb_db_tmp);die;

file_put_contents( $db_file_arr, var_export( $mdb_db, true ) );
file_put_contents( $db_file_arr.'.json', json_encode( $mdb_db, true ) );
<?php

// $profil_url_daten_obj = new ProfilURLDaten();
// //print_r( $profil_url_daten_obj );
// $profil_url_daten_obj->pud_dump();
// die;

$mdb_db = array();
$mdb_db_tmp = array();

$db_file_arr = 'tmp/spd_mdb_db';

$baseurl = $data[ 'current' ][ 'baseurl' ];

$mdb_lst_pg_dd = $data[ 'current' ][ 'domdoc' ];


$mdb_lst_dn = $mdb_lst_pg_dd->getElementById( 'member_overview_list' );

$mdb_lst_dd = new DomDocument;
$mdb_lst_dd->appendChild(
	$mdb_lst_dd->importNode(
		$mdb_lst_dn,
		true
	)
);

$mdb_lst_xp = new DOMXpath( $mdb_lst_dd );

$mdb_lst_lis = $mdb_lst_xp->query( './li' );

foreach ( $mdb_lst_lis as $mdb_lst_li ) {
// 	print_r($value);

	$profil_url_daten_obj = new ProfilURLDaten();

	$mdb_data_arr = $profil_url_daten_obj->profil_url_daten;


	$name_from_list = $mdb_lst_xp->query(
		'.//div[contains(@class, "info_wrapper")]/h3/a',
		$mdb_lst_li
	);
// 	print_r($name_from_list);

	foreach ( $name_from_list as $nfl ) {
// 		print_r( $nfl->getAttribute( 'href' ) );

		$name_arr = array();

		$nfl_val = $nfl->nodeValue;
		$mdb_pl_href = $nfl->getAttribute( 'href' );

		// Clean Up Whitespaces
		$nfl_str = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $nfl_val );

		// Separate Title from Name
		$nfl_ti_fn_nn_arr = explode( '.', $nfl_str );
		$nfl_fn_nn_str = trim( array_pop( $nfl_ti_fn_nn_arr ) );

		// Create Array with NN FN
		$nfl_fn_nn_arr = explode( ' ', $nfl_fn_nn_str );

		if ( count( $nfl_fn_nn_arr ) != 2 ) {
// 			print_r( count( $nfl_fn_nn_arr ) );
// 			print_r( $nfl_fn_nn_arr );
			if ( $nfl_fn_nn_arr[ 2 ] = 'Ridder' ) {
				$mdb_nn = trim( $nfl_fn_nn_arr[ 1 ] )
					. ' '
					. trim( $nfl_fn_nn_arr[ 2 ] );
				$mdb_vn = trim( $nfl_fn_nn_arr[0] );
			} elseif ( $nfl_fn_nn_arr[ 2 ] = 'Rossmann' ) {
				$mdb_nn = trim( $nfl_fn_nn_arr[ 2 ] );
				$mdb_vn = trim( $nfl_fn_nn_arr[ 0 ] )
					. ' '
					. trim( $nfl_fn_nn_arr[ 1 ] );
			}
		} else {
			$mdb_nn = trim( $nfl_fn_nn_arr[ 1 ] );
			$mdb_vn = trim( $nfl_fn_nn_arr[ 0 ] );
		}

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

	$msll_mi_ul = $mdb_lst_xp->query(
		'.//ul[contains(@class, "member_infos")]',
		$mdb_lst_li
	);
	foreach ( $msll_mi_ul as $mmu ) {
		//print_r($mu);

		$wahl_arr = array();

		$mmu_lis = $mdb_lst_xp->query( './li', $mmu );

		foreach ( $mmu_lis as $muli ) {
			//print_r($mul);

			$wk_nr_arr = array();

			$mi_li_val = trim( $muli->nodeValue );
			//print_r( $mi_li_val . PHP_EOL );

			$mi_li_first = strtok( $mi_li_val, ' ' );
			//print_r( $mi_li_first . PHP_EOL );

			if ( $mi_li_first == 'Direktmandat' ) {
				//print_r( $wk_nr );

				$wa_wi_typ = $mi_li_first;

				$wahl_arr[ 'Typ' ] = $wa_wi_typ;

				preg_match( '/(?<=\[).*?(?=\])/', $mi_li_val, $wk_nr_arr );
				//print_r( $wk_nr_arr );

				$wa_wi_data = $wk_nr_arr;

				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'typ' ] = $wa_wi_typ;
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'wahlkreisnummer' ] = $wk_nr_arr[0];

			} elseif ( $mi_li_first == 'Landesliste' ) {
				//print_r( $wk_nr );

				$wa_wi_typ = $mi_li_first;

				$wahl_arr[ 'Typ' ] = $wa_wi_typ;

				preg_match( '/(?<=\[).*?(?=\])/', $mi_li_val, $wk_nr_arr );
				//print_r( $wk_nr_arr );

				$wa_wi_data = $wk_nr_arr;

				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'typ' ] = $wa_wi_typ;
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'wahlkreisnummer' ] = $wk_nr_arr[0];

			} elseif ( $mi_li_first == 'Betreuter' ) {
				//print_r( $mi_li_val );

				$wa_wi_typ = 'Betreut';

				preg_match( '/(?<=\[).*?(?=\])/', $mi_li_val, $wk_nr_arr );
				//print_r( $wk_nr_arr );

				$wa_wi_data = $wk_nr_arr;

				$mdb_data_arr[ 'politik' ][ 'fraktion' ][ 'betreutewahlkreise' ] = $wk_nr_arr;

			} elseif ( $mi_li_first == 'Betreute' ) {
				//print_r( $mi_li_val );

				$wa_wi_typ = 'Betreut';

				preg_match_all( '/(?<=\[).*?(?=\])/', $mi_li_val, $wk_nr_arr );
				//print_r( $wk_nr_arr );

				$wa_wi_data = $wk_nr_arr;

				$mdb_data_arr[ 'politik' ][ 'fraktion' ][ 'betreutewahlkreise' ] = $wk_nr_arr;

			} elseif ( $mi_li_first == 'Ab' || $mi_li_first == 'Bis' ) {
				//print_r( $mi_li_val . PHP_EOL );

				$wa_wi_typ = 'Sonderfall';

				$vb_arr = explode( ' ', $mi_li_val );
				//print_r( $vb_arr );

				$wa_sf_typ = $vb_arr[ 0 ];
				$wa_sf_datum = $vb_arr[ 1 ];

				$wa_sf_grund = '';
				if ( count( $vb_arr ) == 3 ) {
					$clean_third_ele = substr( $vb_arr[ 2 ], 1, -1 );
					$vb_arr[ 2 ] = $clean_third_ele;
					$wa_sf_grund = $vb_arr[ 2 ];
				}

				$wa_wi_data = array(
					'Typ' => $wa_sf_typ,
					'Datum' => $wa_sf_datum,
					'Grund' => $wa_sf_grund,
				);

				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'sonderfall' ][ 'typ' ] = $wa_sf_typ;
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'sonderfall' ][ 'datum' ] = $wa_sf_datum;
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'sonderfall' ][ 'grund' ] = $wa_sf_grund;


			} else {
				die( 'nicht berücksichtigt' );
			}


			$wahl_arr[ $wa_wi_typ ] = $wa_wi_data;

		}
	}

	$mdb_pl_url = $baseurl . $mdb_pl_href;

	$src_file_name = basename( $mdb_pl_url );
	$src_path = 'tmp/spd/src/abg/';
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
	$mdb_pl_cb = $mdb_pl_xp->query( '//*[contains( @class, "map_box_content" )]' );

	/***/
	foreach ( $mdb_pl_cb as $mp_cb ) {
		//print_r($mpcb);

		$mp_cb_lis = $mp_cb->getElementsByTagName( 'li' );

		//$cb_header = $mpcb->getElementsByTagName( 'h3' );
		//print_r($cb_header->item(0)->nodeValue);

		$mp_cb_adr_arr = array();

		foreach ( $mp_cb_lis as $mp_cb_li ) {

			$mp_cb_li_adr_arr = array();
			$mp_cb_li_adr_eff = array();
			$mp_cb_li_kontakt = array();

			$kontakt_arr = $profil_url_daten_obj->kontakt;

			$buero_name = trim( get_element_by_tag_name_unique( $mp_cb_li, 'h3' ) );

			$buero_mail = mailto_remover(
				get_element_by_tag_name_unique(
					$mp_cb_li,
					'a',
					'href'
				)
			);

			$mp_cb_li_kontakt[ 'Mail' ] = $buero_mail;

			$buero_strort = get_element_by_tag_name_item_nr( $mp_cb_li, 'span', 0 );
			$buero_telfax = get_element_by_tag_name_item_nr( $mp_cb_li, 'span', 1 );

			$buero_strort_arr = explode( '|', $buero_strort );
			$buero_telfax_arr = explode( '|', $buero_telfax );

			array_trim( $buero_strort_arr );
			array_trim( $buero_telfax_arr );

			$buero_tel = $buero_telfax_arr[ 0 ];
			$buero_fax = $buero_telfax_arr[ 1 ];


			$cbuero_tel = trim(
				preg_replace(
					'/\s+/u',
					' ',
					preg_replace(
						'/[a-zA-Z\,\.\:\/\-\Ü]/',//[^0-9,.]
						'',
						$buero_tel
					)
				)
			);
			$mp_cb_li_kontakt[ 'Telefon' ] = $cbuero_tel;
			$cbuero_fax = trim(
				preg_replace(
					'/\s+/u',
					' ',
					preg_replace(
						'/[a-zA-Z\,\.\:\/\-\Ü]/',//[^0-9,.]
						'',
						$buero_fax
					)
				)
			);
			$mp_cb_li_kontakt[ 'Fax' ] = $cbuero_fax;

			$buero_strnr = $buero_strort_arr[ 0 ];
			$buero_plzort = $buero_strort_arr[ 1 ];

			//$strnr_arr = explode( ' ', $strnr );
			$buero_strnr_arr = preg_split( '/(?=\d)/', $buero_strnr, 2 );
			array_trim( $buero_strnr_arr );

			//print_r( $strnr_arr );
			if ( ! isset( $buero_strnr_arr[ 1 ] ) && $mdb_nn == 'Kiziltepe' ) {
				//print_r( $vorname . ' ' . $nachname . PHP_EOL );
				$buero_strnr_arr[ 1 ] = '4';
			}

			$buero_str = $buero_strnr_arr[ 0 ];
			$buero_hnr = $buero_strnr_arr[ 1 ];

			//$plzort_arr = explode( ' ', $plzort );
			$buero_plzort_arr = preg_split( '/(?!\d)/', $buero_plzort, 2 );
			array_trim( $buero_plzort_arr );
			$buero_plz = $buero_plzort_arr[ 0 ];
			$buero_ort = $buero_plzort_arr[ 1 ];

			$mp_cb_li_adr_eff[ 'full' ] = $mdb_vn
				. ' '
				. $mdb_nn
				. PHP_EOL
				//. $buero_name
				//. PHP_EOL
				. $buero_strnr
				. PHP_EOL
				. $buero_plzort;

			$mp_cb_li_adr_eff[ 'frag' ] = array(
				'Straße' => $buero_str,
				'Hausnummer' => $buero_hnr,
				'Postleitzahl' => $buero_plz,
				'Ort' => $buero_ort,
			);

			$mp_cb_li_adr_arr = array(
				'Bezeichnung' => $buero_name,
				'Adresse' => $mp_cb_li_adr_eff,
				'Kontakt' => $mp_cb_li_kontakt,
			);

			array_push( $mp_cb_adr_arr, $mp_cb_li_adr_arr );


			$kontakt_arr[ 'bezeichnung' ] = $buero_name;
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'strasse' ] = $buero_str;
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'hausnummer' ] = $buero_hnr;
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'postleitzahl' ] = $buero_plz;
			$kontakt_arr[ 'adresse' ][ 'fragmente' ][ 'ort' ] = $buero_ort;

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
			$kontakt_arr[ 'eda' ][ 'telefon' ] = $cbuero_tel;
			$kontakt_arr[ 'eda' ][ 'fax' ] = $cbuero_fax;

			$mdb_data_arr[ 'kontakte' ][] = $kontakt_arr;

		}
	}
	/***/


	$mdb_pl_ad_dn = $mdb_pl_dd->getElementById( 'article_detail' );

	$mdb_pl_ad_dd = new DomDocument;
	$mdb_pl_ad_dd->appendChild(
		$mdb_pl_ad_dd->importNode(
			$mdb_pl_ad_dn,
			true
		)
	);
	//print_r( $mdb_pl_ad_dd );

	$mdb_pl_ad_xp = new DOMXpath( $mdb_pl_ad_dd );
	//print_r( $mdb_pl_ad_xp );

	/***/
	$mdb_pl_ad_xp_ll = $mdb_pl_ad_xp->query( '//ul[contains( @class, "linklist" )]' );

	$linkliste_arr = array();

	foreach ( $mdb_pl_ad_xp_ll as $mpax_ll ) {
		//print_r( $mpax_ll );

		$mpax_ll_lis = $mdb_pl_ad_xp->query(
			'./li',
			$mpax_ll
		);
		//print_r( $mpax_ll_lis );

		foreach ( $mpax_ll_lis as $mpax_ll_li) {
			//print_r( $mpax_ll_li );

			$mpax_ll_li_val = $mpax_ll_li->nodeValue;

			if ( empty( $mpax_ll_li_val ) ) {
				continue;
			}
			//print_r( $mpax_ll_li->nodeValue . PHP_EOL );

			$mpax_ll_li_a = $mdb_pl_ad_xp->query(
				'./a',
				$mpax_ll_li
			);
			foreach ( $mpax_ll_li_a as $mlla ) {
				$mpax_ll_li_a_href = $mlla->getAttribute( 'href' );
			}
			if ( empty( $mpax_ll_li_a_href ) ) {
				continue;
			}
			//print_r( $mlla->getAttribute( 'href' ) . PHP_EOL );

			$link_arr = array(
				'txt_val' => $mpax_ll_li_val,
				'href' => $mpax_ll_li_a_href,
			);
			if ( preg_match( '/^homepage|website/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'Homepage' ] = $link_arr;
			} elseif ( preg_match( '/ Blog$/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'Homepage' ] = $link_arr;
			} elseif ( preg_match( '/^youtube/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'Youtube' ] = $link_arr;
			} elseif ( preg_match( '/^facebook/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'Facebook' ] = $link_arr;
			} elseif ( preg_match( '/^twitter/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'Twitter' ] = $link_arr;
			} elseif ( preg_match( '/^google+/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'Google+' ] = $link_arr;
			} elseif ( preg_match( '/^flickr/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'flickr' ] = $link_arr;
			} elseif ( preg_match( '/^xing/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'xing' ] = $link_arr;
			} elseif ( preg_match( '/^meinvz/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'meinvz' ] = $link_arr;
			} elseif ( preg_match( '/^studivz/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'studivz' ] = $link_arr;
			} elseif ( preg_match( '/^friendfeed/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'SozialeNetzwerke' ][ 'friendfeed' ] = $link_arr;
			} elseif ( preg_match( '/^abgeordnetenwatch/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'Sonstige' ][ 'spezifisch' ][ 'abgeordnetenwatch' ] = $link_arr;
			} elseif ( preg_match( '/^Reden im Videoarchiv des Bundestags/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'Bundestag' ][ 'Reden' ] = $link_arr;
			} elseif ( preg_match( '/^Porträt auf bundestag\.de/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'Bundestag' ][ 'Profil' ] = $link_arr;
			} elseif ( preg_match( '/^Landesgruppe/iu', $mpax_ll_li_val ) ) {
				$linkliste_arr[ 'Sonstige' ][ 'bereichsspezifisch' ][ 'Landesgruppe' ] = $link_arr;
			} else {
				$linkliste_arr[ 'Sonstige' ][ 'Unsortiert' ][ $mpax_ll_li_val ] = $link_arr;
			}


			$verw_arr = $profil_url_daten_obj->verweisbasis;
			$verw_arr[ 'text' ] = $mpax_ll_li_val;
			$verw_arr[ 'href' ] = $mpax_ll_li_a_href;

			if ( preg_match( '/^homepage|website/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'persoenlicheseite' ] = $verw_arr;
			} elseif ( preg_match( '/ Blog$/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'persoenlicheseite' ] = $verw_arr;
			} elseif ( preg_match( '/^youtube/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Youtube' ] = $verw_arr;
			} elseif ( preg_match( '/^facebook/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Facebook' ] = $verw_arr;
			} elseif ( preg_match( '/^twitter/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Twitter' ] = $verw_arr;
			} elseif ( preg_match( '/^google+/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'Google+' ] = $verw_arr;
			} elseif ( preg_match( '/^flickr/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'flickr' ] = $verw_arr;
			} elseif ( preg_match( '/^xing/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'xing' ] = $verw_arr;
			} elseif ( preg_match( '/^meinvz/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'meinvz' ] = $verw_arr;
			} elseif ( preg_match( '/^studivz/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'studivz' ] = $verw_arr;
			} elseif ( preg_match( '/^friendfeed/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sozialenetzwerke' ][ 'friendfeed' ] = $verw_arr;
			} elseif ( preg_match( '/^abgeordnetenwatch/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'spezifisch' ][ 'abgeordnetenwatch' ] = $verw_arr;
			} elseif ( preg_match( '/^Reden im Videoarchiv des Bundestags/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'bundestag' ][ 'reden' ] = $verw_arr;
			} elseif ( preg_match( '/^Porträt auf bundestag\.de/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'bundestag' ][ 'profil' ] = $verw_arr;
			} elseif ( preg_match( '/^Landesgruppe/iu', $mpax_ll_li_val ) ) {
				$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'bereich' ][ 'Landesgruppe' ] = $verw_arr;
			} else {
				$mdb_data_arr[ 'verweise' ][ 'sonstige' ][ 'unsortiert' ][ $mpax_ll_li_val ] = $verw_arr;
			}

		}

	}
	/***/

	/***/
	$mdb_pl_ad_xp_bg = $mdb_pl_ad_xp->query( '//dl[contains( @class, "block" )]' );

	$biogra_arr = array();

	foreach ( $mdb_pl_ad_xp_bg as $mpax_bg ) {
		//print_r($mpax_bg);

		$mpax_bg_dtdds = $mdb_pl_ad_xp->query(
			'./dt',
			$mpax_bg
		);
		//print_r( $mpax_bg_dtdds );

		foreach ( $mpax_bg_dtdds as $mpax_bg_dddt) {
			//print_r( $mpax_bg_dddt );
			//print_r( $mpax_bg_dddt->nextSibling->nextSibling );

			$mdb_bio_tb_key = substr( $mpax_bg_dddt->nodeValue, 0, -1 );
			$mdb_bio_tb_val = $mpax_bg_dddt->nextSibling->nextSibling->nodeValue;

			$biogra_arr[ $mdb_bio_tb_key ] = $mdb_bio_tb_val;

			if ( $mdb_bio_tb_key == 'Geburtsdatum' ) {
				preg_match( '/(\d{1,2}(.*?)(\d{4}))/', $mdb_bio_tb_val, $geb_dat_arr );
				//print_r( $geb_dat_arr[ 0 ] . PHP_EOL );

				$mdb_data_arr[ 'biografie' ][ 'geburtsdatum' ] = $geb_dat_arr[ 0 ];

				if ( strpos( $mdb_bio_tb_val, 'in' ) !== false ) {
					$geb_ort_arr = explode( 'in ', $mdb_bio_tb_val );
					//print_r( $geb_ort_arr );

					$mdb_data_arr[ 'biografie' ][ 'geburtsort' ] = $geb_ort_arr[1];
				}
			}
			if ( $mdb_bio_tb_key == 'Beruf' ) {
				$beruf_arr = explode( ',', $mdb_bio_tb_val );
				array_trim( $beruf_arr );

				$mdb_data_arr[ 'biografie' ][ 'beruf' ] = $beruf_arr;
			}
			if ( $mdb_bio_tb_key == 'Legislaturperioden' ) {
				$legper_arr = explode( '|', $mdb_bio_tb_val );
				array_trim( $legper_arr );

				$mdb_data_arr[ 'politik' ][ 'bundestag' ][ 'legislaturperioden' ] = $legper_arr;
			}
			if ( $mdb_bio_tb_key == 'Landesliste' ) {
				$mdb_data_arr[ 'politik' ][ 'wahl' ][ 'mandat' ][ 'bundesland' ] = $mdb_bio_tb_val;
			}
		}
		//print_r( $biogra_arr );
	}
	/***/

	$mdb_data_arr[ 'url' ] = $mdb_pl_url;
	$mdb_db[] = $mdb_data_arr;

	/***/
	$mdb_db_tmp[] = array(
		'Name' => $name_arr,
		'Kontaktdaten' => $mp_cb_adr_arr,
		//'Fraktion' => $ffkt_fl_arr,
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
<?php

/**
 * Profil URL Daten - Klasse und Struktur
 * Definition der Standardstruktur der Daten der Fraktionsprofilseite der
 * Mitglieder des Bundestags.
 * Vermittels der Klasse ProfilURLDaten, die zugleich
 * die Handhabung des Datencontainer-Arrays ermöglicht.
 *
 * @package mdb-parser
 */

/**
 * Klasse ProfilURLDaten
 *
 * @author ng
 */
class ProfilURLDaten {

	/**
	 * Profil URL Daten Container
	 *
	 * @var unknown
	 */
	public $profil_url_daten = array();

	/**
	 * Namensbestandeteile
	 *
	 * @var array
	 */
	public $name = array();

	/**
	 * Nachname
	 * > Namensbestandteile
	 *
	 * @var string
	 */
	public $nachname = '';

	/**
	 * Vorname
	 * > Namensbestandteile
	 *
	 * @var string
	 */
	public $vorname = '';

	/**
	 * Adelszusatz
	 * > Namensbestandteile
	 *
	 * @var string
	 */
	public $adelszusatz = '';

	/**
	 * Titel
	 * > Namensbestandteile
	 *
	 * @var string
	 */
	public $titel = '';

	/**
	 * Kontaktdaten
	 *
	 * @var array
	 */
	public $kontakte = array();

	/**
	 * Kontakt
	 * > Kontaktdaten
	 *
	 * @var array
	 */
	public $kontakt = array();

	/**
	 * Bezeichnung der Kontaktmöglichkeit
	 * > Kontaktdaten > Kontakt
	 *
	 * @var string
	 */
	public $kontakt_bezeichnung = '';

	/**
	 * Adressdaten des Kontakts
	 * > Kontaktdaten > Kontakt
	 *
	 * @var array
	 */
	public $kontakt_adresse = array();

	/**
	 * Adressfragmente
	 * > Kontaktdaten > Kontakt > Adresse
	 *
	 * @var array
	 */
	public $kontakt_adresse_fragmente = array();

	/**
	 * Kontaktadressenbestandteil: Straße
	 * > Kontaktdaten > Kontakt > Adresse > Fragmente
	 *
	 * @var string
	 */
	public $kontakt_adresse_strasse = '';

	/**
	 * Kontaktadressenbestandteil: Hausnummer
	 * > Kontaktdaten > Kontakt > Adresse > Fragmente
	 *
	 * @var string
	 */
	public $kontakt_adresse_hausnummer = '';

	/**
	 * Kontaktadressenbestandteil: Postleitzahl
	 * > Kontaktdaten > Kontakt > Adresse > Fragmente
	 *
	 * @var string
	 */
	public $kontakt_adresse_postleitzahl = '';

	/**
	 * Kontaktadressenbestandteil: Ort
	 * > Kontaktdaten > Kontakt > Adresse > Fragmente
	 *
	 * @var string
	 */
	public $kontakt_adresse_ort = '';

	/**
	 * Komplette Adresse
	 * > Kontaktdaten > Kontakt > Adresse
	 *
	 * @var string
	 */
	public $kontakt_adresse_komplett = '';

	/**
	 * Elektronische Datenaustausch (EDA) Informationen des Kontakts
	 * > Kontaktdaten > Kontakt
	 *
	 * @var array
	 */
	public $kontakt_eda = array();

	/**
	 * Mailadresse des Kontakts
	 * > Kontaktdaten > Kontakt > EDA
	 *
	 * @var string
	 */
	public $eda_mail = '';

	/**
	 * Telefonnummer des Kontakts
	 * > Kontaktdaten > Kontakt > EDA
	 *
	 * @var string
	 */
	public $eda_telefon = '';

	/**
	 * Faxnummer des Kontakts
	 * > Kontaktdaten > Kontakt > EDA
	 *
	 * @var string
	 */
	public $eda_fax = '';

	/**
	 * Politik
	 *
	 * @var array
	 */
	public $politik = array();

	/**
	 * Wahl
	 * > Politik
	 * Elemente: Mandat, Sonderfall
	 *
	 * @var array
	 */
	public $wahl = array();

	/**
	 * Mandat
	 * > Politik > Wahl
	 *
	 * @var array
	 */
	public $mandat = array();

	/**
	 * Mandatstyp
	 * > Politik > Wahl > Mandat
	 *
	 * @var string
	 */
	public $mandat_typ = '';

	/**
	 * Wahlkreisnummer
	 * > Politik > Wahl > Mandat
	 *
	 * @var string
	 */
	public $mandat_wahlkreisnummer = '';

	/**
	 * Bundesland - Mandat
	 * > Politik > Wahl > Mandat
	 *
	 * @var string
	 */
	public $mandat_bundesland = '';

	/**
	 * Sonderfall
	 * > Politik > Wahl
	 *
	 * @var array
	 */
	public $sonderfall = array();

	/**
	 * Typ - Sonderfall
	 * > Politik > Wahl > Sonderfall
	 *
	 * @var string
	 */
	public $sonderfall_typ = '';

	/**
	 * Datum - Sonderfall
	 * > Politik > Wahl > Sonderfall
	 *
	 * @var string
	 */
	public $sonderfall_datum = '';

	/**
	 * Bedingung - Sonderfall
	 * > Politik > Wahl > Sonderfall
	 *
	 * @var string
	 */
	public $sonderfall_bedingung = '';

	/**
	 * Fraktion
	 * > Politik
	 * Elemente: Funktionen, Arbeitskreise, betreute Wahlkreise
	 *
	 * @var array
	 */
	public $fraktion = array();

	/**
	 * Funktionen in Fraktion
	 * > Politik > Fraktion
	 *
	 * @var array
	 */
	public $fraktion_funktionen = array();

	/**
	 * Arbeitskreise in Fraktion
	 * > Politik > Fraktion
	 *
	 * @var array
	 */
	public $fraktion_arbeitskreise = array();

	/**
	 * Betreute Wahlkreise
	 * > Politik > Fraktion
	 *
	 * @var array
	 */
	public $betreutewahlkreise = array();

	/**
	 * Bundestag
	 * > Politik
	 *
	 * @var array
	 */
	public $bundestag = array();

	/**
	 * Funktionen im Bundestag
	 * > Politik > Bundestag
	 *
	 * @var array
	 */
	public $bundestag_funktionen = array();

	/**
	 * Legislaturperioden
	 * > Politik > Bundestag
	 *
	 * @var array
	 */
	public $legislaturperioden = array();

	/**
	 * Verweise
	 * Elemente: persönliche Seite, Bundestag, soziale Netzwerke, Sonstige
	 *
	 * @var array
	 */
	public $verweise = array();

	/**
	 * Verweisbasis
	 *
	 * @var array
	 */
	public $verweisbasis = array( 'text' => '', 'href' => '' );

	/**
	 * Persoenliche Seite
	 * > Verweise
	 *
	 * @var array
	 */
	public $persoenlicheseite = array();

	/**
	 * Verweise zu Bundestag
	 * > Verweise
	 *
	 * @var array
	 */
	public $verweise_bundestag = array();

	/**
	 * Verweise zu Bundestagsprofil
	 * > Verweise > Bundestag
	 *
	 * @var array
	 */
	public $verweise_bundestag_profil = array();

	/**
	 * Verweise zu Bundestagsreden
	 * > Verweise > Bundestag
	 *
	 * @var array
	 */
	public $verweise_bundestag_reden = array();

	/**
	 * Verweise zu sozialen Netzwerken
	 * > Verweise
	 *
	 * @var array
	 */
	public $verweise_sozialenetzwerke = array();

	/**
	 * Sonstige Verweise
	 * > Verweise
	 *
	 * @var array
	 */
	public $verweise_sonstige = array();

	/**
	 * Spezielle sonstige Verweise
	 * > Verweise > Sonstige
	 *
	 * @var array
	 */
	public $verweise_sonstige_speziell = array();

	/**
	 * Bereichsspezifische sonstige Verweise
	 * > Verweise > Sonstige
	 *
	 * @var array
	 */
	public $verweise_sonstige_bereich = array();

	/**
	 * Unsortierte sonstige Verweise
	 * > Verweise > Sonstige
	 *
	 * @var array
	 */
	public $verweise_sonstige_unsortiert = array();

	/**
	 * Biografie
	 * Elemente: Geburtsdatum, Geburtsort, Beruf(e)
	 *
	 * @var array
	 */
	public $biografie = array();

	/**
	 * Geburtsdatum
	 * > Biografie
	 *
	 * @var string
	 */
	public $biografie_geburtsdatum = '';

	/**
	 * Geburtsort
	 * > Biografie
	 *
	 * @var string
	 */
	public $biografie_geburtsort = '';

	/**
	 * Beruf(e)
	 * > Biografie
	 *
	 * @var string
	 */
	public $biografie_beruf = array();

	/**
	 * Profil URL
	 *
	 * @var string
	 */
	public $url = '';

	/**
	 * Profilbild
	 *
	 * @var string
	 */
	public $profilbild = '';

	function __construct() {

		/**
		 * Setup
		 */
		$this->profil_url_daten['name'] = $this->name;
		$this->profil_url_daten['kontakte'] = $this->kontakte;
		$this->profil_url_daten['politik'] = $this->politik;
		$this->profil_url_daten['verweise'] = $this->verweise;
		$this->profil_url_daten['biografie'] = $this->biografie;
		$this->profil_url_daten['url'] = $this->url;
		$this->profil_url_daten['profilbild'] = $this->profilbild;

		/**
		 * Setup > Name
		 */
		$this->profil_url_daten['name']['nachname'] = $this->nachname;
		$this->profil_url_daten['name']['vorname'] = $this->vorname;
		$this->profil_url_daten['name']['adelszusatz'] = $this->adelszusatz;
		$this->profil_url_daten['name']['titel'] = $this->titel;

		/**
		 * Setup > Politik
		 */
		$this->profil_url_daten['politik']['wahl'] = $this->wahl;
		$this->profil_url_daten['politik']['fraktion'] = $this->fraktion;
		$this->profil_url_daten['politik']['bundestag'] = $this->bundestag;

		/**
		 * Setup > Politik > Wahl
		 */
		$this->profil_url_daten['politik']['wahl']['mandat'] = $this->mandat;
		$this->profil_url_daten['politik']['wahl']['sonderfall'] = $this->sonderfall;

		/**
		 * Setup > Politik > Wahl > Mandat
		 */
		$this->profil_url_daten['politik']['wahl']['mandat']['typ'] = $this->mandat_typ;
		$this->profil_url_daten['politik']['wahl']['mandat']['wahlkreisnummer'] = $this->mandat_wahlkreisnummer;
		$this->profil_url_daten['politik']['wahl']['mandat']['bundesland'] = $this->mandat_bundesland;

		/**
		 * Setup > Politik > Wahl > Sonderfall
		 */
		$this->profil_url_daten['politik']['wahl']['sonderfall']['typ'] = $this->sonderfall_typ;
		$this->profil_url_daten['politik']['wahl']['sonderfall']['datum'] = $this->sonderfall_datum;
		$this->profil_url_daten['politik']['wahl']['sonderfall']['bedingung'] = $this->sonderfall_bedingung;

		/**
		 * Setup > Politik > Fraktion
		 */
		$this->profil_url_daten['politik']['fraktion']['funktionen'] = $this->fraktion_funktionen;
		$this->profil_url_daten['politik']['fraktion']['arbeitskreise'] = $this->fraktion_arbeitskreise;
		$this->profil_url_daten['politik']['fraktion']['betreutewahlkreise'] = $this->betreutewahlkreise;

		/**
		 * Setup > Politik > Bundestag
		 */
		$this->profil_url_daten['politik']['bundestag']['funktionen'] = $this->bundestag_funktionen;
		$this->profil_url_daten['politik']['bundestag']['legislaturperioden'] = $this->legislaturperioden;

		/**
		 * Setup > Verweise
		 */
		$this->profil_url_daten['verweise']['persoenlicheseite'] = $this->persoenlicheseite;
		$this->profil_url_daten['verweise']['bundestag'] = $this->verweise_bundestag;
		$this->profil_url_daten['verweise']['sozialenetzwerke'] = $this->verweise_sozialenetzwerke;
		$this->profil_url_daten['verweise']['sonstige'] = $this->verweise_sonstige;

		/**
		 * Setup > Verweise > Bundestag
		 */
		$this->profil_url_daten['verweise']['bundestag']['profil'] = $this->verweise_bundestag_profil;
		$this->profil_url_daten['verweise']['bundestag']['reden'] = $this->verweise_bundestag_reden;

		/**
		 * Setup > Verweise > Sonstige
		 */
		$this->profil_url_daten['verweise']['sonstige']['speziell'] = $this->verweise_sonstige_speziell;
		$this->profil_url_daten['verweise']['sonstige']['bereich'] = $this->verweise_sonstige_bereich;
		$this->profil_url_daten['verweise']['sonstige']['unsortiert'] = $this->verweise_sonstige_unsortiert;

		/**
		 * Setup > Biografie
		 */
		$this->profil_url_daten['biografie']['geburtsdatum'] = $this->biografie_geburtsdatum;
		$this->profil_url_daten['biografie']['geburtsort'] = $this->biografie_geburtsort;
		$this->profil_url_daten['biografie']['beruf'] = $this->biografie_beruf;

		/**
		 * Setup > Kontaktdaten > Kontakt
		 */
		$this->kontakt['bezeichnung'] = $this->kontakt_bezeichnung;
		$this->kontakt['adresse'] = $this->kontakt_adresse;
		$this->kontakt['eda'] = $this->kontakt_eda;

		/**
		 * Setup > Kontaktdaten > Kontakt > Adresse
		 */
		$this->kontakt['adresse']['fragmente'] = $this->kontakt_adresse_fragmente;
		$this->kontakt['adresse']['komplett'] = $this->kontakt_adresse_komplett;

		/**
		 * Setup > Kontaktdaten > Kontakt > Adresse > Fragmente
		 */
		$this->kontakt['adresse']['fragmente']['strasse'] = $this->kontakt_adresse_strasse;
		$this->kontakt['adresse']['fragmente']['hausnummer'] = $this->kontakt_adresse_hausnummer;
		$this->kontakt['adresse']['fragmente']['postleitzahl'] = $this->kontakt_adresse_postleitzahl;
		$this->kontakt['adresse']['fragmente']['ort'] = $this->kontakt_adresse_ort;

		/**
		 * Setup > Kontaktdaten > Kontakt > EDA
		 */
		$this->kontakt['eda']['mail'] = $this->eda_mail;
		$this->kontakt['eda']['telefon'] = $this->eda_telefon;
		$this->kontakt['eda']['fax'] = $this->eda_fax;

		//$this->profil_url_daten['kontakte'][] = $this->kontakt;
	}

	public function pud_dump( $pre = FALSE ) {
		if ( $pre ) {
			echo '<pre>';
		}
		print_r( $this->profil_url_daten );
		if ( $pre ) {
			echo '</pre>';
		}
	}

	/**
	 *
	 * @return the unknown
	 */
	public function get_profil_url_daten() {
		return $this->profil_url_daten;
	}

	/**
	 *
	 * @param array $profil_url_daten
	 */
	public function set_profil_url_daten( array $profil_url_daten ) {
		$this->profil_url_daten = $profil_url_daten;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 *
	 * @param array $name
	 */
	public function set_name( array $name ) {
		$this->name = $name;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_nachname() {
		return $this->nachname;
	}

	/**
	 *
	 * @param string $nachname
	 */
	public function set_nachname( $nachname ) {
		$this->nachname = $nachname;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_vorname() {
		return $this->vorname;
	}

	/**
	 *
	 * @param
	 *        $vorname
	 */
	public function set_vorname( $vorname ) {
		$this->vorname = $vorname;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_adelszusatz() {
		return $this->adelszusatz;
	}

	/**
	 *
	 * @param
	 *        $adelszusatz
	 */
	public function set_adelszusatz( $adelszusatz ) {
		$this->adelszusatz = $adelszusatz;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_titel() {
		return $this->titel;
	}

	/**
	 *
	 * @param
	 *        $titel
	 */
	public function set_titel( $titel ) {
		$this->titel = $titel;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_kontakte() {
		return $this->kontakte;
	}

	/**
	 *
	 * @param array $kontakte
	 */
	public function set_kontakte( array $kontakte ) {
		$this->kontakte = $kontakte;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_kontakt() {
		return $this->kontakt;
	}

	/**
	 *
	 * @param array $kontakt
	 */
	public function set_kontakt( array $kontakt ) {
		$this->kontakt = $kontakt;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_kontakt_bezeichnung() {
		return $this->kontakt_bezeichnung;
	}

	/**
	 *
	 * @param
	 *        $kontakt_bezeichnung
	 */
	public function set_kontakt_bezeichnung( $kontakt_bezeichnung ) {
		$this->kontakt_bezeichnung = $kontakt_bezeichnung;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_kontakt_adresse() {
		return $this->kontakt_adresse;
	}

	/**
	 *
	 * @param array $kontakt_adresse
	 */
	public function set_kontakt_adresse( array $kontakt_adresse ) {
		$this->kontakt_adresse = $kontakt_adresse;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_kontakt_adresse_fragmente() {
		return $this->kontakt_adresse_fragmente;
	}

	/**
	 *
	 * @param array $kontakt_adresse_fragmente
	 */
	public function set_kontakt_adresse_fragmente(
		array $kontakt_adresse_fragmente ) {
		$this->kontakt_adresse_fragmente = $kontakt_adresse_fragmente;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_kontakt_adresse_strasse() {
		return $this->kontakt_adresse_strasse;
	}

	/**
	 *
	 * @param
	 *        $kontakt_adresse_strasse
	 */
	public function set_kontakt_adresse_strasse( $kontakt_adresse_strasse ) {
		$this->kontakt_adresse_strasse = $kontakt_adresse_strasse;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_kontakt_adresse_hausnummer() {
		return $this->kontakt_adresse_hausnummer;
	}

	/**
	 *
	 * @param
	 *        $kontakt_adresse_hausnummer
	 */
	public function set_kontakt_adresse_hausnummer( $kontakt_adresse_hausnummer ) {
		$this->kontakt_adresse_hausnummer = $kontakt_adresse_hausnummer;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_kontakt_adresse_postleitzahl() {
		return $this->kontakt_adresse_postleitzahl;
	}

	/**
	 *
	 * @param
	 *        $kontakt_adresse_postleitzahl
	 */
	public function set_kontakt_adresse_postleitzahl(
		$kontakt_adresse_postleitzahl ) {
		$this->kontakt_adresse_postleitzahl = $kontakt_adresse_postleitzahl;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_kontakt_adresse_ort() {
		return $this->kontakt_adresse_ort;
	}

	/**
	 *
	 * @param
	 *        $kontakt_adresse_ort
	 */
	public function set_kontakt_adresse_ort( $kontakt_adresse_ort ) {
		$this->kontakt_adresse_ort = $kontakt_adresse_ort;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_kontakt_adresse_komplett() {
		return $this->kontakt_adresse_komplett;
	}

	/**
	 *
	 * @param
	 *        $kontakt_adresse_komplett
	 */
	public function set_kontakt_adresse_komplett( $kontakt_adresse_komplett ) {
		$this->kontakt_adresse_komplett = $kontakt_adresse_komplett;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_kontakt_eda() {
		return $this->kontakt_eda;
	}

	/**
	 *
	 * @param array $kontakt_eda
	 */
	public function set_kontakt_eda( array $kontakt_eda ) {
		$this->kontakt_eda = $kontakt_eda;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_eda_mail() {
		return $this->eda_mail;
	}

	/**
	 *
	 * @param
	 *        $eda_mail
	 */
	public function set_eda_mail( $eda_mail ) {
		$this->eda_mail = $eda_mail;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_eda_telefon() {
		return $this->eda_telefon;
	}

	/**
	 *
	 * @param
	 *        $eda_telefon
	 */
	public function set_eda_telefon( $eda_telefon ) {
		$this->eda_telefon = $eda_telefon;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_eda_fax() {
		return $this->eda_fax;
	}

	/**
	 *
	 * @param
	 *        $eda_fax
	 */
	public function set_eda_fax( $eda_fax ) {
		$this->eda_fax = $eda_fax;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_politik() {
		return $this->politik;
	}

	/**
	 *
	 * @param array $politik
	 */
	public function set_politik( array $politik ) {
		$this->politik = $politik;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_wahl() {
		return $this->wahl;
	}

	/**
	 *
	 * @param array $wahl
	 */
	public function set_wahl( array $wahl ) {
		$this->wahl = $wahl;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_mandat() {
		return $this->mandat;
	}

	/**
	 *
	 * @param array $mandat
	 */
	public function set_mandat( array $mandat ) {
		$this->mandat = $mandat;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_mandat_typ() {
		return $this->mandat_typ;
	}

	/**
	 *
	 * @param
	 *        $mandat_typ
	 */
	public function set_mandat_typ( $mandat_typ ) {
		$this->mandat_typ = $mandat_typ;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_mandat_wahlkreisnummer() {
		return $this->mandat_wahlkreisnummer;
	}

	/**
	 *
	 * @param
	 *        $mandat_wahlkreisnummer
	 */
	public function set_mandat_wahlkreisnummer( $mandat_wahlkreisnummer ) {
		$this->mandat_wahlkreisnummer = $mandat_wahlkreisnummer;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_mandat_bundesland() {
		return $this->mandat_bundesland;
	}

	/**
	 *
	 * @param
	 *        $mandat_bundesland
	 */
	public function set_mandat_bundesland( $mandat_bundesland ) {
		$this->mandat_bundesland = $mandat_bundesland;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_sonderfall() {
		return $this->sonderfall;
	}

	/**
	 *
	 * @param array $sonderfall
	 */
	public function set_sonderfall( array $sonderfall ) {
		$this->sonderfall = $sonderfall;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_sonderfall_typ() {
		return $this->sonderfall_typ;
	}

	/**
	 *
	 * @param
	 *        $sonderfall_typ
	 */
	public function set_sonderfall_typ( $sonderfall_typ ) {
		$this->sonderfall_typ = $sonderfall_typ;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_sonderfall_datum() {
		return $this->sonderfall_datum;
	}

	/**
	 *
	 * @param
	 *        $sonderfall_datum
	 */
	public function set_sonderfall_datum( $sonderfall_datum ) {
		$this->sonderfall_datum = $sonderfall_datum;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_sonderfall_bedingung() {
		return $this->sonderfall_bedingung;
	}

	/**
	 *
	 * @param
	 *        $sonderfall_bedingung
	 */
	public function set_sonderfall_bedingung( $sonderfall_bedingung ) {
		$this->sonderfall_bedingung = $sonderfall_bedingung;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_fraktion() {
		return $this->fraktion;
	}

	/**
	 *
	 * @param array $fraktion
	 */
	public function set_fraktion( array $fraktion ) {
		$this->fraktion = $fraktion;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_fraktion_funktionen() {
		return $this->fraktion_funktionen;
	}

	/**
	 *
	 * @param array $fraktion_funktionen
	 */
	public function set_fraktion_funktionen( array $fraktion_funktionen ) {
		$this->fraktion_funktionen = $fraktion_funktionen;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_fraktion_arbeitskreise() {
		return $this->fraktion_arbeitskreise;
	}

	/**
	 *
	 * @param array $fraktion_arbeitskreise
	 */
	public function set_fraktion_arbeitskreise( array $fraktion_arbeitskreise ) {
		$this->fraktion_arbeitskreise = $fraktion_arbeitskreise;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_betreutewahlkreise() {
		return $this->betreutewahlkreise;
	}

	/**
	 *
	 * @param array $betreutewahlkreise
	 */
	public function set_betreutewahlkreise( array $betreutewahlkreise ) {
		$this->betreutewahlkreise = $betreutewahlkreise;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_bundestag() {
		return $this->bundestag;
	}

	/**
	 *
	 * @param array $bundestag
	 */
	public function set_bundestag( array $bundestag ) {
		$this->bundestag = $bundestag;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_bundestag_funktionen() {
		return $this->bundestag_funktionen;
	}

	/**
	 *
	 * @param array $bundestag_funktionen
	 */
	public function set_bundestag_funktionen( array $bundestag_funktionen ) {
		$this->bundestag_funktionen = $bundestag_funktionen;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_legislaturperioden() {
		return $this->legislaturperioden;
	}

	/**
	 *
	 * @param array $legislaturperioden
	 */
	public function set_legislaturperioden( array $legislaturperioden ) {
		$this->legislaturperioden = $legislaturperioden;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise() {
		return $this->verweise;
	}

	/**
	 *
	 * @param array $verweise
	 */
	public function set_verweise( array $verweise ) {
		$this->verweise = $verweise;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweisbasis() {
		return $this->verweisbasis;
	}

	/**
	 *
	 * @param array $verweisbasis
	 */
	public function set_verweisbasis( array $verweisbasis ) {
		$this->verweisbasis = $verweisbasis;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_persoenlicheseite() {
		return $this->persoenlicheseite;
	}

	/**
	 *
	 * @param array $persoenlicheseite
	 */
	public function set_persoenlicheseite( array $persoenlicheseite ) {
		$this->persoenlicheseite = $persoenlicheseite;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_bundestag() {
		return $this->verweise_bundestag;
	}

	/**
	 *
	 * @param array $verweise_bundestag
	 */
	public function set_verweise_bundestag( array $verweise_bundestag ) {
		$this->verweise_bundestag = $verweise_bundestag;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_bundestag_profil() {
		return $this->verweise_bundestag_profil;
	}

	/**
	 *
	 * @param array $verweise_bundestag_profil
	 */
	public function set_verweise_bundestag_profil(
		array $verweise_bundestag_profil ) {
		$this->verweise_bundestag_profil = $verweise_bundestag_profil;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_bundestag_reden() {
		return $this->verweise_bundestag_reden;
	}

	/**
	 *
	 * @param array $verweise_bundestag_reden
	 */
	public function set_verweise_bundestag_reden(
		array $verweise_bundestag_reden ) {
		$this->verweise_bundestag_reden = $verweise_bundestag_reden;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_sozialenetzwerke() {
		return $this->verweise_sozialenetzwerke;
	}

	/**
	 *
	 * @param array $verweise_sozialenetzwerke
	 */
	public function set_verweise_sozialenetzwerke(
		array $verweise_sozialenetzwerke ) {
		$this->verweise_sozialenetzwerke = $verweise_sozialenetzwerke;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_sonstige() {
		return $this->verweise_sonstige;
	}

	/**
	 *
	 * @param array $verweise_sonstige
	 */
	public function set_verweise_sonstige( array $verweise_sonstige ) {
		$this->verweise_sonstige = $verweise_sonstige;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_sonstige_speziell() {
		return $this->verweise_sonstige_speziell;
	}

	/**
	 *
	 * @param array $verweise_sonstige_speziell
	 */
	public function set_verweise_sonstige_speziell(
		array $verweise_sonstige_speziell ) {
		$this->verweise_sonstige_speziell = $verweise_sonstige_speziell;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_sonstige_bereich() {
		return $this->verweise_sonstige_bereich;
	}

	/**
	 *
	 * @param array $verweise_sonstige_bereich
	 */
	public function set_verweise_sonstige_bereich(
		array $verweise_sonstige_bereich ) {
		$this->verweise_sonstige_bereich = $verweise_sonstige_bereich;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_verweise_sonstige_unsortiert() {
		return $this->verweise_sonstige_unsortiert;
	}

	/**
	 *
	 * @param array $verweise_sonstige_unsortiert
	 */
	public function set_verweise_sonstige_unsortiert(
		array $verweise_sonstige_unsortiert ) {
		$this->verweise_sonstige_unsortiert = $verweise_sonstige_unsortiert;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_biografie() {
		return $this->biografie;
	}

	/**
	 *
	 * @param array $biografie
	 */
	public function set_biografie( array $biografie ) {
		$this->biografie = $biografie;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_biografie_geburtsdatum() {
		return $this->biografie_geburtsdatum;
	}

	/**
	 *
	 * @param string $biografie_geburtsdatum
	 */
	public function set_biografie_geburtsdatum( $biografie_geburtsdatum ) {
		$this->biografie_geburtsdatum = $biografie_geburtsdatum;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_biografie_geburtsort() {
		return $this->biografie_geburtsort;
	}

	/**
	 *
	 * @param string $biografie_geburtsort
	 */
	public function set_biografie_geburtsort( $biografie_geburtsort ) {
		$this->biografie_geburtsort = $biografie_geburtsort;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function get_biografie_beruf() {
		return $this->biografie_beruf;
	}

	/**
	 *
	 * @param array $biografie_beruf
	 *        Numerisches Array, dass den oder die Berufe enthält
	 */
	public function set_biografie_beruf( $biografie_beruf ) {
		$this->biografie_beruf = $biografie_beruf;
		return $this;
	}

	/**
	 * Get Variable $url
	 *
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Set Variable $url
	 *
	 * @param string $url
	 */
	public function set_url( $url ) {
		$this->url = $url;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function get_profilbild() {
		return $this->profilbild;
	}

	/**
	 *
	 * @param string $profilbild
	 */
	public function set_profilbild( $profilbild ) {
		$this->profilbild = $profilbild;
		return $this;
	}
}
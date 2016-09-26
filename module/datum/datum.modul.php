<?php
class Datum extends Modul {
	private $wochentage = Array("1" => "Montag", "2" => "Dienstag",
		"3" => "Mittwoch", "4" => "Donnerstag", "5" => "Freitag",
		"6" => "Samstag", "7" => "Sonntag");
	private $monate = Array("1" => "Januar", "2" => "Februar", "3" => "März",
		"4" => "April", "5" => "Mai", "6" => "Juni", "7" => "Juli", 
		"8" => "August", "9" => "September", "10" => "Oktober",
		"11" => "November", "12" => "Dezember");

	public function datenLaden($datenbank) {
		$wochentag = $this->wochentage[date("N")];
		$monat = $this->monate[date("n")];
		$tag = date("j");
		$kalenderwoche = date("W");
		$jahr = date("Y");

		$this->templateVars["wochentag"] = $wochentag;
		$this->templateVars["monat"] = $monat;
		$this->templateVars["tag"] = $tag;
		$this->templateVars["kalenderwoche"] = $kalenderwoche;
		$this->templateVars["jahr"] = $jahr;

		parent::datenLaden($datenbank);
	}
}
?>
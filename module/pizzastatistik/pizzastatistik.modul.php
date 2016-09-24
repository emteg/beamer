<?php
class Pizzastatistik extends Modul {
	public function datenLaden($datenbank) {
		
    try {
      $daten = @file_get_contents("http://pizza.hadiko.de/Aperture_Science_-_We_do_what_we_must_because_we_can") or false;
    } catch (Exception $e) {
      $daten = false;
    }
		// Encoding?
		if ($daten !== false) {
			$verkaufe = $this->csvLesen($daten);
		} else {
			$verkaufe = Array();
		}
		
		$this->templateVars["verkaufe"] = $verkaufe;
		$this->templateVars["limit"] = $this->limitAuslesen("pizzastatistikAnzahl");
    parent::datenLaden($datenbank);
		
	}
	
	private function csvLesen($daten) {
		
		$result = Array();
		$zeilen = explode("\n", $daten);
		$limit = $this->limitAuslesen("pizzastatistikAnzahl");
		
		foreach ($zeilen as $zeile) {
		
			$neu = $this->zeileLesen(explode(";", $zeile));
			
			if ($neu !== true) {
				$result[] = $neu;
			}
			
			if (count($result) == $limit) {
				break;
			}
			
		}
	
		return $result;
	}
	
	private function zeileLesen($werte) {
		
		if (isset($werte[0]) && isset($werte[1])) {
		
			$name = $werte[0];
			$verkauft = $werte[1];
		
			return Array("Name" => $name, "Verkauft" => $verkauft);
		} else {
			return false;
		}
		
	}
	
}
?>
<?php
class Verkaufsstatistik extends Modul {
	public function datenLaden($datenbank) {
		
		$daten = file_get_contents("http://localhost/kasse/stats.php?format=csv");
		
		if ($daten !== false) {
			$verkaufe = $this->csvLesen(@mb_convert_encoding($daten, "UTF-8", "Windows-1252 (CP1252)"));
		} else {
			$verkaufe = Array();
		}
		
		$this->templateVars["verkaufe"] = $verkaufe;
		$this->templateVars["limit"] = $this->limitAuslesen("verkaufsstatistikAnzahl");
    parent::datenLaden($datenbank);
		
	}
	
	private function csvLesen($daten) {
		
		$result = Array();
		$zeilen = explode("\n", $daten);
		$limit = $this->limitAuslesen("verkaufsstatistikAnzahl");
		
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
			$istLeer = false;
			
			if ($hatMenge = isset($werte[2])) {
				$menge = $werte[2];
				if ($menge == $verkauft) {
					$istLeer = true;
				}
				$prozent = number_format($verkauft / $menge * 100, 0);
			} else {
				$menge = 0;
				$prozent = 0;
			}
		
			return Array("Name" => $name, "Verkauft" => $verkauft, 
				"HatMenge" => $hatMenge, "Menge" => $menge, "IstLeer" => $istLeer,
				"Prozent" => $prozent);
		} else {
			return false;
		}
		
	}
	
}
?>
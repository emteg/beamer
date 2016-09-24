<?php
class Bildseite extends Modul {
	public function datenLaden($datenbank) {
	
		$letzteSeite = $this->letzteSeitenIdAuslesen();
		$this->neueSeiteLaden($letzteSeite, $datenbank);
    parent::datenLaden($datenbank);
		
	}
	
	private function letzteSeitenIdAuslesen() {
		
		if (isset($_COOKIE["bildSeiteLetzteId"])) {
			return $_COOKIE["bildSeiteLetzteId"];
		} else {
			return 0;
		}

	}
	
	private function neueSeiteLaden($letzteSeite, $datenbank) {
	
		$records = $this->aktiveSeitenLaden($datenbank);
		
		if (count($records) > 0) {
		
			$index = $this->naechsteSeiteBestimmen($records, $letzteSeite);
			$this->seitenInhalteSpeichern($records[$index]);
			$this->neueSeitenIdSetzen($records[$index]["Id"]);
			
		} else {	// Keine aktive Seite vorhanden
			$this->templateVars["hatDaten"] = false;
			$this->neueSeitenIdSetzen(0);
		}	
		
	}
	
	private function seitenInhalteSpeichern($record) {
	
		$this->templateVars["hatDaten"] = true;
		$this->templateVars["id"] = $record["Id"];	
		$this->templateVars["extension"] = $record["Extension"];
		$this->templateVars["beschriftung"] = nl2br($record["Beschriftung"]);
		$this->templateVars["layout"] = $record["Layout"];
		
	}
	
	private function aktiveSeitenLaden($datenbank) {
	
		$sql = "
			SELECT 
				*
			FROM 
				`bildseite` 
			WHERE 
				ZeigenAb <= NOW() AND 
				(ZeigenBis IS NULL OR ZeigenBis >= NOW())";
		
		return $datenbank->queryDirektArray($sql);
		
	}
	
	private function naechsteSeiteBestimmen($records, $letzteSeite) {
	
		foreach ($records as $key => $record) {
			if ($record["Id"] > $letzteSeite) {
				return $key;			// Index der nächsten Seite zurückgeben
			}
		}
		return 0;						// oder Index der ersten Seite zurückgeben
		
	}
	
	private function neueSeitenIdSetzen($seitenId) {
		
		setcookie("bildSeiteLetzteId", $seitenId);
		
	}
}
?>
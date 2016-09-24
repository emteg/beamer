<?php
class Spielderstunde extends Modul {
	public function datenLaden($datenbank) {
		$termine = Array();
		
		$sql = "
			SELECT
				*
			FROM 
				`event` 
			WHERE 
				(Beginn >= NOW() OR 
				(Beginn <= NOW() AND Ende >= NOW())) AND
				Kategorie = 2
			ORDER BY
				Beginn ASC
			LIMIT " . $this->limitAuslesen("zeitplanAnzahl");
		$records = $datenbank->queryDirektArray($sql);
		
		foreach ($records as $record) {
			$termine[] = $this->eventVerarbeiten($record);
		}
    
		
		$this->templateVars["termine"] = $termine;
		$this->templateVars["zeit"] = date("H:i");
		$this->templateVars["limit"] = $this->limitAuslesen("zeitplanAnzahl");
    
    parent::datenLaden($datenbank);
	}
	
	private function eventVerarbeiten($record) {	
		$result["Titel"] = $record["Titel"];
		$result["Beginn"] = date("D H:i", strtotime($record["Beginn"]));
		
		$now = new DateTime();
		$beginn = new DateTime($record["Beginn"]);
		$ende = new DateTime($record["Ende"]);
		
		if (strtotime($record["Beginn"]) > time()) {
			$result["hatAngefangen"] = false;
			$interval = $beginn->diff($now);
		} else {
			$result["hatAngefangen"] = true;
			$interval = $ende->diff($now);
		}
		
		$hours = $interval->h + $interval->days * 24;
		
		if ($hours == 0) {
      if ($result["hatAngefangen"]) {
        $result["Restzeit"] = $interval->format("jetzt");
      } else {
        $result["Restzeit"] = $interval->format("%im");
      }
		} else if ($hours > 3) {
			$result["Restzeit"] = $hours . "h";
		} else {
			$result["Restzeit"] = $interval->format("%Hh %im");
		}
		
		return $result;
	}

  
  /**
   * Ausgabe für Flipdot-Anzeige
   * Nür $lines SdS in Kurzfassung als String ausgeben. */
  public function getFlipDotOutput($lines = 2) {
    $i = 0;
    $s = "";
    
    foreach ($this->templateVars["termine"] as $termin) {
      if ($i < $lines) {
      
        if ($termin["hatAngefangen"]) {
          $s .= $termin["Restzeit"] . " " . $termin["Titel"] . "\n";
        } else {
          $beginn = explode(" ", $termin["Beginn"])[1];
          $s .= $beginn . " " . $termin["Titel"] . "\n";
        }
        
        $i++;
        
      } else {
        break;
      }
    }
    
    return $s;
  }
}
?>

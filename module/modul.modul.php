<?php
require_once "./klassen/einstellung.class.php";

abstract class Modul {
    protected $templateVars = Array();
    
    public function datenLaden($datenbank) {
        $this->getModulAnzeigeDauer($datenbank);
        $this->templateVars["zeit"] = date("H:i");
        
        $this->templateVars["alarmAnzeigen"] = 
            $this->getEinstellung("alarmAnzeigen", $datenbank, "false");

        $this->templateVars["alarmText"] = 
            $this->getEinstellung("alarmText", $datenbank);
        $this->templateVars["strings"] = $this->loadStrings();
        
        $this->templateVars["fontZoom"] = $this->fontZoomAuslesen();
        
        $this->templateVars["design"] =
            $this->getEinstellung("design", $datenbank, "default");
        $this->templateVars["event"] =
            $this->getEinstellung("event", $datenbank, "php-info-beamer");
        $this->templateVars["eventDate"] =
            $this->getEinstellung("eventDate", $datenbank, "event-date");
    }
	
	public function getTemplateVars() {
		return $this->templateVars;
	}

	public function getTemplateVar($name) {
		return $this->templateVars[$name];
	}
	
	public function getName() {
		return get_class($this);
	}
  
  public function getFlipDotOutput($lines = 2) {
    return "No FlipDot output\nfor this module :/";
  }
  
  public function getModulAnzeigeDauer($datenbank) {
    $this->templateVars["modulAnzeigeDauer"] = 
      $this->getEinstellung("ModulAnzeigeDauerSekunden", $datenbank);
    return $this->templateVars["modulAnzeigeDauer"];
  }
  
  public function getEinstellung($name, $datenbank, $default = "") {
    $einstellung = new TEinstellung();
    $wert = $einstellung->read($name, $datenbank);
    
    if ($wert === false) {
        $wert = $default;
        $einstellung->create($name, $wert, $datenbank);
    }
    
    return $wert;
  }
  
  public function setEinstellung($name, $wert, $datenbank) {
    $einstellung = new TEinstellung();
    $einstellung->update($name, $wert, $datenbank);
  }
  
  public function loadStrings() {
    global $config;
    
    $result = Array();
    $temp = Array();
    $file = "strings.txt";
    $contents = file_get_contents($file);
    
    if ($contents !== false) {
      if (strpos($contents, "\r\n") === false) {
        $temp = explode("\n", $contents);
      } else {
        $temp = explode("\r\n", $contents);
      }
      
      foreach ($temp as $value) {
        if (strlen($value) > 0) {
          $values = explode("=", $value);
          if (count($values) == 2) {
            $result[$values[0]] = $values[1];
          }
        }
      }
    }

    return $result;
    
  }
  
    public function limitAuslesen($name) {
    
        if (isset($_COOKIE[$name])) {
            $limit = $_COOKIE[$name];
        } else {
            $limit = 8;
        }
        if (isset($_GET[$name])) {
        
            if ($_GET[$name] == "mehr") {
                $limit++;
            } else if (is_numeric($_GET[$name]) and intval($_GET[$name]) > 0) {
                $limit = intval($_GET[$name]);
            } else {
                $limit = max($limit - 1, 1);
            }
        
        }
        
        setcookie($name, $limit);
        return $limit;
        
    }
    
    public function fontZoomAuslesen() {
        
        if (isset($_GET["fontZoom"]) and is_numeric($_GET["fontZoom"]) and intval($_GET["fontZoom"]) >= 50) {
            return intval($_GET["fontZoom"]);
        } else {
            return 100;
        }
        
    }
}
?>
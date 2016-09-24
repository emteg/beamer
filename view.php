<?php
require_once "./config.php";
require_once "./klassen/datenbank.class.php";
require_once "./libs/smarty/Smarty.class.php";
require_once "./module/modul.modul.php";
require_once "./klassen/modul.class.php";
require_once "./klassen/einstellung.class.php";

/* Skriptablauf */

$datenbank = new Datenbank();

// Prüfen, ob Sonderformat für FlipDot-Anzeige von Urmel geforder ist
if (isset($_GET["format"]) && $_GET["format"] == "flipdot") {
  $flipDot = true;
} else {
  $flipDot = false;
}

// Gewünschtes Modul aus URL auslesen
if (isset($_GET["modul"])) {
  $zielModul = $_GET["modul"];
  $modul = new TModul;

  if ($modul->getByName($zielModul, $datenbank)) {
    $modulName = $modul->name;
  } else {
    menuZeigen($zielModul, $datenbank);
  }
 
} else {
  menuZeigen("", $datenbank);
}

// Falls laden und ausgeben, falls vorhanden
require_once "./module/" . strtolower($modulName) . "/" . strtolower($modulName) . ".modul.php";

// Modul-Objekt erzeugen, Daten laden lassen und Modul anzeigen
$modul = modulErzeugen($modulName, $datenbank);
modulAusgeben($modul, -1);



/* Funktionen */

function menuZeigen($zielModul, $datenbank) {
  $alleModule = Array();
  $sql = TModul::SQL_SELECT_ALLE;
  
  $alleModule = $datenbank->queryArray($sql, Array(), new ModulFactory());
  menuAusgeben($alleModule, $zielModul);
  
  exit();
}

function modulErzeugen($name, $datenbank) {
	$modul = new $name;
	$modul->datenLaden($datenbank);
	return $modul;
}

function modulAusgeben($modul, $naechstePosition) {
	global $flipDot;
  
	$smarty = new Smarty();
	$smarty->setTemplateDir("./module/" . strtolower($modul->getName()) . "/");
	
	$smarty->assign("modulName", $modul->getName());
	$smarty->assign("naechstePosition", -1);
  
  $smarty->assign("url", $_SERVER["PHP_SELF"] . "?modul=" . $modul->getName());
	
	foreach ($modul->getTemplateVars() as $key => $var) {
		$smarty->assign($key, $var);
	}
  
  if ($flipDot) {
    echo $modul->getFlipDotOutput();
  } else {
    $smarty->display("display.tpl");
  }
}

function menuAusgeben($module, $zielModul) {
  global $config;
  
  $smarty = new Smarty();
	$smarty->setTemplateDir("./seiten/templates/");
	
  $smarty->assign("module", $module);
  $smarty->assign("zielModul", $zielModul);
  $smarty->assign("config", $config);
  
	$smarty->display("view.tpl");
}
?>
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

$design = getDesignName($datenbank);

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
modulAusgeben($modul, $design, -1);



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

function modulAusgeben($modul, $design, $naechstePosition) {
	global $flipDot;

	$smarty = new Smarty();
	$templateDir = "./designs/" . $design . "/";
	$smarty->setTemplateDir($templateDir);

	$smarty->assign("modulName", $modul->getName());
	$smarty->assign("naechstePosition", -1);

        if ($modul->getTemplateVar("fontZoom") != 100) {
                $zoom = "&fontZoom=" . $modul->getTemplateVar("fontZoom");
        } else {
                $zoom = "";
        }

	$smarty->assign("url", $_SERVER["PHP_SELF"] . "?modul=" . $modul->getName() . $zoom);

	foreach ($modul->getTemplateVars() as $key => $var) {
		$smarty->assign($key, $var);
	}

	if ($flipDot) {
		echo $modul->getFlipDotOutput();
	} else {
		try {
			$smarty->display(strtolower($modul->getName()) . ".tpl");
		} catch (Exception $e) {
			displayError($e, $modul, $templateDir);
		}
	}
}

function menuAusgeben($module, $zielModul) {
	global $config;

	$smarty = new Smarty();
	$templateDir = "./seiten/templates/";
	$smarty->setTemplateDir($templateDir);

	$smarty->assign("module", $module);
	$smarty->assign("zielModul", $zielModul);
	$smarty->assign("config", $config);

	$smarty->display("view.tpl");
}

function displayError($e, $modul, $templateDir) {
	echo "Failed to create output:<br/>";
	echo $templateDir . "<br/>";
	echo $e->getMessage() . "<br/>";
	echo "The following variables are available:<br/>";
	foreach ($modul->getTemplateVars() as $key => $var) {
		if (!is_array($var)) {
			echo $key . ": " . $var . "<br/>";
		} else {
			echo $key . ": ";
			var_dump($var);
			echo "<br/>";
		}
	}
	die();
}

function getDesignName($datenbank) {
	$einstellung = new TEinstellung();
	return $einstellung->read("design", $datenbank);
}
?>

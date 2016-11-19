<?php
require_once "./config.php";
require_once "./klassen/datenbank.class.php";
require_once "./libs/smarty/Smarty.class.php";
require_once "./module/modul.modul.php";
require_once "./klassen/playlist.class.php";
require_once "./klassen/einstellung.class.php";

/* Skriptablauf */

$datenbank = new Datenbank();
$playlist = new Playlist();

// Alarm?
$einstellung = new TEinstellung();
$alarmAnzeigen = $einstellung->read("alarmAnzeigen", $datenbank);
if ($alarmAnzeigen === "true") {
  $alarmAnzeigen = true;
  $alarmText = $einstellung->read("alarmText", $datenbank);
} else {
  $alarmAnzeigen = false;
}

// Den Namen des nächsten Moduls ermitteln und die entsprechende Datei einbinden
$aktuellePlaylistPosition = playlistPositionErmitteln();
$naechstePlaylistPosition = naechstePlaylistPositionErmitteln($aktuellePlaylistPosition);

if ($alarmAnzeigen) {
  $aktuellesModulName = "Textseite";
  $aktuellesModul = "textseite";
} else {
  $aktuellesModulName = $playlist->playlist[$aktuellePlaylistPosition]["Name"];
  $aktuellesModul = strtolower($aktuellesModulName);
}

// Name des aktuellen Designs laden
$design = getDesignName($datenbank);

require_once "./module/" . $aktuellesModul . "/" . $aktuellesModul . ".modul.php";

// Modul-Objekt erzeugen, Daten laden lassen und Modul anzeigen
$modul = modulErzeugen($aktuellesModulName, $datenbank);
modulAusgeben($modul, $design, $naechstePlaylistPosition);

/* Funktionen */

function playlistPositionErmitteln() {
	global $datenbank;
	global $playlist;

	$playlist->ladePlaylist($datenbank);
	
	if (isset($_GET["playlistItem"])) {
		$aktuell = $_GET["playlistItem"];
	} else {
		$aktuell = 0;
	}
	
	if ($aktuell < count($playlist->playlist)) {
		return $aktuell;
	} else {
		return 0;
	}
}

function naechstePlaylistPositionErmitteln($aktuellePosition) {
	global $playlist;
	
	if ($aktuellePosition < count($playlist->playlist) - 1) {
		return $aktuellePosition + 1;
	} else {
		return 0;
	}
}

function modulErzeugen($name, $datenbank) {
	$modul = new $name;
	$modul->datenLaden($datenbank);
	return $modul;
}

function getDesignName($datenbank) {
	$einstellung = new TEinstellung();
	return $einstellung->read("design", $datenbank);
}

function modulAusgeben($modul, $design, $naechstePosition) {
	global $aktuellePlaylistPosition;
	
	$smarty = new Smarty();
	$templateDir = "./designs/" . $design . "/";
	$smarty->setTemplateDir($templateDir);
	
	$smarty->assign("modulName", $modul->getName());
	$smarty->assign("naechstePosition", $naechstePosition);
	
	if ($modul->getTemplateVar("fontZoom") != 100) {
		$zoom = "&fontZoom=" . $modul->getTemplateVar("fontZoom");
	} else {
		$zoom = "";
	}

	$smarty->assign("url", $_SERVER["PHP_SELF"] . 
		"?playlistItem=" . $aktuellePlaylistPosition . 
		$zoom);
       $smarty->assign("next_url", $_SERVER["PHP_SELF"] . 
		"?playlistItem=" . $naechstePosition . 
		$zoom);
	
	foreach ($modul->getTemplateVars() as $key => $var) {
		$smarty->assign($key, $var);
	}
	
	try {
		$smarty->display(strtolower($modul->getName()) . ".tpl");
	} catch (Exception $e) {
		displayError($e, $modul, $templateDir);
	}
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
?>

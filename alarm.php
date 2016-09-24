<?php
require_once "./config.php";
require_once "./klassen/datenbank.class.php";
require_once "./klassen/einstellung.class.php";

$datenbank = new Datenbank();

$einstellung = new TEinstellung();
$alarmAnzeigen = $einstellung->read("alarmAnzeigen", $datenbank);

if ($alarmAnzeigen !== "true") {
  $alarmAnzeigen = "false";
}

//echo "true";
echo $alarmAnzeigen;
?>
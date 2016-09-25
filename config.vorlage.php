<?php
$config["beamercontrolPath"] = "../beamercontrol/";

// Load config values from beamercontrol
require_once $config["beamercontrolPath"] . "config.values.php";

// Overwrite rootDir
$config["rootDir"] = "/beamer";
?>
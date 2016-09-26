<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$modulName}</title>
    		<meta http-equiv="refresh" content="{$modulAnzeigeDauer};url={$url}">
		<link rel="stylesheet" type="text/css" href="./designs/{$design}/font/font.css">
		<link rel="stylesheet" type="text/css" href="./designs/{$design}/infobeamer.css">
	</head>
	<body style="font-size: {$fontZoom}%">
{block name=logo}
		<img src="./designs/{$design}/logo" class="logo">
{/block}
{block name=titel}<h1>{$event}</h1>{/block}
{block name=body}{/block}
{block name=uhrzeit}<span class="unten-links">{$zeit}</span>{/block}
  <script>var alarmAnzeigen = '{$alarmAnzeigen}';</script>
  <script src="poll.js"></script>
	</body>
</html>

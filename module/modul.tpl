<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$modulName}</title>
    {if $naechstePosition >= 0}
		<meta http-equiv="refresh" content="{$modulAnzeigeDauer};url=./index.php?playlistItem={$naechstePosition}">
    {else}
    <meta http-equiv="refresh" content="{$modulAnzeigeDauer};url={$url}">
    {/if}
		<link rel="stylesheet" type="text/css" href="./designs/{$design}/font/font.css">
		<link rel="stylesheet" type="text/css" href="./designs/{$design}/infobeamer.css">
	</head>
	<body style="font-size: {$fontZoom}%">
{block name=logo}
		<img src="./designs/{$design}/logo.png" class="logo">
{/block}
{block name=titel}<h1>{$event}</h1>{/block}
{block name=body}{/block}
{block name=uhrzeit}<span class="unten-links">{$zeit}</span>{/block}
  <script>var alarmAnzeigen = '{$alarmAnzeigen}';</script>
  <script src="poll.js"></script>
	</body>
</html>

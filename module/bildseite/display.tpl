{extends file="../modul.tpl"}
{block name=logo}
{if $hatDaten && $layout == "Mittig"}
		<div class="imgAutoSizeContainer">
			<img src="./img_upload/{$id}.{$extension}" class="imgAutoSize">
		</div>
{else}
		<img src="./designs/{$design}/logo" class="logo">
{/if}
{/block}
{if $layout == "Mittig"}
{block name=titel}{/block}
{/if}
{block name=body}
{if $hatDaten}
{if $layout != "Mittig"}
		<div class="linkeSpalte">
			<img src="./img_upload/{$id}.{$extension}" class="imgAutoSize">
		</div>
		<div class="rechteSpalte">{$beschriftung}</div>
{else}
		<div class="imgBeschriftungUntenMittig">{$beschriftung}</div>
{/if}
{else}
	<div style="text-align: center; font-size: 200%;">Kein Bilder zum Anzeigen vorhanden</div>
{/if}
{/block}
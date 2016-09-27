{extends file="./modul.tpl"}
{block name=body}
	<div class="datum">
		{$wochentag}, {$tag}. {$monat} {$jahr}
		<div class="zeit">{$zeit} Uhr</div>
		KW {$kalenderwoche}
	</div>
{/block}
{block name=uhrzeit}{/block}
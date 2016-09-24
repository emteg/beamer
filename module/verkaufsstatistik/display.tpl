{extends file="../modul.tpl"}
{block name=body}
		<h2>Verkaufsstatistik</h2>
		<div class="zweiSpalten">
{foreach $verkaufe as $artikel}
		{$artikel["Verkauft"]}
{if $artikel["IstLeer"]}
		<s>{$artikel["Name"]}</s>
{else}
		{$artikel["Name"]}
{/if}
{if $artikel["HatMenge"]}
		({$artikel["Prozent"]}%)
{/if}
		<br/>
{foreachelse}
		- Es wurde noch nichts verkauft -
{/foreach}
		</div>
		<span class="configButtons">
			<a href="{$url}&verkaufsstatistikAnzahl=mehr" title="Mehr Artikel anzeigen">+</a>
			<a href="">{$limit}</a>
			<a href="{$url}&verkaufsstatistikAnzahl=weniger" title="Weniger Artikel anzeigen">-</a>
		</span>
{/block}
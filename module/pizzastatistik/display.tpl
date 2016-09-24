{extends file="../modul.tpl"}
{block name=body}
		<h2>Pizza-Verkaufsstatistik</h2>
		<div class="zweiSpalten">
{foreach $verkaufe as $artikel}
		{$artikel["Verkauft"]}
		{$artikel["Name"]}
		<br/>
{foreachelse}
		- Es wurde noch nichts verkauft -
{/foreach}
		</div>
		<span class="configButtons">
			<a href="{$url}&pizzastatistikAnzahl=mehr" title="Mehr Artikel anzeigen">+</a>
			<a href="">{$limit}</a>
			<a href="{$url}&pizzastatistikAnzahl=weniger" title="Weniger Artikel anzeigen">-</a>
		</span>
{/block}
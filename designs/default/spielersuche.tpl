{extends file="./modul.tpl"}
{block name=titel}<h1>{$strings["spielersuche-titel"]}</h1>{/block}
{block name=body}
		<table class="table-auto-height">
{foreach $gesuche as $gesuch}
			<tr>
				<td>{$gesuch["Name"]}</td>
				<td style="text-align: right;">{$gesuch["Zeit"]}</td>
			</tr>
			<tr style="font-size: 50%">
				<td>{$gesuch["Server"]}</td>
				<td style="text-align: right;">{$gesuch["Spieler"]}</td>
			</tr>
{foreachelse}
			<tr><td colspan="4" class="tdMittig">{$strings["spielersuche-no-requests"]}</td></tr>
{/foreach}
		</table>
		<span class="unten-rechts">{$strings["spielersuche-info"]}</span>
		<span class="configButtons">
			<a href="{$url}&spielersucheAnzahl=mehr" title="{$strings["spielersuche-show-more"]}">{$strings["show-more"]}</a>
			<a href="">{$limit}</a>
			<a href="{$url}&spielersucheAnzahl=weniger" title="{$strings["spielersuche-show-less"]}">{$strings["show-less"]}</a>
		</span>
{/block}

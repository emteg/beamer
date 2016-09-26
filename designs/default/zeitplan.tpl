{extends file="./modul.tpl"}
{block name=titel}<h1>{$strings["zeitplan-titel"]}</h1>{/block}
{block name=body}
		<table>
{foreach $termine as $termin}
			<tr>
				<td style="width: 6em;">{$termin["Beginn"]}</td>
				<td>{$termin["Titel"]}</td>
				<td style="width: 8em; text-align: right;">
{if $termin["hatAngefangen"]}
					{$strings["time-remaining"]} {$termin["Restzeit"]}
{else}
					{$strings["time-until"]} {$termin["Restzeit"]}
{/if}
				</td>
			</tr>
{foreachelse}
			<tr><td colspan="3" class="tdMittig">{$strings["zeitplan-no-events"]}</td></tr>
{/foreach}
		</table>
		<span class="unten-rechts">{$strings["zeitplan-info"]}</span>
		<span class="configButtons">
			<a href="{$url}&zeitplanAnzahl=mehr" title="{$strings["zeitplan-show-more"]}">{$strings["show-more"]}</a>
			<a href="">{$limit}</a>
			<a href="{$url}&zeitplanAnzahl=weniger" title="{$strings["zeitplan-show-less"]}">{$strings["show-less"]}</a>
		</span>
{/block}

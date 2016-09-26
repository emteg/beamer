{extends file="./modul.tpl"}
{block name=titel}<h1>{$strings["sds-titel"]}</h1>{/block}
{block name=body}
    <table class="table-auto-height">
{if count($termine) > 0}
      <tr>
        <td colspan="3" class="tdHervorgehoben">
  {if $termine[0]["hatAngefangen"]}
          {$strings["sds-current"]} {$termine[0]["Titel"]}
  {else}
          {$termine[0]["Titel"]} {$strings["sds-next"]} {$termine[0]["Restzeit"]}
  {/if}<br/>
        </td>
      </tr>
{/if}
{foreach $termine as $termin name=loop}
{if !$smarty.foreach.loop.first}
			<tr>
				<td>{$termin["Titel"]}</td>
				<td style="width: 7em; text-align: right;">
{if $termin["hatAngefangen"]}
					{$strings["time-remaining"]} {$termin["Restzeit"]}
{else}
					{$termin["Restzeit"]}
{/if}
				</td>
			</tr>
{/if}
{foreachelse}
			<tr><td colspan="3" class="tdMittig">{$strings["sds-no-games"]}</td></tr>
{/foreach}
		</table>
		<span class="unten-rechts">{$strings["sds-info"]}</span>
		<span class="configButtons">
			<a href="{$url}&spielderstundeAnzahl=mehr" title="{$strings["sds-show-more"]}">{$strings["show-more"]}</a>
			<a href="{$url}">{$limit}</a>
			<a href="{$url}&spielderstundeAnzahl=weniger" title="{$strings["sds-show-less"]}">{$strings["show-less"]}</a>
		</span>
{/block}

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Einzelmodulansicht</title>
  </head>
  <body>
    <h1>Modul auswählen:</h1>
    <p>Ein Modul mit dem Namen "{$zielModul}" ist leider nicht vorhanden :/<br/>Die folgenden Module sind verfügbar:</p>
    <ul>
      {foreach $module as $modul}
        <li><a href="{$config["rootDir"]}/view.php?modul={$modul->name}">{$modul->name}</a></li>
      {foreachelse}
        <li>Es sind zur Zeit keine Module installiert :/</li>
      {/foreach}
    </ul>
  </body>
</html>
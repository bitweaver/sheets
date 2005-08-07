<link rel="stylesheet" href="lib/sheet/style.css" type="text/css" />
<h1><a href="sheets.php" class="pagetitle">{tr}{$title}{/tr}</a></h1>

<div>
{$description}
</div>

<ul>
{section name=revision_date loop=$history}
	<li><a href="view_sheets.php?sheet_id={$sheet_id}&readdate={$history[revision_date].stamp}">{$history[revision_date].string}</a></li>
{/section}
</ul>

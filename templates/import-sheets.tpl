<link rel="stylesheet" href="lib/sheet/style.css" type="text/css" />
<h1><a href="sheets.php" class="pagetitle">{tr}{$title}{/tr}</a></h1>

<div>
{$description}
</div>

{if $page_mode eq 'submit'}
{$grid_content}

{else}
	<form method="post" action="import_sheet.php?mode=import&sheet_id={$sheet_id}" enctype="multipart/form-data">
		<h2>{tr}Import From File{/tr}</h2>
		<select name="handler">
{section name=key loop=$handlers}
			<option value="{$handlers[key].class}">{$handlers[key].name} V. {$handlers[key].version}</option>
{/section}
		</select>
		<input type="file" name="file" />
		<input type="submit" class="btn" value="Import" />
	</form>
	<form method="post" action="import_sheet.php?mode=import&sheet_id={$sheet_id}">
		<h2>{tr}Grab Wiki Tables{/tr}</h2>
		<input type="text" name="page"/>
		<input type="hidden" name="handler" value="BitSheetWikiTableHandler"/>
		<input type="submit" class="btn" value="Import"/>
	</form>
{/if}

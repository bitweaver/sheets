<h1>{tr}{$title}{/tr}</h1>

<div>
{$description}
</div>

{if $page_mode eq 'submit'}
{$grid_content}

{else}
	<form method="post" action="{$smarty.const.SHEETS_PKG_URL}export_sheet.php?mode=export&sheet_id={$sheet_id}" enctype="multipart/form-data">
		<select name="handler">
{section name=key loop=$handlers}
			<option value="{$handlers[key].class}">{$handlers[key].name} V. {$handlers[key].version}</option>
{/section}
		</select>
		<input type="submit" value="Export" />
	</form>
{/if}

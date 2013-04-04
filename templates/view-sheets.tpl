<h1>{tr}{$title}{/tr}</h1>

<div>
{$description}
</div>

{if $page_mode eq 'edit'}
	<div id="panel">
		<menu>
			<li><a href="#" onClick="insertRowClick()" class="linkbut">{tr}Insert Row{/tr}</a></li>
			<li><a href="#" onClick="insertColumnClick()" class="linkbut">{tr}Insert Column{/tr}</a></li>
			<li><a href="#" onClick="removeRowClick()" class="linkbut">{tr}Remove Row{/tr}</a></li>
			<li><a href="#" onClick="removeColumnClick()" class="linkbut">{tr}Remove Column{/tr}</a></li>
			<li><a href="#" onclick="mergeCellClick()" class="linkbut">{tr}Merge Cells{/tr}</a></li>
			<li><a href="#" onclick="restoreCellClick()" class="linkbut">{tr}Restore Cells{/tr}</a></li>
			<li><a href="#" onclick="copyCalculationClick()" class="linkbut">{tr}Copy Calculation{/tr}</a></li>
			<li><a href="#" onclick="formatCellClick()" class="linkbut">{tr}Format Cell{/tr}</a></li>
		</menu>
		<div id="detail"></div>
	</div>
	<form method="post" action="{$smarty.const.SHEETS_PKG_URL}view_sheets.php?mode=edit&sheet_id={$sheet_id}" id="Grid"></form>
	<div class='submit'><input type="submit" class="btn" onclick='g.target.style.visibility = "hidden"; g.prepareSubmit(); g.target.submit();' value="{tr}Save{/tr}" /></div>
	<script language="JavaScript" type="text/javascript" src="{$smarty.const.SHEETS_PKG_URL}sheet/grid.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$smarty.const.SHEETS_PKG_URL}sheet/control.js"></script>
	<script language="JavaScript" type="text/javascript" src="{$smarty.const.SHEETS_PKG_URL}sheet/formula.js"></script>
	<script language="JavaScript">
	var g;
{$init_grid}

	controlInsertRowBefore = '<form name="insert" onSubmit="insertRowSubmit(this)"><input type="radio" name="pos" value="before" checked="checked" id="sht_ins_row_before" /> <label for="sht_ins_row_before">{tr}Before{/tr}</label> <input type="radio" name="pos" value="after" id="sht_ins_row_after" /> <label for="sht_ins_row_after">{tr}After{/tr}</label> <select name="row">';
	controlInsertRowAfter = '</select><input type="text" name="qty" value="1" size="2" /><input type="submit" class="btn" name="submit" value="{tr}Insert Row{/tr}" /></form>';
	
	controlInsertColumnBefore = '<form name="insert" onSubmit="insertColumnSubmit(this)"><input type="radio" name="pos" value="before" checked="checked" id="sht_ins_col_before" /> <label for="sht_ins_col_before">{tr}Before{/tr}</label> <input type="radio" name="pos" value="after" id="sht_ins_col_after" /> <label for="sht_ins_col_after">{tr}After{/tr}</label> <select name="column">';
	controlInsertColumnAfter = '</select><input type="text" name="qty" value="1" size="2" /><input type="submit" class="btn" name="submit" value="{tr}Insert Column{/tr}" /></form>';

	controlRemoveRowBefore = '<form name="remove" onSubmit="removeRowSubmit(this)"><select name="row">';
	controlRemoveRowAfter = '</select><input type="submit" class="btn" name="submit" value="{tr}Remove Row{/tr}" /></form>';

	controlRemoveColumnBefore = '<form name="remove" onSubmit="removeColumnSubmit(this)"><select name="column">';
	controlRemoveColumnAfter = '</select><input type="submit" class="btn" name="submit" value="{tr}Remove Column{/tr}" /></form>';
	controlCopyCalculation = '<form name="copy" onSubmit="copyCalculationSubmit(this)"><input type="submit" class="btn" name="type" value="Left" onclick="document.copy.clicked.value = this.value;" /><input type="submit" name="type" value="Right" onclick="document.copy.clicked.value = this.value;" /><input type="submit" name="type" value="Up" onclick="document.copy.clicked.value = this.value;" /><input type="submit" name="type" value="Down" onclick="document.copy.clicked.value = this.value;" /><input type="hidden" name="clicked" /></form>';
	initGrid();
	controlFormatCellBefore = '<form name="format" onSubmit="formatCellSubmit(this)"><select name="format"><option value="">None</option>';
	controlFormatCellAfter = '</select><input type="submit" class="btn" name="submit" value="{tr}Format Cell{/tr}" /></form>';
	</script>

{else}
{$grid_content}
{if $gBitUser->hasPermission('bit_p_edit_sheet') || $gBitUser->hasPermission('bit_p_sheet_admin') || $gBitUser->isAdmin()}
<a href="{$smarty.const.SHEETS_PKG_URL}view_sheets.php?sheet_id={$sheet_id}&readdate={$read_date}&mode=edit" class="linkbut">{biticon ipackage='liberty' iname='edit' iexplain='Edit'}</a>
{/if}
  {if $chart_enabled eq 'y'}
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}graph_sheet.php?sheet_id={$sheet_id}">{biticon ipackage='sheets' iname='icn_budgetgraph' iexplain='Graph'}</a>
  {/if}
  {if $gBitUser->hasPermission('bit_p_admin_sheet') or $gBitUser->isAdmin() or $gBitUser->hasPermission('bit_p_view_sheet_history') }
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}history_sheets.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;sheet_id={$sheet_id}">{biticon ipackage='sheets' iname='history_layer' iexplain='History'}</a>
  {/if}
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}export_sheet.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;sheet_id={$sheet_id}">{biticon ipackage='liberty' iname='export' iexplain='Export'}</a>
  {if $gBitUser->hasPermission('bit_p_admin_sheet') or $gBitUser->isAdmin() }
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}import_sheet.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;sheet_id={$sheet_id}">{biticon ipackage='liberty' iname='import' iexplain='Import'}</a>
  {/if}
{/if}

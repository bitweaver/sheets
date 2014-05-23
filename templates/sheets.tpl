<h1>{tr}BitSheet{/tr}</h1>

{if $gBitUser->hasPermission('bit_p_edit_sheet') or $gBitUser->hasPermission('bit_p_admin_sheet') or $gBitUser->isAdmin()}
{if $edit_mode eq 'y'}
{if $sheet_id eq 0}
<h2>{tr}Create a sheet{/tr}</h2>
{else}
<h2>{tr}Edit this sheet:{/tr} {$title}</h2>
<a class="linkbut" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?edit_mode=1&amp;sheet_id=0">{tr}create new sheet{/tr}</a>
{/if}
<div align="center">
{if $individual eq 'y'}
<a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}objectpermissions.php?objectName={$name|escape:"url"}&amp;objectType=sheet&amp;permType=sheet&amp;objectId={$sheet_id}">{tr}There are individual permissions set for this sheet{/tr}</a>
{/if}
<form action="{$smarty.const.SHEETS_PKG_URL}sheets.php" method="post">
<input type="hidden" name="sheet_id" value="{$sheet_id|escape}" />
<table class="normal">
<tr><td class="formcolor">{tr}Title{/tr}:</td><td class="formcolor"><input type="text" name="title" value="{$title|escape}"/></td></tr>
<tr><td class="formcolor">{tr}Description{/tr}:</td><td class="formcolor"><textarea rows="5" cols="40" name="description">{$description|escape}</textarea></td></tr>
<tr><td class="formcolor">{tr}Class Name{/tr}:</td><td class="formcolor"><input type="text" name="class_name" value="{$class_name|escape}"/></td></tr>
<tr><td class="formcolor">{tr}Header Rows{/tr}:</td><td class="formcolor"><input type="text" name="header_row" value="{$header_row|escape}"/></td></tr>
<tr><td class="formcolor">{tr}Footer Rows{/tr}:</td><td class="formcolor"><input type="text" name="footer_row" value="{$footer_row|escape}"/></td></tr>
				{if $gBitSystem->isPackageActive( 'categories' )}
					{jstab title="Categorize"}
						{legend legend="Categorize"}
							{include file="bitpackage:categories/categorize.tpl"}
						{/legend}
					{/jstab}
				{/if}

<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" class="btn btn-default" value="{tr}save{/tr}" name="edit" /></td></tr>
</table>
</form>
</div>
<br />
{else}
<a href="{$smarty.const.SHEETS_PKG_URL}sheets.php?edit_mode=edit&sheet_id=0" class="linkbut">{tr}Create new Sheet{/tr}</a>
{/if}
{/if}
{if $sheet_id > 0}
{if $edited eq 'y'}
<div class="wikitext">
{tr}You can access the sheet using the following URL{/tr}: <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}{$url}?sheet_id={$sheet_id}">{$url}?sheet_id={$sheet_id}</a>
</div>
{/if}
{/if}
<h2>{tr}Available Sheets{/tr}</h2>
<div align="center">
<table class="findtable">
<tr><td class="findtable">{tr}Find{/tr}</td>
   <td class="findtable">
   <form method="get" action="{$smarty.const.SHEETS_PKG_URL}sheets.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" class="btn btn-default" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </form>
   </td>
</tr>
</table>
<table class="normal">
<tr>
<td class="heading"><a class="tableheading" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'title_desc'}title_asc{else}title_desc{/if}">{tr}Title{/tr}</a></td>
<td class="heading"><a class="tableheading" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'description_desc'}description_asc{else}description_desc{/if}">{tr}Description{/tr}</a></td>
<td class="heading"><a class="tableheading" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'user_desc'}user_asc{else}user_desc{/if}">{tr}User{/tr}</a></td>
<td  class="heading">{tr}Actions{/tr}</td>
</tr>
{cycle values="odd,even" print=false}
{section name=changes loop=$sheets}
<tr>
  <td class="{cycle advance=false}"><a class="galname" href="{$smarty.const.SHEETS_PKG_URL}view_sheets.php?sheet_id={$sheets[changes].sheet_id}">{$sheets[changes].title|escape}</a></td>
  <td class="{cycle advance=false}">{$sheets[changes].description}</td>
  <td class="{cycle advance=false}">{displayname user_id=$sheets[changes].author}</td>
  <td class="{cycle}">
{if $gBitUser->hasPermission('bit_p_edit_sheet') || $gBitUser->hasPermission('bit_p_sheet_admin') || $gBitUser->isAdmin()}
<a href="{$smarty.const.SHEETS_PKG_URL}view_sheets.php?sheet_id={$sheets[changes].sheet_id}&readdate={$sheets[changes].read_date}&mode=edit" class="linkbut">{biticon ipackage='liberty' iname='edit' iexplain='Edit'}</a>
{/if}
  {if $chart_enabled eq 'y'}
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}graph_sheet.php?sheet_id={$sheets[changes].sheet_id}">{biticon ipackage='sheets' iname='icn_budgetgraph' iexplain='Graph'}</a>
  {/if}
  {if $gBitUser->hasPermission('bit_p_admin_sheet') or ($author and $sheets[changes].author eq $author) or $gBitUser->isAdmin() or $gBitUser->hasPermission('bit_p_view_sheet_history') }
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}history_sheets.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;sheet_id={$sheets[changes].sheet_id}">{biticon ipackage='sheets' iname='history_layer' iexplain='History'}</a>
  {/if}
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}export_sheet.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;sheet_id={$sheets[changes].sheet_id}">{biticon ipackage='liberty' iname='export' iexplain='Export'}</a>
  {if $gBitUser->hasPermission('bit_p_admin_sheet') or ($author and $sheets[changes].author eq $author) or $gBitUser->isAdmin() }
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}import_sheet.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;sheet_id={$sheets[changes].sheet_id}">{biticon ipackage='liberty' iname='import' iexplain='Import'}</a>
  {/if}
  {if $gBitUser->hasPermission('bit_p_admin_sheet') or ($author and $sheets[changes].author eq $author) or $gBitUser->isAdmin() }
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;edit_mode=1&amp;sheet_id={$sheets[changes].sheet_id}">{biticon ipackage='liberty' iname='config' iexplain='Edit'}</a>
    <a class="gallink" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;removesheet={$sheets[changes].sheet_id}">{biticon ipackage='liberty' iname='delete' iexplain='Delete'}</a>
  {/if}
  </td>
</tr>
{sectionelse}
<tr><td colspan="6">
<b>{tr}No records found{/tr}</b>
</td></tr>
{/section}
</table>
<br />
<div class="mini">
{if $prev_offset >= 0}
[<a class="galprevnext" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="galprevnext" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $site_direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="{$smarty.const.SHEETS_PKG_URL}galleries.php?find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div>

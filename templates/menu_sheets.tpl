{strip}
<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="{$packageMenuClass}">
	{if $gBitUser->hasPermission( 'bit_p_read_sheet' )}
		<li><a class="item" href="{$smarty.const.SHEETS_PKG_URL}index.php">{tr}List Sheets{/tr}</a></li>
	{/if}{if $gBitUser->hasPermission( 'bit_p_create_sheet' )}
		<li><a class="item" href="{$smarty.const.SHEETS_PKG_URL}sheets.php?edit_mode=edit&amp;sheet_id=0">{tr}Create a Sheet{/tr}</a></li>
	{/if}
</ul>
{/strip}

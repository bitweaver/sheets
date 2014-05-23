<h1>{$title}</h1>
<p>
{$description}
</p>
{if ($mode eq 'graph')}
<h2>{tr}Select Graphic Type{/tr}</h2>
<form method="get" action="{$smarty.const.SHEETS_PKG_URL}graph_sheet.php">
<input type="hidden" name="sheet_id" value="{$sheet_id}"/>
<table>
<tr>
	<td><input type="radio" name="graphic" id="g_pie" value="PieChartGraphic"/> <label for='g_pie'>{tr}Pie Chart{/tr}</label></td>
	<td><input type="radio" name="graphic" id="g_mline" value="MultilineGraphic"/> <label for='g_mline'>{tr}Multiline{/tr}</label></td>
	<td><input type="radio" name="graphic" id="g_mbar" value="MultibarGraphic"/> <label for='g_mbar'>{tr}Multibar{/tr}</label></td>
	<td><input type="radio" name="graphic" id="g_stack" value="BarStackGraphic"/> <label for='g_stack'>{tr}Bar Stack{/tr}</label></td>
</tr>
<tr>
	<td><label for="g_pie">{biticon ipackage="sheets" iname="graph.pie" iexplain="Pie Chart"}</label></td>
	<td><label for="g_mline">{biticon ipackage="sheets" iname="graph.multiline" iexplain="Multiline"}</label></td>
	<td><label for="g_mbar">{biticon ipackage="sheets" iname="graph.multibar" iexplain="Multibar"}</label></td>
	<td><label for="g_stack">{biticon ipackage="sheets" iname="graph.barstack" iexplain="Bar Stack"}</label></td>
</tr>
</table>
{if $haspdflib}
<div>
	<select name="format">
		<option>Letter</option>
		<option>Legal</option>
		<option>A4</option>
		<option>A3</option>
	</select>
	<select name="orientation">
		<option value="landscape">{tr}Landscape{/tr}</option>
		<option value="portrait">{tr}Portrait{/tr}</option>
	</select>
	<input type="submit" class="btn btn-default" name="renderer" value="PDF"/>
</div>
{/if}
{if $hasgd}
<div>
	<input type="text" name="width" value="500" size="4"/>
	<input type="text" name="height" value="400" size="4"/>
	<input type="submit" class="btn btn-default" name="renderer" value="PNG"/>
	<input type="submit" class="btn btn-default" name="renderer" value="JPEG"/>
</div>
{/if}
</form>
{/if}
{if ($mode eq 'param')}
<form method="get" action="{$smarty.const.SHEETS_PKG_URL}graph_sheet.php">
<input type="hidden" name="sheet_id" value="{$sheet_id}"/>
<input type="hidden" name="graphic" value="{$graph}"/>
<input type="hidden" name="renderer" value="{$renderer}"/>
<input type="hidden" name="format" value="{$format}"/>
<input type="hidden" name="orientation" value="{$orientation}"/>
<input type="hidden" name="width" value="{$im_width}"/>
<input type="hidden" name="height" value="{$im_height}"/>
<table class="normal">
	<tr>
		<td class="formcolor">{tr}Title{/tr}:</td>
		<td class="formcolor"><input type="text" name="title" value="{$title}"/></td>
	</tr>
{if $showgridparam}
	<tr>
		<td class="formcolor">{tr}Independant Scale{/tr}:</td>
		<td class="formcolor">
			<input type="radio" name="independant" value="horizontal" id="ind_ori_hori" checked="checked" />
			<label for="ind_ori_hori">{tr}Horizontal{/tr}</label>
			<input type="radio" name="independant" value="vertical" id="ind_ori_verti" />
			<label for="ind_ori_verti">{tr}Vertical{/tr}</label>
		</td>
	</tr>
	<tr>
		<td class="formcolor">{tr}Horizontal Scale{/tr}:</td>
		<td class="formcolor">
			<input type="radio" name="horizontal" value="bottom" id="hori_pos_bottom" checked="checked" />
			<label for="hori_pos_bottom">{tr}Bottom{/tr}</label>
			<input type="radio" name="horizontal" value="top" id="hori_pos_top" />
			<label for="hori_pos_top">{tr}Top{/tr}</label>
		</td>
	</tr>
	<tr>
		<td class="formcolor">{tr}Vertical Scale{/tr}:</td>
		<td class="formcolor">
			<input type="radio" name="vertical" value="left" id="verti_pos_left" checked="checked" />
			<label for="verti_pos_left">{tr}Left{/tr}</label>
			<input type="radio" name="vertical" value="right" id="verti_pos_right" />
			<label for="verti_pos_right">{tr}Right{/tr}</label>
		</td>
	</tr>
{/if}
	<tr>
		<td class="formcolor" colspan="2">{tr}Series{/tr}:</td>
	</tr>
{section name=i loop=$series}
	<tr>
		<td class="formcolor">{$series[i]}</td>
		<td class="formcolor"><input type="text" name="series[{$series[i]}]"/>
	</tr>
{/section}
	<tr>
		<td class="formcolor" colspan="2"><input type="submit" class="btn btn-default" value="{tr}show{/tr}" /></td>
	</tr>
</table>
</form>
{/if}

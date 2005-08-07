<?php
/* Bit-Wiki plugin example 
 *
 * This is an example plugin to let you know how to create
 * a plugin. Plugins are called using the syntax
 * {NAME(params)}content{NAME}
 * Name must be in uppercase!
 * params is in the form: name=>value,name2=>value2 (don't use quotes!)
 * If the plugin doesn't use params use {NAME()}content{NAME}
 *
 * The function will receive the plugin content in $data and the params
 * in the asociative array $params (using extract to pull the arguments
 * as in the example is a good practice)
 * The function returns some text that will replace the content in the
 * wiki page.
 */
function wikiplugin_sheet_help() {
	return tra("BitSheet").":<br />~np~{SHEET(id=>)}".tra("Sheet Heading")."{SHEET}~/np~";
}
function wikiplugin_sheet($data, $params) {
	global $dbBit, $tikilib, $bit_p_edit_sheet, $bit_p_admin_sheet, $bit_p_admin;
	extract ($params,EXTR_SKIP);
	$tikilib = &new BitLib( $dbBit );

	if (!isset($id)) {
		return ("<b>missing id parameter for plugin</b><br />");
	}

	if( !class_exists( 'BitSheet' ) )
		require SHEETS_PKG_PATH."grid.php";

	// Build required objects
	$sheet = &new BitSheet;
	$db = &new BitSheetDatabaseHandler( $id );
	$out = &new BitSheetOutputHandler( $data );

	// Fetch sheet from database
	$sheet->import( $db );
	
	// Grab sheet output
	ob_start();
	$sheet->export( $out );
	$ret = ob_get_contents();
	ob_end_clean();

	if( $bit_p_edit_sheet == 'y' || $bit_p_admin_sheet == 'y' || $bit_p_admin )
		$ret .= "<a href='view_sheets.php?sheet_id=$id&readdate=" . time() . "&mode=edit' class='linkbut'>" . tra("Edit Sheet") . "</a>";
	
	return $ret;
}

?>

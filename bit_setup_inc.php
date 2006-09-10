<?php
// Initialise global variables
global $gBitSystem, $gBitUser;
// Register package with kernel
$registerHash = array(
	'package_name' => 'sheets',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );
// If package is installed
if( $gBitSystem->isPackageActive( 'sheets' ) )
{
    // Register user menu
    $gBitSystem->registerAppMenu( 'sheets', 'Spreadsheets', SHEETS_PKG_URL.'index.php', 'bitpackage:sheets/menu_sheets.tpl', 'Spreadsheets' );
}
?>

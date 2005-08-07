<?php
// Initialise global variables
global $gBitSystem, $gBitUser;
// Register package with kernel
$gBitSystem->registerPackage( 'sheets', dirname( __FILE__ ).'/' );
// If package is installed
if( $gBitSystem->isPackageActive( 'sheets' ) )
{
    // Register user menu
    $gBitSystem->registerAppMenu( 'sheets', 'Spreadsheets', SHEETS_PKG_URL.'index.php', 'bitpackage:sheets/menu_sheets.tpl', 'Spreadsheets' );
}
?>

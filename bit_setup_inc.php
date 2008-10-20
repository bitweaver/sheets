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
	$menuHash = array(
		'package_name'       => SHEETS_PKG_NAME,
		'index_url'          => SHEETS_PKG_URL.'index.php',
		'menu_template'      => 'bitpackage:sheets/menu_sheets.tpl',
	);
    // Register user menu
    $gBitSystem->registerAppMenu( $menuHash );
}
?>

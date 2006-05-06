<?php

$tables = array(
'tiki_sheet_layout' => "
  sheet_id I4 NOTNULL PRIMARY,
  cell_begin I8 NOTNULL PRIMARY,
  cell_end I8,
  header_row I4 NOTNULL,
  footer_row I4 NOTNULL,
  class_name C(64)
",

'tiki_sheet_values' => "
  sheet_id I4 NOTNULL PRIMARY,
  cell_begin I8 NOTNULL PRIMARY,
  cell_end I8,
  row_index I4 NOTNULL PRIMARY,
  column_index I4 NOTNULL PRIMARY,
  cell_value C(255),
  calculation C(255),
  width I4 NOTNULL DEFAULT '1',
  height I4 NOTNULL DEFAULT '1',
  format C(255)
",

'tiki_sheets' => "
  sheet_id I4 AUTO PRIMARY,
  title C(160) NOTNULL,
  description C(250),
  author I4 NOTNULL
",

);

global $gBitInstaller;

$gBitInstaller->makePackageHomeable(SHEETS_PKG_NAME);

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( SHEETS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( SHEETS_PKG_NAME, array(
	'description' => "A Blog is a web based journal or diary.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array (
//	'tiki_sheets_user_id_idx' => array( 'table' => 'tiki_sheets', 'cols' => 'user_id', 'opts' => NULL )
);
// TODO - SPIDERR - following seems to cause time _decrease_ cause bigint on postgres. need more investigation
//	'tiki_sheet_posts_created_idx' => array( 'table' => 'tiki_sheet_posts', 'cols' => 'created', 'opts' => NULL ),
$gBitInstaller->registerSchemaIndexes( SHEETS_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'tiki_sheet_values_sheet_id_seq' => array( 'start' => 1 ) 
);
$gBitInstaller->registerSchemaSequences( SHEETS_PKG_NAME, $sequences );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( SHEETS_PKG_NAME, array(
	array('bit_p_create_sheet', 'Can create a sheet', 'registered', SHEETS_PKG_NAME),
	array('bit_p_edit_sheet', 'Can create and edit sheets', 'editors', SHEETS_PKG_NAME),
	array('bit_p_read_sheet', 'Can read sheets', 'basic', SHEETS_PKG_NAME),
	array('bit_p_admin_sheet', 'Can admin sheet', 'admin', SHEETS_PKG_NAME),
	array('bit_p_view_sheet_history', 'Can view sheet history', 'admin', SHEETS_PKG_NAME),
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( SHEETS_PKG_NAME, array(
//	array( SHEETS_PKG_NAME, 'feature_sheets','y'),
) );

?>

<?php

// $Header: /cvsroot/bitweaver/_bit_sheets/import_sheet.php,v 1.5 2010/02/08 21:27:25 wjames5 Exp $

// Based on galleries.php
// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// Initialization
require_once ('../kernel/setup_inc.php');
require_once (SHEETS_PKG_PATH.'sheet/grid.php');

$gBitSystem->isPackageActive('sheets');

$gBitSystem->verifyPermission('bit_p_view_sheet');
$gBitSystem->verifyPermission('bit_p_admin_sheet');
$gBitUser->isAdmin();


$smarty->assign('sheet_id', $_REQUEST["sheet_id"]);

// Individual permissions are checked because we may be trying to edit the gallery

// Init smarty variables to blank values
//$smarty->assign('theme','');

$info = $sheetlib->get_sheet_info( $_REQUEST["sheet_id"] );

$smarty->assign('title', $info['title']);
$smarty->assign('description', $info['description']);

$smarty->assign('page_mode', 'form' );

// Process the insertion or modification of a gallery here

$grid = &new BitSheet;

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	$smarty->assign('page_mode', 'submit' );

	$sheet_id = $_REQUEST['sheet_id'];
	$handler = $_REQUEST['handler'];
	
	// Instanciate the handler
	switch( $handler )
	{
	case 'BitSheetWikiTableHandler': // Well known, special handlers
		$handler = &new $handler( $_POST['page'] );
		break;
	default: // All file based handlers registered
		if( !in_array( $handler, BitSheet::getHandlerList() ) )
		{
			$smarty->assign('msg', "Handler is not allowed.");
			$smarty->display("error.tpl");
			die;
		}

		$handler = &new $handler( $_FILES['file']['tmp_name'] );
	}

	if( !$grid->import( $handler ) )
	{
		$smarty->assign('msg', "Impossible to import the file.");

		$smarty->display("error.tpl");
		die;
	}

	$handler = &new BitSheetDatabaseHandler( $sheet_id );
	$grid->export( $handler );

	ob_start();
	$handler = &new BitSheetOutputHandler;
	$grid->export( $handler );
	$smarty->assign( "grid_content", ob_get_contents() );
	ob_end_clean();
}
else
{
	$list = array();

	$handlers = BitSheet::getHandlerList();
	
	foreach( $handlers as $key=>$handler )
	{
		$temp = &new $handler;
		if( !$temp->supports( BITSHEET_LOAD_DATA | BITSHEET_LOAD_CALC ) )
			continue;

		$list[$key] = array(
			"name" => $temp->name(),
			"version" => $temp->version(),
			"class" => $handler
		);
	}

	$smarty->assign_by_ref( "handlers", $list );
}

if( $gBitSystem->isPackageActive( 'categories' ) ) {
	// Check to see if page is categorized
	$cat_objid = $gContent->mContentId;
	$cat_obj_type = BITPAGE_CONTENT_TYPE_GUID;
	include_once( CATEGORIES_PKG_PATH.'categories_display_inc.php' );
	include_once ( CATEGORIES_PKG_PATH.'categorize_display_inc.php' );
}

$section = 'sheet';

// Display the template
$gBitSystem->display("bitpackage:sheets/import-sheets.tpl", NULL, array( 'display_mode' => 'display' ));

?>

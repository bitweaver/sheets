<?php

// $Header: /cvsroot/bitweaver/_bit_sheets/view_sheets.php,v 1.1 2005/08/07 11:35:07 wolff_borg Exp $

// Based on galleries.php
// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('../bit_setup_inc.php');
require_once (SHEETS_PKG_PATH.'sheet/grid.php');

// Now check permissions to access this page
$gBitSystem->isPackageActive('sheets');

$gBitSystem->verifyPermission('bit_p_view_sheet');
$gBitSystem->verifyPermission('bit_p_admin_sheet');
$gBitUser->isAdmin();

if ( !isset($_REQUEST['sheet_id']) ) {
	$smarty->assign('msg', tra("A Sheet ID is required."));

	$smarty->display("error.tpl");
	die;
}

$smarty->assign('sheet_id', $_REQUEST["sheet_id"]);
$smarty->assign('chart_enabled', (function_exists('imagepng') || function_exists('pdf_new')) ? 'y' : 'n');

// Individual permissions are checked because we may be trying to edit the gallery

// Init smarty variables to blank values
//$smarty->assign('theme','');

$info = $sheetlib->get_sheet_info( $_REQUEST["sheet_id"] );

$smarty->assign('title', $info['title']);
$smarty->assign('description', $info['description']);

$smarty->assign('page_mode', 'view' );

// Process the insertion or modification of a gallery here

$grid = &new BitSheet;

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	if (!$gBitUser->hasPermission('bit_p_edit_sheet') && !$gBitUser->hasPermission('bit_p_admin_sheet') && !$gBitUser->isAdmin()) {
		$smarty->assign('msg', tra("Access Denied").": feature_sheets");

		$smarty->display("error.tpl");
		die;
	}

	// Load data from the form
	$handler = &new BitSheetFormHandler;
	if( !$grid->import( $handler ) )
		$grid = &new BitSheet;

	// Save the changes
	$handler = &new BitSheetDatabaseHandler( $_REQUEST["sheet_id"] );
	$grid->export( $handler );

	// Load the layout settings from the database
	$grid = &new BitSheet;
	$grid->import( $handler );

	$handler = &new BitSheetOutputHandler;

	ob_start();
	$grid->export( $handler );
	$smarty->assign( 'grid_content', ob_get_contents() );
	ob_end_clean();
}
else
{
	$handler = &new BitSheetDatabaseHandler( $_REQUEST["sheet_id"] );

	$date = time();
	if( !empty( $_REQUEST[ 'readdate' ] ) )
	{
		$date = $_REQUEST[ 'readdate' ];

		if( !is_numeric( $date ) )
			$date = strtotime( $date );

		if( $date == -1 )
			$date = time();
	}

	$smarty->assign( 'read_date', $date );
	$handler->setReadDate( $date );
	
	$grid->import( $handler );

	if( isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'edit' )
	{
		$handler = &new BitSheetFormHandler;

		ob_start();
		$grid->export( $handler );
		$smarty->assign( 'init_grid', ob_get_contents() );
		ob_end_clean();

		$smarty->assign('page_mode', 'edit' );
	}
	else
	{
		$handler = &new BitSheetOutputHandler;

		ob_start();
		$grid->export( $handler );
		$smarty->assign( 'grid_content', ob_get_contents() );
		ob_end_clean();
	}
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
$gBitSystem->display("bitpackage:sheets/view-sheets.tpl");

?>

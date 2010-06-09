<?php

// $Header$

// Copyright (c) 2002-2005, TikiWiki Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// Copyright (c) 2005, BitWeaver Stephan Borg
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// Initialization
require_once ('../kernel/setup_inc.php');
require_once (SHEETS_PKG_PATH.'sheet/grid.php');

$gBitSystem->isPackageActive('sheets');

$gBitSystem->verifyPermission('bit_p_view_sheet');
$gBitSystem->verifyPermission('bit_p_admin_sheet');
$gBitUser->isAdmin();

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}

$smarty->assign('find', $find);

if (!isset($_REQUEST["sheet_id"])) {
	$_REQUEST["sheet_id"] = 0;
}

$smarty->assign('sheet_id', $_REQUEST["sheet_id"]);

// Init smarty variables to blank values
//$smarty->assign('theme','');
$smarty->assign('title', '');
$smarty->assign('description', '');
$smarty->assign('edit_mode', 'n');
$smarty->assign('chart_enabled', (function_exists('imagepng') || function_exists('pdf_new')) ? 'y' : 'n');

// If we are editing an existing gallery prepare smarty variables
if (isset($_REQUEST["edit_mode"]) && $_REQUEST["edit_mode"]) {

	// Get information about this galleryID and fill smarty variables
	$smarty->assign('edit_mode', 'y');

	if ($_REQUEST["sheet_id"] > 0) {
		$info = $sheetlib->get_sheet_info($_REQUEST["sheet_id"]);

		$smarty->assign('title', $info["title"]);
		$smarty->assign('description', $info["description"]);

		$info = $sheetlib->get_sheet_layout($_REQUEST["sheet_id"]);

		$smarty->assign('class_name', $info["class_name"]);
		$smarty->assign('header_row', $info["header_row"]);
		$smarty->assign('footer_row', $info["footer_row"]);
	}
	else
	{
		$smarty->assign('class_name', 'default');
		$smarty->assign('header_row', '0');
		$smarty->assign('footer_row', '0');
	}
}

// Process the insertion or modification of a gallery here
if (isset($_REQUEST["edit"])) {
	// Saving information
	// If the user is not gallery admin
	if (!$gBitUser->hasPermission('bit_p_admin_sheet') && !$gBitUser->isAdmin()) {
		$gBitSystem->verifyPermission('bit_p_edit_sheet');

	}

	// Everything is ok so we proceed to edit the gallery
	$smarty->assign('edit_mode', 'y');
	//$smarty->assign_by_ref('theme',$_REQUEST["theme"]);
	$smarty->assign_by_ref('title', $_REQUEST["title"]);
	$smarty->assign_by_ref('description', $_REQUEST["description"]);

	$smarty->assign_by_ref('class_name', $_REQUEST["class_name"]);
	$smarty->assign_by_ref('header_row', $_REQUEST["header_row"]);
	$smarty->assign_by_ref('footer_row', $_REQUEST["footer_row"]);

	$gid = $sheetlib->replace_sheet($_REQUEST["sheet_id"], $_REQUEST["title"], $_REQUEST["description"], $gBitUser->getUserId() );
	$sheetlib->replace_layout($gid, $_REQUEST["class_name"], $_REQUEST["header_row"], $_REQUEST["footer_row"] );

	$cat_desc = substr($_REQUEST["description"], 0, 200);
	$cat_name = $_REQUEST["title"];
if( $gBitSystem->isPackageActive( 'categories' ) ) {
	// Check to see if page is categorized
	$cat_objid = $gContent->mContentId;
	$cat_obj_type = BITPAGE_CONTENT_TYPE_GUID;
	include_once( CATEGORIES_PKG_PATH.'categories_display_inc.php' );
	include_once ( CATEGORIES_PKG_PATH.'categorize_display_inc.php' );
}
	$smarty->assign('edit_mode', 'n');
}

if (isset($_REQUEST["removesheet"])) {
	if (!$gBitUser->hasPermission('bit_p_admin_sheet') && !$gBitUser->isAdmin()) {

		$smarty->assign('msg', tra("Permission denied you cannot remove this gallery"));

		$smarty->display("error.tpl");
		die;
	}
  $area = 'delsheet';
  $sheetlib->remove_sheet($_REQUEST["removesheet"]);
}

if (!isset($_REQUEST["sort_mode"])) {
	$sort_mode = 'title_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

$smarty->assign_by_ref('sort_mode', $sort_mode);

// If offset is set use it if not then use offset =0
// use the maxRecords php variable to set the limit
// if sortMode is not set then use lastModif_desc
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}

$smarty->assign_by_ref('offset', $offset);

// Get the list of libraries available for this user (or public galleries)
// GET ALL GALLERIES SINCE ALL GALLERIES ARE BROWSEABLE
$sheets = $sheetlib->list_sheets($offset, $maxRecords, $sort_mode, $find);

// If there're more records then assign next_offset
$cant_pages = ceil($sheets["cant"] / $maxRecords);
$smarty->assign_by_ref('cant_pages', $cant_pages);
$smarty->assign('actual_page', 1 + ($offset / $maxRecords));

if ($sheets["cant"] > ($offset + $maxRecords)) {
	$smarty->assign('next_offset', $offset + $maxRecords);
} else {
	$smarty->assign('next_offset', -1);
}

// If offset is > 0 then prev_offset
if ($offset > 0) {
	$smarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$smarty->assign('prev_offset', -1);
}

$smarty->assign_by_ref('sheets', $sheets["data"]);
//print_r($galleries["data"]);
if( $gBitSystem->isPackageActive( 'categories' ) ) {
	// Check to see if page is categorized
	$cat_objid = $gContent->mContentId;
	$cat_obj_type = BITPAGE_CONTENT_TYPE_GUID;
	include_once( CATEGORIES_PKG_PATH.'categories_display_inc.php' );
	include_once ( CATEGORIES_PKG_PATH.'categorize_display_inc.php' );
}


$section = 'sheet';

// Display the template
$gBitSystem->display("bitpackage:sheets/sheets.tpl", NULL, array( 'display_mode' => 'display' ));

?>

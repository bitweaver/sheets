<?php

// $Header: /cvsroot/bitweaver/_bit_sheets/history_sheets.php,v 1.1 2005/08/07 11:35:07 wolff_borg Exp $

// Based on galleries.php
// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('../bit_setup_inc.php');
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

$smarty->assign('page_mode', 'view' );

$result = $gBitSystem->query( "SELECT DISTINCT `begin` FROM `tiki_sheet_values` WHERE `sheet_id` = ? ORDER BY begin DESC", array( $_REQUEST['sheet_id'] ) );
$data = array();
while( $row = $result->fetchRow() )
	$data[] = array( "stamp" =>$row['begin'], "string" => date( "Y-m-d H:i:s", $row['begin'] ) );

$smarty->assign_by_ref( 'history', $data );

$section = 'sheet';

// Display the template
$gBitSystem->display("bitpackage:sheets/history-sheets.tpl");

?>

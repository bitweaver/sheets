<?php

// $Header: /cvsroot/bitweaver/_bit_sheets/graph_sheet.php,v 1.5 2010/02/08 21:27:25 wjames5 Exp $

// Based on galleries.php
// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// Initialization
require_once ('../kernel/setup_inc.php');
require_once (SHEETS_PKG_PATH.'sheet/grid.php');
require_once (SHEETS_PKG_PATH.'graph-engine/gd.php');
require_once (SHEETS_PKG_PATH.'graph-engine/pdflib.php');
require_once (SHEETS_PKG_PATH.'graph-engine/graph.pie.php');
require_once (SHEETS_PKG_PATH.'graph-engine/graph.bar.php');
require_once (SHEETS_PKG_PATH.'graph-engine/graph.multiline.php');

function handle_series( $serie, &$sheet )
{
	if( !$range = $sheet->getRange( $serie ) )
		$range = explode( ', ', $serie );

	if( !is_array( $range ) )
		return array();

	return $range;
}

// Various validations {{{1

$gBitSystem->isPackageActive('sheets');

$gBitSystem->verifyPermission('bit_p_view_sheet');
$gBitSystem->verifyPermission('bit_p_admin_sheet');
$gBitUser->isAdmin();

// This condition will be removed when a php-based renderer will be written
if( !function_exists( 'pdf_new' ) && !function_exists( 'imagepng' ) )
{
	$smarty->assign('msg', tra("No valid renderer found. GD or PDFLib required.") );

	$smarty->display("error.tpl");
	die;
}

if( !isset( $_REQUEST['sheet_id'] ) )
{
	$smarty->assign('msg', tra("No sheet specified.") );

	$smarty->display("error.tpl");
	die;
}
// }}}1

$valid_graphs = array( 'PieChartGraphic', 'MultilineGraphic', 'MultibarGraphic', 'BarStackGraphic' );

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

if( isset( $_REQUEST['title'] ) )
{
	$sheet_id = $_REQUEST['sheet_id'];

	$handler = &new BitSheetDatabaseHandler( $sheet_id );
	$grid->import( $handler );

	if( !in_array( $_REQUEST['graphic'], $valid_graphs ) )
		die( "Unknown Graphic." );

	if( !isset( $_REQUEST['renderer'] ) )
		$_REQUEST['renderer'] = null;
	switch( $_REQUEST['renderer'] )
	{
	case 'PNG':
		$renderer = &new GD_GRenderer( $_REQUEST['width'], $_REQUEST['height'], 'png' );
		$ext = 'png';
		break;
	case 'JPEG':
		$renderer = &new GD_GRenderer( $_REQUEST['width'], $_REQUEST['height'], 'jpg' );
		$ext = 'jpg';
		break;
	case 'PDF':
		$renderer = &new PDFLib_GRenderer( $_REQUEST['format'], $_REQUEST['orientation'] );
		$ext = 'pdf';
		break;
	default:
		$smarty->assign('msg', tra("You must select a renderer."));

		$smarty->display("error.tpl");
		die;
	}

	$graph = $_REQUEST['graphic'];
	$graph = new $graph;

	// Create Output
	$series = array();
	foreach( $_REQUEST['series'] as $key => $value )
		if( !empty( $value ) )
		{
			$s = handle_series( $value, $grid );
			if( count( $s ) > 0 )
				$series[$key] = $s;
		}

	if( !$graph->setData( $series ) )
	{
		$smarty->assign('msg', tra("Invalid Series for current graphic.") );

		$smarty->display("error.tpl");
		die;
	}

	if( !empty( $_REQUEST['title'] ) )
		$graph->setTitle( $_REQUEST['title'] );

	if( isset( $_REQUEST['independant'] ) )
	{
		$graph->setParam( 'grid-independant-location', $_REQUEST['independant'] );
		$graph->setParam( 'grid-vertical-position', $_REQUEST['vertical'] );
		$graph->setParam( 'grid-horizontal-position', $_REQUEST['horizontal'] );
	}

	$graph->draw( $renderer );

	$renderer->httpOutput( "graph.$ext" );

	exit;
}
else
{
	if( isset( $_GET['graphic'] ) && in_array( $_GET['graphic'], $valid_graphs ) )
	{
		$graph = $_GET['graphic'];
		$g = new $graph;
		$series = array();
		foreach( array_keys( $g->getRequiredSeries() ) as $s )
			if( $s == 'y0' )
			{
				$series[] = 'y0';
				$series[] = 'y1';
				$series[] = 'y2';
				$series[] = 'y3';
				$series[] = 'y4';
			}
			else
				$series[] = $s;

		$smarty->assign( 'mode', 'param' );
		$smarty->assign( 'series', $series );
		$smarty->assign( 'graph', $graph );
		$smarty->assign( 'renderer', $_GET['renderer'] );

		if( function_exists( 'pdf_new' ) )
		{
			$smarty->assign( 'format', $_GET['format'] );
			$smarty->assign( 'orientation', $_GET['orientation'] );
		}
		if( function_exists( 'imagepng' ) )
		{
			$smarty->assign( 'im_width', $_GET['width'] );
			$smarty->assign( 'im_height', $_GET['height'] );
		}

		if( is_a( $g, 'GridBasedGraphic' ) )
			$smarty->assign( 'showgridparam', true );
	}
	else
	{
		$smarty->assign( 'mode', 'graph' );
		$smarty->assign( 'hasgd', function_exists( 'imagepng' ) && function_exists( 'imagejpeg' ) );
		$smarty->assign( 'haspdflib', function_exists( 'pdf_new' ) );
	}
}

// Display the template
$gBitSystem->display("bitpackage:sheets/graph-sheets.tpl", NULL, array( 'display_mode' => 'display' ));

?>

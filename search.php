<?php
// Load Elgg engine
include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// make sure only logged in users can see this page	
gatekeeper();

// set the title
$title = "Search stackoverflow";

// start building the main column of the page
$area1 = elgg_view_title($title);

// Add the form to this section
$area1 .= elgg_view("stackoverflow/search");
$area1 .= elgg_view("stackoverflow/search_results");

// layout the page
$body = elgg_view_layout('one_column', $area1);
// draw the page
echo page_draw($title, $body);
?>

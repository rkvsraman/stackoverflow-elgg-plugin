<?php
global $CONFIG;


add_menu('Stackoverflow', $CONFIG->wwwroot . "mod/stackoverflow/search.php" );
register_action("stackoverflow/search", false, $CONFIG->pluginspath . "stackoverflow/actions/search.php");
register_action("stackoverflow/details", false, $CONFIG->pluginspath . "stackoverflow/actions/details.php");
register_action("stackoverflow/save", false, $CONFIG->pluginspath . "stackoverflow/actions/save.php");
function stackoverflow_init() {

	elgg_extend_view('metatags', 'stackoverflow/metatags');
	elgg_extend_view('css', 'stackoverflow/css');
}

register_elgg_event_handler('init', 'system', 'stackoverflow_init');

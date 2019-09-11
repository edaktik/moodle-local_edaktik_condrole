<?php

function local_edaktik_condrole_extend_settings_navigation(settings_navigation $nav){
	$url = new moodle_url('/local/edaktik_condrole/index.php');
	$pluginname = get_string('pluginname', 'local_edaktik_condrole');
	
	$node = $nav->get('root');
    if ($node) {
        $node = $node->get('users');
		$node = $node->get('roles');
    }
	//echo '<pre>'.print_r($node,true).'</pre>';
	//User policies
	if ($node) {
        $condrole = $node->add($pluginname, $url, navigation_node::TYPE_SETTING,
                                $pluginname, null);
        
    }
	
	
}
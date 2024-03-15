<?php
function smarty_function_widget_search($params, $template){
	$front = new frontend_model_template();
    $front->addConfigFile(
		array(component_core_system::basePath().'/plugins/search/i18n/'),
		array('public_local_'),
		false
	);
	$front->configLoad();
}
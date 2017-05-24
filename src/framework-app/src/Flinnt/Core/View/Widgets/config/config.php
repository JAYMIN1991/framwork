<?php

return [


	'default_namespace' => 'App\Http\Widgets',

	/*
	 * Set default namespace for the widget within module
	 */
	'default_namespace_in_module' => '{{default_module_namespace}}\Http\Widgets',

    'use_jquery_for_ajax_calls' => false,

    /*
    * Set Ajax widget middleware
    */
    'route_middleware' => [],

    /*
    * Relative path from the base directory to a regular widget stub.
    */
    'widget_stub'  => 'vendor/flinnt/framework-app/src/Flinnt/Core/View/Widgets/Console/stubs/widget.stub',

    /*
    * Relative path from the base directory to a plain widget stub.
    */
    'widget_plain_stub'  => 'vendor/flinnt/framework-app/src/Flinnt/Core/View/Widgets/Console/stubs/widget_plain.stub',

	/*
	 * Ajax link where widget can grab content.
	 *
	 * @var string
	 */
	'ajaxLink' => '/flinnt/load-widget',
];

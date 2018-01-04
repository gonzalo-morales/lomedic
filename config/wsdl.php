<?php
return [
	/*
	|--------------------------------------------------------------------------
	| Default Wsdl Connection Name
	|--------------------------------------------------------------------------
	*/

	'default' => env('WSDL_CONNECTION', 'solucionfactible'),

	/*
	|--------------------------------------------------------------------------
	| Wsdl Connections
	|--------------------------------------------------------------------------
	*/

	'connections' => [
	    'solucionfactible' => [
	        'url' => 'https://testing.solucionfactible.com/ws/services/Timbrado?wsdl',
	        'function' => 'timbrar',
	        'options'   => [
	            'encoding' => 'UTF-8',
	            'stream_context' => stream_context_create([
	                'ssl' => [
	                    'verify_peer'       => false,
	                    'verify_peer_name'  => false,
	                    'allow_self_signed' => true,
	                    'cache_wsdl'        => WSDL_CACHE_NONE,
	                ]
	            ])
            ],
	        'parameters' => [
	            'usuario' => 'testing-cfdi33@lom990211kq2.sf',
	            'password' => '7qEgq6mVYyeTI7Jj8WA1rWXq',
	            'zip'=>false,
	        ],
	    ],
	],
];
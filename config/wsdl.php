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
	    'solucionfactible_timbrado' => [
	        'url' => 'https://testing.solucionfactible.com/ws/services/Timbrado?wsdl',
	        'options'  => [
	            'encoding' => 'UTF-8',
	            'stream_context' => stream_context_create([
	                'ssl' => [
	                    'verify_peer'       => false,
	                    'verify_peer_name'  => false,
	                    'allow_self_signed' => true,
	                    'cache_wsdl'        => 'WSDL_CACHE_NONE',
	                ]
	            ])
            ],
	        'parameters' => [
	            'usuario' => 'testing-cfdi33@lom990211kq2.sf',
	            'password' => '7qEgq6mVYyeTI7Jj8WA1rWXq',
	            'zip'=>false,
	        ],
	    ],

	    'solucionfactible_cancelacion' => [
	        'url' => 'https://testing.solucionfactible.com/ws/services/Cancelacion?wsdl',
	        'options'  => [
	            'encoding' => 'UTF-8',
	            'stream_context' => stream_context_create([
	                'ssl' => [
	                    'verify_peer'       => false,
	                    'verify_peer_name'  => false,
	                    'allow_self_signed' => true,
	                    'cache_wsdl'        => 'WSDL_CACHE_NONE',
	                ]
	            ])
	        ],
	        'parameters' => [
	            'usuario' => 'testing-cfdi33@lom990211kq2.sf',
	            'password' => '7qEgq6mVYyeTI7Jj8WA1rWXq',
	        ],
	    ],
	    
	    
	    'localhost_areas' => [
	        'url' => 'http://localhost:81/abisa/Wsdl/Areas/?wsdl',
	        'options'  => [
	            'encoding' => 'UTF-8',
	            'stream_context' => stream_context_create([
	                'ssl' => [
	                    'verify_peer'       => false,
	                    'verify_peer_name'  => false,
	                    'allow_self_signed' => true,
	                    'cache_wsdl'        => 'WSDL_CACHE_NONE',
	                ]
	            ])
	        ],
	        'parameters' => [
	            'usuario' => 'testing-cfdi33@lom990211kq2.sf',
	            'password' => '7qEgq6mVYyeTI7Jj8WA1rWXq',
	        ],
	    ],
	],
];

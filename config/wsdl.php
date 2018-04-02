<?php
return [
	/*
	|--------------------------------------------------------------------------
	| Default RestApi Connection Name
	|--------------------------------------------------------------------------
	*/

	'default' => env('WSDL_CONNECTION', 'solucionfactible'),

	/*
	|--------------------------------------------------------------------------
	| RestApi Connections
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
		
		'ipejal' => [
	        'url' => 'https://pensiones.jalisco.gob.mx/Farmacia/wsFarmacia.asmx?wsdl',
	        'options'  => [
				'encoding' => 'UTF-8',
				'verifypeer' => false,
				'verifyhost' => false,
				'soap_version' => SOAP_1_1,
				'trace' => 1,
				'exceptions' => 1,
				"connection_timeout" => 180,
				'stream_context' => stream_context_create([
					'ssl' => ['ciphers'=>'RC4-SHA', 'verify_peer'=>false, 'verify_peer_name'=>false]
				]),
	        ],
	        'parameters' => [
	            'Usr' => 'ABISALUD',
	            'Pwd' => 'TB-x23-G3',
	        ],
	    ],
	    
	    
	    'localhost_areas' => [
	        'url' => 'http://localhost:81/abisa/RestApi/PagosController/?wsdl',
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

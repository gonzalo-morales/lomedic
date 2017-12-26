<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => public_path(),
        ],
        'tickets' => [
            'driver' => 'local',
            'root' => storage_path('app/tickets'),
        ],
        'socios_anexos' => [
            'driver' => 'local',
            'root' => storage_path('app/sociosnegocio/anexos'),
        ],
        'proyectos_contratos' => [
            'driver' => 'local',
            'root' => storage_path('app/proyectos/contratos'),
        ],
        'proyectos_anexos' => [
            'driver' => 'local',
            'root' => storage_path('app/proyectos/anexos'),
        ],
        'pedidos_anexos' => [
            'driver' => 'local',
            'root' => storage_path('app/pedidos/anexos'),
        ],
        'logotipos' => [
            'driver' => 'local',
            'root' => public_path('img/logotipos'),
            'visibility' => 'public',
        ],
        'certificados' => [
            'driver' => 'local',
            'root' => storage_path('app/sat/certificado'),
        ],
        'factura_proveedor' => [
            'driver' => 'local',
            'root' => storage_path('app/facturas/proveedores')
        ],
        'pagos' => [
            'driver' => 'local',
            'root' => storage_path('app/compras/pagos')
        ],
        'notas_proveedor' => [
            'driver' => 'local',
            'root' => storage_path('app/notas/proveedores')
        ],
        'public_storage' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],
    ],
];
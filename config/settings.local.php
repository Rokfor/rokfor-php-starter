<?php
return [
    'settings' => [
        'rokfor' => [
          'ro-key'    => '9011ec2f37d2516e057ca2adcab50e45',
          'rw-key'    => '',
          'user'      => '',
          'endpoint'  => 'https://demo.rokfor.ch/api'
        ],
        'timezone'          => "Europe/Zurich",                 // Ref. http://php.net/manual/de/timezones.php
        'displayErrorDetails' => true,                          // Display Error Settings: set to false in production
        'determineRouteBeforeAppMiddleware'   => true,
        'view' => [                                             // Jade Renderer settings
          'template_path' => __DIR__ . '/../templates/',        // Path to templates
          'cache_path'    => __DIR__ . '/../cache/',            // Path to cache dir
        ]
    ]
];

<?php
return [
    'settings' => [
        'rokfor' => [
          'ro-key'    => '',
          'rw-key'    => '',
          'user'      => '',
          'endpoint'  => ''
        ],
        'timezone'          => "Europe/Zurich",                 // Ref. http://php.net/manual/de/timezones.php
        'displayErrorDetails' => true,                          // Display Error Settings: set to false in production
        'determineRouteBeforeAppMiddleware'   => true,
        'view' => [                                             // Jade Renderer settings
          'template_path' => __DIR__ . '/../templates/',        // Path to templates
          'cache_path'    => __DIR__ . '/../cache/',            // Path to cache dir
        ],
        'logger' => [                                           // Monolog settings
          'path'          => false,                             // Path to Log File. 
                                                                // If false, PHPConsole for Chrome will be used 
                                                                // http://php-console.com
          'level'         => Monolog\Logger::INFO,              // Error Level: 
                                                                // Monolog\Logger::INFO shows a lot of stuff from the API
                                                                // Monolog\Logger::ERROR
        ]
    ]
];

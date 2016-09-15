<?php

$container = $app->getContainer();

// Logger: Global, to be reached from the api decode callback

$logger = new Monolog\Logger('app');
if ($container->get('settings')['logger']['path']) {
  $logger->pushHandler(new Monolog\Handler\StreamHandler(
    $container->get('settings')['logger']['path'], 
    $container->get('settings')['logger']['level']
  ));
}
else {
  $logger->pushHandler(new Monolog\Handler\PHPConsoleHandler(
    [], 
    null, 
    $container->get('settings')['logger']['level']
  ));
}

// Integrate Jade View Renderer
$container['view'] = function ($c) {
  $settings = $c->get('settings')['view'];
  $view = new \Slim\Views\Jade($settings['template_path'], ['cache' => $settings['cache_path']]);
  return $view;
};

// Override the default Not Found Handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
      $c['view']->render($c['response']->withStatus(404), 'error.jade', []);
      return $c['response'];
    };
};

// Json API
$container['api'] = function ($c) {
  $settings = $c->get('settings')['rokfor'];
  $api = new RestClient([
      'base_url' => $settings['endpoint'], 
      'headers' => ['Authorization' => 'Bearer '.$settings['ro-key']], 
  ]);
  $api->register_decoder('json', function($data){
    $parsed = json_decode($data, TRUE);
    $GLOBALS['logger']->info(print_r($parsed,true));
    return $parsed;
  });
  return $api;
};
<?php

function u($data) {
  return urlencode(preg_replace('/ +/', '_', preg_replace('/[^A-Za-z0-9 ]/','',$data)));
};

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

  $api->register_cachechecker(function($hash, $key) use (&$c) {
    if ($hash === "timebased") {
      if (file_exists($c->get('settings')['view']['cache_path'].'/.api_cache/'.$key.'/time')) {
        $_timecheck = file_get_contents($c->get('settings')['view']['cache_path'].'/.api_cache/'.$key.'/time');
        $_control   = (time() - $_timecheck) < ($c->get('settings')['view']['cache_timecheck_offset'] ? $c->get('settings')['view']['cache_timecheck_offset'] : 86400)
                        ? $hash
                        : false;
      }
    }
    else {
      if (file_exists($c->get('settings')['view']['cache_path'].'/.api_cache/'.$key.'/hash')) {
        $_control = file_get_contents($c->get('settings')['view']['cache_path'].'/.api_cache/'.$key.'/hash');
      }
      else {
        return false;
      }
    }
    if ($_control == $hash) {
      $u = unserialize(file_get_contents($c->get('settings')['view']['cache_path'].'/.api_cache/'.$key.'/content'));
      return $u;
    }
    return false;
  });

  $api->register_decoder('json', function($data, $caller) use (&$c) {
    $parsed = json_decode($data, TRUE);
    if ($caller->options['caching'] === true) {
      $hash = $caller->_timestamp_hash ? $caller->_timestamp_hash : $caller->headers->x_cache_hash;
      $path = $c->get('settings')['view']['cache_path'].'/.api_cache';
      // Create directory
      if (!file_exists($path)) mkdir($path);
      if (!file_exists($path.'/'.$hash)) mkdir($path.'/'.$hash);
      // Store
      file_put_contents($path.'/'.$hash.'/hash',    $parsed["Hash"]);
      file_put_contents($path.'/'.$hash.'/time',    time());
      file_put_contents($path.'/'.$hash.'/content', serialize([
        "Headers" => $caller->headers,
        "Decoded" => $parsed,
        "Info"    => $caller->info,
        "Error"   => $caller->error
      ]));
    }
    //$GLOBALS['logger']->info(print_r($parsed,true));
    return $parsed;
  });
  return $api;
};
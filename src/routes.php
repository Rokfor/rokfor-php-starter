<?php

/*  
 * Index Page
 *
 */

use Slim\Exception\NotFoundException;


$app->get('/', function ($request, $response, $args) {
  $result = $this->api->get("books");
  if($result->info->http_code == 200) {
       $args = $result->decode_response();
       $this->view->render($response, 'index.jade', $args);
  }
  else {
    throw new NotFoundException($request, $response);
  }
}); 


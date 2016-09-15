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
       // Any kind of data can be pushed to the $args array
       $args['__rawData']  = print_r($args, true);
       $args['__pageTitle'] = "Book Overview";
       // $args will be expanded and exposed in the jade template.
       // $args['__pageTitle'] will be available as __pageTitle or
       // $__pageTitle if used in a more complex string.
       $this->view->render($response, 'index.jade', $args);
  }
  else {
    throw new NotFoundException($request, $response);
  }
}); 

$app->get('/Book/{id:[0-9]*}', function ($request, $response, $args) {
  $result = $this->api->get("books/".$args['id']."?populate=true");
  if($result->info->http_code == 200) {
       $args = $result->decode_response();
       $args['__rawData']   = print_r($args, true);
       $args['__pageTitle'] = "Book Detail";
       $this->view->render($response, 'book.jade', $args);
  }
  else {
    throw new NotFoundException($request, $response);
  }
}); 


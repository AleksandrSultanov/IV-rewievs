<?php

use Intervolga\Reviews\api\Controller;
use Intervolga\Reviews\store\Reviews;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

include_once dirname(__DIR__)."/vendor/autoload.php";

$app = AppFactory::create();

$controller = new Controller(new Reviews(new SQLite3('/home/intervolga/PhpstormProjects/reviews/IV')));

$app->get('/api/feedbacks/{id}', array($controller, 'showOne'));
$app->get('/api/feedbacks/', array($controller, 'show'));


//$act = function (Request $request, Response $response, $args) {
//    $response->getBody()->write("Hello world!");
//    $response->getBody()->write(json_encode($request->getQueryParams()));
//    return $response;
//};
//$app->get('/', $act);

$app->run();


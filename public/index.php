<?php

use Intervolga\Reviews\api\Controller;
use Intervolga\Reviews\store\Reviews;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

include_once dirname(__DIR__, 1)."/vendor/autoload.php";

$app = AppFactory::create();

$controller = new Controller(new Reviews(new SQLite3('/home/intervolga/PhpstormProjects/reviews/IV')));
$app->get('/api/feedbacks/{id}', array($controller, 'showOne'));
$app->get('/api/feedbacks/page/{page}', array($controller, 'show'));
$app->get('/api/feedbacks/delete/{id}', array($controller, 'deleteReview'));
$app->post('/api/feedbacks/add', array($controller, 'addReview'));

$app->run();
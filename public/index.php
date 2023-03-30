<?php


use Intervolga\Reviews\api\Controller;
use Intervolga\Reviews\store\Reviews;
use Slim\Factory\AppFactory;


require_once dirname(__DIR__) . "/scr/api/AuthClass.php";
include_once dirname(__DIR__) . "/vendor/autoload.php";

$app = AppFactory::create();

$controller = new Controller(new Reviews(new SQLite3('/home/aleksandr/PhpstormProjects/Reviews/IV')));

$auth = new AuthClass();

$app->get('/api/feedbacks/page/{page}/', array($controller, 'showAjax'));
$app->get('/api/', array($controller, 'addAjax'));
$app->get('/api/feedbacks/ajax/page/{page}/', array($controller, 'showOnePage'));
$app->get('/api/out/', array($controller, 'out'));
$app->get('/api/feedbacks/delete/{id}/', array($controller, 'deleteReview'));
$app->get('/api/feedbacks/update/{id}', array($controller, 'updateAjax'));
$app->post('/api/feedbacks/addAjax', array($controller, 'addReview'));
$app->post('/api/feedbacks/updateAjax/{id}', array($controller, 'updateReview'));
$app->post('/api/login/', array($controller, 'showAuth'));
$app->get('/api/login/', array($controller, 'showAuth'));

$app->run();
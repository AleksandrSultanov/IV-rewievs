<?php

use Intervolga\Reviews\api\Controller;
use Intervolga\Reviews\store\Reviews;
use Slim\Factory\AppFactory;
require_once dirname(__DIR__, 1)."/config/config.php";
include_once dirname(__DIR__, 1)."/vendor/autoload.php";

$app = AppFactory::create();

$controller = new Controller(new Reviews(new SQLite3('/home/intervolga/PhpstormProjects/reviews/IV')));

try {$reviews = new SimpleXMLElement($xmlStr);
    $login = $reviews->authentication->users->login;
    $password = $reviews->authentication->users->password;
    $app->add(new Tuupola\Middleware\HttpBasicAuthentication([
        "path" => ["/api/feedbacks/delete"],
        "realm" => "Protected",
        "users" => [
            "$login" => "$password"
        ]
    ]));
} catch (Exception $e) {}

$app->get('/api/feedbacks/{id}', array($controller, 'showOne'));
$app->get('/api/feedbacks/page/{page}', array($controller, 'show'));
$app->get('/api/feedbacks/delete/{id}', array($controller, 'deleteReview'));
$app->post('/api/feedbacks/add', array($controller, 'addReview'));

$app->run();

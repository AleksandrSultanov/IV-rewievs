<?php

namespace Intervolga\Reviews\api;
use Intervolga\Reviews\Review;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Intervolga\reviews\store\Reviews;
include_once dirname(__DIR__) . "/vendor/autoload.php";

class Controller
{
    public Reviews $reviewStore;

    function __construct(Reviews $reviewStore){
        $this->reviewStore = $reviewStore;
    }

    function showOne(Request $request, Response $response, array $args): Response
    {
        $review = $this->reviewStore->findById($args['id']);
        $reviewToJson = $this->toJson($review);
        $response->getBody()->write((string) $reviewToJson);
        return $response;
    }

    function show(Request $request, Response $response, array $args): Response
    {
        $reviews = $this->reviewStore->find(($args['id']-1) * 20 + 1);
        foreach ($reviews as $review) {
            $reviewsToJson = $this->toJson($review);
            $response->getBody()->write((string)$reviewsToJson);
        }
        return $response;
    }

    function toJson(Review $review): bool|string
    {
        $review = array( 'id' => $review->id,
                         'name_creator' => $review->name_creator,
                         'date_create' => $review->date_create,
                         'content' => $review->content
        );
        return json_encode($review);
    }
}
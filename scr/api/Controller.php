<?php

namespace Intervolga\Reviews\api;
use Intervolga\Reviews\Review;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Intervolga\reviews\store\Reviews;



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
        //var_export($args['page']);
        $reviews = $this->reviewStore->find(($args['page']-1) * 20 + 1);
        foreach ($reviews as $review) {
            $reviewsToJson = $this->toJson($review);
            $response->getBody()->write($reviewsToJson."\n");
        }
        return $response;
    }

    function addReview(Request $request, Response $response, array $args): int {
        $allPostPutVars = $request->getParsedBody();
        foreach($allPostPutVars as $key => $param)
            $rev[$key] = $param;
        $review = new Review(0, $rev["name_creator"], date("Y-m-d H:i:s"), $rev["content"]);
        $result = $this->reviewStore->addReview($review);
        if ($result)
            return true;
        else return false;
    }

    function deleteReview(Request $request, Response $response, array $args): Response
    {
        $review = $this->reviewStore->deleteReview($args['id']);
        $reviewToJson = $this->toJson($review);
        $response->getBody()->write((string) $reviewToJson);
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
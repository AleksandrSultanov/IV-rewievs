<?php

namespace Intervolga\Reviews\api;
use Intervolga\Reviews\Review;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
        $reviews = $this->reviewStore->find(($args['page']-1) * 20 + 1);
        $reviewsToJson = json_encode($reviews);
        $response->getBody()->write((string) $reviewsToJson);
        return $response;
    }

    function addReview(Request $request, Response $response, array $args): int {
        $allPostPutVars = $request->getParsedBody();
        foreach($allPostPutVars as $key => $param)
            $rev[$key] = $param;
        $review = new Review(0, $rev["name_creator"], date("Y-m-d H:i:s"), $rev["content"]);
        $this->reviewStore->addReview($review);
        return $response;
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

    function showAjax(Request $request, Response $response, array $args): Response
    {
        //Печатаем html
        $response->getBody()->write('
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>Отзывы</title>
            <link rel="stylesheet" href="/view/css/style.css">
        </head>
        <body>
            <a href="#" class="button13" onclick="showReviews();">Обновить отзывы</a>
            <div id="result">
                <hr>
                        <ol id="" class="rounded">
                            <li><a id="name_creator" href="#">Имя автора</a></li>
                        </ol>
                <hr>
            </div>
        </body>
        <script src="/view/js/showReviews.js"></script>
        </html>');
        return $response;
    }

    function addAjax(Request $request, Response $response, array $args): Response
    {
        //Печатаем html
        $response->getBody()->write('
        <!DOCTYPE HTML>
        <html lang="ru">
        <head>
            <meta charset="utf-8">
            <title>Добавление отзыва</title>
            <link rel="stylesheet" href="/view/css/style.css">
        </head>
        <body>
            <form name="add" action="" class="ui-form">
                <h3>Добавить отзыв</h3>
                <div class="form-row">
                    <input type="text" id="name_creator"><label for="name_creator">Введите имя: </label>
                </div>
                <div class="form-row">
                    <textarea rows="5" cols="34" id="content"></textarea><label for="content">Введите отзыв: </label>
                </div>
                <p><input type="submit"  value="Добавить" onclick="addReview();"></p>
                <div id="result" class="res">
               
                </div>
            </form>
            
        </body>
        <script src="/view/js/addReview.js"></script>
        </html>');
        return $response;
    }
}
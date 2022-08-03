<?php

namespace Intervolga\Reviews\api;
use Intervolga\Reviews\Review;
use Intervolga\Reviews\store\StoreException;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Intervolga\reviews\store\Reviews;

class Controller
{
    public Reviews $reviewStore;

    function __construct(Reviews $reviewStore) {
        $this->reviewStore = $reviewStore;
    }

    function showOne(Request $request, Response $response, array $args): Response {
        try {
            $review = $this->reviewStore->findById($args['id']);
            if($review) {
                $responseContent = $this->getSuccessResponse($this->toArray($review));
            }
            else {
                $responseContent = $this->getSuccessResponse(null);
            }
        } catch (StoreException $e) {
            $responseContent = $this->getErrorResponse('Непредвиденная ошибка');
        } catch (\Exception $e) {
            $responseContent = $this->getErrorResponse('Не удалось получить ответ');
        }

        $response->getBody()->write($responseContent);
        return $response;
    }

    function show(Request $request, Response $response, array $args): Response {
        try {
            $reviews = $this->reviewStore->find(($args['page'] - 1) * 20);
            if($reviews) {
                $responseContent = $this->getSuccessResponse($this->toArrayMany($reviews));
            }
            else {
                $responseContent = $this->getSuccessResponse(null);
            }
        }
        catch (StoreException $e) {
            $responseContent = $this->getErrorResponse('Непредвиденная ошибка');
        } catch (\Exception $e) {
            $responseContent = $this->getErrorResponse('Не удалось получить ответ');
        }
        $response->getBody()->write( $responseContent);
        return $response;
    }

    function addReview(Request $request, Response $response, array $args): Response {
        $allPostPutVars = $request->getParsedBody();
        foreach($allPostPutVars as $key => $param)
            $rev[$key] = $param;
        $review = new Review(0, $rev["name_creator"], date("Y-m-d H:i:s"), $rev["content"]);
        $this->reviewStore->addReview($review);
        return $response;
    }

    function deleteReview(Request $request, Response $response, array $args): Response {
        try {
            $review = $this->reviewStore->deleteReview($args['id']);
            if($review) {
                $responseContent = $this->getSuccessResponse($this->toArray($review));
            }
            else {
                $responseContent = $this->getSuccessResponse(null);
            }
        }
        catch (StoreException $e) {
            $responseContent = $this->getErrorResponse('Непредвиденная ошибка');
        } catch (\Exception $e) {
            $responseContent = $this->getErrorResponse('Не удалось получить ответ');
        }
        $response->getBody()->write( $responseContent);
        return $response;
    }

    function toJson(Review $review): bool|string {
        $review = array( 'id' => $review->id,
                         'name_creator' => $review->name_creator,
                         'date_create' => $review->date_create,
                         'content' => $review->content
        );
        return json_encode($review);
    }

    function getSuccessResponse($result): string {
        try {
            return json_encode(array(
                'result' => $result,
                'status' => 'success'
            ), JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \Exception("could not get success response", 0, $e);
        }
    }

    function getErrorResponse(string $message): string {
        try {
            return json_encode(array(
                'message' => $message,
                'status' => 'error'
            ), JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \Exception("could not get error response", 0, $e);
        }
    }

    function showAjax(Request $request, Response $response, array $args): Response {
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

    function addAjax(Request $request, Response $response, array $args): Response {
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

    #[ArrayShape(['id' => "int", 'name_creator' => "string", 'date_create' => "string", 'content' => "string"])]
    function toArray(Review $review): array {
        return array( 'id' => $review->id,
            'name_creator' => $review->name_creator,
            'date_create' => $review->date_create,
            'content' => $review->content
        );
    }

    private function toArrayMany(array $reviews): array {
        $array = [];
        foreach ($reviews as $review)
            $array[] = array( 'id' => $review->id,
                'name_creator' => $review->name_creator,
                'date_create' => $review->date_create,
                'content' => $review->content
            );
        return $array;
    }
}
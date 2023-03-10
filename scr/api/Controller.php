<?php

namespace Intervolga\Reviews\api;


use AuthClass;
use Exception;
use Intervolga\Reviews\Review;
use Intervolga\reviews\store\Reviews;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SQLite3;


class Controller {
    public Reviews $reviewStore;

    function __construct(Reviews $reviewStore) {
        $this->reviewStore = $reviewStore;
    }

    //Метод для добавления информации на страницу.
    //Если база отдала верный ответ, то добавляем данные, иначе добавляем null.

    function out(Request $request, Response $response, array $args): Response {
        $auth = new AuthClass();

        if ($auth->isAuth()) {
            $auth->out();
            $response = $response->withStatus(302);
            return $response->withHeader('Location', '/api/');
        } else {
            $response->getBody()->write(StaticContent::getNeedAuthHtml());
        }

        return $response;
    }

    //Метод для добавления 20 отзывов на страницу. Аналогично методу выше
    function showOnePage(Request $request, Response $response, array $args): Response {
        try {
            $reviews = $this->reviewStore->find(($args['page'] - 1) * 20);
            if ($reviews) {
                $responseContent = $this->getSuccessResponse($this->toArrayMany($reviews));
            } else {
                $responseContent = $this->getSuccessResponse(null);
            }
        } catch (Exception $e) {
            $responseContent = $this->getErrorResponse('Не удалось получить ответ');
        }
        $response->getBody()->write($responseContent);
        return $response;
    }

    //Метод для подготовки данных для бд перед добавлением
    function addReview(Request $request, Response $response, array $args): Response {
        $allPostPutVars = $request->getParsedBody();
        foreach ($allPostPutVars as $key => $param) {
            $rev[$key] = $param;
        }
        $review = new Review(0, $rev["name_creator"], date("Y-m-d H:i:s"), $rev["content"]);
        $this->reviewStore->addReview($review);
        return $response;
    }

    function updateReview(Request $request, Response $response, array $args): Response {
        $auth = new AuthClass();
        if ($auth->isAuth()) {
            $idReview = $args["id"];
            $allPostPutVars = $request->getParsedBody();
            foreach ($allPostPutVars as $key => $param) {
                $rev[$key] = $param;
            }
            $review = new Review((int) $idReview, $rev["name_creator"], date("Y-m-d H:i:s"), $rev["content"]);
            $result = $this->reviewStore->updateReview($review);
            if (!$result) {
                $responseContent = $this->getErrorResponse('Не удалось обновить отзыв.');
            }
            $response->getBody()->write($responseContent);
        } else {
            $response->getBody()->write(StaticContent::getNeedAuthHtml());
        }
        return $response;
    }

    //Метод для удаления отзыва. Аналогично самому верхнему.
    function deleteReview(Request $request, Response $response, array $args): Response {
        $auth = new AuthClass();

        if ($auth->isAuth()) {
            try {
                $review = $this->reviewStore->deleteReview($args['id']);
                if ($review) {
                    $response->getBody()->write('Отзыв удален.');
                } else {
                    $response->getBody()->write('Отзыва с таким id нет!');
                }
            } catch (Exception $e) {
                $response->getBody()->write('Не удалось получить ответ. ' . $e);
            }
        } else {
            $response->getBody()->write(StaticContent::getNeedAuthHtml());
        }
        return $response;
    }

    function toJson(Review $review): bool|string {
        $review = array(
                'id'           => $review->id,
                'name_creator' => $review->name_creator,
                'date_create'  => $review->date_create,
                'content'      => $review->content,
        );
        return json_encode($review);
    }

    function getSuccessResponse($result): string {
        try {
            return json_encode(array(
                    'result' => $result,
                    'status' => 'success',
            ), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new Exception("Could not get success response", 0, $e);
        }
    }

    function getErrorResponse(string $message): string {
        try {
            return json_encode(array(
                    'message' => $message,
                    'status'  => 'error',
            ), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new Exception("Could not get error response", 0, $e);
        }
    }

    function showAjax(Request $request, Response $response, array $args): Response {
        //Печатаем html
        $auth = new AuthClass();
        if ($auth->isAuth()) {
            $response->getBody()->write(StaticContent::getListReviewsHtml());
        } else {
            $response->getBody()->write(StaticContent::getNeedAuthHtml());
        }
        return $response;
    }

    function addAjax(Request $request, Response $response, array $args): Response {
        //Печатаем html
        $response->getBody()->write(StaticContent::getAddHtml());
        return $response;
    }

    function updateAjax(Request $request, Response $response, array $args): Response {
        $auth = new AuthClass();
        if ($auth->isAuth()) {
            $idReview = $args["id"];
            $reviews = new Reviews(new SQLite3('/home/aleksandr/PhpstormProjects/Reviews/IV'));
            $review = $reviews->findById($idReview);
            //Печатаем html
            $request->getQueryParams();
            $response->getBody()->write(StaticContent::getUpdateHtml($review->id, $review->name_creator,
                    $review->content));
        } else {
            $response->getBody()->write(StaticContent::getNeedAuthHtml());
        }
        return $response;
    }

    function showAuth(Request $request, Response $response, array $args): Response {
        $auth = new AuthClass();
        $formValues = $request->getParsedBody();
        if (isset($formValues["login"]) && isset($formValues["password"])) { //Если логин и пароль были отправлены
            if (!$auth->auth($formValues["login"], $formValues["password"])) { //Если логин и пароль введен не правильно
                $response->getBody()->write(StaticContent::getWrongDataWithAuthFrom());
                return $response;
            }
        }

        if (!$auth->isAuth()) {
            $response->getBody()->write(StaticContent::getAuthFormHtml());
        } else {
            $response->getBody()->write(StaticContent::getLKHtml($auth->getLogin()));
        }
        return $response;
    }

    private function toArrayMany(array $reviews): array {
        $array = [];
        foreach ($reviews as $review) {
            $array[] = array(
                    'id'           => $review->id,
                    'name_creator' => $review->name_creator,
                    'date_create'  => $review->date_create,
                    'content'      => $review->content,
            );
        }
        return $array;
    }
}
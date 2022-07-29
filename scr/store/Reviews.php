<?php

namespace Intervolga\Reviews\store;
include_once dirname(__DIR__)."/vendor/autoload.php";

use Intervolga\reviews\Review;
use SQLite3;

class Reviews
{
    public SQLite3 $connection;
    function __construct(SQLite3 $connection){
        $this->connection = $connection;
    }

    function findById(int $id): ?Review{
        $result = $this->connection->query('SELECT * FROM reviews WHERE id=' . '$id');
        $reviewInfo = $result->fetchArray();
        return new Review(
            $reviewInfo["id"],
            $reviewInfo["name_creator"],
            $reviewInfo["date_create"],
            $reviewInfo["content"]
        );

    }

    /**
     * @return Review[]
     */
    function find(int $id): array
    {
        // Запрашиваем определенное количетсво записей
        $result = $this->connection->query("SELECT * FROM `reviews` LIMIT $id,20");
        $reviewsInfo = $result->fetchArray();
        $reviews = array();

        // Создаем массив Review
        foreach ($reviewsInfo as $reviewInfo) {
            $reviews[] = new Review(
                $reviewInfo["id"],
                $reviewInfo["name_creator"],
                $reviewInfo["date_create"],
                $reviewInfo["content"]
            );
        }

        return $reviews;
    }
}
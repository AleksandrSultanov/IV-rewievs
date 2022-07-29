<?php

namespace Intervolga\Reviews\store;

use Intervolga\Reviews\Review;
use SQLite3;

class Reviews
{
    public SQLite3 $connection;
    function __construct(SQLite3 $connection) {
        $this->connection = $connection;
    }

    function findById(int $id): ?Review {
        $result = $this->connection->query("SELECT * FROM reviews WHERE id="."$id");
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
    function find(int $page): array
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

    function addReview (Review $review): int{
        $result = $this->connection->query("INSERT INTO reviews VALUES ('$review->name_creator','$review->date_create','$review->content')");
        $id = (new \SQLite3)->lastInsertRowID();
        if ($result)
            return $id;
        else return false;
    }

    function deleteReview(int $id): Review|bool {
        $result = $this->connection->query("SELECT * FROM reviews WHERE id="."$id");
        $reviewInfo = $result->fetchArray();
        $result = $this->connection->query("DELETE FROM reviews WHERE id="."$id");
        if ($result)
            return new Review(
                $reviewInfo["id"],
                $reviewInfo["name_creator"],
                $reviewInfo["date_create"],
                $reviewInfo["content"]
            );
        else return false;
    }
}
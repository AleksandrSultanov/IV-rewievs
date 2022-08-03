<?php

namespace Intervolga\Reviews\store;
include_once dirname(__DIR__)."/vendor/autoload.php";

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

    function addReview (Review $review): void{
        $result = $this->connection->query("INSERT INTO reviews (name_creator, date_create, content) VALUES ('$review->name_creator','$review->date_create','$review->content')");
        $arr = $this->connection->query("SELECT last_insert_rowid();");
        $row = $arr->fetchArray(SQLITE3_ASSOC);
        var_dump($row);
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
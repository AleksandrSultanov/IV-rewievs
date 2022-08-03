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
        $statement = $this->connection->prepare("SELECT * FROM reviews WHERE id = :id;");
        $statement->bindValue(':id', $id);
        $result = $statement->execute();
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
        // Запрашиваем определенное количество записей
        $statement = $this->connection->prepare("SELECT * FROM reviews LIMIT :page,20");
        $statement->bindValue(':page', $page);
        $result = $statement->execute();
        $reviewsInfo = $result->fetchArray(SQLITE3_ASSOC);
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

    function addReview (Review $review): bool {
        $statement = $this->connection->prepare("INSERT INTO reviews VALUES (':name_creator',':date_create',':content')");
        $statement->bindValue(':name_creator', $review->name_creator);
        $statement->bindValue(':date_create', $review->date_create);
        $statement->bindValue(':content', $review->content);
        $result = $statement->execute();
        if ($result)
            return true;
        else return false;
    }

    function deleteReview(int $id): Review|bool {
        $statement = $this->connection->prepare("SELECT * FROM reviews WHERE id = :id;");
        $statement->bindValue(':id', $id);
        $result = $statement->execute();
        $reviewInfo = $result->fetchArray();
        $statement = $this->connection->prepare("DELETE FROM reviews WHERE id="."$id");
        $result = $statement->bindValue(':id', $id);
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
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

    /**
     * @throws StoreException
     */
    function findById(int $id): ?Review {
        $statement = $this->connection->prepare("SELECT * FROM reviews WHERE id = :id;");
        $statement->bindValue(':id', $id);
        $result = $statement->execute();
        $reviewInfo = $result->fetchArray();
        if($reviewInfo) {
            return new Review(
                $reviewInfo["id"],
                $reviewInfo["name_creator"],
                $reviewInfo["date_create"],
                $reviewInfo["content"]
            );
        }
        else return null;
    }

    /**
     * @param int $page
     * @return array|null
     */
    function find(int $page): ?array {
        // Запрашиваем определенное количество записей
        $statement = $this->connection->prepare("SELECT * FROM reviews LIMIT :page,20");
        $statement->bindValue(':page', $page);
        $result = $statement->execute();
        if($result) {
            $reviews = array();

            // Создаем массив Review
            while ($reviewInfo = $result->fetchArray(SQLITE3_ASSOC)) {
                $reviews[] = new Review(
                    $reviewInfo["id"],
                    $reviewInfo["name_creator"],
                    $reviewInfo["date_create"],
                    $reviewInfo["content"]
                );
            }
            return $reviews;
        }
        else return null;
    }

    function addReview (Review $review): bool {
        $statement = $this->connection->prepare("INSERT INTO reviews (name_creator, date_create, content) VALUES (:name_creator, :date_create, :content);");
        $statement->bindValue(':name_creator', $review->name_creator);
        $statement->bindValue(':date_create', $review->date_create);
        $statement->bindValue(':content', $review->content);
        $result = $statement->execute();
        $reviewInfo = $result->fetchArray();
        if($reviewInfo)
            return true;
        else return false;
    }

    function deleteReview(int $id): Review|bool {
        $statement = $this->connection->prepare("SELECT * FROM reviews WHERE id = :id;");
        $statement->bindValue(':id', $id);
        $result = $statement->execute();
        $reviewInfo = $result->fetchArray();
        $statement = $this->connection->prepare("DELETE FROM reviews WHERE id="."$id");
        $statement->bindValue(':id', $id);
        $result = $statement->execute();
        if ($reviewInfo)
            return new Review(
                $reviewInfo["id"],
                $reviewInfo["name_creator"],
                $reviewInfo["date_create"],
                $reviewInfo["content"]
            );
        else return false;
    }
}
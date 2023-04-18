<?php


namespace Intervolga\Reviews\store;


use Intervolga\Reviews\Review;
use SQLite3;

class Reviews {
    public SQLite3 $connection;

    function __construct(SQLite3 $connection) {
        $this->connection = $connection;
    }

    //Метод для поиска отзыва по id. Подготавливаем, выполняем запрос.
    // Данные переводим в массив, а после в объект Review
    function findById(int $id): ?Review {
        $statement = $this->connection->prepare("SELECT * FROM reviews WHERE id = :id;");
        $statement->bindValue(':id', $id);
        $result = $statement->execute();
        $reviewInfo = $result->fetchArray();

        if ($reviewInfo) {
            return new Review(
                    $reviewInfo["id"],
                    $reviewInfo["name_creator"],
                    $reviewInfo["date_create"],
                    $reviewInfo["date_change"] ?? '',
                    $reviewInfo["content"],
                    $reviewInfo["rating"]
            );
        }
        return null;
    }

    //Метод для выборки 20 отзывов по страницам. Подготавливаем, выполняем запрос.
    // Данные переводим в массив, а после в массив объектов Review
    /**
     * @param int $page
     * @return array|null
     */
    function find(int $page): ?array {
        // Запрашиваем определенное количество записей
        $statement = $this->connection->prepare("SELECT * FROM reviews LIMIT :page,30");
        $statement->bindValue(':page', $page);
        $result = $statement->execute();

        if ($result) {
            $reviews = array();
            // Создаем массив Review
            while ($reviewInfo = $result->fetchArray(SQLITE3_ASSOC)) {
                $reviews[] = new Review(
                        $reviewInfo["id"],
                        $reviewInfo["name_creator"],
                        $reviewInfo["date_create"],
                        $reviewInfo["date_change"] ?? '',
                        $reviewInfo["content"],
                        $reviewInfo["rating"]
                );
            }
            return $reviews;
        }
        return null;
    }

    //Метод для добавления нового отзыва
    function addReview(Review $review): bool {
        $statement = $this->connection->prepare(
                "INSERT INTO reviews (name_creator, date_create, content, rating) 
                        VALUES (:name_creator, :date_create, :content, :rating);"
        );

        $statement->bindValue(':name_creator', $review->name_creator);
        $statement->bindValue(':date_create', $review->date_create);
        $statement->bindValue(':content', $review->content);
        $statement->bindValue(':rating', $review->rating);

        $result = $statement->execute();

        if ($result) {
            return true;
        }
        return false;
    }

    //Метод для удаления отзыва по id.
    //Сначала вытаскиваем этот отзыв, проверяем на наличие и возвращаем удаленный отзыв.
    function deleteReview(int $id): Review|bool {
        $statement = $this->connection->prepare("SELECT * FROM reviews WHERE id = :id;");
        $statement->bindValue(':id', $id);
        $result = $statement->execute();
        $reviewInfo = $result->fetchArray();

        if ($reviewInfo) {
            $statement = $this->connection->prepare("DELETE FROM reviews WHERE id = :id;");
            $statement->bindValue(':id', $id);
            $result = $statement->execute();

            if ($result) {
                return new Review(
                        $reviewInfo["id"],
                        $reviewInfo["name_creator"],
                        $reviewInfo["date_create"],
                        $reviewInfo["date_change"] ?? '',
                        $reviewInfo["content"],
                        $reviewInfo["rating"]
                );
            }
        }
        return false;
    }

    function updateReview(Review $review): bool {
        $statement = $this->connection->prepare(
                "UPDATE reviews 
                        SET name_creator=:name_creator, date_change=:date_change, content=:content, rating=:rating 
                        WHERE id=:id;"
        );

        $statement->bindValue(':name_creator', $review->name_creator);
        $statement->bindValue(':date_change', $review->date_change);
        $statement->bindValue(':content', $review->content);
        $statement->bindValue(':id', $review->id);
        $statement->bindValue(':rating', $review->rating);

        $result = $statement->execute();
        if ($result)
            return true;
        return false;
    }
}
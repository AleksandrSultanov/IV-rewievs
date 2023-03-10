<?php


namespace Intervolga\Reviews\api;


class StaticContent {
    public static function getNeedAuthHtml(): string {
        return ' <!DOCTYPE html>
                <html lang="ru">
                <head>
                    <meta charset="UTF-8">
                    <title>Отзывы</title>
                    <link rel="stylesheet" href="/view/css/style.css">
                </head>
                
                <body>
                    <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a><hr>
                    <div>
                        Для просмотра этой этой страницы вам нужно 
                        <a href="/api/">авторизоваться</a> 
                    </div>
                </body>
                </html>';
    }

    public static function getListReviewsHtml(): string {
        return '
                <!DOCTYPE html>
                <html lang="ru">
                <head>
                    <meta charset="UTF-8">
                    <title>Отзывы</title>
                    <link rel="stylesheet" href="/view/css/style.css">
                </head>
                
                <body>
                    <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a>
                    <a href="/api/" class="button15" >Личный кабинет</a><hr>
                    <div id="result">
                        <hr>
                                <ol id="" class="rounded">
                                    <li><a id="name_creator" href="#">Имя автора</a></li>
                                </ol>
                        <hr>
                    </div>
                </body>
                <script src="/view/js/showReviews.js"></script>
                <script src="/view/js/deleteReview.js"></script>
                </html>';
    }

    public static function getAddHtml(): string {
        return '
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
                        <p><input type="button"  value="Добавить" onkeypress="return event.keyCode != 13;" onclick="addReview();"></p>
                        <div id="result" class="res">
                       
                        </div>
                    </form>
                    
                </body>
                <script src="/view/js/addReview.js"></script>
                </html>';
    }

    public static function getUpdateHtml($id, $name_creator, $content): string {
        return '
                <!DOCTYPE HTML>
        <html lang="ru">
        <head>
            <meta charset="utf-8">
            <title>Добавление отзыва</title>
            <link rel="stylesheet" href="/view/css/style.css">
        </head>
        <body>
            <form name="update" action="" class="ui-form">
                <h3>Обновление отзыва</h3>
                <div class="form-row">
                    <input type="text" id="name_creator" value="' . $name_creator . '"><label for="name_creator">Имя: </label>
                </div>
                <div class="form-row">
                    <textarea rows="5" cols="34" id="content">' . $content . '</textarea><label for="content">Отзыв: </label>
                </div>
                <p><input type="button"  value="Обновить" onkeypress="return event.keyCode != 13;" onclick="updateReview('
                . $id . ');"></p>
                <div id="result" class="res">
               
                </div>
            </form>
            
        </body>
        <script src="/view/js/updateReview.js"></script>
        </html>';
    }

    public static function getAuthFormHtml(): string {
        return '
                <!DOCTYPE HTML>
                <html lang="ru">
                <head>
                    <meta charset="utf-8">
                    <title>Авторизация</title>
                    <link rel="stylesheet" href="/view/css/style.css">
                </head>
                <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a>
                <hr>
                <form method="post" class="ui-form" action="">
                    <div class="form-row">
                    Логин:
                        <input
                                type="text" name="login"
                                value=""
                        />
                        <br/>
                    </div>
                    
                    <div class="form-row">
                    Пароль:
        
                        <input
                                type="password"
                                name="password"
                                value=""
                                        />
                        <br/>
                        <input class="form-row" type="submit" value="Войти"/>
                    </div>
                </form>
            ';
    }

    public static function getLKHtml($login): string {
        return '
                <!DOCTYPE HTML>
                <html lang="ru">
                <head>
                    <meta charset="utf-8">
                    <title>Личный кабинет</title>
                    <link rel="stylesheet" href="/view/css/style.css">
                </head>
                <div class="ui-form">
                    Здравствуйте, ' . $login . '
                    <br/><br/><a href="/api/feedbacks/">Отзывы</a>
                    <br/><br/><a  href="/api/out/">Выйти</a>
                </div>
            ';
    }

    public static function getWrongDataWithAuthFrom(): string {
        return '
                <!DOCTYPE HTML>
                <html lang="ru">
                <head>
                    <meta charset="utf-8">
                    <title>Авторизация</title>
                    <link rel="stylesheet" href="/view/css/style.css">
                </head>
                <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a>
                <hr>
                <form method="post" class="ui-form" action="">
                    <div class="form-row">
                    <h5 style="color:red;">Логин или пароль введен не правильно!</h5>
                    Логин:
                        <input
                                type="text" name="login"
                                value=""
                        />
                        <br/>
                    </div>
                    
                    <div class="form-row">
                    Пароль:
        
                        <input
                                type="password"
                                name="password"
                                value=""
                                        />
                        <br/>
                        <input class="form-row" type="submit" value="Войти"/>
                    </div>
                </form>
                
        ';

    }
}
<?php


namespace Intervolga\Reviews\api;


class StaticContent {
    public static function getNeedAuthHtml(): string {
        return self::getHeader('Отзывы') . '
                <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a><hr>
                   <div>
                       Для просмотра этой этой страницы вам нужно 
                       <a href="/api/">авторизоваться</a> 
                   </div>' .
                self::getFooter();
    }

    public static function getListReviewsHtml(): string {
        return self::getHeader('Отзывы') . '
                <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a>
                <a href="/api/" class="button15" >Личный кабинет</a><hr>
                <div id="result">
                    <hr>
                            <ol id="" class="rounded">
                                <li><a id="name_creator" href="#">Имя автора</a></li>
                            </ol>
                    <hr>
                </div>' .
                self::getFooter(array('/view/js/showReviews.js', '/view/js/deleteReview.js'));
    }

    public static function getAddHtml(): string {
        return self::getHeader('Добавление отзыва') . '
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
                </form>' .
                self::getFooter(array('/view/js/addReview.js'));
    }

    public static function getUpdateHtml($id, $name_creator, $content): string {
        return self::getHeader('Обновление отзыва') . '
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
                </form>' .
                self::getFooter(array('/view/js/updateReview.js'));
    }

    public static function getAuthFormHtml(): string {
        return self::getHeader('Авторизация') . '
                <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a>
                <hr>
                <form method="post" class="ui-form" action="">
                   <div class="form-row">
                       Логин:
                       <input type="text" name="login" value="" />
                       <br/>
                   </div>
                   <div class="form-row">
                       Пароль:
                       <input type="password" name="password" value="" />
                       <br/>
                       <input class="form-row" type="submit" value="Войти"/>
                   </div>
                </form>' .
                self::getFooter();
    }

    public static function getLKHtml($login): string {
        return self::getHeader('Личный кабинет') . '
                <div class="ui-form">
                   Здравствуйте, ' . $login . '
                   <br/><br/><a href="/api/feedbacks/">Отзывы</a>
                   <br/><br/><a  href="/api/out/">Выйти</a>
                </div>' .
                self::getFooter();
    }

    public static function getWrongDataWithAuthFrom(): string {
        return self::getHeader('Авторизация') . '
                <a href="/api/feedbacks/add/" class="button13" id="update">Добавить отзыв</a>
                <hr>
                <form method="post" class="ui-form" action="">
                    <div class="form-row">
                    <h5 style="color:red;">Логин или пароль введен не правильно!</h5>
                       Логин:
                       <input type="text" name="login" value="" />
                       <br/>
                   </div>
                   <div class="form-row">
                       Пароль:
                       <input type="password" name="password" value="" />
                       <br/>
                       <input class="form-row" type="submit" value="Войти"/>
                   </div>
                </form>' .
                self::getFooter();
    }

    public static function getHeader($title): string {
        return '<!DOCTYPE HTML>
                <html lang="ru">
                <head>
                    <meta charset="utf-8">
                    <title>' . $title . '</title>
                    <link rel="stylesheet" href="/view/css/style.css">
                    <link rel="icon" href="/view/favicon.ico">
                </head>
                <body>';
    }

    public static function getFooter($pathsToScript = array()): string {
        $script = '';
        foreach ($pathsToScript as $pathToScript) {
            $script .= '<script src="' . $pathToScript . '"></script>';
        }
        return '</body>'
                . $script . '
                </html>';
    }
}
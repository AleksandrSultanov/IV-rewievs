<?php
session_start();

/**
 * Класс для авторизации
 */
class AuthClass {
    private array $date = array(
            "demo"  => "www.ox2.ru",
            "admin" => "root",
    );

    /**
     * Проверяет, авторизован пользователь или нет.
     * Возвращает true если авторизован, иначе false
     *
     * @return boolean
     */
    public function isAuth(): bool {
        return $_SESSION["is_auth"] ?? false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }

    /**
     * Авторизация пользователя
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function auth(string $login, string $password): bool {
        $auth = false;
        foreach ($this->date as $_login => $_password) {
            if ($login == $_login && $password == $_password) { //Если логин и пароль введены правильно
                $_SESSION["is_auth"] = true;                    //Делаем пользователя авторизованным
                $_SESSION["login"] = $login;                    //Записываем в сессию логин пользователя
                $auth = true;
            } else { //Логин и пароль не подошел
                $_SESSION["is_auth"] = false;
                $auth = false;
            }
        }
        return $auth;
    }

    /**
     * Метод возвращает логин авторизованного пользователя
     */
    public function getLogin() {
        if ($this->isAuth()) {         //Если пользователь авторизован
            return $_SESSION["login"]; //Возвращаем логин, который записан в сессию
        }
        return '';
    }

    public function out(): void {
        $_SESSION = array(); //Очищаем сессию
        session_destroy();   //Уничтожаем
    }
}

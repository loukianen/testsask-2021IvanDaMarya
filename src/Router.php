<?php
namespace App;

use Controllers\DaysController;
use Controllers\MainController;
use Controllers\StatisticsController;
use Controllers\NotFoundController;

class Router
{
    public static function getController()
    {
        $controllers = [
          'days' => new DaysController(),
          'main' => new MainController(),
          'statistics' => new StatisticsController()
        ];
        // Если не пришла переменная PATH_INFO, установка по умолчанию $page как main.
        $page = 'main';
        // Если в гет-массиве есть переменная page.
        if (isset($_SERVER['PATH_INFO'])) {
            // Установка в $page имени страницы.
            $page = str_replace('/', '', $_SERVER['PATH_INFO']);
        }
        // Проверка на несуществующую страницу
        if (!isset($controllers[$page])) {
            // Если контроллер не существует, возвращаем NotfoundController.
            return new NotFoundController();
        }
        // Подключение файла контроллера.
        return $controllers[$page];
    }
}
?>
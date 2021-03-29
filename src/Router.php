<?php
namespace App;
 
// Использование пространства имен контроллеров.
use Controllers\DayController;
use Controllers\MainController;
use Controllers\StatisticsController;
 
class Router
{
    // Статичный метод поиска контроллера
    public static function getController()
    {
        $controllers = [
          'days' => new DayController(),
          'main' => new MainController(),
          'statistics' => new StatisticsController()
        ];
        // Если в не пришла переменная PATH_INFO, установка по умолчанию ее как main (главная страница).
        $page = 'main';
        // Если в гет-массиве есть переменная page.
        if (isset($_SERVER['PATH_INFO'])) {
            // Установка в $page имени страницы.
            $page = str_replace('/', '', $_SERVER['PATH_INFO']);
        }
        /* надо добавить реакцию на несуществующую страницу
        if (!file_exists('controllers/' . $controllerClass . '.php')) {
            // Если контроллер не существует, устанавливаем имя контроллера NotfoundController – контроллер страницы 404 – не найдено.
            $controllerClass = 'NotfoundController';
        }*/
        // Подключение файла контроллера.
		return $controllers[$page];
    }
}
?>
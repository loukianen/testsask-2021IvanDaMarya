<?php
namespace src;
 
// Использование пространства имен контроллеров.
use controllers;
 
class Router
{
    
	// Статичный метод поиска контроллера
    public static function getController($params = [])
    {
        // Если в гет-массив не пришла переменная page, установка по умолчанию ее как main (главная страница).
        $page = 'main';
        // Если в гет-массиве есть переменная page.
        if (isset($params['page'])) {
            // Установка в $page имени страницы.
            $page = $params['page'];
        }
        print_r($page);
        print_r('Hello');
        // С помощью функции mb_convert_case изменяется имя переменной в формат первая буква – заглавная, остальные строчные.
        // Добавляется в конец переменной слово Controller. Таким образом имя страницы преобразуется в имя контроллера.
        $controllerClass = mb_convert_case($page, MB_CASE_TITLE, "UTF-8") . 'Controller';
		// Проверка на существование файла контроллера с помощью функции file_exists.
		//print_r($controllerClass);
        if (!file_exists('controllers/' . $controllerClass . '.php')) {
            // Если контроллер не существует, устанавливаем имя контроллера NotfoundController – контроллер страницы 404 – не найдено.
            $controllerClass = 'NotfoundController';
        }
        // Подключение файла контроллера.
        require_once 'controllers/' . $controllerClass . '.php';
		// Формирование имени класса контроллера с учетом пространства имен.
        $controllerClass = 'controllers\\' . $controllerClass;
		// Создание экземпляра контроллера и возвращение его наружу.
		return new $controllerClass();
    }
}
?>
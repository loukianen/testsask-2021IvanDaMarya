<?php
namespace App;

class App
{
    // Статичное свойство, содержащее конфигурацию.
    public static $config;
	// Статичное свойство, содержащее подключение к базе данных.
    public static $db;
	// Статичное свойство, содержащее роутер.
    public static $router;

    public static function init()
    {

        // Инициализация конфигурации.
        self::$config = new Config();
        // Инициализация базы данных.
        self::$db = new Db();
        self::$db->connect();
        // Инициализация роутера.
        self::$router = new Router();
    }

    public static function run()
    {
        $controller = self::$router->getController();
        // Запускаем работу контроллера.
        $controller->run($_GET);
    }
}
?>
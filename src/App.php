<?php
namespace src;

// Создание класса App.
class App
{
    // Статичное свойство, содержащее конфигурацию.
    public static $config;
	// Статичное свойство, содержащее подключение к базе данных.
    public static $db;
	//  public static $fp;
	// Статичное свойство, содержащее роутер.
    public static $router;
	// Статичное свойство, содержащее пользователя.
  // public static $user;
 
 
    //Статичный метод. 
    public static function init()
    {
        // Инициализация сессии пользователя.
        // session_start();
		
        // Инициализация конфигурации.
        self::$config = new Config();
			
		// Инициализация базы данных.
    //    self::$db = new Db();
    //    self::$db->connect(self::$config->get('database'));
		
		// Инициализация роутера.
        self::$router = new Router();
		
		// Инициализация пользователя.
    //    self::$user =  new User();
    }
     
    // Запуск приложения.
    /*public static function run()
    {
        		
        $controller = new BaseController();
            // Запускаем работу контроллера.
            $controller->run(['vars'=>json_encode($_GET)]);
		
		self::$router->getController($_GET);
        // Получение контроллера в зависимости от гет-переменной.
        $controller = self::$router->getController($_GET);
        // Запускаем работу контроллера.
        $controller->run($_GET);
	}*/
		
		//вариант запуска приложения из 4.4.Авторизация
		// Запуск приложения.
    public static function run()
    {
        //print_r($_GET);
		self::$router->getController($_GET);
        // Получение контроллера в зависимости от гет-переменной.
        $controller = self::$router->getController($_GET);
        // Запускаем работу контроллера.
        $controller->run(['vars'=>json_encode($_GET)]);
    }
}
?>
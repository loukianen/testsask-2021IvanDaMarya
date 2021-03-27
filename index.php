<?php
// Отображение ошибок.
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
 
// Подключение автоконфига.
$autoloadPath1 = __DIR__ . '/../../../autoload.php';
// Путь для локальной работы с проектом
$autoloadPath2 = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}
 
// Подключение класса App.
use src\App;

 
// Инициализация App.
//App::init();
$res = new App();
$res->init(); 
// Запуск приложения.
//App::run();
$res = new App();
$res->run(); 

?>
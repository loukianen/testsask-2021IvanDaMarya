<?php
namespace controllers;
 
// Указываем, что используем базовый класс контроллера.
use classes\BaseController;
use classes\App;
 
// Объявляем класс контроллера как дочерний от базового контроллера.
class MainController
{
    // Переопределенный метод run для отрисовки страницы
    public function run($data)
    {
      $this->render();
    }

    public function render()
    {
      $result = file_get_contents(App::$config->get('basedir').'/views/' . main . '.tpl');
      return $result;
	}
}
?>

<?php
namespace Controllers;
 
// Указываем, что используем базовый класс контроллера.
// use classes\BaseController;
use App\App;
use App\Tpl;
 
// Объявляем класс контроллера как дочерний от базового контроллера.
class DayController
{
  private $tpl;
    // Переопределенный метод run для отрисовки страницы
    public function run()
    {
      $this->tpl = new Tpl();
      return $this->render();
    }

    private function getUsersData()
    {
      $query = 'SELECT `id`, `name` FROM `users` WHERE `type` = 5 ORDER BY `name`';
      return App\App::$db->query($query);
    }

    private function render()
    {
      // $usersData = $this->getUsersData();
      $this->tpl->makeDayWorks('');
      $this->tpl->render('day');
	}
}
?>

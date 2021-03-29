<?php
namespace Controllers;

use App\Tpl;
use App\App;

class MainController
{
    // Переменная для отрисовщика шаблонов
    private $tpl;

    public function run()
    {
      $this->tpl = new Tpl();
      return $this->render();
    }
    // Выбираем пользователей из базы по типу
    private function getUsersData()
    {
      $query = 'SELECT `id`, `name` FROM `users` WHERE `type` = 5 ORDER BY `name`';
      return App::$db->query($query);
    }

    private function render()
    {
      $usersData = $this->getUsersData();
      $this->tpl->makeUsersList($usersData);
      $this->tpl->render('main');
	}
}
?>

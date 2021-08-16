<?php
namespace Controllers;

use App\Tpl;
use Models\UserModel;

class MainController
{
    // Переменные для отрисовщика шаблонов и модели
    private $tpl;
    private $model;

    public function run()
    {
      $this->tpl = new Tpl();
      $this->model = new UserModel;
      return $this->render();
    }

    private function render()
    {
      $usersData = $this->model->getUsersData();
      $this->tpl->makeUsersList($usersData);
      $this->tpl->render('main');
	}
}
?>

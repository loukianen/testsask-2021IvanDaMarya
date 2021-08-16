<?php
namespace Models;

use App\App;

class UserModel
{
    // Выбираем пользователей из базы по типу
    public function getUsersData()
    {
      $query = 'SELECT `id`, `name` FROM `users` WHERE `type` = 5 ORDER BY `name`';
      return App::$db->query($query);
    }
}
?>
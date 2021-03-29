<?php
namespace App;
 
class Db
{
    // Свойство для хранения подключения к базе данных.
    private $linkDb;

    // Метод подключения к базе данных.
    public function connect()
    {
      if ($this->linkDb == null) {
        $this->linkDb = new \SQLite3('db/phpsqlite.db');
        $this->prepare();
      }
      return $this->linkDb;
    }
    // Создаем базу из исходников, если ее нет
    public function prepare()
    {
      $tables = ['builds', 'prices', 'rooms', 'statistics', 'users', 'works'];
      array_walk($tables, function($table) {
        $requestC = file_get_contents('query/create_' . $table);
        $requestI = file_get_contents('query/insert_' . $table);
        $this->linkDb->query($requestC);
        $this->linkDb->query($requestI);
      });
    }
    // Метод, выполняющий запрос к базе
    public function query($query)
    {
      $response = $this->linkDb->query($query);
      $data = [];
      while($result = $response->fetchArray(1)) {
        $data[] = $result;
      }
      return $data;
    }
}
?>
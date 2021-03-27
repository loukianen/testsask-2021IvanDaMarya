<?php
namespace src;
 
class Db
{
    // Свойство для хранения подключения к базе данных.
    protected $linkDb;
     
    // Метод подключения к базе данных.
    public function connect($config)
    {
        // Подключение к базе данных.
        /*$this->linkDb = new \mysqli($conf_db['host'], $conf_db['username'], $conf_db['password'],
            $conf_db['name']);*/
		$this->linkDb = new \mysqli($config['host'], $config['username'], $config['password'],
            $config['name']);
        // Установка кодировки utf-8.
        $this->linkDb->query("SET NAMES utf8");
    }
	
	// Метод проверяет тип переменной и обрабатывает ее соответствующим образом.
    protected function envelope($entry = false)
    {
        // Проверка переменной на число.
        if (is_numeric($entry)) {
            // Переменная возвращается без изменений.
            return $entry;
        } elseif (is_array($entry)) {
            // Если переменная – массив, обрабатывается каждая переменная массива этим же методом.
            return array_map(array('classes\Db', 'envelope'), $entry);
        }
        // Если переменная – строка, добавляются кавычки.
        return '\'' . addslashes($entry) . '\'';
    }
	
	// Метод производит запрос к базе данных.
    public function query($query, $value = [])
    {
        // Переменная для хранения результата запроса.
        $data = [];
        // Разделение строки на подстроки по знаку вопроса.
        $pieces = explode('?', $query);
        // Получение количества подстрок.
        $entry = sizeof($pieces);
        if ($entry > 0 && sizeof($value) > 0) {
            // Обнуление строки запроса.
            $query = '';
            // Обработка переменных методом envelope.
            $value = array_map(array("classes\Db", "envelope"), $value);
            $i = 0;
            // Перебор всех подстрок запроса
            foreach ($pieces as $piece) {
                // Составление конечного запроса из подстроки и значения переменной.
                $query .= $piece;
                if (array_key_exists($i, $value)) {
                    $query .= $value[$i];
                }
                $i++;
            }
        }
		//print_r($query);
        // Запрос в базу данных, результат запроса попадает в переменную $result;
        if ($result = $this->linkDb->query($query)) {
            // Если вернулся не объект, запрос был на внесение записи.
            if (!is_object($result)) {
                // Возвращает id внесенной записи.
                return $this->linkDb->insert_id;
            }
 
            // Чтение результата построчно из $result.
            while ($row = $result->fetch_assoc()) {
                // Сохранение в data.
                $data[] = $row;
            }
        }
        return $data;
    }
}
?>
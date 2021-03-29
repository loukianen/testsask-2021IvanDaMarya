<?php
// Пространство имен
namespace App;

class Config
{
    // Свойство для хранения данных из файла конфигурации.
    protected $data = [];
 
    // Метод конструктор, который инициализирует загрузку данных.
    function __construct()
    {
        $this->load();
    }
 
    public function get($key)
    {
        // print_r($this->data);
        // Проверка на наличие в массиве данных с заданным ключом.
        if (array_key_exists($key, $this->data)) {
            // Если ключ существует, возвращаем данные конфига.
            return $this->data[$key];
        }
        // Если ключ не существует, возвращаем пустой ответ.
        return null;
    }
 
    // Метод загрузки данных из файла конфигурации.
    public function load()
    {
        $this->data = [];
        // Подключение файла конфигураций.
        $this->data = parse_ini_file('config/config.ini', true);
        // Добавление в конфиг базовой папки приложения.
        $this->data['basedir'] = __dir__.'/..';
    }
}
?>
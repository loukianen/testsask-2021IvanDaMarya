<?php
// Пространство имен
namespace App;
 
class Tpl
{
    // Список переменных для шаблона.
    protected $varList = [];
 
    // Добавление новой переменной для шаблона main.
    public function makeUsersList($data)
    {
	    // Добавление в массив varList новой переменной.
        $selectItems = array_map(function($arr)
        {
          ['id' => $userId, 'name' => $userName] = $arr;
          return '<option value="' . $userId . '">' . $userName . '</option>';
        }, $data); 
        $this->varList['main'] = implode($selectItems);
    }
 
    // Добавление новой переменной для шаблона day.
    public function makeDayWorks($data)
    {
      $this->varList['day'] = $data;
    }
 
    // Отрисовка шаблона.
    public function render($tplName)
    {
        // Загрузка шаблона из файла, в качестве части пути используется получение базовой директории сайта.
        $result = file_get_contents(App::$config->get('basedir').'/views/' . $tplName . '.tpl');
        // Заменяем закладку на подготовленные данные
        $result = str_replace('{#dataId:' . $tplName . '}', $this->varList[$tplName], $result);
        echo $result;
    }
}
?>
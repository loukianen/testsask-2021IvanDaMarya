<?php
namespace App;
 
class Tpl
{
    // Список переменных для шаблона.
    protected $varList = [];
 
    // Добавление новой переменной для шаблона main.
    public function makeUsersList($data)
    {
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
      ['works' => $works, 'userName' => $userName, 'date' => $date] = $data;
      $this->varList['day'] = $works;
      // Заголовок таблицы
      $htmlParts[] = '<h3 class="align-self-center">Перечень работ, выполненных ' . $userName . ' за ' . $date . '</h3>';
      $htmlParts[] = '<table class="table table-striped text-center"><thead><tr><th scope="col">#</th><th scope="col">Номер</th><th scope="col">Категория номера</th><th scope="col">Тип уборки</th><th scope="col">Начало уборки</th><th scope="col">Конец уборки</th><th scope="col">Сумма за уборку</th></tr></thead><tbody>';
      // Строки таблицы
      $rowNumbers = array_keys($works);
      $tableRows = array_map(function($number)
      {
        $firstRow[] = '<tr>';
        $rowNumber = $number + 1;
        $firstRow[] = '<th scope="row">' . $rowNumber . '</th>';
        $rows = array_reduce($this->varList['day'][$number], function($acc, $item)
          {
            $acc[] = '<th>' . $item . '</th>';
            return $acc;
          }, $firstRow);
        $rows[] = '</tr>';
        return implode($rows);
      }, $rowNumbers);
      array_push($htmlParts, ...$tableRows);
      // Завершаем таблицу
      $htmlParts[] = '</tbody></table><hr>';
      // Сумма итого за день
      $salary = array_reduce($works, function($acc, $work)
      {
        $acc += $work['sum'];
        return $acc;
      });
      $htmlParts[] = '<div class="d-flex flex-row justify-content-between px-5 font-weight-bold"><div>Итоговая сумма за день</div><div>' . $salary . ' рублей</div></div>';
      
      $this->varList['day'] = implode($htmlParts);
    }
 
    // Отрисовка шаблона.
    public function render($tplName)
    {
        // Загрузка шаблона из файла, в качестве части пути используется получение базовой директории сайта.
        $result = file_get_contents(App::$config->get('basedir').'/views/' . $tplName . '.tpl');
        // Заменяем закладку на подготовленные данные, если такие есть
        if (isset($this->varList[$tplName])) {
          $result = str_replace('{#dataId:' . $tplName . '}', $this->varList[$tplName], $result);
        }
        echo $result;
    }
}
?>

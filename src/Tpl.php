<?php
namespace App;
 
class Tpl
{
    // Список переменных для шаблона.
    protected $varList = [];

    private function generateTableRows($data)
    {
      $rowNumbers = array_keys($data);
      $rows = array_map(function($number)
      {
        $firstCol[] = '<tr>';
        $rowNumber = $number + 1;
        $firstCol[] = '<th scope="row">' . $rowNumber . '</th>';
        $restCols = array_reduce($this->varList['dayData'][$number], function($acc, $item)
          {
            $acc[] = '<th>' . $item . '</th>';
            return $acc;
          }, $firstCol);
        $restCols[] = '</tr>';
        return implode($restCols);
      }, $rowNumbers);
      return implode($rows);
    }

    private function getElement($layoutName, $data)
    {
      $layout = file_get_contents(App::$config->get('basedir').'/views/' . $layoutName . '.tpl');
      return $this->pasteDataByMark($layout, $data);
    }

    private function pasteDataByMark($source, $data)
    {
      $result = $source;
      foreach($data as $mark => $value) {
        $result = str_replace('{#dataId:' . $mark . '}', $value, $result);
      }
      return $result;
    }

    // Добавление новой переменной для шаблона main.
    public function makeUsersList($data)
    {
        $selectItems = array_map(function($userData)
        {
          ['id' => $userId, 'name' => $userName] = $userData;
          return '<option value="' . $userId . '">' . $userName . '</option>';
        }, $data); 
        $this->varList['main'] = implode($selectItems);
    }

    // Добавление новой переменной для шаблона day.
    public function makeDayWorks($data)
    {
      ['works' => $works, 'userName' => $userName, 'date' => $date] = $data;
      $this->varList['dayData'] = $works;
      $salary = array_reduce($works, function($acc, $work)
      {
        $acc += $work['sum'];
        return $acc;
      });

      $daySalary = $this->getElement('daySalary', ['salary' => $salary]);
      $tableLabel = $this->getElement('dayTableLabel', ['userName' => $userName, 'date' => $date]);

      $tableRows = $this->generateTableRows($works);
      $tablePartsData = [
        'dayTableLabel' => $tableLabel,
        'tableRows' => $tableRows,
        'daySalary' => $daySalary,
      ];
      $this->varList['day'] = $this->getElement('dayTable', $tablePartsData);
    }

    // Отрисовка шаблона.
    public function render($tplName)
    {
      // Загрузка шаблона из файла, в качестве части пути используется получение базовой директории сайта.
      $content = file_get_contents(App::$config->get('basedir').'/views/' . $tplName . '.tpl');
      // Заменяем закладку на подготовленные данные, если такие есть
      $renderData = [];
      $renderData[$tplName] = $this->varList[$tplName];
      if (isset($this->varList[$tplName])) {
        $content = $this->pasteDataByMark($content, $renderData);
      }
      echo $content;
    }
}
?>

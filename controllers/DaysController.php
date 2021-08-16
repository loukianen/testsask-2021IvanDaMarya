<?php
namespace Controllers;

use Models\DaysModel;
use App\Tpl;

class DaysController
{
    private $tpl;
    private $model;

    public function run($data)
    {
      $this->tpl = new Tpl();
      $this->model = new DaysModel;
      if (!isset($data['date']) || !isset($data['staff'])) {
        return null;
      }
      return $this->render($data);
    }

    private function render($data)
    {
      $dayData = $this->model->getDayWorksData($data);
      $this->tpl->makeDayWorks($dayData);
      $this->tpl->render('day');
	}
}
?>

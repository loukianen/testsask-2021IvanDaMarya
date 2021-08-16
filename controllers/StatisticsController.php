<?php
namespace Controllers;

use Models\StatisticsModel;

class StatisticsController
{
    private $model;

    public function run($data)
    {
      if (!isset($data['userId']) || !isset($data['period'])) {
        return null;
      }

      $this->model = new StatisticsModel();
      $result = $this->model->getStatistics($data);

      echo json_encode($result, true);
    }
}
?>

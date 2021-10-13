<?php
namespace Models;

use App\App;

class DaysModel extends BaseModel
{
  private function generateWorksInfoQuery($userId, $startPeriod, $endPeriod)
  {
    $idOfWorkWithExtraMoney = 3;
    $extraMoneyForBed = 30;
    $extraMoneyForTowels = 10;

    return 'SELECT
      rooms.id,
      rooms.num,
      builds.name AS b_name,
      rooms.type,
      works.name AS w_name,
      s.start,
      s.end,
      s.work AS workType,
      s.bed,
      s.towels,
      CASE WHEN s.work == ' . BaseModel::ID_OF_EXTRA_MONEY_WORK . '
        THEN prices.price
          + s.bed * ' . BaseModel::EXTRA_MONEY_FOR_BED . '
          + s.towels * ' . BaseModel::EXTRA_MONEY_FOR_TOWELS . '
        ELSE prices.price
      END AS sum
    FROM statistics AS s
    JOIN rooms ON rooms.id = s.room
    JOIN works ON works.id = s.work
    JOIN builds ON builds.id = rooms.build
    JOIN prices ON prices.work = works.id
    WHERE s.staff = ' . $userId . '
      AND (s.start BETWEEN '. $startPeriod . ' AND ' . $endPeriod . ')
      AND s.work > 0
      AND prices.room_type = rooms.type';
  }

  private function generateWorksInfo($query)
    {
      $worksData = App::$db->query($query);

      return array_map(function($work)
      {
        $result = [];
        $result['room'] = $work['num'] . ' корпус ' . $work['b_name'];
        $result['roomType'] = $work['type'];
        $result['work'] = $work['w_name'];
        // Получаем время из даты
        $startDate = date_create_from_format('Y-m-d H:i:s', $work['start']);
        $result['start'] = $startDate->format('H:i');
        $endDate = date_create_from_format('Y-m-d H:i:s', $work['end']);
        $result['end'] = $startDate->format('H:i');

        $result['sum'] = $work['sum'];

        return $result;
      }, $worksData);
    }

    public function getDayWorksData($data)
    {
      ['date' => $date, 'staff' => $userId] = $data;

      $startPeriod = '"' .$date . ' 00:00:00"';
      $endPeriod = '"' .$date . ' 23:59:59"';

      $userName = App::$db->query('SELECT name FROM users WHERE id = ' . $userId)[0]['name'];

      $query = $this->generateWorksInfoQuery($userId, $startPeriod, $endPeriod);
      $works = $this->generateWorksInfo($query);
      return ['works' => $works, 'userName' => $userName, 'date' => $date];
    }
}
?>

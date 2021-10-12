<?php
namespace Models;

use App\App;

class DaysModel extends BaseModel
{
  private function generateWorksInfoQuery($userId, $startPeriod, $endPeriod)
  {
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
      s.towels
    FROM statistics AS s
    JOIN rooms ON rooms.id = s.room
    JOIN works ON works.id = s.work
    JOIN builds ON builds.id = rooms.build
    WHERE `staff` = ' . $userId . '
      AND (`start` BETWEEN '. $startPeriod . ' AND ' . $endPeriod . ')
      AND `work` > 0';
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

        // Время
        $result['start'] = substr(explode(' ', $work['start'])[1], 0, 5);
        $result['end'] = substr(explode(' ', $work['end'])[1], 0, 5);

        // Деньги
        $addishinalAmount = ($work['workType'] === 3) ? $work['bed'] * 30 + $work['towels'] * 10 : 0; //Если текущая уборка, считаем полотенца и белье
        $result['sum'] = $this->getPrice(['room' => $work['id'], 'work' => $work['workType']]) + $addishinalAmount;
        return $result;
      }, $worksData);
    }

    public function getDayWorksData($data)
    {
      ['date' => $date, 'staff' => $userId] = $data;

      $startPeriod = '"' .$date . ' 00:00:00"';
      $endPeriod = '"' .$date . ' 23:59:59"';

      $userName = App::$db->query('SELECT `name` FROM users WHERE `id` = ' . $userId)[0]['name'];

      $query = $this->generateWorksInfoQuery($userId, $startPeriod, $endPeriod);
      $works = $this->generateWorksInfo($query);
      return ['works' => $works, 'userName' => $userName, 'date' => $date];
    }
}
?>

<?php
namespace Models;

use App\App;

class DaysModel extends BaseModel
{
    private function generateWorksInfo($worksData)
    {
      return array_map(function($work)
      {
        ['room' => $roomId, 'start' => $start, 'end' => $end, 'work' => $workType, 'bed' => $bed, 'towels' => $towels] = $work;
        $result = [];

        // Информация по комнате
        $roomInfoQuery = 'SELECT `num`, `build`, `type` FROM rooms WHERE `id` = ' . $roomId;
        $roomInfo = App::$db->query($roomInfoQuery);
        ['num' => $num, 'build' => $build, 'type'=> $roomType] = $roomInfo[0];
        $buildNameQuery = 'SELECT `name` FROM builds WHERE `id` = ' . $build;
        $buildName = App::$db->query($buildNameQuery);
        $result['room'] = $num . ' корпус ' . $buildName[0]['name'];
        $result['roomType'] = $roomType;

        // Тип уборки
        $workNameQuery = 'SELECT `name` FROM works WHERE `id` = ' . $workType;
        $result['work'] = App::$db->query($workNameQuery)[0]['name'];

        // Время
        $result['start'] = substr(explode(' ', $start)[1], 0, 5);
        $result['end'] = substr(explode(' ', $end)[1], 0, 5);

        // Деньги
        $addishinalAmount = ($workType === 3) ? $bed * 30 + $towels * 10 : 0; //Если текущая уборка, считаем полотенца и белье
        $result['sum'] = $this->getPrice($work) + $addishinalAmount;
        return $result;
      }, $worksData);
    }

    public function getDayWorksData($data)
    {
      ['date' => $date, 'staff' => $userId] = $data;

      $startPeriod = '"' .$date . ' 00:00:00"';
      $endPeriod = '"' .$date . ' 23:59:59"';

      $userName = App::$db->query('SELECT `name` FROM users WHERE `id` = ' . $userId)[0]['name'];

      $worksQuery = 'SELECT `id`, `room`, `start`, `end`, `work`, `bed`, `towels` FROM statistics WHERE `staff` = ' . $userId . ' AND (`start` BETWEEN '. $startPeriod . ' AND ' . $endPeriod . ') AND `work` > 0';
      $worksData = App::$db->query($worksQuery);

      $works = $this->generateWorksInfo($worksData);
      return ['works' => $works, 'userName' => $userName, 'date' => $date];
    }
}
?>

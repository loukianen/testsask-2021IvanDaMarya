<?php
namespace Controllers;

use App\App;

class StatisticsController
{
    private $staffId;

    private function getDayStatistics($dayDate)
    {
      $startPeriod = '"' .$dayDate . ' 00:00:00"';
      $endPeriod = '"' .$dayDate . ' 23:59:59"';
      $fields = '`room`, `work`, `bed`, `towels`';

      $periodQury = 'SELECT ' . $fields . ' FROM statistics WHERE `staff` = ' . $this->staffId . ' AND (`start` BETWEEN '. $startPeriod . ' AND ' . $endPeriod . ') AND (`work` > 0)';
      $per_data = App::$db->query($periodQury);

      $statistic = array_reduce($per_data, function($acc, $item)
        {
          ['room' => $room, 'work' => $work, 'bed' => $bed, 'towels' => $towels] = $item;
          $acc['sum'] += $this->getPrice($item) + $bed * 30 + $towels * 10;
          $worksMapping = [1 => 'check_in', 2 => 'general', 3 => 'current'];
          $acc[$worksMapping[$work]] += 1;
          return $acc;
        }, ['sum' => 0, 'check_in' => 0, 'general' => 0, 'current' => 0]);
      return $statistic;
    }

    private function getPrice($work)
    {
      $roomTypeQuery = 'SELECT `type` FROM rooms WHERE `id` = ' . $work['room'];
      $roomType = $roomType = App::$db->query($roomTypeQuery)[0]['type'];
      $roomPriceQuery = 'SELECT `price` FROM prices WHERE `room_type` = ' . $roomType . ' AND `work` = ' . $work['work'];
      $price = App::$db->query($roomPriceQuery)[0]['price'];
      return $price;
    }

    public function run($data)
    {
      if (!isset($data['userId']) || !isset($data['period'])) {
        return null;
      }
      ['userId' => $userId, 'period' => $period] = $data;
      $this->staffId = $userId;
      $startPeriod = '"' .$period . '-01 00:00:00"';
      $endPeriod = '"' .$period . '-31 23:59:59"';
      $workingDaysQuery = 'SELECT `start`, `end` FROM statistics WHERE `staff` = ' . $userId . ' AND (`start` BETWEEN '. $startPeriod . ' AND ' . $endPeriod . ') AND `work` = 0';
      $workingDaysData = App::$db->query($workingDaysQuery);
      $workingDays = array_reduce($workingDaysData, function($acc, $arr)
        {
         [$day, $startTime] = explode(' ', $arr['start']);
         [, $endTime] = explode(' ', $arr['end']);
         $acc[$day] = ['start' => $startTime, 'end' => $endTime];
         return $acc;
        }, []);
      $dates = array_keys($workingDays);
      $daysStatistic = array_reduce($dates, function($acc, $date)
        {
          $acc[$date] = $this->getDayStatistics($date);
          return $acc;
        }, []);
      $result = array_merge_recursive($workingDays, $daysStatistic);
      $tt = json_encode($result, true);
      print_r($tt);
      return $tt;
    }
}
?>

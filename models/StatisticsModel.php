<?php
namespace Models;

use App\App;

class StatisticsModel extends BaseModel
{
    private $staffId;

    private function getPrice($work)
    {
      $roomTypeQuery = 'SELECT `type` FROM rooms WHERE `id` = ' . $work['room'];
      $workPriceQuery = 'SELECT `price` FROM prices
        WHERE `room_type` = (' . $roomTypeQuery . ') AND `work` = ' . $work['work'];

      $price = App::$db->query($workPriceQuery)[0]['price'];
 
      return $price;
    }

    private function getDayStatistics($dayDate)
    {
      $startPeriod = '"' .$dayDate . ' 00:00:00"';
      $endPeriod = '"' .$dayDate . ' 23:59:59"';
      $fields = '`room`, `work`, `bed`, `towels`';

      $periodQury = 'SELECT ' . $fields . ' FROM statistics WHERE `staff` = ' . $this->staffId . ' AND (`start` BETWEEN '. $startPeriod . ' AND ' . $endPeriod . ') AND (`work` > 0)';
      $periodData = App::$db->query($periodQury);

      $statistic = array_reduce($periodData, function($acc, $item)
        {
          ['room' => $room, 'work' => $work, 'bed' => $bed, 'towels' => $towels] = $item;
          //Если текущая уборка, считаем полотенца и белье
          $addishinalAmount = ($work === BaseModel::ID_OF_EXTRA_MONEY_WORK)
            ? $bed * BaseModel::EXTRA_MONEY_FOR_BED + $towels * BaseModel::EXTRA_MONEY_FOR_TOWELS
            : 0;
          $acc['sum'] += $this->getPrice($item) + $addishinalAmount;

          $worksMapping = [1 => 'check_in', 2 => 'general', 3 => 'current'];

          $acc[$worksMapping[$work]] += 1;

          return $acc;
        }, ['sum' => 0, 'check_in' => 0, 'general' => 0, 'current' => 0]);
      return $statistic;
    }

    public function getStatistics($data)
    {
      ['userId' => $userId, 'period' => $period] = $data;
      $this->staffId = $userId;

      $startPeriod = '"' .$period . '-01 00:00:00"';
      $endDate = date_create_from_format('Y-m-d H:i:s', $period . '-01 23:59:59');
      $endPeriod = '"' . $endDate->format('Y-m-t H:i:s') . '"';

      $workingDaysQuery = 'SELECT start, end, staff FROM statistics WHERE staff = ' . $userId . ' AND (start BETWEEN ' . $startPeriod . ' AND ' . $endPeriod . ') AND work = 0';
      $workingDaysData = App::$db->query($workingDaysQuery);

      $workingDays = array_reduce($workingDaysData, function($acc, $arr)
        {
         [$day, $startTime] = explode(' ', $arr['start']);
         [, $endTime] = explode(' ', $arr['end']);
         $acc[$day] = ['start' => substr($startTime, 0, 5), 'end' => substr($endTime, 0, 5), 'staff' => $arr['staff']];
         return $acc;
        }, []);

      $dates = array_keys($workingDays);
      $daysStatistic = array_reduce($dates, function($acc, $date)
        {
          $acc[$date] = $this->getDayStatistics($date);
          return $acc;
        }, []);

      $result = array_merge_recursive($workingDays, $daysStatistic);
      
      return $result;
    }
}
?>

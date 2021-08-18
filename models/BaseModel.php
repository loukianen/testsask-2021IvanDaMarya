<?php
namespace Models;

use App\App;

class BaseModel
{
    public function getPrice($work)
    {
      $roomTypeQuery = 'SELECT `type` FROM rooms WHERE `id` = ' . $work['room'];
      $roomType = $roomType = App::$db->query($roomTypeQuery)[0]['type'];
      
      $workPriceQuery = 'SELECT `price` FROM prices WHERE `room_type` = ' . $roomType . ' AND `work` = ' . $work['work'];
      $price = App::$db->query($workPriceQuery)[0]['price'];
      
      return $price;
    }
}
?>

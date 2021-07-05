<?php
namespace Controllers;

use App\Tpl;

class NotFoundController
{
    private $tpl;
    public function run()
    {
      $this->tpl = new Tpl();
      return $this->render();
    }

    private function render()
    {
      $this->tpl->render('notFound');
  }
}
?>

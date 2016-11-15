<?php

namespace Tests\Reversi\Model;

use Reversi\Model\Cell;
use Reversi\Model\Player;

class PlayerTest extends \PHPUnit_Framework_TestCase
{

  public function testGetPlayNameShouldReturnPlayerNameWithCellTypeSymbol()
  {

    $player = new Player('toto', Cell::TYPE_BLACK);
    $this->assertEquals($player->getPlayName(), 'toto (○)');

  }

}

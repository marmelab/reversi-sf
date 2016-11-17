<?php

namespace Tests\Reversi\Model;

use Reversi\Model\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{

  public function testMarkAsFinishedShouldMarkGameAsFinished()
  {

    $game = new Game;
    $this->assertEquals(false, $game->isFinished());
    $game->markAsFinished();
    $this->assertEquals(true, $game->isFinished());

  }

}

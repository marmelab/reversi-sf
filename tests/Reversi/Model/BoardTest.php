<?php

namespace Tests\Reversi\Model;

use Reversi\Model\Board;
use Reversi\Model\Cell;

class BoardTest extends \PHPUnit_Framework_TestCase
{

    private $board;

    protected function setUp()
    {
        $this->board = new Board(4, 4);
    }

    public function testBoardCellsAfterInitialization()
    {

        $this->assertEquals($this->board->getCells(), [
          [0, 0, 0, 0],
          [0, 1, 2, 0],
          [0, 2, 1, 0],
          [0, 0, 0, 0]
        ]);

    }

    public function testIsInBoundsShouldReturnFalseForInvalidPosition()
    {

      $this->assertEquals($this->board->isInBounds(42, 42), false);

    }

    public function testGetCellTypeDistributionShouldReturnMapOfCountCellByType()
    {

      $this->assertEquals($this->board->getCellTypeDistribution(), [
        Cell::TYPE_EMPTY => 12,
        Cell::TYPE_BLACK => 2,
        Cell::TYPE_WHITE => 2
      ]);

    }

}

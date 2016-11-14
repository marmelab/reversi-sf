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

    /**
     * @expectedException Reversi\Exception\OutOfBoardException
     */
    public function testAddCellChangeToInvalidPositionMustThrowException()
    {

      $this->board->addCellChange(new Cell(42, 42, 0));

    }

    /**
     * @expectedException Reversi\Exception\InvalidCellTypeException
     */
    public function testAddCellChangeWithInvalidCellTypeMustThrowException()
    {

      $this->board->addCellChange(new Cell(0, 0, "test"));

    }

}

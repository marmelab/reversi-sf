<?php

namespace Tests\Reversi;

use Reversi\Model\Cell;
use Reversi\Model\Board;
use Reversi\Model\Player;
use Reversi\BoardManipulator;
use Reversi\Exception\InvalidCellChangeException;

class BoardManipulatorTest extends \PHPUnit_Framework_TestCase
{

  public function testCanApplyCellChangeShouldReturnTrueOnLegalCellChange()
  {

    $manipulator = new BoardManipulator(new Board(4, 4));
    $this->assertEquals($manipulator->canApplyCellChange(new Cell(0, 1, Cell::TYPE_WHITE)), true);

  }

  public function testCanApplyCellChangeShouldReturnFalseOnillegalCellChange()
  {

    $manipulator = new BoardManipulator(new Board(4, 4));
    $this->assertEquals($manipulator->canApplyCellChange(new Cell(1, 1, Cell::TYPE_WHITE)), false);

  }

  /**
   * @expectedException Reversi\Exception\InvalidCellChangeException
   */
  public function testAddCellChangeToInvalidPositionMustThrowException()
  {

    $manipulator = new BoardManipulator(new Board(4, 4));
    $manipulator->applyCellChange(new Cell(42, 42, 0));

  }

  public function testGetDirectionnalVectorsShouldReturnDirectionnalVectors()
  {

    $manipulator = new BoardManipulator(new Board(4, 4));

    $this->assertEquals(
      $manipulator->getDirectionnalVectors(),
      [[0, 1], [1, 1], [1, 0], [1, -1], [0, -1], [-1, -1], [-1, 0], [-1, 1]]
    );

  }

  public function testGetFlippedCellsFromCellChangeInDirectionShouldReturnFlippedCells()
  {

    $manipulator = new BoardManipulator(new Board(4, 4));
    $flipped = $manipulator->getFlippedCellsFromCellChangeInDirection(new Cell(0, 1, Cell::TYPE_WHITE), 1, 0);
    $this->assertEquals($flipped, [new Cell(1, 1, Cell::TYPE_WHITE)]);
    $flipped = $manipulator->getFlippedCellsFromCellChangeInDirection(new Cell(0, 1, Cell::TYPE_BLACK), 1, 0);
    $this->assertEquals($flipped, []);
    $flipped = $manipulator->getFlippedCellsFromCellChangeInDirection(new Cell(1, 3, Cell::TYPE_BLACK), 0, -1);
    $this->assertEquals($flipped, [new Cell(1, 2, Cell::TYPE_BLACK)]);

  }

  public function testGetAvailableCellChangesShouldReturnAvailableCellChange()
  {

      $manipulator = new BoardManipulator(new Board(4, 4));
      $this->assertEquals($manipulator->getAvailableCellChanges(Cell::TYPE_WHITE), [
        new Cell(1, 0, Cell::TYPE_WHITE),
        new Cell(0, 1, Cell::TYPE_WHITE),
        new Cell(3, 2, Cell::TYPE_WHITE),
        new Cell(2, 3, Cell::TYPE_WHITE)
      ]);

  }

}

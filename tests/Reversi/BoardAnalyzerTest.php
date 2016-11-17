<?php

namespace Tests\Reversi;

use Reversi\Model\Cell;
use Reversi\Model\Board;
use Reversi\Model\Player;
use Reversi\BoardAnalyzer;
use Reversi\Exception\InvalidCellChangeException;

class BoardAnalyzerTest extends \PHPUnit_Framework_TestCase
{

  public function testCanApplyCellChangeShouldReturnTrueOnLegalCellChange()
  {

    $analyzer = new BoardAnalyzer(new Board(4, 4));
    $this->assertEquals($analyzer->canApplyCellChange(new Cell(0, 1, Cell::TYPE_WHITE)), true);

  }

  public function testCanApplyCellChangeShouldReturnFalseOnillegalCellChange()
  {

    $analyzer = new BoardAnalyzer(new Board(4, 4));
    $this->assertEquals($analyzer->canApplyCellChange(new Cell(1, 1, Cell::TYPE_WHITE)), false);

  }

  public function testGetDirectionnalVectorsShouldReturnDirectionnalVectors()
  {

    $analyzer = new BoardAnalyzer(new Board(4, 4));

    $this->assertEquals(
      $analyzer->getDirectionnalVectors(),
      [[0, 1], [1, 1], [1, 0], [1, -1], [0, -1], [-1, -1], [-1, 0], [-1, 1]]
    );

  }

  public function testGetFlippedCellsFromCellChangeInDirectionShouldReturnFlippedCells()
  {

    $analyzer = new BoardAnalyzer(new Board(4, 4));
    $flipped = $analyzer->getFlippedCellsFromCellChangeInDirection(new Cell(0, 1, Cell::TYPE_WHITE), 1, 0);
    $this->assertEquals($flipped, [new Cell(1, 1, Cell::TYPE_WHITE)]);
    $flipped = $analyzer->getFlippedCellsFromCellChangeInDirection(new Cell(0, 1, Cell::TYPE_BLACK), 1, 0);
    $this->assertEquals($flipped, []);
    $flipped = $analyzer->getFlippedCellsFromCellChangeInDirection(new Cell(1, 3, Cell::TYPE_BLACK), 0, -1);
    $this->assertEquals($flipped, [new Cell(1, 2, Cell::TYPE_BLACK)]);

  }

  public function testGetAvailableCellChangesShouldReturnAvailableCellChange()
  {

      $analyzer = new BoardAnalyzer(new Board(4, 4));
      $this->assertEquals($analyzer->getAvailableCellChanges(Cell::TYPE_WHITE), [
        new Cell(1, 0, Cell::TYPE_WHITE),
        new Cell(0, 1, Cell::TYPE_WHITE),
        new Cell(3, 2, Cell::TYPE_WHITE),
        new Cell(2, 3, Cell::TYPE_WHITE)
      ]);

  }

}

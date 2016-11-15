<?php

namespace Tests\Reversi\Model;

use Reversi\Model\Cell;

class CellTest extends \PHPUnit_Framework_TestCase
{

  public function testGetCellTypeShouldReturnAllCellType()
  {

    $this->assertEquals([
      Cell::TYPE_EMPTY,
      Cell::TYPE_BLACK,
      Cell::TYPE_WHITE
    ], Cell::getTypes());

  }

  public function testGetReverseCellTypeShouldReturnReverseCellType()
  {

    $this->assertEquals(Cell::getReverseType(Cell::TYPE_EMPTY), Cell::TYPE_EMPTY);
    $this->assertEquals(Cell::getReverseType(Cell::TYPE_BLACK), Cell::TYPE_WHITE);
    $this->assertEquals(Cell::getReverseType(Cell::TYPE_WHITE), Cell::TYPE_BLACK);

  }

  /**
   * @expectedException Reversi\Exception\InvalidCellTypeException
   */
  public function testGetReverseCellTypeShouldThrowInvalidCellTypeExceptionOnInvalidType()
  {

    Cell::getReverseType(42);

  }

  public function testGetTypeSymbolShouldReturnCellTypeSymbol()
  {

    $this->assertEquals(Cell::getTypeSymbol(Cell::TYPE_EMPTY), " ");
    $this->assertEquals(Cell::getTypeSymbol(Cell::TYPE_BLACK), "○");
    $this->assertEquals(Cell::getTypeSymbol(Cell::TYPE_WHITE), "●");

  }

  /**
   * @expectedException Reversi\Exception\InvalidCellTypeException
   */
  public function testGetTypeSymbolShouldThrowInvalidCellTypeExceptionOnInvalidType()
  {

    Cell::getTypeSymbol(42);

  }

}

<?php

namespace Reversi\Model;

use Reversi\Exception\OutOfBoardException;
use Reversi\Exception\InvalidCellTypeException;
use Reversi\Exception\InvalidBoardSizeException;
use Reversi\Spec\ReversiBoardSizeSpecification;

class Board
{

  private $cells;

  public function __construct($cols, $rows)
  {
    $this->validateBoardSize($cols, $rows);
    $this->initializeCells($cols, $rows);
  }

  public function getCells()
  {
    return $this->cells;
  }

  public function drawCells($cells)
  {

    foreach($cells as $cell){
      list($x, $y) = $cell->getPosition();
      $this->cells[$y][$x] = $cell->getType();
    }

    return clone $this;

  }

  public function isInBounds($xPos, $yPos)
  {
    return array_key_exists($yPos, $this->cells) && array_key_exists($xPos, $this->cells[$yPos]);
  }

  public function getCellTypeDistribution()
  {

    $distribution = [];

    foreach($this->cells as $rows){
      foreach($rows as $type){
        if(!array_key_exists($type, $distribution)){
          $distribution[$type] = 0;
        }
        $distribution[$type]++;
      }
    }

    return $distribution;

  }

  private function initializeCells($cols, $rows)
  {

    for($y = 0; $y < $rows; $y++){
      for($x = 0; $x < $cols; $x++){
        $this->cells[$y][$x] = Cell::TYPE_EMPTY;
      }
    }

    $xMiddle = $cols / 2;
    $yMiddle = $rows / 2;

    $this->cells[$yMiddle][$xMiddle] = Cell::TYPE_BLACK;
    $this->cells[$yMiddle - 1][$xMiddle - 1] = Cell::TYPE_BLACK;
    $this->cells[$yMiddle][$xMiddle - 1] = Cell::TYPE_WHITE;
    $this->cells[$yMiddle - 1][$xMiddle] = Cell::TYPE_WHITE;

    return $this;

  }

  private function validateBoardSize($cols, $rows)
  {

    $boardSizeSpec = new ReversiBoardSizeSpecification();

    if(!$boardSizeSpec->isSatisfiedBy($cols, $rows)){
      throw new InvalidBoardSizeException();
    }

    return $this;

  }

}

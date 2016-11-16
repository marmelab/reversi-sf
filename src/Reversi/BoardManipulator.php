<?php

namespace Reversi;

use Reversi\Model\Cell;
use Reversi\Model\Board;
use Reversi\Spec\ReversiCellChangeSpecification;
use Reversi\Exception\InvalidCellChangeException;

class BoardManipulator
{

  private $board;

  public function __construct(Board $board)
  {
    $this->board = clone $board;
  }

  public function getBoard()
  {
    return $this->board;
  }

  public function canApplyCellChange(Cell $cellChange)
  {

    $cells = $this->board->getCells();
    list($x, $y) = $cellChange->getPosition();

    if(!$this->board->isInBounds($x, $y) || $cells[$y][$x] !== Cell::TYPE_EMPTY){
      return false;
    }

    foreach ($this->getDirectionnalVectors() as $vect) {
      if(count($this->getFlippedCellsFromCellChangeInDirection($cellChange, $vect[0], $vect[1])) > 0){
        return true;
      }
    }

    return false;

  }

  public function applyCellChange(Cell $cellChange)
  {

    list($x, $y) = $cellChange->getPosition();

    if(!$this->canApplyCellChange($cellChange)){
      throw new InvalidCellChangeException(sprintf("You can't change cell at %d,%d", $x, $y));
    }

    $flipped = $this->getFlippedCellsFromCellChange($cellChange);
    $this->board->drawCells(array_merge($flipped, [$cellChange]));

    return $this;

  }

  public function getAvailableCellChanges($cellType)
  {

    $cellChanges = [];

    foreach($this->board->getCells() as $y => $rows){
      foreach($rows as $x => $cell){
        $cellChange = new Cell($x, $y, $cellType);
        if($this->canApplyCellChange($cellChange)){
          $cellChanges[] = $cellChange;
        }
      }
    }

    return $cellChanges;

  }

  public function getAvailableCellPositions($cellType)
  {

    $cellChanges = $this->getAvailableCellChanges($cellType);
    $positions = [];

    foreach($cellChanges as $cellChange){
      $positions[] = $cellChange->getPosition();
    }

    return $positions;

  }

  public function getFlippedCellsFromCellChange(Cell $cellChange)
  {

    $cells = $this->board->getCells();
    list($x, $y) = $cellChange->getPosition();

    if(!$this->board->isInBounds($x, $y) || $cells[$y][$x] !== Cell::TYPE_EMPTY){
      return [];
    }

    $flipped = [];
    foreach ($this->getDirectionnalVectors() as $direction) {
      $flipped = array_merge(
        $flipped,
        $this->getFlippedCellsFromCellChangeInDirection($cellChange, $direction[0], $direction[1])
      );
    }

    return $flipped;

  }

  public function getFlippedCellsFromCellChangeInDirection(Cell $cellChange, $xVect, $yVect)
  {

    $cells = $this->board->getCells();
    $reverseCellType = Cell::getReverseType($cellChange->getType());

    list($currX, $currY) = $cellChange->getPosition();
    $localCellType = Cell::TYPE_EMPTY;
    $flipped = [];

    while(true){
      list($currX, $currY) = [$currX+$xVect, $currY+$yVect];
      if(!$this->board->isInBounds($currX, $currY) || (($localCellType = $cells[$currY][$currX]) !== $reverseCellType)){
        break;
      }
      $flipped[] = new Cell($currX, $currY, $cellChange->getType());
    }

    if($localCellType === $cellChange->getType() && count($flipped) > 0){
      return $flipped;
    }

    return [];

  }

  public function getDirectionnalVectors()
  {
    return [[0, 1], [1, 1], [1, 0], [1, -1], [0, -1], [-1, -1], [-1, 0], [-1, 1]];
  }

}

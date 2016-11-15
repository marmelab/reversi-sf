<?php

namespace Reversi\Model;

use Reversi\Exception\OutOfBoardException;
use Reversi\Exception\InvalidCellTypeException;
use Reversi\Exception\InvalidBoardSizeException;

class Board
{

  private $cells;

  public function __construct($xSize, $ySize)
  {

    $this->validateBoardSize($xSize, $ySize);
    $this->initializeCells($xSize, $ySize);

  }

  public function addCellChange(Cell $cell)
  {

    list($x, $y) = $cell->getPosition();
    $type = $cell->getType();
    
    if(!$this->isValidPosition($x, $y)){
      throw new OutOfBoardException('You can\'t change cell out of board');
    }

    if(!in_array($type, Cell::getTypes())){
      throw new InvalidCellTypeException(sprintf("%s cell type is invalid.", $type));
    }

    if(!$this->isLegalCellChange($cell)){
      throw new IllegalCellChangeException(sprintf("You can't change cell at %d,%d", $x, $y));
    }

    $this->cells[$y][$x] = $type;

    return $this;

  }

  public function isLegalCellChange(Cell $cell)
  {
    return count($this->getFlippedCellsFromCellChange($cell)) > 0;
  }

  public function getFlippedCellsFromCellChange(Cell $cell)
  {

    list($x, $y) = $cell->getPosition();

    if($this->cells[$y][$x] !== Cell::TYPE_EMPTY){
      return [];
    }



  }

  public function getFlippedCellsFromCellChangeInDirection(Cell $cell, $xVect, $yVect)
  {

    $reverseCellType = Cell::getReverseType($cell->getType());
    list($xVectPos, $yVectPos) = $cell->getPosition();
    $flipped = [];
    $localCellType;

    while(true){
      list($xVectPos, $yVectPos) = [$xVectPos+$xVect, $yVectPos+$yVect];
      if(!$this->isValidPosition($xVectPos, $yPos) || $this->cells[$yPos][$xPos] !== $reverseCellType){
        break;
      }
      $localCellType = $this->cells[$yPos][$xPos];
      $flipped[] = new Cell($xVectPos, $yVectPos, $cell->getType());
    }

    if($localCellType === $cell->getType() && count($flipped) > 0){
      return $flipped;
    }

    return [];

  }

  public function getCells()
  {
    return $this->cells;
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

  public function isValidPosition($xPos, $yPos)
  {

    return array_key_exists($yPos, $this->cells) && array_key_exists($xPos, $this->cells[$yPos]);

  }

  private function initializeCells($xSize, $ySize)
  {

    for($y = 0; $y < $ySize; $y++){
      for($x = 0; $x < $xSize; $x++){
        $this->cells[$y][$x] = Cell::TYPE_EMPTY;
      }
    }

    $xMiddle = $xSize / 2;
    $yMiddle = $ySize / 2;

    $this->cells[$yMiddle][$xMiddle] = Cell::TYPE_BLACK;
    $this->cells[$yMiddle - 1][$xMiddle - 1] = Cell::TYPE_BLACK;
    $this->cells[$yMiddle][$xMiddle - 1] = Cell::TYPE_WHITE;
    $this->cells[$yMiddle - 1][$xMiddle] = Cell::TYPE_WHITE;

    return $this;

  }

  private function validateBoardSize($xSize, $ySize)
  {

    if($xSize%2 !== 0 || $ySize%2 !== 0){
      throw new InvalidBoardSizeException('Board size must be even.');
    }

    if($xSize < 4 || $ySize < 4){
      throw new InvalidBoardSizeException('Board size must be greater than 4.');
    }

    return $this;

  }

}

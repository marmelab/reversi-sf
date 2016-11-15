<?php

namespace Reversi\Model;
use Reversi\Exception\InvalidCellTypeException;

class Cell
{

  const TYPE_EMPTY = 0;
  const TYPE_BLACK = 1;
  const TYPE_WHITE = 2;

  private $x;
  private $y;
  private $type;

  public function __construct($x, $y, $type)
  {

    $this->x = $x;
    $this->y = $y;
    $this->type = $type;

  }

  public function getPosition()
  {
    return [$this->x, $this->y];
  }

  public function getType()
  {
    return $this->type;
  }

  public static function getReverseType($type)
  {

    switch($type){
      case self::CELL_BLACK:
        return self::CELL_WHITE;
      case self::CELL_WHITE:
        return self::CELL_BLACK;
      case self::CELL_EMPTY:
        return self::CELL_EMPTY;
    }

    throw new InvalidCellTypeException(sprintf('Unknow cell type %s', $type));

  }

  public static function getTypeSymbol($type)
  {

    switch($type){
      case self::CELL_EMPTY:
        return " ";
      case self::CELL_BLACK:
        return "○";
      case self::CELL_WHITE:
        return "●";
    }

    throw new InvalidCellTypeException(sprintf("%s cell type is invalid.", $type));

  }

  public static function getTypes()
  {

    return [
      self::CELL_EMPTY,
      self::CELL_BLACK,
      self::CELL_WHITE
    ];

  }

  public function __toString()
  {
    return $this->x . 'x' . $this->y . '(' . self::getTypeSymbol($this->type) . ')';
  }

}

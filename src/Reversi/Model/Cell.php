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
      case self::TYPE_BLACK:
        return self::TYPE_WHITE;
      case self::TYPE_WHITE:
        return self::TYPE_BLACK;
      case self::TYPE_EMPTY:
        return self::TYPE_EMPTY;
    }

    throw new InvalidCellTypeException(sprintf('Unknow cell type %s', $type));

  }

  public static function getTypeSymbol($type)
  {

    switch($type){
      case self::TYPE_EMPTY:
        return " ";
      case self::TYPE_BLACK:
        return "○";
      case self::TYPE_WHITE:
        return "●";
    }

    throw new InvalidCellTypeException(sprintf("%s cell type is invalid.", $type));

  }

  public static function getTypes()
  {

    return [
      self::TYPE_EMPTY,
      self::TYPE_BLACK,
      self::TYPE_WHITE
    ];

  }

}

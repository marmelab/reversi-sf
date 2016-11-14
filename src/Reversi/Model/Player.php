<?php

namespace Reversi\Model;

class Player
{

  private $name;

  private $cellType;

  public function __construct($name, $cellType)
  {
    $this->name = $name;
    $this->cellType = $cellType;
  }

  public function getPlayName()
  {
    return $this->name . '(' . Board::getCellTypeSymbol() . ')';
  }

  public function getName()
  {
    return $this->name;
  }

  public function getCellType()
  {
    return $this->cellType;
  }

}

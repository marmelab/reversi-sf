<?php

namespace Reversi\Model;

use Reversi\Model\Cell;

class Player
{

  protected $id;
  protected $name;
  protected $cellType;

  public function __construct($name, $cellType)
  {
    $this->name = $name;
    $this->cellType = $cellType;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getCellType()
  {
    return $this->cellType;
  }

  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }

  public function setCellType($cellType)
  {
    $this->cellType = $cellType;
    return $this;
  }

  public function getPlayName()
  {
    return $this->name . ' (' . Cell::getTypeSymbol($this->cellType) . ')';
  }

}

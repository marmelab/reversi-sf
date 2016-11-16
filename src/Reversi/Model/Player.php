<?php

namespace Reversi\Model;

use Reversi\Model\Cell;

class Player
{

  protected $id;
  protected $name;
  protected $cellType;
  protected $origin;
  protected $token;

  public function __construct($name, $cellType, $origin = null, $token = null)
  {
    $this->name = $name;
    $this->cellType = $cellType;
    $this->origin = $origin;
    $this->token = $token;
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

  public function getOrigin()
  {
    return $this->origin;
  }

  public function setOrigin($origin)
  {
    $this->origin = $origin;
    return $this;
  }

  public function getToken()
  {
    return $this->token;
  }

  public function setToken($token)
  {
    $this->token = $token;
    return $this;
  }

}

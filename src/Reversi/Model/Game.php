<?php

namespace Reversi\Model;

class Game
{

  private $firstPlayer;

  private $secondPlayer;

  private $currentPlayer;

  private $board;

  private $isFinished;

  public function __construct()
  {

    $this->board = new Board(8, 8);
    $this->isFinished = false;

  }

  public function getCurrentPlayer()
  {
    return $this->currentPlayer;
  }

  public function switchPlayer()
  {

    if($this->currentPlayer === $this->firstPlayer){
      $this->currentPlayer = $this->secondPlayer;
    } else {
      $this->currentPlayer = $this->firstPlayer;
    }

    return $this;

  }

  public function markAsFinished()
  {
    $this->isFinished = true;
    return $this;
  }

  public function isFinished()
  {
    return $this->isFinished;
  }

}

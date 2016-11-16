<?php

namespace Reversi\Model;

use Reversi\Exception\TooManyPlayersException;
use Reversi\Model\Cell;
use Reversi\Model\Board;

class Game
{

  protected $id;
  protected $firstPlayer;
  protected $secondPlayer;
  protected $currentPlayer;
  protected $board;
  protected $isFinished;
  protected $startAt;

  public function __construct()
  {

    $this->board = new Board(8, 8);
    $this->isFinished = false;
    $this->startAt = new \DateTime();

  }

  public function getId()
  {
    return $this->id;
  }

  public function getCurrentPlayer()
  {
    return $this->currentPlayer;
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

  public function addPlayer(Player $player)
  {
      if (!$this->firstPlayer) {
          $this->firstPlayer = $player;
      } elseif (!$this->secondPlayer) {
          $this->secondPlayer = $player;
      } else {
          throw new TooManyPlayersException();
      }

      $this->assignFirstPlayer();

      return $this;

  }

  public function assignFirstPlayer()
  {

    if($this->currentPlayer){
      return;
    }

    // First player always has Black Cell Type

    if($this->firstPlayer->getCellType() !== Cell::TYPE_BLACK){
      $this->currentPlayer = $this->secondPlayer;
    } else {
      $this->currentPlayer = $this->firstPlayer;
    }

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

  public function getBoard()
  {
    return $this->board;
  }

  public function setBoard(Board $board)
  {
    $this->board = $board;
    return $this;
  }

}

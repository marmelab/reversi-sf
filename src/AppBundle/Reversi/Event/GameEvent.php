<?php

namespace AppBundle\Reversi\Event;

use Reversi\Model\Game;
use Symfony\Component\EventDispatcher\Event;

class GameEvent extends Event
{

  private $game;
  private $recipient;

  public function __construct($recipient, Game $game = null)
  {
    $this->game = $game;
    $this->recipient = $recipient;
  }

  public function getGame()
  {
    return $this->game;
  }

  public function getRecipient()
  {
    return $this->recipient;
  }

}

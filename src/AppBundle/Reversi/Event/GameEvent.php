<?php

namespace AppBundle\Reversi\Event;

use Reversi\Model\Game;
use Symfony\Component\EventDispatcher\Event;

class GameEvent extends Event
{
    private $game;
    private $recipient;
    private $payload;

    public function __construct($recipient, Game $game = null, $payload = [])
    {
        $this->game = $game;
        $this->recipient = $recipient;
        $this->payload = $payload;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}

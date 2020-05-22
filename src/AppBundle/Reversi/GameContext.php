<?php

namespace AppBundle\Reversi;

class GameContext
{
    private $message;
    private $playerOrigin;
    private $playerToken;

    public function __construct($message, $playerOrigin, $playerToken)
    {
        $this->message = $message;
        $this->playerOrigin = $playerOrigin;
        $this->playerToken = $playerToken;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getPlayerOrigin()
    {
        return $this->playerOrigin;
    }

    public function getPlayerToken()
    {
        return $this->playerToken;
    }
}

<?php

namespace AppBundle\Reversi;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Reversi\Event\GameEvent;
use AppBundle\Reversi\Event\GameEvents;
use AppBundle\Reversi\GameContext;
use Reversi\Model\Game;
use Reversi\Model\Player;
use Reversi\BoardAnalyzer;
use Reversi\Exception\InvalidCellChangeException;

class GameContextHandler
{
    private $manager;
    private $ai;
    private $dispatcher;

    public function __construct(GameManager $manager, GameAi $ai, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->ai = $ai;
        $this->dispatcher = $dispatcher;
    }

    public function handle(GameContext $context)
    {

        // Attempt to retrieve Game

        if (!($game = $this->retrieveGame($context))) {
            return;
        }

        // Close context handling on demand

        if ($this->gameMustClose($game, $context)) {
            return;
        }

        // Play turn

        try {
            $this->playTurn($game, $context);
        } catch (\Exception $e) {
            $errorEvent = new GameEvent(
              $context->getPlayerToken(),
              $game,
              ['message' => $e->getMessage()]
            );
            $this->dispatcher->dispatch(GameEvents::ACTION_ERROR, $errorEvent);
            return;
        }

        // If game is finished, show winner
        // Else, ask for position

        if ($game->isFinished()) {
            $this->dispatcher->dispatch(GameEvents::ACTION_FINISH, new GameEvent($context->getPlayerToken(), $game));
        } else {
            $this->dispatcher->dispatch(GameEvents::ACTION_ASK_FOR_POSITION, new GameEvent($context->getPlayerToken(), $game));
        }
    }

    private function retrieveGame(GameContext $context)
    {
        $playerToken = $context->getPlayerToken();
        if (!($game = $this->manager->get($context))) {
            if (strtolower($context->getMessage()) !== 'othello') {
                $this->dispatcher->dispatch(GameEvents::ACTION_EXPLAIN_START, new GameEvent($playerToken));
                return;
            }
            $game = $this->manager->create($context);
            $this->dispatcher->dispatch(GameEvents::ACTION_WELCOME, new GameEvent($playerToken, $game));
            $this->dispatcher->dispatch(GameEvents::ACTION_ASK_FOR_POSITION, new GameEvent($playerToken, $game));

            return;
        }

        return $game;
    }

    private function gameMustClose(Game $game, GameContext $context)
    {
        if (in_array($context->getMessage(), ['close', 'finish'])) {
            $this->dispatcher->dispatch(GameEvents::ACTION_FINISH, new GameEvent(
              $context->getPlayerToken(),
              $game
            ));
            $this->manager->finish($game);
            return true;
        }
        return false;
    }

    private function playTurn(Game $game, GameContext $context)
    {
        $playerToken = $context->getPlayerToken();

        // It's not the turn of the context player

        if ($game->getCurrentPlayer()->getToken() !== $playerToken) {
            $this->dispatcher->dispatch(GameEvents::ACTION_NOT_YOUR_TURN, new GameEvent($playerToken, $game));
            return;
        }

        // Current player turn

        try {
            if (!is_numeric($context->getMessage())) {
                throw new \Exception('Invalid position.');
            }
            $this->manager->playPosition($game, intval($context->getMessage()));
            $this->dispatcher->dispatch(GameEvents::ACTION_SHOW_BOARD, new GameEvent($playerToken, $game));
        } catch (\InvalidArgumentException $e) {
            $this->dispatcher->dispatch(GameEvents::ACTION_ERROR, new GameEvent($playerToken, $game, ['message' => $e->getMessage()]));
            return;
        }

        // Game is still playable ?

        if (!$this->checkPlayability($game)) {
            return;
        }

        // Reverse player turn

        do {
            try {
                $bestCellChange = $this->ai->getBestCellChange($game);
                $this->manager->play($game, $bestCellChange);
            } catch (\InvalidArgumentException $e) {
                $this->dispatcher->dispatch(GameEvents::ACTION_ERROR, new GameEvent($playerToken, $game, ['message' => $e->getMessage()]));
            }
        } while (!$this->playerCanPlay($game, $game->getHumanPlayer()) || !$this->checkPlayability($game));

        // Game is still playable ?

        $this->checkPlayability($game);
    }

    public function checkPlayability(Game $game)
    {
        if ($game->getBoard()->isFull() || !$this->playerCanPlay($game, $game->getCurrentPlayer())) {
            $this->manager->finish($game);
            return false;
        }

        return true;
    }

    public function playerCanPlay(Game $game, Player $player)
    {
        $boardAnalyzer = new BoardAnalyzer($game->getBoard());
        return $boardAnalyzer->hasAvailableCellChanges($player->getCellType());
    }
}

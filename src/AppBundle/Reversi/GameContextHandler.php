<?php

namespace AppBundle\Reversi;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Reversi\Event\GameEvent;
use AppBundle\Reversi\Event\GameEvents;
use AppBundle\Reversi\Event\GameNotificationEvent;
use AppBundle\Reversi\GameManager;
use Reversi\BoardAnalyzer;
use Reversi\Model\Game;

class GameContextHandler
{

  private $manager;
  private $dispatcher;

  public function __construct(GameManager $manager, EventDispatcherInterface $dispatcher)
  {
    $this->manager = $manager;
    $this->dispatcher = $dispatcher;
  }

  public function handle(GameContext $context)
  {

    // Retrieve or create game from context
    // If game does not exist and message is not othello, explain how to play

    if(!($game = $this->manager->get($context))){
      if(strtolower($context->getMessage()) !== 'othello'){
        $this->dispatcher->dispatch(GameEvents::ACTION_EXPLAIN_START, new GameEvent($context->getPlayerToken()));
        return;
      }
      $game = $this->manager->create($context);
      $this->dispatcher->dispatch(GameEvents::ACTION_WELCOME, new GameEvent($context->getPlayerToken(), $game));
      $this->dispatcher->dispatch(GameEvents::ACTION_ASK_FOR_POSITION, new GameEvent($context->getPlayerToken(), $game));
      return;
    }

    // Close game on demand

    if(in_array($context->getMessage(), ['close', 'finish'])){
      $this->manager->finish($game);
      $this->dispatcher->dispatch(GameEvents::ACTION_FINISH, new GameEvent($context->getPlayerToken(), $game));
      return;
    }

    // It's not the turn of the context player

    if($game->getCurrentPlayer()->getToken() !== $context->getPlayerToken()){
      $this->dispatcher->dispatch(GameEvents::ACTION_NOT_YOUR_TURN, new GameEvent($context->getPlayerToken(), $game));
      return;
    }

    // It's current player turn
    // Attempt to apply user choice and AI choice

    if(!is_numeric($context->getMessage())){
      $this->dispatcher->dispatch(GameEvents::ACTION_INVALID_INPUT, new GameEvent($context->getPlayerToken(), $game));
      return;
    }

    try
    {
        $this->manager->playPosition($game, intval($context->getMessage()));
        $this->dispatcher->dispatch(GameEvents::ACTION_SHOW_BOARD, new GameEvent($context->getPlayerToken(), $game));

        // TO REPLACE WITH AI
        $boardAnalyzer = new BoardAnalyzer($game->getBoard());
        $currentPlayer = $game->getCurrentPlayer();
        $availableCellChanges = $boardAnalyzer->getAvailableCellChanges($currentPlayer->getCellType());
        $this->manager->play($game, $availableCellChanges[0]);

        $this->dispatcher->dispatch(GameEvents::ACTION_SHOW_BOARD, new GameEvent($context->getPlayerToken(), $game));
        $this->dispatcher->dispatch(GameEvents::ACTION_ASK_FOR_POSITION, new GameEvent($context->getPlayerToken(), $game));

    }
    catch(\Exception $e)
    {

        $this->dispatcher->dispatch(GameEvents::ACTION_ASK_FOR_POSITION, new GameEvent($context->getPlayerToken(), $game));

    }

    // TODO
    // Handle users can play or is full => Finished

    // If game is finished, show winner

    if($game->isFinished()){
      $this->dispatcher->dispatch(GameEvents::ACTION_FINISH, new GameEvent($context->getPlayerToken(), $game));
    }

  }

}

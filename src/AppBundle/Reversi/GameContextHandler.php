<?php

namespace AppBundle\Reversi;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Reversi\Event\GameEvent;
use AppBundle\Reversi\Event\GameEvents;
use AppBundle\Reversi\Event\GameNotificationEvent;
use AppBundle\Reversi\GameManager;
use Reversi\BoardManipulator;

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

    $origin = $context->getPlayerOrigin();
    $playerToken = $context->getPlayerToken();
    $message = $context->getMessage();

    // If game doesn't exist, Send welcome message

    $gameExist = $this->manager->exist($origin, $playerToken);

    if(!$gameExist && strtolower($message) === 'othello'){
      $game = $this->manager->create($origin, $playerToken);
      $this->dispatcher->dispatch(GameEvents::ACTION_WELCOME, new GameEvent($playerToken, $game));
    } elseif ($gameExist) {
      $game = $this->manager->get($origin, $playerToken);
    } else {
      $this->dispatcher->dispatch(GameEvents::ACTION_EXPLAIN_START, new GameEvent($playerToken));
      return;
    }

    // Manage game closing

    if(in_array($message, ['close', 'finish'])){
      $game->markAsFinished();
      $this->manager->save($game);
      $this->dispatcher->dispatch(GameEvents::ACTION_FINISH, new GameEvent($playerToken, $game));
      return;
    }

    // Manage playing

    if($game->getCurrentPlayer()->getToken() !== $playerToken){
      $this->dispatcher->dispatch(GameEvents::ACTION_NOT_YOUR_TURN, new GameEvent($playerToken, $game));
      return;
    } else {

      $boardManipulator = new BoardManipulator($game->getBoard());
      $availableCellChanges = $boardManipulator->getAvailableCellChanges($game->getCurrentPlayer()->getCellType());

      if(is_numeric($message) && in_array(intval($message), array_keys($availableCellChanges))){

        $boardManipulator->applyCellChange($availableCellChanges[$message]);
        $game->setBoard($boardManipulator->getBoard());
        $this->manager->save($game);
        
        $this->dispatcher->dispatch(GameEvents::ACTION_SHOW_BOARD, new GameEvent($playerToken, $game));

      } else {
        $this->dispatcher->dispatch(GameEvents::ACTION_ASK_FOR_POSITION, new GameEvent($playerToken, $game));
      }

    }

    if($game->isFinished()){
      $this->dispatcher->dispatch(GameEvents::ACTION_FINISH, new GameEvent($playerToken, $game));
    }

  }

}

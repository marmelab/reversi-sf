<?php

namespace AppBundle\Reversi;

use Doctrine\ORM\EntityManagerInterface;
use Reversi\Model\Game;
use Reversi\Model\Cell;
use Reversi\Model\Player;
use Reversi\BoardAnalyzer;
use AppBundle\Reversi\GameRepository;
use AppBundle\Reversi\GameContext;

class GameManager
{

  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {

    $this->entityManager = $entityManager;

  }

  public function exist(GameContext $context)
  {

    $gameRepository = $this->entityManager->getRepository('App:Game');

    $game = $gameRepository->findLastActiveGameByPlayerOriginAndToken(
      $context->getPlayerOrigin(),
      $context->getPlayerToken()
    );

    return $game !== null;

  }

  public function get(GameContext $context)
  {

    $gameRepository = $this->entityManager->getRepository('App:Game');

    return $gameRepository->findLastActiveGameByPlayerOriginAndToken(
      $context->getPlayerOrigin(),
      $context->getPlayerToken()
    );

  }

  public function create(GameContext $context)
  {

    $game = new Game();
    $game->addPlayer(new Player('Player 1', Cell::TYPE_BLACK, $context->getPlayerOrigin(), $context->getPlayerToken()));
    $game->addPlayer(new Player('Bot', Cell::TYPE_WHITE));

    $this->save($game);

    return $game;

  }

  public function save(Game $game)
  {

    $this->entityManager->persist($game);
    $this->entityManager->flush();

    return $this;

  }

  public function remove(Game $game)
  {

    $this->entityManager->remove($game);
    $this->entityManager->flush();

    return $this;

  }

  public function finish(Game $game)
  {

    $game->markAsFinished();
    $this->save($game);

    return $this;

  }

  public function playPosition(Game $game, $position)
  {

    $boardAnalyzer = new BoardAnalyzer($game->getBoard());
    $currentPlayer = $game->getCurrentPlayer();
    $availableCellChanges = $boardAnalyzer->getAvailableCellChanges($currentPlayer->getCellType());

    if(!array_key_exists($position, $availableCellChanges)){
      throw new \InvalidArgumentException('Invalid cell change position.');
    }

    $this->play($game, $availableCellChanges[$position]);

    return $this;

  }

  public function play(Game $game, Cell $cellChange)
  {

    $boardAnalyzer = new BoardAnalyzer($game->getBoard());

    if(!$boardAnalyzer->canApplyCellChange($cellChange)){
      list($x, $y) = $cellChange->getPosition();
      throw new InvalidCellChangeException(sprintf("You can't change cell at %d,%d", $x, $y));
    }

    $flipped = $boardAnalyzer->getFlippedCellsFromCellChange($cellChange);
    $game->setBoard($game->getBoard()->drawCells(array_merge($flipped, [$cellChange])));
    $game->switchPlayer();
    $this->save($game);

    return $this;

  }

}

<?php

namespace AppBundle\Reversi;

use Doctrine\ORM\EntityManagerInterface;
use Reversi\Model\Game;
use Reversi\Model\Cell;
use Reversi\Model\Player;
use Reversi\BoardManipulator;
use AppBundle\Reversi\GameRepository;

class GameManager
{

  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function exist($origin, $playerToken)
  {

    $gameRepository = $this->entityManager->getRepository('App:Game');
    return $gameRepository->findLastActiveGameByPlayerOriginAndToken($origin, $playerToken) !== null;

  }

  public function get($origin, $playerToken)
  {
    $gameRepository = $this->entityManager->getRepository('App:Game');
    return $gameRepository->findLastActiveGameByPlayerOriginAndToken($origin, $playerToken);
  }

  public function create($origin, $playerToken)
  {
    $game = new Game();
    $game->addPlayer(new Player('Player 1', Cell::TYPE_BLACK, $origin, $playerToken));
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

}

<?php

namespace AppBundle\Facebook;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Reversi\Event\GameEvent;
use AppBundle\Reversi\Event\GameEvents;

class FacebookContextActionSubscriber implements EventSubscriberInterface
{

  private $api;
  private $router;

  public function __construct(FacebookApi $api, RouterInterface $router)
  {
    $this->api = $api;
    $this->router = $router;
  }

  public static function getSubscribedEvents()
  {
      return array(
         GameEvents::ACTION_WELCOME => 'onWelcomeAction',
         GameEvents::ACTION_FINISH  => 'onFinishAction',
         GameEvents::ACTION_ASK_FOR_POSITION => 'onAskForPositionAction',
         GameEvents::ACTION_SHOW_BOARD => 'onShowBoardAction',
         GameEvents::ACTION_NOT_YOUR_TURN => 'onNotYourTurnAction',
         GameEvents::ACTION_EXPLAIN_START => 'onExplainHowToStartAction',
         GameEvents::ACTION_INVALID_INPUT => 'onInvalidInputAction'
      );
  }

  public function onWelcomeAction(GameEvent $e)
  {
    $this->api->sendMessage($e->getRecipient(), 'Welcome !');
  }

  public function onFinishAction(GameEvent $e)
  {
    $this->api->sendMessage($e->getRecipient(), 'The winner is !!!!');
  }

  public function onAskForPositionAction(GameEvent $e)
  {
    $game = $e->getGame();
    $this->api->sendMessage($e->getRecipient(),  [
      'attachment' => [
        'type' => 'image',
        'payload' => [
          'url' => $this->router->generate(
            'app_reversi_game_board_png',
            ['id' => $game->getId(), 'choices_for' => $game->getCurrentPlayer()->getCellType()],
            UrlGeneratorInterface::ABSOLUTE_URL
          ),
          'is_reusable' => false
        ]
      ]
    ]);
  }

  public function onShowBoardAction(GameEvent $e)
  {
    $game = $e->getGame();
    $this->api->sendMessage($e->getRecipient(),  [
      'attachment' => [
        'type' => 'image',
        'payload' => [
          'url' => $this->router->generate(
            'app_reversi_game_board_png',
            ['id' => $game->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
          ),
          'is_reusable' => false
        ]
      ]
    ]);
  }

  public function onNotYourTurnAction(GameEvent $e)
  {

    $this->api->sendMessage($e->getRecipient(), 'It\'s not your turn !!!');

  }

  public function onExplainHowToStartAction(GameEvent $e)
  {

    $this->api->sendMessage(
      $e->getRecipient(),
      "Nice to meet you.\nI only know how to play reversi. \nTape \"othello\" to see."
    );

  }

  public function onInvalidInputAction(GameEvent $e)
  {
    $this->api->sendMessage($e->getRecipient(), 'Your position choice is not valid.');
  }

}

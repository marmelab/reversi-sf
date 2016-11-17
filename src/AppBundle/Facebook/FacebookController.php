<?php

namespace AppBundle\Facebook;

use AppBundle\RequestHandler\RequestHandlerInterface;
use AppBundle\Facebook\FacebookApi;
use AppBundle\Reversi\GameContext;
use AppBundle\Reversi\GameContextAction;
use AppBundle\Reversi\GameContextActionResolver;
use AppBundle\Reversi\Event\GameEvents;
use AppBundle\Reversi\GameContextHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FacebookController
{

  private $api;
  private $gameContextHandler;

  public function __construct(FacebookApi $api, GameContextHandler $gameContextHandler)
  {
    $this->api = $api;
    $this->gameContextHandler = $gameContextHandler;
  }

  public function webhookAction(Request $request)
  {

      $query = $request->query;

      if($query->get('hub_mode') === 'subscribe' && $this->api->isValidVerifyToken($query->get('hub_verify_token'))){
        return new Response($query->get('hub_challenge'));
      }

      $message = json_decode($request->getContent(), true);
      $lastMessaging = null;

      if($message['object'] === 'page'){
       foreach($message['entry'] as $entry){
          foreach($entry['messaging'] as $messaging){
            $lastMessaging = $messaging;
          }
        }
      }

      if($lastMessaging){
        $this->gameContextHandler->handle(new GameContext(
          $lastMessaging['message']['text'],
          'facebook',
          $lastMessaging['sender']['id']
        ));
      }

      return new Response("");

  }

}

<?php

namespace AppBundle\Facebook;

use AppBundle\Reversi\GameContext;
use AppBundle\Reversi\GameContextHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        if ($query->get('hub_mode') === 'subscribe' && $this->api->isValidVerifyToken($query->get('hub_verify_token'))) {
            return new Response($query->get('hub_challenge'));
        }

        $message = json_decode($request->getContent(), true);
        $lastMessaging = null;

        if ($message['object'] === 'page') {
            foreach ($message['entry'] as $entry) {
                foreach ($entry['messaging'] as $messaging) {
                    $lastMessaging = $messaging;
                }
            }
        }

        if ($lastMessaging) {
            $context = new GameContext(
              $lastMessaging['message']['text'],
              'facebook',
              $lastMessaging['sender']['id']
            );
            $this->gameContextHandler->handle($context);
        }

        return new Response('');
    }
}

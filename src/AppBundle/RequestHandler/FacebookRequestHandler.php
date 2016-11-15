<?php

namespace AppBundle\RequestHandler;

use AppBundle\RequestHandler\RequestHandlerInterface;
use AppBundle\Facebook\FacebookApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FacebookRequestHandler implements RequestHandlerInterface
{

  private $api;

  public function __construct(FacebookApi $api)
  {
    $this->api = $api;
  }

  public function handle(Request $request)
  {

    $query = $request->query;

    if($query->get('hub_mode') === 'subscribe' && $this->api->isValidVerifyToken($query->get('hub_verify_token'))){
      return new Response($query->get('hub_challenge'));
    }

    $message = json_decode($request->getContent(), true);

     if($message['object'] === 'page'){
       foreach($message['entry'] as $entry){
          foreach($entry['messaging'] as $messaging){
              $this->api->sendMessage($messaging['sender']['id'], 'coucou');
          }
        }
     }

    return new Response("");

  }

}

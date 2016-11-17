<?php

namespace AppBundle\Facebook;

use GuzzleHttp\Client;

class FacebookApi
{

  const ENDPOINT = 'https://graph.facebook.com/v2.6/me/messages';

  const ACTION_MARK_SEEN  = 'mark_seen';
  const ACTION_TYPING_ON  = 'typing_on';
  const ACTION_TYPING_OFF = 'typing_off';

  private $config;
  private $client;

  public function __construct($config = [])
  {
    $this->config = $config;
    $this->client = new Client();
  }

  public function sendMessage($recipientId, $message)
  {
    $message = is_string($message) ? ['text' => $message] : $message;
    return $this->send($recipientId, ['message' => $message]);
  }

  public function sendAction($recipientId, $action)
  {
    return $this->send($recipientId, ['send_action' => $action]);
  }

  public function send($recipientId, $data)
  {
    $data = array_merge(['recipient' => ['id' => $recipientId]], $data);
    return $this->client->post($this->getTokenizedEnpoint(), ['json' => $data]);
  }

  public function isValidVerifyToken($token)
  {
    return $this->config['verify_token'] === $token;
  }

  private function getTokenizedEnpoint()
  {
    return self::ENDPOINT . '?access_token=' . $this->config['access_token'];
  }

}

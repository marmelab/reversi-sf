<?php

namespace AppBundle\Facebook;

use GuzzleHttp\Client;

class FacebookApi
{

  const ENDPOINT = 'https://graph.facebook.com/v2.6/me/messages';

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

    $data = [
      'recipient' => [
        'id' => $recipientId
      ],
      'message' => $message
    ];

    $this->client->post(self::ENDPOINT . '?access_token=' . $this->config['access_token'], ['json' => $data]);

  }

  public function isValidVerifyToken($token)
  {
    return $this->config['verify_token'] === $token;
  }

}

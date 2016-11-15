<?php

namespace AppBundle\RequestHandler;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\RequestHandler\RequestHandlerInterface;

interface RequestHandlerRegistryInterface
{

  public function all();

  public function has($identifier);

  public function register($identifier, RequestHandlerInterface $requestHandler);

  public function unregister($identifier);

  public function get($identifier);

}

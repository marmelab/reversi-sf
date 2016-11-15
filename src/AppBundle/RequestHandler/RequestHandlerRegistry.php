<?php

namespace AppBundle\RequestHandler;

use AppBundle\RequestHandler\RequestHandlerInterface;

class RequestHandlerRegistry implements RequestHandlerRegistryInterface
{

  private $requestHandlers;

  public function __construct()
  {
    $this->requestHandlers = [];
  }

  public function all()
  {
    return $this->requestHandlers;
  }

  public function register($identifier, RequestHandlerInterface $requestHandler)
  {
    if($this->has($identifier)){
        throw new \InvalidArgumentException(sprintf('RequestHandler "%s" is already registred.', $identifier));
    }
    $this->requestHandlers[$identifier] = $requestHandler;
    return $this;
  }

  public function has($identifier)
  {
    return array_key_exists($identifier, $this->requestHandlers);
  }

  public function get($identifier)
  {
    if(!$this->has($identifier)){
      throw new \InvalidArgumentException(sprintf('RequestHandler "%s" does not exist or is not registred.', $identifier));
    }
    return $this->requestHandlers[$identifier];
  }

  public function unregister($identifier)
  {
    if(!$this->has($identifier)){
      throw new \InvalidArgumentException(sprintf('RequestHandler "%s" does not exist or is not registred.', $identifier));
    }
    unset($this->requestHandlers[$identifier]);
    return $this;
  }

}

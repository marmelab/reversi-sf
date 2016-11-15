<?php

namespace AppBundle\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{

  public function handle(Request $request);

}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GuzzleHttp\Client;
use AppBundle\RequestHandler\FacebookRequestHandler;

class WebhookController extends Controller
{

    public function indexAction($handler, Request $request){

      $requestHandlerRegistry = $this->get('app.request_handler.registry');

      if(!$requestHandlerRegistry->has($handler)){
        throw new NotFoundHttpException();
      }

      try{
        return $requestHandlerRegistry->get($handler)->handle($request);
      }
      catch(\Exception $e){
        throw new NotFoundHttpException();
      }

    }

}

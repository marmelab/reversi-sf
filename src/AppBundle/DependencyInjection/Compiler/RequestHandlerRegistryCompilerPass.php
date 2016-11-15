<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RequestHandlerRegistryCompilerPass implements CompilerPassInterface
{

  public function process(ContainerBuilder $container)
  {

     if (!$container->has('app.request_handler.registry')) {
         return;
     }

     $registry = $container->findDefinition('app.request_handler.registry');
     $requestHandlers = $container->findTaggedServiceIds('app.request_handler');

     foreach ($requestHandlers as $id => $requestHandler) {
       foreach ($requestHandler as $attributes) {
          $registry->addMethodCall('register', array(
            $attributes['alias'],
            new Reference($id)
          ));
       }

     }

  }

}

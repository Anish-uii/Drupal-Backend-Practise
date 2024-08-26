<?php

namespace Drupal\my_routing_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class RoutingController extends ControllerBase {

  public function routingCustomPage(){
    return [
      '#type' => 'markup',
      '#markup' => t('This is my module for managing routing for my custom module.'),
    ];
  }

  public function customParameter($param) {
    return [
      '#type' => 'markup',
      '#markup' => t('This is the value revcieved from the URL : @param', ['@param' => $param]),
    ];
  }
}
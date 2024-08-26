<?php

namespace Drupal\my_routing_module\EventSubscriber;

use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Check if the route exists before altering.
    if ($route = $collection->get('my_routing_module.custom_page')) {
      // Remove access for the 'content_editor' role.
      $route->setRequirement('_custom_access', '\Drupal\my_routing_module\Access\RoutingAccessCheck::accessWithoutEditor');
    }
  }
}

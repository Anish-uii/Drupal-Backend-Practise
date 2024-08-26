<?php

namespace Drupal\custom_welcome\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

class CustomWelcomeSubscriber implements EventSubscriberInterface {

  protected $currentUser;
  protected $routeMatch;

  public function __construct(AccountInterface $current_user, RouteMatchInterface $route_match) {
    $this->currentUser = $current_user;
    $this->routeMatch = $route_match;
  }

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequest', 30];
    return $events;
  }

  public function onKernelRequest(RequestEvent $event) {
    $request = $event->getRequest();
    if ($this->currentUser->isAuthenticated() && $this->routeMatch->getRouteName() == 'user.login') {
      $response = new RedirectResponse('/custom-welcome-page');
      $event->setResponse($response);
    }
  }

}

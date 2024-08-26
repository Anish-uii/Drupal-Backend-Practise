<?php

namespace Drupal\my_routing_module\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;

class RoutingAccessCheck implements AccessInterface {

  /**
   * Custom access check for the route.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function access(AccountInterface $account) {
    // Allow access for users with the administrator or editor roles.
    if ( ($account->hasPermission('access the routing custom page')) || ( ($account->hasRole('administrator')) || ($account->hasRole('content_editor')) ) ) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

  public function accessWithoutEditor(AccountInterface $account) {
    // Allow access for users with the administrator role, but not editor.
    if ($account->hasRole('administrator')) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }
}

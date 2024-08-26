<?php

namespace Drupal\block_hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\block\Entity\Block;

class CustomWelcomeController extends ControllerBase {

  /**
   * Returns a render array for the custom welcome page.
   */
  public function content() {
    // Load the block plugin by its machine name (id).
    $block = \Drupal::entityTypeManager()
      ->getStorage('block')
      ->load('welcome_block');

    // Build the block content.
    $block_content = \Drupal::entityTypeManager()
      ->getViewBuilder('block')
      ->view($block);

    return [
      '#markup' => render($block_content),
    ];
  }
}

<?php

namespace Drupal\custom_events\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\node\NodeInterface;

/**
 * Event that is fired when a node is viewed.
 */
class NodeViewEvent extends Event {

  // This makes it easier for subscribers to reliably use our event name.
  const EVENT_NAME = 'custom_events_node_view';

  /**
   * The node being viewed.
   *
   * @var \Drupal\node\NodeInterface
   */
  public $node;

  /**
   * Constructs the object.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node being viewed.
   */
  public function __construct(NodeInterface $node) {
    $this->node = $node;
  }

}

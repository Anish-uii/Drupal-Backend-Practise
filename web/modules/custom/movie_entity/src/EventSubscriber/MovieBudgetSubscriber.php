<?php

namespace Drupal\movie_entity\EventSubscriber;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\node\NodeInterface;
use Drupal\node\NodeEvents;
use Drupal\node\Event\NodeViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listens to node view events.
 */
class MovieBudgetSubscriber implements EventSubscriberInterface {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a MovieBudgetSubscriber object.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[NodeEvents::NODE_VIEW][] = 'onNodeView';
    return $events;
  }

  /**
   * Responds to node view events.
   *
   * @param \Drupal\node\Event\NodeViewEvent $event
   *   The node view event.
   */
  public function onNodeView(NodeViewEvent $event) {
    $node = $event->getNode();

    if ($node->getType() === 'movie') {
      $config = \Drupal::config('movie_entity.settings');
      $budget_friendly_amount = $config->get('budget_friendly_amount');

      $movie_price = $node->get('price')->value;

      if ($budget_friendly_amount > $movie_price) {
        $this->messenger->addMessage('The movie is under budget', MessengerInterface::TYPE_STATUS);
      }
      elseif ($budget_friendly_amount < $movie_price) {
        $this->messenger->addMessage('The movie is over budget', MessengerInterface::TYPE_WARNING);
      }
      else {
        $this->messenger->addMessage('The movie is within budget', MessengerInterface::TYPE_INFO);
      }
    }
  }
}

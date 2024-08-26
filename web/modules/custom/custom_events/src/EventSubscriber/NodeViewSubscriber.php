<?php

namespace Drupal\custom_events\EventSubscriber;

use Drupal\custom_events\Event\NodeViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Datetime\DateFormatterInterface;

/**
 * Class NodeViewSubscriber.
 *
 * @package Drupal\custom_events\EventSubscriber
 */
class NodeViewSubscriber implements EventSubscriberInterface {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new NodeViewSubscriber object.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(MessengerInterface $messenger, DateFormatterInterface $date_formatter) {
    $this->messenger = $messenger;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      NodeViewEvent::EVENT_NAME => 'onNodeView',
    ];
  }

  /**
   * Subscribe to the node view event dispatched.
   *
   * @param \Drupal\custom_events\Event\NodeViewEvent $event
   *   Our custom event object.
   */
  public function onNodeView(NodeViewEvent $event) {
    $node = $event->node;
    // Check if the node is of type 'movie'.
    if ($node->getType() === 'movie') {
      $amount = $node->get('price')->value;
      $config = \Drupal::config('movie_entity.settings');
      $budget = $config->get('budget_friendly_amount');

      if ($amount > $budget) {
        $message = t('The movie is over budget.');
      }
      elseif ($amount < $budget) {
        $message = t('The movie is under budget.');
      }
      else {
        $message = t('The movie is within budget.');
      }

      $this->messenger->addStatus($message);
    }
  }

}

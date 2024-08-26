<?php
/**
 * @file
 * Contains \Drupal\dependency_inject_services\Plugin\Block\CustomBlock.
 */

namespace Drupal\dependency_inject_services\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dependency_inject_services\services\DatabaseInsert;

/**
 * Provides a 'Custom' block.
 *
 * @Block(
 *   id = "dependency_injection_services",
 *   admin_label = @Translation("Services and dependency injection"),
 *   category = @Translation("Custom block example")
 * )
 */
class CustomBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The DatabaseInsert service.
   *
   * @var \Drupal\dependency_inject_services\services\DatabaseInsert
   */
  protected $databaseInsert;

  /**
   * Constructs a CustomBlock object.
   *
   * @param \Drupal\dependency_inject_services\services\DatabaseInsert $databaseInsert
   *   The database insert service for data insertion and fetching.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DatabaseInsert $databaseInsert) {
    $this->databaseInsert = $databaseInsert;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dependency_inject_services')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = $this->databaseInsert->getData();
    return [
      '#theme' => 'my_template',
      '#data' => $data,
    ];
  }
}

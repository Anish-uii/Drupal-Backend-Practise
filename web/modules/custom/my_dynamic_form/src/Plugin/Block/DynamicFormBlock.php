<?php

namespace Drupal\my_dynamic_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;

/**
 * Provides a 'DynamicFormBlock' block.
 *
 * @Block(
 *   id = "dynamic_form_block",
 *   admin_label = @Translation("Dynamic Form Block"),
 * )
 */
class DynamicFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = '<div class="flagship-programmes">';
    $output .= '<h2>FLAGSHIP PROGRAMMES</h2>';
    
    $output .= '<div class="programmes-container">';
    
    $results = Database::getConnection()->select('dynamic_form_data', 'd')
      ->fields('d')
      ->execute()
      ->fetchAll();

    foreach ($results as $result) {
      $output .= '<div class="programme">';
      $output .= '<h3>' . $result->group_name . '</h3>';
      $output .= '<div class="label">' . $result->first_label_name . '</div>';
      $output .= '<div class="value">' . $result->first_label_value . '</div>';
      $output .= '<div class="label">' . $result->second_label_name . '</div>';
      $output .= '<div class="value">' . $result->second_label_value . '</div>';
      $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '</div>';

    return [
      '#markup' => $output,
      '#attached' => [
        'library' => [
          'my_dynamic_form/dynamic_form_styles',
        ],
      ],
    ];
  }

}

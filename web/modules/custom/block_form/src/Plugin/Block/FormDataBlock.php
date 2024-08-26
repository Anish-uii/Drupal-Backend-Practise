<?php

namespace Drupal\block_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a block with the form data.
 *
 * @Block(
 *   id = "form_data_block",
 *   admin_label = @Translation("Form Data Block"),
 * )
 */
class FormDataBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $query = Database::getConnection()->select('flagship_form', 'f')
      ->fields('f')
      ->orderBy('id', 'DESC')
      ->range(0, 5)
      ->execute();
    
    $rows = $query->fetchAllAssoc('id');

    $data = [];
    foreach ($rows as $row) {
      $data[] = [
        'name' => $row->name,
        'label_1' => $row->label_1,
        'value_1' => $row->value_1,
        'label_2' => $row->label_2,
        'value_2' => $row->value_2,
      ];
    }

    return [
      '#theme' => 'flagship_custom',
      '#data' => $data,
    ];
  }
}

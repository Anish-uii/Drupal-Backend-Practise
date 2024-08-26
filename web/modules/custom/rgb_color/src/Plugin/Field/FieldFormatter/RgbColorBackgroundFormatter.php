<?php

namespace Drupal\rgb_color\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'rgb_color_background' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_color_background",
 *   label = @Translation("Display Background Color"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorBackgroundFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $color_value = $item->value;

      if (!empty($color_value) && strpos($color_value, '#') !== 0) {
        $color_value = '#' . $color_value;
      }
      
      $elements[$delta] = [
        '#type' => 'inline_template',
        '#template' => '<div style="background-color: {{ color }}; padding: 10px;">{{ color }}</div>',
        '#context' => ['color' => $color_value],
      ];
      
    }

    return $elements;
  }
}

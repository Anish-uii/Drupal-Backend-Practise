<?php

namespace Drupal\rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'rgb_color_color_picker' widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_color_picker",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorColorPickerWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = [
      '#type' => 'color',
      '#title' => $this->t('Color Picker'),
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : '',
      '#description' => $this->t('Select the color.'),
    ];

    return $element;
  }
}

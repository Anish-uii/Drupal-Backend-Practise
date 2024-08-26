<?php

namespace Drupal\rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Plugin implementation of the 'rgb_color_hex' widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_hex",
 *   label = @Translation("Hex Color Code"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorHexWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hex Color Code'),
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : '',
      '#description' => $this->t('Enter the color in hex format (e.g., #FF5733).'),
    ];

    return $element;
  }
}

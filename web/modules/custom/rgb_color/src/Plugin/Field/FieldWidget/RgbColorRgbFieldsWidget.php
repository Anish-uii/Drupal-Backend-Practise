<?php

namespace Drupal\rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'rgb_color_rgb_fields' widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_rgb_fields",
 *   label = @Translation("RGB Fields"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorRgbFieldsWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Extract existing color value and convert to RGB components.
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '#000000';
    $red = hexdec(substr($value, 1, 2));
    $green = hexdec(substr($value, 3, 2));
    $blue = hexdec(substr($value, 5, 2));

    $element['red'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Red'),
      '#default_value' => $red,
      '#description' => $this->t('Enter the red component (0-255).'),
    ];

    $element['green'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Green'),
      '#default_value' => $green,
      '#description' => $this->t('Enter the green component (0-255).'),
    ];

    $element['blue'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Blue'),
      '#default_value' => $blue,
      '#description' => $this->t('Enter the blue component (0-255).'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function extractFormValues(FieldItemListInterface $items, array $form, FormStateInterface $form_state) {
    $values = $form_state->getValue($this->fieldDefinition->getName());
    $red = str_pad(dechex($values['red']), 2, '0', STR_PAD_LEFT);
    $green = str_pad(dechex($values['green']), 2, '0', STR_PAD_LEFT);
    $blue = str_pad(dechex($values['blue']), 2, '0', STR_PAD_LEFT);

    // Combine into a single hex color value.
    $items[0]->value = '#' . $red . $green . $blue;
  }
}

<?php

namespace Drupal\rgb_color\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface; 
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'rgb_color' field type.
 *
 * @FieldType(
 *   id = "rgb_color",
 *   label = @Translation("RGB Color"),
 *   description = @Translation("Stores an RGB color value."),
 *   default_widget = "rgb_color_hex",
 * )
 */
class RgbColorItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_storage) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 7,
          'not null' => FALSE,
        ],
      ],
      'indexes' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return empty($value);
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'max_length' => 7,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_storage_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('RGB Color Value'))
      ->setDescription(t('The RGB color value as a hex string.'))
      ->setRequired(TRUE);

    return $properties;
  }
}

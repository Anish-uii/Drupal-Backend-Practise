<?php

namespace Drupal\block_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class FlagshipDynamic extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'flagship_dynamic';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $group_count = $form_state->get('group_count');

    if ($group_count === NULL) {
      $form_state->set('group_count', 1);
      $group_count = 1;
    }
    $form['#attached']['library'][] = 'block_form/form_css';

    $form['groups'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Groups'),
      '#prefix' => '<div id="group-wrapper">',
      '#suffix' => '</div>',
      '#tree' => TRUE,
    ];

    for ($i = 0; $i < $group_count; $i++) {
      $form['groups'][$i] = [
        '#type' => 'details',
        '#title' => $this->t('Group @number', ['@number' => $i + 1]),
        '#open' => TRUE,
      ];
      $form['groups'][$i]['group_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the group'),
        '#default_value' => '',
        '#required' => TRUE,
      ];
      $form['groups'][$i]['label_1'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 1st label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];
      $form['groups'][$i]['value_1'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 1st value of 1st label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];
      $form['groups'][$i]['label_2'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 2nd label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];
      $form['groups'][$i]['value_2'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 2nd value of 2nd label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];
    }

    $form['add_group'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::updateForm',
        'wrapper' => 'group-wrapper',
      ],
    ];

    if ($group_count > 1) {
      $form['groups']['remove_group'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::removeOne'],
        '#ajax' => [
          'callback' => '::updateForm',
          'wrapper' => 'group-wrapper',
        ],
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function updateForm(array &$form, FormStateInterface $form_state) {
    return $form['groups'];
  }

  public function addOne(array &$form, FormStateInterface $form_state) {
    $group_count = $form_state->get('group_count');
    $add_button = $group_count + 1;
    $form_state->set('group_count', $add_button);
    $form_state->setRebuild();
  }

  public function removeOne(array &$form, FormStateInterface $form_state) {
    $group_count = $form_state->get('group_count');
    if ($group_count > 1) {
      $remove_button = $group_count - 1;
      $form_state->set('group_count', $remove_button);
    }
    $form_state->setRebuild();
  }
  
  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();

    $groups = $form_state->getValue('groups');

    foreach ($groups as $group_key => $group) {
      if (is_array($group) && isset($group['group_name'], $group['label_1'], $group['value_1'], $group['label_2'], $group['value_2'])) {
        $formData = [
          'name' => $group['group_name'],
          'label_1' => $group['label_1'],
          'value_1' => $group['value_1'],
          'label_2' => $group['label_2'],
          'value_2' => $group['value_2'],
        ];

        $conn->insert('flagship_form')
          ->fields($formData)
          ->execute();
      }
    }
    $this->messenger()->addMessage($this->t('The form has been submitted.'));
  }
}

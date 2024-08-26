<?php

namespace Drupal\my_dynamic_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class DynamicForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dynamic_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $num_groups = $form_state->get('num_groups');
    if (is_null($num_groups)) {
      $num_groups = 1;
      $form_state->set('num_groups', $num_groups);
    }

    $form['#tree'] = TRUE;

    $form['groups'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Groups'),
      '#prefix' => '<div id="groups-wrapper">',
      '#suffix' => '</div>',
    ];

    for ($i = 0; $i < $num_groups; $i++) {
      $form['groups'][$i] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Group') . ' ' . ($i + 1),
      ];

      $form['groups'][$i]['group_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the group'),
        '#required' => TRUE,
      ];

      $form['groups'][$i]['first_label_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 1st label'),
        '#required' => TRUE,
      ];

      $form['groups'][$i]['first_label_value'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Value of the 1st label'),
        '#required' => TRUE,
      ];

      $form['groups'][$i]['second_label_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 2nd label'),
        '#required' => TRUE,
      ];

      $form['groups'][$i]['second_label_value'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Value of the 2nd label'),
        '#required' => TRUE,
      ];

      if ($i > 0) {
        $form['groups'][$i]['remove_group'] = [
          '#type' => 'submit',
          '#value' => $this->t('Remove'),
          '#submit' => ['::removeGroup'],
          '#ajax' => [
            'callback' => '::addmoreCallback',
            'wrapper' => 'groups-wrapper',
          ],
        ];
      }
    }

    $form['add_group'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'groups-wrapper',
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValue('groups') as $group) {
      // Save each group to the database.
      \Drupal::database()->insert('dynamic_form_data')
        ->fields([
          'group_name' => $group['group_name'],
          'first_label_name' => $group['first_label_name'],
          'first_label_value' => $group['first_label_value'],
          'second_label_name' => $group['second_label_name'],
          'second_label_value' => $group['second_label_value'],
        ])
        ->execute();
    }

    // Display a message to the user.
    \Drupal::messenger()->addMessage($this->t('Form has been submitted and data has been saved.'));
  }

  /**
   * AJAX callback handler to add more groups.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['groups'];
  }

  /**
   * Custom submit handler to add one more group.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $num_groups = $form_state->get('num_groups');
    $add_button = $num_groups + 1;
    $form_state->set('num_groups', $add_button);
    $form_state->setRebuild();
  }

  /**
   * Custom submit handler to remove a group.
   */
  public function removeGroup(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $parents = $triggering_element['#parents'];
    array_pop($parents);
    $group_index = array_pop($parents);

    $num_groups = $form_state->get('num_groups');
    if ($num_groups > 1) {
      $form_state->set('num_groups', $num_groups - 1);
    }

    $form_state->unsetValue(['groups', $group_index]);
    $form_state->setRebuild();
  }

}

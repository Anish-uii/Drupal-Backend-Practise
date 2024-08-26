<?php

namespace Drupal\block_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Database\Database;

/**
 * Provides a form with dynamic field groups.
 */
class FlagshipProgrammes extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'form_module_example_ajax';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#attached']['library'][] = 'block_form/form_css';

    // Container for dynamic field groups.
    $form['groups'] = [
      '#type' => 'container',
      '#tree' => TRUE,
    ];
    
    $form['groups']['group'] = [
      '#type' => 'container',
      '#prefix' => '<div id="group-wrapper">',
      '#suffix' => '</div>',
    ];

    // Initialize group count in form state.
    if (!$form_state->get('group_count')) {
      $form_state->set('group_count', 1);
    }

    // Add existing groups.
    $this->addGroups($form, $form_state);

    // Add more and remove buttons.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['add_more'] = [
      '#type' => 'button',
      '#value' => $this->t('Add More'),
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'event' => 'click',
      ],
    ];

    $form['actions']['remove_last'] = [
      '#type' => 'button',
      '#value' => $this->t('Remove Last'),
      '#ajax' => [
        'callback' => '::removeLastCallback',
        'event' => 'click',
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Adds existing groups to the form.
   */
  protected function addGroups(array &$form, FormStateInterface $form_state) {
    $group_count = $form_state->get('group_count');

    for ($i = 1; $i <= $group_count; $i++) {
      $form['groups']['group']['group_' . $i] = [
        '#type' => 'container',
        '#prefix' => '<div class="group-item" id="group-' . $i . '">',
        '#suffix' => '</div>',
      ];

      $form['groups']['group']['group_' . $i]['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the group'),
        '#default_value' => '',
        '#required' => TRUE,
      ];

      $form['groups']['group']['group_' . $i]['label_1'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 1st label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];

      $form['groups']['group']['group_' . $i]['value_1'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Value of 1st label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];

      $form['groups']['group']['group_' . $i]['label_2'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name of the 2nd label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];

      $form['groups']['group']['group_' . $i]['value_2'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Value of 2nd label'),
        '#default_value' => '',
        '#required' => TRUE,
      ];
    }
  }

  /**
   * AJAX callback to add more groups.
   */
  public function addMoreCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    // Increment the group count.
    $group_count = $form_state->get('group_count');
    $form_state->set('group_count', $group_count + 1);

    // Add new group.
    $this->addGroups($form, $form_state);

    // Update the group wrapper with the new group.
    $response->addCommand(new ReplaceCommand('#group-wrapper', $form['groups']['group']));

    return $response;
  }

  /**
   * AJAX callback to remove the last group.
   */
  public function removeLastCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $wrapper_id = '#group-wrapper';

    // Get the groups container.
    $group_wrapper = $form['groups']['group'];

    // Get the current group count.
    $group_count = $form_state->get('group_count');

    if ($group_count > 1) {
      // Remove the last group.
      $last_group_id = 'group_' . $group_count;
      unset($group_wrapper[$last_group_id]);

      // Update the group count.
      $form_state->set('group_count', $group_count - 1);

      // Update the HTML.
      $response->addCommand(new ReplaceCommand($wrapper_id, $group_wrapper));
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $conn = Database::getConnection();
    
    // Retrieve all form values.
    $form_values = $form_state->getValue('groups');
    
    // Process each group.
    foreach ($form_values['group'] as $group) {
      $formData = [
        'name' => $group['name'],
        'label_1' => $group['label_1'],
        'value_1' => $group['value_1'],
        'label_2' => $group['label_2'],
        'value_2' => $group['value_2'],
      ];
      
      $conn->insert('flagship_form')
        ->fields($formData)
        ->execute();
    }

    $this->messenger()->addStatus($this->t('The data has been sent.'));
  }
}

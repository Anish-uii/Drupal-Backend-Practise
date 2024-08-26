<?php

namespace Drupal\movie_entity\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MovieBudgetConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['movie_entity.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'movie_budget_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('movie_entity.settings');

    $form['budget_friendly_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Budget-friendly Amount'),
      '#description' => $this->t('Set the budget-friendly amount for movies.'),
      '#default_value' => $config->get('budget_friendly_amount') ?? 0,
      '#min' => 0,
      '#step' => 0.5,
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('movie_entity.settings')
      ->set('budget_friendly_amount', $form_state->getValue('budget_friendly_amount'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}

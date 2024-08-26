<?php

declare(strict_types=1);

namespace Drupal\movie_awards\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\movie_awards\Entity\MovieAwards;

/**
 * Movie awards form.
 */
final class MovieAwardsForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {

    $form = parent::form($form, $form_state);

    // Label field
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#required' => TRUE,
    ];

    // ID field
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => [MovieAwards::class, 'load'],
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    // Status field
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $this->entity->status(),
    ];

    // Description field
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $this->entity->get('description'),
    ];

    // Movie Title field
    $form['movie_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Movie Title'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->get('movie_title'),
      '#required' => TRUE,
    ];

    // Movie Year field
    $form['movie_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Movie Year'),
      '#default_value' => $this->entity->get('movie_year'),
      '#required' => TRUE,
      '#min' => 1900, // Example minimum value
      '#max' => date('Y'), // Example maximum value (current year)
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $this->messenger()->addStatus(
      match($result) {
        \SAVED_NEW => $this->t('Created new movie award %label.', $message_args),
        \SAVED_UPDATED => $this->t('Updated movie award %label.', $message_args),
      }
    );
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}


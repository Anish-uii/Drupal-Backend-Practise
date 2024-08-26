<?php

declare(strict_types=1);

namespace Drupal\form_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Provides a form_module form.
 */
final class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'form_module_example';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['fullName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter your full name..'),
      '#required' => TRUE,
      '#maxlength' => 50,
    ];
    $form['phoneNumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter your Phone Number..'),
      '#required' => TRUE,
      '#maxlength' => 10,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Enter your email id..'),
      '#required' => TRUE,
      '#maxlength' => 100,
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Select your gender: '),
      '#required' => TRUE,
      '#options' => [
        '1' => $this->t('Male'),
        '2' => $this->t('Female'),
        '3' => $this->t('Others'),
      ],
      '#default_value' => '1',
    ];
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $formField = $form_state->getValues();

    $fullName = trim($formField['fullName']);
    $phoneNumber = trim($formField['phoneNumber']);
    $email = trim($formField['email']);

    if (!preg_match("/^([a-zA-Z' ]+)$/", $fullName)) {
      $form_state->setErrorByName('fullName', $this->t('Enter a valid name.'));
    }

    if (!preg_match("/^[6-9][0-9]{9}$/", $phoneNumber)) {
      $form_state->setErrorByName('phoneNumber', $this->t('Enter a valid phone Number.'));
    }

    if (!\Drupal::service('email.validator')->isValid($email)) {
      $form_state->setErrorByName('email', $this->t('Enter a valid email address.'));
    }

    if (substr($email, -4) !== '.com') {
      $form_state->setErrorByName('email', $this->t('Email address must end with .com.'));
    }

    $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com', 'hotmail.com'];
    $email_domain = substr(strrchr($email, "@"), 1);
    if (!in_array($email_domain, $public_domains)) {
      $form_state->setErrorByName('email', $this->t('This email address domain is not allowed.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $conn = Database::getConnection();

    $formField = $form_state->getValues();

    $formData = [
      'fullname' => $formField['fullName'],
      'phonenumber' => $formField['phoneNumber'],
      'email' => $formField['email'],
      'gender' => $formField['gender'],
    ];

    $conn->insert('user')
      ->fields($formData)
      ->execute();

    $this->messenger()->addStatus($this->t('Registered successfully!!'));
    $form_state->setRedirect('form_module.example');
  }
}

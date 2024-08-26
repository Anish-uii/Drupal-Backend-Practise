<?php

declare(strict_types=1);

namespace Drupal\form_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;

/**
 * Provides a form_module form.
 */
final class UserAjaxForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'user_ajax';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['element'] = [
      '#type' => 'markup',
      '#markup' => "<div class='success_message'></div>",
    ];
    $form['fullName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter your full name..'),
      '#required' => TRUE,
      '#maxlength'=> 50,
    ];
    $form['phoneNumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter your Phone Number..'),
      '#required' => TRUE,
      '#maxlength'=> 10,
    ];
    $form['emailID'] = [
      '#type' => 'email',
      '#title' => $this->t('Enter your email id..'),
      '#required' => TRUE,
      '#maxlength'=> 100,
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Select your gender: '),
      '#required' => TRUE,
      '#options' => [
        'option1' => $this->t('Male'),
        'option2' => $this->t('Female'),
        'option3' => $this->t('Others'),
      ],
      '#default_value' => 'option1',
    ];
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
        '#ajax' => [
          'callback' => '::submitData',
          'wrapper' => 'form-wrapper',
        ],
      ],
    ];

    $form['#prefix'] = '<div id="form-wrapper">';
    $form['#suffix'] = '</div>';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $fullName = trim($form_state->getValue('fullName'));
    $phoneNumber = trim($form_state->getValue('phoneNumber'));
    $email = trim($form_state->getValue('emailID'));

    if (!preg_match("/^([a-zA-Z' ]+)$/", $fullName)) {
      $form_state->setErrorByName('fullName', $this->t('Enter a valid name.'));
    }

    if (!preg_match("/^[6-9][0-9]{9}+$/", $phoneNumber)) {
      $form_state->setErrorByName('phoneNumber', $this->t('Enter a valid phone Number.'));
    }

    if (!\Drupal::service('email.validator')->isValid($email)) {
      $form_state->setErrorByName('emailID', $this->t('Enter a valid email address.'));
    }

    if (substr($email, -4) !== '.com') {
      $form_state->setErrorByName('emailID', $this->t('Email address must end with .com.'));
    }

    $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com', 'hotmail.com'];
    
    $email_domain = substr(strrchr($email, "@"), 1);
    if (!in_array($email_domain, $public_domains)) {
     $form_state->setErrorByName('emailID', $this->t('This email address domain is not allowed.'));
    }
  }

  /**
   * AJAX callback for form submission.
   */
  public function submitData(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();

    if ($form_state->hasAnyErrors()) {
      // If there are errors, prevent submission and show error messages.
      $errors = $form_state->getErrors();
      $error_message = '';
      foreach ($errors as $name => $message) {
        $error_message .= "<div class='error'><h3><i>$message</i></h3></div>";
      }
      $response->addCommand(new HtmlCommand('.success_message', $error_message));
      $response->addCommand(new InvokeCommand('#form-wrapper', 'reset')); // Reset the form state to show errors
    } else {
      // Handle the form submission.
      $message = $this->t('Form submitted successfully!');
      $response->addCommand(new HtmlCommand('.success_message', $message));
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // No need to implement this function for AJAX submission.
    $conn = Database::getConnection();

      $formField = $form_state->getValues();

      $formData = [
        'fullname' => $formField['fullName'],
        'phonenumber' => $formField['phoneNumber'],
        'email' => $formField['emailID'],
        'gender' => $formField['gender'],
      ];

      $conn->insert('user')
        ->fields($formData)
        ->execute();
  }

}

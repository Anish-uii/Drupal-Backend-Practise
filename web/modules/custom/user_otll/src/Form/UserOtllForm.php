<?php

namespace Drupal\user_otll\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

class UserOtllForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_otll_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_id'] = [
      '#type' => 'number',
      '#title' => $this->t('User ID'),
      '#description' => $this->t('Enter the User ID for which you want to generate a one-time login link.'),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate OTLL'),
      '#ajax' => [
        'callback' => '::ajaxSubmitHandler',
        'wrapper' => 'otll-result-wrapper',
      ],
    ];

    $form['result'] = [
      '#type' => 'markup',
      '#markup' => '<div id="otll-result-wrapper"></div>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // This function is required but not used since we're handling the submission with AJAX.
  }

  /**
   * AJAX submit handler.
   */
  public function ajaxSubmitHandler(array &$form, FormStateInterface $form_state) {
    $response = new \Drupal\Core\Ajax\AjaxResponse();
    $user_id = $form_state->getValue('user_id');
    $user = User::load($user_id);

    if ($user) {
      $otll = user_pass_reset_url($user);
      $message = $this->t('The one-time login link for User ID @uid is: <a href="@link" target="_blank">@link</a>', [
        '@uid' => $user_id,
        '@link' => $otll,
      ]);
    }
    else {
      $message = $this->t('No user found with User ID @uid.', ['@uid' => $user_id]);
    }

    $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#otll-result-wrapper', $message));
    return $response;
  }

}

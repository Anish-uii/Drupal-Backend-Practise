<?php
declare(strict_types=1);

namespace Drupal\form_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Database\Database;

/**
 * Provides a form_module form.
 */
final class UserRegisterAjaxForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId(): string {
      return 'user_register_ajax';
    }
  
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array {
  
      $form['element'] = [
        '#type' => 'markup',
        '#markup' => "<div class='success_message'></div>",
      ];
      $form['fullname'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Enter your full name..'),
        // '#required' => TRUE,
        '#maxlength'=> 50,
        '#suffix' => '<div class="error" id="fullname"></div>',
      ];
      $form['phonenumber'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Enter your Phone Number..'),
        // '#required' => TRUE,
        '#maxlength'=> 10,
        '#suffix' => '<div class="error" id="phonenumber"></div>',
      ];
      $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('Enter your email id..'),
        // '#required' => TRUE,
        '#maxlength'=> 100,
        '#suffix' => '<div class="error" id="email"></div>',
      ];
      $form['gender'] = [
        '#type' => 'radios',
        '#title' => $this->t('Select your gender: '),
        '#required' => TRUE,
        '#suffix' => '<div class="error" id="gender"></div>',
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
      $form ['#attached']['library'][] = 'form_module/user_css';
      $form ['#attached']['library'][] = 'form_module/user_js';
      return $form;
    }

    /**
     * AJAX callback for form submission.
     */
    public function submitData(array &$form, FormStateInterface $form_state): AjaxResponse {
      $response = new AjaxResponse();
      
      $conn = Database::getConnection();

      $formField = $form_state->getValues();
      
      $flag = TRUE;
      if (trim($formField['fullname']) == '' ){
        $flag = FALSE;
        return $response->addCommand(new HtmlCommand('#fullname', 'Please enter correct name.'));
      }
      if (trim($formField['phonenumber']) == '' ){
        $flag = FALSE;
        return $response->addCommand(new HtmlCommand('#phonenumber', 'Please enter correct phonenumber.'));
      }
      if (trim($formField['email']) == '' ){
        $flag = FALSE;
        return $response->addCommand(new HtmlCommand('#email', 'Please enter correct email id.'));
      }

      if ($flag) {
      $formData = [
        'fullname' => $formField['fullname'],
        'phonenumber' => $formField['phonenumber'],
        'email' => $formField['email'],
        'gender' => $formField['gender'],
      ];
  
      $conn->insert('user')
        ->fields($formData)
        ->execute();
      $response->addCommand(new InvokeCommand('#edit-fullname','val',['']));      
      $response->addCommand(new InvokeCommand('#edit-phonenumber','val',['']));
      $response->addCommand(new InvokeCommand('#edit-email','val',['']));

      $response->addCommand(new HtmlCommand('.success_message', 'Form submitted successfully.'));
      return $response;
      }
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state): void {
      // No need to implement this function for AJAX submission.
    }
  
  }
  
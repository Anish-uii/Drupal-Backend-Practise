<?php
/**
 * @file
 * Contains \Drupal\customform\Form\customForm.
 */
namespace Drupal\dependency_inject_services\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dependency_inject_services\services\DatabaseInsert;
use Drupal\Core\Messenger\MessengerInterface;

class customForm extends FormBase {

  protected $loaddata;
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form';
  }

  /**
   * Constructs a customForm object.
   */
  public function __construct(DatabaseInsert $loaddata, MessengerInterface $messenger) {
    $this->loaddata = $loaddata;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dependency_inject_services'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Member Name:'),
      '#required' => TRUE,
    ];

    $form['mail'] = [
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->loaddata->setData($form_state);
    $this->messenger->addMessage(t('Record has been saved'), 'status');
  }
}

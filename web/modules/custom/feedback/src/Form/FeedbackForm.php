<?php
/**
 * @file
 * Contains \Drupal\feedback\Form\FeedbackForm
 */
namespace Drupal\feedback\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides an feedback form.
 */
class FeedbackForm extends FormBase {

  /**
   * The Database Connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * FeedbackForm constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *
   */
  public function __construct(Connection $database, RequestStack $request_stack, AccountProxyInterface $current_user, ConfigFactoryInterface $config_factory) {
    $this->database = $database;
    $this->requestStack = $request_stack;
    $this->currentUser = $current_user;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('request_stack'),
      $container->get('current_user'),
      $container->get('config.factory')
    );
  }

  /**
   * (@inheritdoc)
   */
  public function getFormId() {
    return 'feedback_form';
  }
  /**
   * (@inheritdoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $getFilterValue = $this->requestStack->getCurrentRequest()->query->get('num');
    $record = array();
    if (isset($getFilterValue)) {
      $query = $this->database->select('feedback', 'f')
        ->condition('feedback_id', $getFilterValue)
        ->fields('f');
      $record = $query->execute()->fetchAssoc();
    }

    $form['name'] = array(
      '#title' => $this->t('Your Name'),
      '#type' => 'textfield',
      '#size' => 50,
      '#required' => TRUE,
      '#default_value' => (isset($record['name']) && $getFilterValue) ? $record['name']:'',
    );
    $form['email'] = array(
      '#title' => $this->t('Your Email'),
      '#type' => 'email',
      '#size' => 25,
      '#required' => TRUE,
      '#default_value' => (isset($record['email']) && $getFilterValue) ? $record['email']:'',
    );
    $form['message'] = array(
      '#title' => $this->t('Your Message'),
      '#type' => 'textarea',
      '#required' => TRUE,
      '#default_value' => (isset($record['message']) && $getFilterValue) ? $record['message']:'',
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Send')
    );
    return $form;
  }

  /**
   * start validation forms
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    if (strlen($form_state->getValue('name')) < 5) {
      $form_state->setErrorByName('name', $this->t('Name is too short.'));
    }
    if (strlen($form_state->getValue('message')) < 1) {
      $form_state->setErrorByName('message', $this->t('Message is too short.'));
    }

    $config = $this->configFactory->getEditable('feedback.settings');
    $configuration = $config->get('allowed_value');
//    get user's email
    $email_now = $form_state->getValue('email');
//    check email in the database
    $query = $this->database->select('feedback', 'f');
    $query->addField('f', 'email');
    $email_db = $query->execute()->fetchCol();

    if ($email_db[0] == $email_now && $configuration == 1 ) {
      $form_state->setErrorByName('email', $this -> t('The email can not be use for form resubmit'));
    }
  }

  /**
   * Start submit form
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $getFilterValue = $this->requestStack->getCurrentRequest()->query->get('num');
    if (isset($getFilterValue)) {
      $query = $this->database;
      $userId = $this->currentUser->id();
      $user = \Drupal\user\Entity\User::load($this->currentUser->id());
      $query->update('feedback')
        ->fields([
          'name' => $form_state -> getValue('name'),
          'email' => $form_state -> getValue('email'),
          'message' => $form_state -> getValue('message'),
          'uid' => $user -> id(),
          'created' => time(),
        ])
        ->condition('feedback_id', $getFilterValue)
        ->execute();
      drupal_set_message("Successfully changed");
      $form_state->setRedirect('feedback.report');
    }
    else {
      $query = $this->database->insert('feedback');
      $user = \Drupal\user\Entity\User::load($this->currentUser->id());
      $query->fields([
        'name' => $form_state -> getValue('name'),
        'email' => $form_state -> getValue('email'),
        'message' => $form_state -> getValue('message'),
        'uid' => $user -> id(),
        'created' => time(),
      ]);
      $query->execute();
      drupal_set_message(t('Your message has been sent'));
    }


  }
}

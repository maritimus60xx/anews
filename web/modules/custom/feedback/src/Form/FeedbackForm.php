<?php

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
   * Retrieves the request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Gets the current active user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Retrieves the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * FeedbackForm constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The Database Connection.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Retrieves the request stack.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Gets the current active user.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Retrieves the configuration factory.
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
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'feedback_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Check GET value.
    $getFilterValue = $this->requestStack->getCurrentRequest()->query->get('fid');
    // Checking for form editing.
    $record = array();
    if (isset($getFilterValue)) {
      $query = $this->database->select('feedback', 'f')
        ->condition('feedback_id', $getFilterValue)
        ->fields('f');
      $record = $query->execute()->fetchAssoc();
      // This field created for editing form.
      // When form creating at the first time this field do not using.
      $form['fid'] = array(
        '#title' => $this->t('feedback_id'),
        '#type' => 'hidden',
        '#size' => 6,
        '#required' => FALSE,
        // If form editing then id form insert to this field.
        '#default_value' => $getFilterValue,
      );
    }
    // Add name field.
    $form['name'] = array(
      '#title' => $this->t('Your Name'),
      '#type' => 'textfield',
      '#size' => 50,
      '#required' => TRUE,
      // If form editing then insert default values from database to this input.
      '#default_value' => (isset($record['name']) && $getFilterValue) ? $record['name'] : '',
    );
    // Add email field.
    $form['email'] = array(
      '#title' => $this->t('Your Email'),
      '#type' => 'email',
      '#size' => 25,
      '#required' => TRUE,
      // If form editing then insert default values from database to this input.
      '#default_value' => (isset($record['email']) && $getFilterValue) ? $record['email'] : '',
    );
    // Add textarea field.
    $form['message'] = array(
      '#title' => $this->t('Your Message'),
      '#type' => 'textarea',
      '#required' => TRUE,
      // If form editing then insert default values from database to this input.
      '#default_value' => (isset($record['message']) && $getFilterValue) ? $record['message'] : '',
    );
    $form['actions']['#type'] = 'actions';
    // Submit button.
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    );
    return $form;
  }

  /**
   * Start validation form.
   *
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Check length name.
    if (strlen($form_state->getValue('name')) < 5) {
      $form_state->setErrorByName('name', $this->t('Name is too short.'));
    }
    // Check length message.
    if (strlen($form_state->getValue('message')) < 5) {
      $form_state->setErrorByName('message', $this->t('Message is too short.'));
    }

    // Get configuration form value.
    $config = $this->configFactory->getEditable('feedback.settings');
    $configuration = $config->get('allowed_value');

    // Get user's email.
    $email_now = $form_state->getValue('email');

    // Check email in the database.
    $query = $this->database->select('feedback', 'f');
    $query->addField('f', 'email');
    $email_db = $query->execute()->fetchCol();

    // One email to one feedback.
    // There is a configuration form where you can cancel it.
    if ($email_db[0] == $email_now && $configuration == 1) {
      $form_state->setErrorByName('email', $this->t('The email can not be use for form resubmit, because it was already used for sending'));
    }
  }

  /**
   * Start submit form.
   *
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get Feedback id if form edit.
    $feedback_id = $form_state->getValue('fid');
    // If we edit feedback, then values update,
    // else values insert.
    if ($feedback_id > 0) {
      // Database connection.
      $query = $this->database;
      // Current user.
      $userId = $this->currentUser->id();
      // Update feedback.
      $query->update('feedback')
        ->fields([
          'name' => $form_state->getValue('name'),
          'email' => $form_state->getValue('email'),
          'message' => $form_state->getValue('message'),
          'uid' => $userId,
          'created' => time(),
        ])
        ->condition('feedback_id', $feedback_id)
        ->execute();
      drupal_set_message($this->t('Successfully changed'));
      // Return to report page.
      $form_state->setRedirect('feedback.report');

    }
    else {
      // If you create a feedback, this part of a code is being used.
      $query = $this->database->insert('feedback');
      $userId = $this->currentUser->id();
      $query->fields([
        'name' => $form_state->getValue('name'),
        'email' => $form_state->getValue('email'),
        'message' => $form_state->getValue('message'),
        'uid' => $userId,
        'created' => time(),
      ]);
      $query->execute();
      drupal_set_message($this->t('Your message has been sent'));

    }
  }

}

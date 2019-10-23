<?php

namespace Drupal\feedback\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FormDelete.
 *
 * @package Drupal\feedback\Form
 */
class FormDelete extends ConfirmFormBase {

  /**
   * The Database Connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * FormDelete constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $database = $container->get('database');
    $controller = new static($database);
    return $controller;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_form';
  }

  public $cid;

  public function getQuestion() {
    return t('Do you want to delete feedback?');
  }

  public function getCancelUrl() {
    return new Url('feedback.report');
  }

  public function getDescription() {
    return t('Do you want to delete feedback?');
  }
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }
  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {
    $this->feedback_id = $cid;
    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = $this->database;
    $query->delete('feedback')
      ->condition('feedback_id',$this->feedback_id)
      ->execute();

    drupal_set_message("Successfully deleted");

    $form_state->setRedirect('feedback.report');
  }
}

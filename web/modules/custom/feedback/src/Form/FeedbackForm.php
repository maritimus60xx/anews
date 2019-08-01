<?php
/**
 * @file
 * Contains \Drupal\feedback\Form\FeedbackForm
 */
namespace Drupal\feedback\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\User\Entity\User;

/**
 * Provides an feedback form.
 */
class FeedbackForm extends FormBase {
  /**
   * (@inheritdoc)
   */
  public function getFormId()
  {
    return 'feedback_form';
  }
  /**
   * (@inheritdoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
//    $node = \Drupal::routeMatch()-> getParameter('node');
//    $nid = $node->nid->value;
    $form['name'] = array(
      '#title' => t('Your Name'),
      '#type' => 'textfield',
      '#size' => 50,
      '#required' => TRUE
    );
    $form['email'] = array(
      '#title' => t('Your Email'),
      '#type' => 'email',
      '#size' => 25,
      '#required' => TRUE
    );
    $form['message'] = array(
      '#title' => t('Your Message'),
      '#type' => 'textfield',
      '#required' => TRUE
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Send')
    );
//    $form['nid'] = array(
//      '#type' => 'hidden',
//      '#value' => $nid,
//    );
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
    if (strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('message', $this->t('Message is too short.'));
    }
  }

  /**
   * Start submit form
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $query = \Drupal::database()->insert('feedback');
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $query->fields([
      'name' => $form_state -> getValue('name'),
      'email' => $form_state -> getValue('email'),
//      'nid' => $form_state -> getValue('nid'),
      'message' => $form_state -> getValue('message'),
      'uid' => $user -> id(),
      'created' => time(),
    ]);
    $query->execute();
    drupal_set_message(t('Your message has been sent'));
  }
}

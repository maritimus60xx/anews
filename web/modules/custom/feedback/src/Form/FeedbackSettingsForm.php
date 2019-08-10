<?php
/**
 * @file
 * Contains \Drupal\feedback\Form\FeedbackSettingsForm
 */
namespace Drupal\feedback\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form to configure Feedback module settings
 */
class FeedbackSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'feedback.admin_settings';
  }
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'feedback.settings'
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this -> config('feedback.settings');
    $form['allowed_value'] = array(
      '#type' => 'checkbox',
      '#title' => $this -> t('Prohibit resubmit the form'),
      '#default_value' => $config -> get('allowed_value'),
      '#description' => $this -> t('User will not be able to submit the form an unlimited number.'),
    );

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this -> config('feedback.settings');
    $config
      -> set('allowed_value', $form_state -> getValue('allowed_value'))
      -> save();

    parent::submitForm($form, $form_state);
  }
}

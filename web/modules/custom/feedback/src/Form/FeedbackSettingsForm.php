<?php
/**
 * @file
 * Contains \Drupal\feedback\Form\FeedbackSettingsForm
 */
namespace Drupal\feedback\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
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
}

<?php

namespace Drupal\feedback\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a feedback.
 *
 * @ingroup feedback
 */
class FeedbackDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the feedback?');
  }

  /**
   * {@inheritdoc}
   *
   * If the delete command is canceled, return to the report list.
   */
  public function getCancelUrl() {
    return new Url('feedback.report');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    $form_state->setRedirect('feedback.report');
  }

}
